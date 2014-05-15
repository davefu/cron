<?php

namespace FoowieTests\Cron\DI;

use Foowie\Cron\DI\CronExtension;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronExtensionTest extends BaseExtension {


	public function testDateTimeProvider() {
		$container = $this->createContainer();
		$provider = $container->getByType('Foowie\Cron\DateTime\IDateTimeProvider', false);
		Assert::type('Foowie\Cron\DateTime\SystemDateTimeProvider', $provider);
	}

}

run(new CronExtensionTest());