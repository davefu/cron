<?php

namespace FoowieTests\Cron\Job;

use Foowie\Cron\Job\TimeIntervalJob;
use Foowie\Cron\JobInfo;
use FoowieTests\Mocks\JobMock;
use FoowieTests\Mocks\RepositoryMock;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class TimeIntervalJobTest extends TestCase {

	/**
	 * @var JobMock
	 */
	public $jobMock;

	/**
	 * @var RepositoryMock
	 */
	public $repositoryMock;

	protected function setUp() {
		$this->jobMock = new JobMock();
		$this->repositoryMock = new RepositoryMock();
		$this->repositoryMock->findItems = array(
			'1' => new JobInfo(1, '1', new \DateTime('now - 3 hour')),
			'2' => new JobInfo(2, '2', new \DateTime('now - 1 hour')),
			'3' => new JobInfo(3, '3'),
		);
	}

	public function testShouldRun() {
		$job = new TimeIntervalJob('1', '2 hour', $this->jobMock, $this->repositoryMock);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::true($this->jobMock->hasCalled);
	}

	public function testShouldNotRun() {
		$job = new TimeIntervalJob('2', '2 hour', $this->jobMock, $this->repositoryMock);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::false($this->jobMock->hasCalled);
	}

	public function testNoLastRunShouldRun() {
		$job = new TimeIntervalJob('3', '2 hour', $this->jobMock, $this->repositoryMock);
		Assert::false($this->jobMock->hasCalled);
		$job->run();
		Assert::true($this->jobMock->hasCalled);
	}


}

run(new TimeIntervalJobTest());