<?php

namespace FoowieTests\Mocks;

use Foowie\Cron\JobInfo;
use Foowie\Cron\Repository\IRepository;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class RepositoryMock implements IRepository {

	/**
	 * @var JobInfo[]
	 */
	public $findItems = array();

	public $onUpdate = null;

	function find($name) {
		return $this->findItems[$name];
	}

	function updateTime($name, \DateTime $time) {
		if($this->onUpdate !== null) {
			$this->onUpdate($name, $time);
		}
		$this->findItems[$name]->setLastRun($time);
	}
}