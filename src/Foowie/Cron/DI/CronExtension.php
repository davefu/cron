<?php

namespace Foowie\Cron\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers as CompilerHelpers;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\DI\Resolver;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Dumper;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Utils\AssertionException;
use Nette\Utils\Json;
use stdClass;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 *
 * @property-read stdClass $config
 */
class CronExtension extends CompilerExtension {

	const REPOSITORY_NDB = 'ndb';
	const REPOSITORY_DOCTRINE = 'doctrine';

	public function getConfigSchema(): Schema {
		return Expect::structure([
			'cronJobTag' => Expect::string('cronJob'),
			'repositoryType' => Expect::bool()->nullable(), // null = autodetect, false = none
			'table' => Expect::string('cron'), // ndb only

			'mapPresenter' => Expect::bool(true),
			'maxExecutionTime' => Expect::int()->nullable(),
			'memoryLimit' => Expect::string()->nullable(),
			'securityToken' => Expect::anyOf(Expect::string()->nullable(), Expect::bool(false)),
			'mapping' => Expect::arrayOf(Expect::string(), Expect::string())->default([
				'FoowieCron' => 'Foowie\\Cron\\Application\\UI\\*\\*Presenter',
			]),
			'router' => Expect::structure([
				'pattern' => Expect::string('cron[/<token>]'),
				'metadata' => Expect::string('FoowieCron:Cron:default'),
			]),
			'jobs' => Expect::array(),
			'panel' => Expect::bool(true),
		]);
	}

	public function loadConfiguration() {

		$builder = $this->getContainerBuilder();

		$config = $this->config;

		$factory = $builder->addDefinition($this->prefix('contextCronFactory'))
			->setType('Foowie\Cron\ContextCronFactory')
			->setArguments(['@\Nette\DI\Container', $config->cronJobTag]);

		$builder->addDefinition($this->prefix('cron'))
			->setType('Foowie\Cron\ICron')
			->setFactory([$factory, 'create']);

		$builder->addDefinition($this->prefix('dateTimeProvider'))
			->setType('Foowie\Cron\DateTime\SystemDateTimeProvider');

		$this->addMappingDefinitions();

		$this->loadRepository();

		$this->loadJobs();

	}

	public function beforeCompile(): void {
		$this->registerMapping();
	}

	public function afterCompile(ClassType $class) {
		$container = $this->getContainerBuilder();
		$config = $this->config;
		$init = $class->getMethod('initialize');
		if ($config->panel && $container->parameters['debugMode']) {
			$init->addBody((new Dumper())->format(
				'Foowie\Cron\Diagnostics\Panel::register($this->getByType(?), $this->getByType(?));',
				'Foowie\Cron\ICron',
				'Nette\Http\Request'
			));
		}
	}

	protected function addMappingDefinitions() {
		$config = $this->config;
		if (!$config->mapPresenter) {
			return;
		}

		if ($config->securityToken === false) {
			throw new AssertionException('Specify security token [' . $this->name . '.securityToken] please.');
		}

		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('securityToken'))
			->setFactory('Foowie\Cron\Application\UI\SecurityToken', [$config->securityToken]);
		$builder->addDefinition($this->prefix('router'))
			->setFactory('Foowie\Cron\Application\Routers\Route', [$config->router->pattern, $config->router->metadata])
			->setAutowired(false);

		$builder->addDefinition($this->prefix('cronPresenter'))
			->setType('Foowie\Cron\Application\UI\CronPresenter')
			->addSetup('setMaxExecutionTime', [$config->maxExecutionTime])
			->addSetup('setMemoryLimit', [$config->memoryLimit]);
	}

	protected function registerMapping() {
		$config = $this->config;
		if (!$config->mapPresenter) {
			return;
		}

		$builder = $this->getContainerBuilder();

		/** @var ServiceDefinition $netteRouter */
		$netteRouter = $builder->getDefinition('router');
		$netteRouter->addSetup('Foowie\Cron\Application\Routers\Route::prependToRouteList($service, ?)', [$this->prefix('@router')]);

		/** @var ServiceDefinition $nettePresenterFactory */
		$nettePresenterFactory = $builder->getDefinition('nette.presenterFactory');
		$nettePresenterFactory->addSetup('setMapping', [$config->mapping]);
	}

	protected function loadRepository() {
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$repositoryType = $config->repositoryType === null ? $this->detectRepositoryType() : $config->repositoryType;
		switch ($repositoryType) {
			case static::REPOSITORY_DOCTRINE:
				$builder->addDefinition($this->prefix('repository'))
					->setType('Foowie\Cron\Repository\Doctrine2Repository');
				break;
			case static::REPOSITORY_NDB:
				$builder->addDefinition($this->prefix('repository'))
					->setFactory('Foowie\Cron\Repository\NetteDatabaseRepository', ["@\Nette\Database\Explorer", $config->table]);
				break;
			case false:
				break;
			default:
				throw new AssertionException("Invalid repository type [$repositoryType]!");
		}
	}

	protected function loadJobs() {
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$resolver = new Resolver($builder);
		foreach ($config->jobs as $subscriber) {
			$def = $builder->addDefinition($this->prefix('job.' . md5(Json::encode($subscriber))));
			list($def->factory) = CompilerHelpers::filterArguments([
				is_string($subscriber) ? new Statement($subscriber) : $subscriber
			]);

			$subscriberClass = $resolver->resolveEntityType($def->factory);
			if (class_exists($subscriberClass)) {
				$def->setType($subscriberClass);
			}

			$def->setAutowired(false);
			$def->addTag($config->cronJobTag);
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