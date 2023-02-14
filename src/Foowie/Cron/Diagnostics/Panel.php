<?php

namespace Foowie\Cron\Diagnostics;

use Foowie\Cron\Cron;
use Nette\Http\Request;
use Nette\Http\UrlImmutable;
use Nette\SmartObject;
use Tracy\Debugger;
use Tracy\IBarPanel;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Panel implements IBarPanel {
	use SmartObject;

	/** @var Cron */
	protected $cron;

	/** @var Request */
	protected $request;

	final public function __construct(Cron $cron, Request $request) {
		$this->cron = $cron;
		$this->request = $request;

		$query = $request->getQuery("run-cron-jobs");
		if ($query == 1) {
			$this->runCronJobs();
		}
	}

	/**
	 * Renders HTML code for custom tab.
	 * @return string
	 */
	function getTab() {
		$url = $this->request->getUrl()->getBaseUrl() . '?run-cron-jobs=1';
		return '<img onclick="window.location=\'' . $url . '\'" style="cursor: pointer;" title="Run cron jobs" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAOCAYAAAAmL5yKAAABJ0lEQVQoU43TvSuGYRTH8c8jlFWS/BVWRQYZZJCiRBlMkpQsNmW3MCgp8jbI20BSz8BiktlgUP4ApUSUdHQ/T1e3+5FruOs+L9/rnN85V8nvU4dBDKGEryykAbfYwEslLQLS044VXGIH74kzYrsxj1WUw5cCWrGJKTwliQM4T/6jkjUc4SIF7GERD7mqDjGcs9XjGJMVQAdGsVCgSREgwjrRH4Ae3KEZz6lAGawWIHJP4hPlHWAXEzUqGMNHka/SQozsDJ8FQX2YwzpO8/rkx1iQ/2MK5Weyvsezi6ot1EoqsjfhLXN0oTetYAn7uP8HsTEb40QKaME2pvH4BySSQ48QvZzXoC1b5Wts4TUBxRvpxSyWcZVf5fR99GMEsXHpY7rJ1r0K/gYCEjbuAZZyKwAAAABJRU5ErkJggg==">';
	}

	/**
	 * Renders HTML code for custom panel.
	 * @return string
	 */
	function getPanel() {
		return '';
	}

	protected function runCronJobs() {
		$this->cron->run();
		header('Location: ' . $this->getReferrer());
		exit;
	}

	public static function register(Cron $cron, Request $request) {
		$bar = Debugger::getBar();
		$panel = new static($cron, $request);
		$bar->addPanel($panel);
		return $panel;
	}

	protected function getReferrer(): ?UrlImmutable {
		$referrer = $this->request->headers['referer'] ?? null;
		if (!empty($referrer)) {
			return new UrlImmutable($referrer);
		}

		return $this->request->getOrigin();
	}
}