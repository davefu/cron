<?php

namespace Foowie\Cron\Application\UI;

use Nette\Object;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class SecurityToken extends Object {

	/**
	 * @var null|string
	 */
	protected $securityToken;

	function __construct($securityToken) {
		$this->securityToken = $securityToken;
	}

	/**
	 * @return null|string
	 */
	public function getSecurityToken() {
		return $this->securityToken;
	}

} 