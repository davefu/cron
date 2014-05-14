<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\CronExtension;
use Foowie\Cron\Job\MidnightJob;
use Foowie\Cron\JobInfo;
use FoowieTests\Mocks\JobMock;
use FoowieTests\Mocks\RepositoryMock;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\Utils\AssertionException;
use Nette\Utils\FileSystem;
use Tester\Assert;
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

	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer() {
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
