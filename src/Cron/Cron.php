<?php

namespace Cron;

use Nette\Object;
use Nette\InvalidStateException;
use Exception;
use Nette\Diagnostics\Debugger;

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
					throw new InvalidStateException('Job must implement interface IJob!');
				}
				$job->run();
			} catch (Exception $e) {
				Debugger::log($e, Debugger::ERROR);
			}
		}
	}

}
