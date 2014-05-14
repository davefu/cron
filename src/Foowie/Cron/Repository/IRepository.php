<?php

namespace Foowie\Cron\Repository;
use Foowie\Cron\JobInfo;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IRepository {

	/**
	 * @param string $name
	 * @return JobInfo
	 */
	function find($name);

	/**
	 * @param string $name
	 * @param \DateTime $time
	 */
	function updateTime($name, \DateTime $time);

}