<?php

namespace FoowieTests\Cron\Job;

use Foowie\Cron\Job\CallbackJob;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CallbackJobTest extends TestCase {

	public $result;

	protected function setUp() {
		$this->result = null;
	}

	public function testRunArrayCallback() {
		$job = new CallbackJob(array($this, 'runArrayCallbackHandler'));
		$job->run();
		Assert::true($this->result);
	}

	public function runArrayCallbackHandler() {
		$this->result = true;
	}

	public function testRunFunction() {
		$t = $this;
		$job = new CallbackJob(function () use ($t) {
			$t->result = true;
		});
		$job->run();
		Assert::true($this->result);
	}

	public function testRunNotCallable() {
		$job = new CallbackJob(array($this, 'notAMethod'));
		Assert::exception(function() use($job) {
			$job->run();
		}, 'Foowie\Cron\Exceptions\CronException');
	}

}

run(new CallbackJobTest());