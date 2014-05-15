<?php

namespace FoowieTests\Mocks;

use Foowie\Cron\DateTime\IDateTimeProvider;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class DateTimeProviderMock implements IDateTimeProvider {

	public $map = array();

	function __construct(array $map = array()) {
		$this->map = $map;
	}


	/**
	 * @param string|\DateTime $time
	 * @return \DateTime
	 */
	function getTime($time = 'now') {
		if(isset($this->map[$time])) {
			return $this->map[$time];
		} else {
			throw new \Exception("Time for key [$time] is not defined!");
		}
	}
}