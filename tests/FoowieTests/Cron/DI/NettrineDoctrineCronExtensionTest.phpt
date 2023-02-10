<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\NettrineDoctrineCronExtension;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class NettrineDoctrineCronExtensionTest extends TestCase {

	/**
	 * @var KdybyDoctrineCronExtension
	 */
	protected $extension;

	public function setUp() {
		$this->extension = new NettrineDoctrineCronExtension();
	}

	public function testKdybyInterfaceExists() {
		Assert::true(class_exists('Nettrine\ORM\DI\OrmExtension'));
	}

}

run(new NettrineDoctrineCronExtensionTest());