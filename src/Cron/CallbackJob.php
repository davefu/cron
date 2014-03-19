<?php

namespace Cron;

use Nette\Object;
use Nette\Callback;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CallbackJob extends Object implements IJob {

	/** @var Callback */
	protected $callback;

	function __construct($callback) {
		$this->callback = callback($callback);
	}

	public function run() {
		return $this->callback->invoke();
	}

}
