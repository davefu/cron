<?php

namespace Cron;

use Nette\Config\CompilerExtension;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronExtension extends \Nette\Config\CompilerExtension {

	public $defaults = array(
		'cronJobTag' => 'cronJob',
		'table' => 'cron',
	);

	public function loadConfiguration() {

		$builder = $this->getContainerBuilder();

		$config = $this->getConfig($this->defaults);

		$connectionClass = (defined('NETTE_VERSION_ID') && NETTE_VERSION_ID < 20100) ? '\Nette\Database\Connection' : '\Nette\Database\Context';
		$builder->addDefinition($this->prefix('repository'))->setClass('Cron\Repository')->setArguments(array("@$connectionClass", $config['table']));

		$factory = $builder->addDefinition($this->prefix('contextCronFactory'))->setClass('Cron\ContextCronFactory')->setArguments(array('@\Nette\DI\Container', $config['cronJobTag']));

		$builder->addDefinition($this->prefix('cron'))->setClass('Cron\ICron')->setFactory(array($factory, 'create'));

	}

}