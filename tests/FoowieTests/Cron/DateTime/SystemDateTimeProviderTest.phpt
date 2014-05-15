<?php

namespace FoowieTests\Cron\Job;

use Foowie\Cron\DateTime\SystemDateTimeProvider;
use Foowie\Cron\Job\MidnightJob;
use Foowie\Cron\JobInfo;
use FoowieTests\Mocks\JobMock;
use FoowieTests\Mocks\RepositoryMock;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class SystemDateTimeProviderTest extends TestCase {

	/**
	 * @var SystemDateTimeProvider
	 */
	protected $dateTimeProvider;

	protected function setUp() {
		$this->dateTimeProvider = new SystemDateTimeProvider();
	}

	public function testReturnsDateTime() {
		$date = $this->dateTimeProvider->getTime();
		Assert::type('\DateTime', $date);
	}

	public function testExactDate() {
		$date = $this->dateTimeProvider->getTime('2014-01-01 12:00:00');
		Assert::same('2014-01-01 12:00:00', $date->format('Y-m-d H:i:s'));
	}

}

run(new SystemDateTimeProviderTest());