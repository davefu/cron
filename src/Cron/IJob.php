<?php

namespace Cron;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IJob {

	function run();
}