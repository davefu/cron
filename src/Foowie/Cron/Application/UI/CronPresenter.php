<?php

namespace Foowie\Cron\Application\UI;

use Foowie\Cron\ICron;
use Nette\Application;
use Nette\Application\UI\Presenter;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronPresenter extends Presenter {

	/**
	 * @var SecurityToken
	 */
	protected $securityToken;

	/**
	 * @var ICron
	 */
	protected $cron;

	/**
	 * @var null|int
	 */
	protected $maxExecutionTime = null;

	/**
	 * @var null|string
	 */
	protected $memoryLimit = null;

	function __construct(ICron $cron, SecurityToken $securityToken) {
		parent::__construct();
		$this->cron = $cron;
		$this->securityToken = $securityToken;
	}

	/**
	 * @param int|null $maxExecutionTime
	 */
	public function setMaxExecutionTime($maxExecutionTime) {
		$this->maxExecutionTime = $maxExecutionTime;
	}

	/**
	 * @param null|string $memoryLimit
	 */
	public function setMemoryLimit($memoryLimit) {
		$this->memoryLimit = $memoryLimit;
	}

	public function checkRequirements($element): void {
		if ($this->securityToken !== null && $element instanceof Application\UI\PresenterComponentReflection) {
			if ($this->getParameter('token') != $this->securityToken->getSecurityToken()) {
				throw new Application\ForbiddenRequestException('Security token does not match!');
			}
		}
		parent::checkRequirements($element);
	}

	public function actionDefault() {
		if($this->maxExecutionTime !== null) {
			ini_set('max_execution_time', $this->maxExecutionTime);
		}
		if($this->memoryLimit !== null) {
			ini_set('memory_limit', $this->memoryLimit);
		}

		$this->cron->run();
		$this->sendResponse(new Application\Responses\TextResponse(''));
	}

} 