<?php

namespace Foowie\Cron\Job;

use DateTime;
use Foowie\Cron\Repository\IRepository;
use Nette\Object;
use Nette\Utils\AssertionException;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class DailyJob extends Object implements IJob {

	/** @var IJob */
	protected $job;

	/** @var string */
	protected $name;

	/** @var string */
	protected $hour;

	/** @var IRepository */
	protected $repository;

	/**
	 * Warning: do not use times >~23:00 (depends on cron execution frequency)
	 * @param $name
	 * @param $hour
	 * @param IJob $job
	 * @param IRepository $repository
	 */
	function __construct($name, $hour, IJob $job, IRepository $repository) {
		$this->name = $name;
		$this->job = $job;
		$this->repository = $repository;
		$this->hour = $hour;
		if(!Strings::match($hour, '/[012]\d:[012345]\d/')) {
			throw new AssertionException('Hour in DailyJob must be like 06:00 or 22:30');
		}
		if((int)$hour >= 23) {
			throw new AssertionException('Due to implementation limits do not use this class with times greater than 23:00');
		}
	}

	public function run() {
		$record = $this->repository->find($this->name);

		$midnight = new DateTime('midnight');
		$now = new DateTime();
		$expectedTime = new DateTime($this->hour);
		if (($record->lastRun !== null && $record->lastRun >= $midnight) || $now < $expectedTime) {
			return false;
		}

		$this->repository->updateTime($this->name, new DateTime());
		return $this->job->run();
	}

}
