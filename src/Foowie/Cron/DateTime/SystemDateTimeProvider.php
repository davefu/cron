<?php

namespace Foowie\Cron\DateTime;

use Nette\Object;
use Nette\Utils\DateTime;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class SystemDateTimeProvider extends Object implements IDateTimeProvider {

	/**
	 * @param string|\DateTimel $time
	 * @return \DateTime
	 */
	function getTime($time = 'now') {
		return DateTime::from($time);
	}
}