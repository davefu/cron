<?php

namespace FoowieTests\Mocks;

use Foowie\Cron\Job\IJob;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class JobMock implements IJob {

	public $hasCalled = false;

	/**
	 * Executes cron job.
	 */
	function run() {
		$this->hasCalled = true;
	}
}