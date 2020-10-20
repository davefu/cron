<?php

namespace Foowie\Cron\Job;

use Foowie\Cron\Exceptions\CronException;
use Nette\SmartObject;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CallbackJob implements IJob {
	use SmartObject;

	/** @var callback */
	protected $callback;

	function __construct($callback) {
		$this->callback = $callback;
	}

	public function run() {
		if(!is_callable($this->callback)) {
			throw new CronException('Not a valid callback!');
		}
		call_user_func($this->callback);
	}

}
