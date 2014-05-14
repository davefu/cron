<?php

namespace Foowie\Cron;

use Nette\Object;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class JobInfo extends Object {

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \DateTime
	 */
	protected $lastRun;

	/**
	 * @var string
	 */
	protected $data;

	function __construct($id = null, $name = null, $lastRun = null, $data = null) {
		$this->id = $id;
		$this->name = $name;
		$this->lastRun = $lastRun;
		$this->data = $data;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastRun() {
		return $this->lastRun;
	}

	/**
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param \DateTime $lastRun
	 */
	public function setLastRun($lastRun) {
		$this->lastRun = $lastRun;
	}

	/**
	 * @param string $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public static function getClassName() {
		return get_called_class();
	}

}