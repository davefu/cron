<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\CronExtension;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Tester\TestCase;


/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
abstract class BaseExtension extends TestCase {

	/**
	 * @var CronExtension
	 */
	protected $extension;

	public function setUp() {
		$this->extension = new CronExtension();
	}

	protected function createContainer(): Container {
		$config = new Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$cronExtension = $this->extension;
		$config->onCompile[] = function ($config, Compiler $compiler) use ($cronExtension) {
			$compiler->addExtension('cron', $cronExtension);
		};
		$config->addConfig(__DIR__ . '/config.neon');
		return $config->createContainer();
	}


}
