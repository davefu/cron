<?php

namespace Foowie\Cron;

use Foowie\Cron\Exceptions\CronException;
use Foowie\Cron\Job\IJob;
use Nette\Object;
use Exception;
use Tracy\Debugger;

if (!class_exists('Tracy\Debugger')) {
	class_alias('Nette\Diagnostics\Debugger', 'Tracy\Debugger');
}

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Cron extends Object implements ICron {

	/** @var IJob[] */
	protected $jobs;

	/**
	 * @param IJob[] $jobs
	 */
	function __construct(array $jobs) {
		$this->jobs = $jobs;
	}

	public function run() {
		foreach ($this->jobs as $job) {
			try {
				if (!($job instanceof IJob)) {
					throw new CronException('Job must implement interface IJob!');
				}
				$job->run();
			} catch (Exception $e) {
				Debugger::log($e, Debugger::ERROR);
			}
		}
	}

}
