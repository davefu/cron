<?php

namespace Foowie\Cron\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Nette\Utils\AssertionException;
use Nette\Utils\Json;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronExtension extends CompilerExtension {

	const REPOSITORY_NDB = 'ndb';
	const REPOSITORY_DOCTRINE = 'doctrine';

	public $defaults = array(
		'cronJobTag' => 'cronJob',
		'repositoryType' => null, // null = autodetect, false = none
		'table' => 'cron', // ndb only

		'mapPresenter' => true,
		'maxExecutionTime' => null,
		'memoryLimit' => null,
		'securityToken' => false,
		'mapping' => array(
			'FoowieCron' => 'Foowie\\Cron\\Application\\UI\\*\\*Presenter'
		),
		'router' => array(
			'pattern' => 'cron[/<token>]',
			'metadata' => 'FoowieCron:Cron:default',
		),
		'jobs' => array(),
		'panel' => true,
	);

	public function loadConfiguration() {

		$builder = $this->getContainerBuilder();

		$config = $this->getConfig($this->defaults);

		$factory = $builder->addDefinition($this->prefix('contextCronFactory'))
			->setClass('Foowie\Cron\ContextCronFactory')
			->setArguments(array('@\Nette\DI\Container', $config['cronJobTag']));

		$builder->addDefinition($this->prefix('cron'))
			->setClass('Foowie\Cron\ICron')
			->setFactory(array($factory, 'create'));

		$builder->addDefinition($this->prefix('dateTimeProvider'))
			->setClass('Foowie\Cron\DateTime\SystemDateTimeProvider');

		if ($config['mapPresenter']) {
			$this->loadMapping();
		}

		$this->loadRepository();

		$this->loadJobs();

	}

	public function afterCompile(ClassType $class) {
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		if ($config['panel'] && $container->parameters['debugMode']) {
			$init = $class->methods['initialize'];
			$init->addBody(Helpers::format(
				'Foowie\Cron\Diagnostics\Panel::register($this->getByType(?), $this->getByType(?));',
				'Foowie\Cron\ICron',
				'Nette\Http\Request'
			));
		}
	}

	protected function loadMapping() {
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		if ($config['securityToken'] === false) {
			throw new AssertionException('Specify security token [' . $this->name . '.securityToken] please.');
		}
		$builder->addDefinition($this->prefix('securityToken'))
			->setClass('Foowie\Cron\Application\UI\SecurityToken', array($config['securityToken']));
		$builder->addDefinition($this->prefix('router'))
			->setClass('Foowie\Cron\Application\Routers\Route', array($config['router']['pattern'], $config['router']['metadata']))
			->setAutowired(false);
		$builder->getDefinition('router')
			->addSetup('Foowie\Cron\Application\Routers\Route::prependToRouteList($service, ?)', array($this->prefix('@router')));
		$builder->getDefinition('nette.presenterFactory')
			->addSetup('setMapping', array($config['mapping']));
		$builder->addDefinition($this->prefix('cronPresenter'))
			->setClass('Foowie\Cron\Application\UI\CronPresenter')
			->addSetup('setMaxExecutionTime', [$config['maxExecutionTime']])
			->addSetup('setMemoryLimit', [$config['memoryLimit']]);
	}

	protected function loadRepository() {
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$repositoryType = $config['repositoryType'] === null ? $this->detectRepositoryType() : $config['repositoryType'];
		switch ($repositoryType) {
			case static::REPOSITORY_DOCTRINE:
				$builder->addDefinition($this->prefix('repository'))
					->setClass('Foowie\Cron\Repository\Doctrine2Repository');
				break;
			case static::REPOSITORY_NDB:
				$builder->addDefinition($this->prefix('repository'))
					->setClass('Foowie\Cron\Repository\NetteDatabaseRepository')
					->setArguments(array("@\Nette\Database\Context", $config['table']));
				break;
			case false:
				break;
			default:
				throw new AssertionException("Invalid repository type [$repositoryType]!");
		}
	}

	protected function loadJobs() {
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		foreach ($config['jobs'] as $subscriber) {
			$def = $builder->addDefinition($this->prefix('job.' . md5(Json::encode($subscriber))));
			list($def->factory) = Compiler::filterArguments(array(
				is_string($subscriber) ? new Statement($subscriber) : $subscriber
			));

			list($subscriberClass) = (array)$builder->normalizeEntity($def->factory->entity);
			if (class_exists($subscriberClass)) {
				$def->class = $subscriberClass;
			}

			$def->setAutowired(false);
			$def->addTag($config['cronJobTag']);
		}
	}

	public function detectRepositoryType() {
		if (interface_exists('Doctrine\ORM\EntityManagerInterface')) {
			return self::REPOSITORY_DOCTRINE;
		} else {
			return self::REPOSITORY_NDB;
		}
	}

	/**
	 * @param Configurator $configurator
	 */
	public static function register(Configurator $configurator) {
		$configurator->onCompile[] = function ($config, Compiler $compiler) {
			$compiler->addExtension('cron', new CronExtension());
		};
	}

}