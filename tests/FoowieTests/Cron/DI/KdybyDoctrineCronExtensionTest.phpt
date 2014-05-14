<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\CronExtension;
use Foowie\Cron\DI\KdybyDoctrineCronExtension;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class KdybyDoctrineCronExtensionTest extends TestCase {

	/**
	 * @var KdybyDoctrineCronExtension
	 */
	protected $extension;

	public function setUp() {
		$this->extension = new KdybyDoctrineCronExtension();
	}

	public function testKdybyInterfaceExists() {
		Assert::true(interface_exists('Kdyby\Doctrine\DI\IEntityProvider'));
	}

	public function testHasEntityDefinition() {
		$mappings = $this->extension->getEntityMappings();
		Assert::type('array', $mappings);
		Assert::true(isset($mappings['JobInfo']));
		Assert::type('object', $mappings['JobInfo']);
	}

}

run(new KdybyDoctrineCronExtensionTest());