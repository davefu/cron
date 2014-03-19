<?php

namespace Cron;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IRepository {

	/**
	 * @param string $name
	 * @return \Nette\Database\Table\ActiveRow
	 */
	function find($name);

	/**
	 * @param string $name
	 * @param \DateTime $time
	 */
	function updateTime($name, $time);

}