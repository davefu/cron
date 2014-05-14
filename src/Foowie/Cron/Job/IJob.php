<?php

namespace Foowie\Cron\Job;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IJob {

	/**
	 * Executes cron job.
	 */
	function run();
}