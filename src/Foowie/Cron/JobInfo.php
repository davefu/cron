<?php

namespace Foowie\Cron;

use Nette\SmartObject;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 *
 * @property-read int $id
 * @property string $name
 * @property \DateTime|null $lastRun
 * @property string|null $data
 */
class JobInfo {
	use SmartObject;

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
	 * @return \DateTime|null
	 */
	public function getLastRun() {
		return $this->lastRun;
	}

	/**
	 * @return string|null
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