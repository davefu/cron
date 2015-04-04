<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\CronExtension;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronExtensionDoctrineTest extends BaseExtension {


	public function testRepositoryDoctrine() {
		$this->extension->defaults['repositoryType'] = CronExtension::REPOSITORY_DOCTRINE;
		$container = $this->createContainer('doctrine');
		$repository = $container->getByType('Foowie\Cron\Repository\IRepository');
		Assert::type('Foowie\Cron\Repository\Doctrine2Repository', $repository);
	}

	public function testDetectRepositoryTypeDoctrine() {
		$this->extension->defaults['repositoryType'] = null;
		$type = $this->extension->detectRepositoryType();
		Assert::true(interface_exists('Doctrine\ORM\EntityManagerInterface'));
		Assert::same(CronExtension::REPOSITORY_DOCTRINE, $type);
	}

}

run(new CronExtensionDoctrineTest());