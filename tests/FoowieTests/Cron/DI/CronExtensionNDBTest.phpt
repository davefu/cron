<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\CronExtension;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronExtensionNDBTest extends BaseExtension {


	public function testRepositoryNDB() {
		$this->extension->defaults['repositoryType'] = CronExtension::REPOSITORY_NDB;
		$container = $this->createContainer();
		$repository = $container->getByType('Foowie\Cron\Repository\IRepository');
		Assert::type('Foowie\Cron\Repository\NetteDatabaseRepository', $repository);
	}

}

run(new CronExtensionNDBTest());