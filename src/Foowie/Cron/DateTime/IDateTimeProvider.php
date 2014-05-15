<?php

namespace Foowie\Cron\DateTime;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IDateTimeProvider {

	/**
	 * @param string|\DateTime $time
	 * @return \DateTime
	 */
	function getTime($time = 'now');

} 