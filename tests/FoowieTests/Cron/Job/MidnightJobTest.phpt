<?php

namespace FoowieTests\Cron\Job;

use Foowie\Cron\Job\MidnightJob;
use Foowie\Cron\JobInfo;
use FoowieTests\Mocks\DateTimeProviderMock;
use FoowieTests\Mocks\JobMock;
use FoowieTests\Mocks\RepositoryMock;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class MidnightJobTest extends TestCase {

	/**
	 * @var JobMock
	 */
	public $jobMock;

	/**
	 * @var RepositoryMock
	 */
	public $repositoryMock;

	/**
	 * @var DateTimeProviderMock
	 */
	public $dateTimeProvider;

	protected function setUp() {
		$this->jobMock = new JobMock();
		$this->repositoryMock = new RepositoryMock();
		$this->repositoryMock->findItems = array(
			'1' => new JobInfo(1, '1', new \DateTime('2014-01-01 00:00:00 - 1 sec')),
			'2' => new JobInfo(2, '2', new \DateTime('2014-01-01 00:00:00 + 1 sec')),
			'3' => new JobInfo(3, '3'),
			'4' => new JobInfo(4, '4', new \DateTime('2014-01-01 00:00:00 + 1 year')),
			'5' => new JobInfo(5, '5', new \DateTime('2014-01-01 00:00:00')),
		);
		$this->dateTimeProvider = new DateTimeProviderMock(array(
			'midnight' => new \DateTime('2014-01-01 00:00:00'),
			'2013-12-31' => new \DateTime('2013-12-31 00:00:00'),
			'2014-01-01' => new \DateTime('2014-01-01 00:00:00'),
			'2015-01-01' => new \DateTime('2015-01-01 00:00:00'),
		));
	}

	public function testLastRunBeforeMidnight() {
		$job = new MidnightJob('1', $this->jobMock, $this->repositoryMock, $this->dateTimeProvider);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::true($this->jobMock->hasCalled);
	}

	public function testLastRunAfterMidnight() {
		$job = new MidnightJob('2', $this->jobMock, $this->repositoryMock, $this->dateTimeProvider);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::false($this->jobMock->hasCalled);
	}

	public function testNoLastRun() {
		$job = new MidnightJob('3', $this->jobMock, $this->repositoryMock, $this->dateTimeProvider);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::true($this->jobMock->hasCalled);
	}

	public function testLastRunIsNextYear() {
		$job = new MidnightJob('4', $this->jobMock, $this->repositoryMock, $this->dateTimeProvider);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::true($this->jobMock->hasCalled);
	}

	public function testLastRunOnMidnight() {
		$job = new MidnightJob('5', $this->jobMock, $this->repositoryMock, $this->dateTimeProvider);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::false($this->jobMock->hasCalled);
	}

}

run(new MidnightJobTest());