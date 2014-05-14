<?php

namespace Foowie\Cron\Application\UI;

use Nette\Application;
use Nette\Application\UI\Presenter;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CronPresenter extends Presenter {

	/**
	 * @var \Foowie\Cron\Application\UI\SecurityToken @inject
	 */
	public $securityToken;

	/**
	 * @var \Foowie\Cron\ICron @inject
	 */
	public $cron;

	public function checkRequirements($element) {
		if ($this->securityToken !== null && $element instanceof Application\UI\PresenterComponentReflection) {
			if ($this->getParameter('token') != $this->securityToken->getSecurityToken()) {
				throw new Application\ForbiddenRequestException('Security token does not match!');
			}
		}
		parent::checkRequirements($element);
	}

	public function actionDefault() {
		$this->cron->run();
		$this->sendResponse(new Application\Responses\TextResponse(''));
	}

} 