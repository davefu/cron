<?php

namespace Foowie\Cron\Job;

use DateTime;
use Foowie\Cron\DateTime\IDateTimeProvider;
use Foowie\Cron\Repository\IRepository;
use Nette\SmartObject;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class MidnightJob implements IJob {
	use SmartObject;

	/** @var IJob */
	protected $job;

	/** @var string */
	protected $name;

	/** @var IRepository */
	protected $repository;

	/** @var IDateTimeProvider */
	protected $dateTimeProvider;

	function __construct($name, IJob $job, IRepository $repository, IDateTimeProvider $dateTimeProvider) {
		$this->name = $name;
		$this->job = $job;
		$this->repository = $repository;
		$this->dateTimeProvider = $dateTimeProvider;
	}

	public function run() {
		$record = $this->repository->find($this->name);

		if ($record->getLastRun() !== null) {
			$midnight = $this->dateTimeProvider->getTime('midnight');
			$midnightOfLastRun = $this->dateTimeProvider->getTime($record->getLastRun()->format('Y-m-d'));
			if($midnight == $midnightOfLastRun) {
				return false;
			}
		}

		$this->repository->updateTime($this->name, new DateTime());
		return $this->job->run();
	}

}
