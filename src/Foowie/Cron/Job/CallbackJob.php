<?php

namespace Foowie\Cron\Job;

use Foowie\Cron\Exceptions\CronException;
use Nette\Object;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CallbackJob extends Object implements IJob {

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
