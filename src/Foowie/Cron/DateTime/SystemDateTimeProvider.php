<?php

namespace Foowie\Cron\DateTime;

use Nette\SmartObject;
use Nette\Utils\DateTime;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class SystemDateTimeProvider implements IDateTimeProvider {
	use SmartObject;

	/**
	 * @param string|\DateTimel $time
	 * @return \DateTime
	 */
	function getTime($time = 'now') {
		return DateTime::from($time);
	}
}