<?php

namespace Foowie\Cron;

use Foowie\Cron\Job\IJob;
use Nette\Object;
use Nette\DI\Container;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ContextCronFactory extends Object {

	/** @var Container */
	protected $context;

	/** @var string */
	protected $tagName;

	function __construct(Container $context, $tagName = 'cronJob') {
		$this->context = $context;
		$this->tagName = $tagName;
	}

	public function create() {
		$serviceNames = $this->context->findByTag($this->tagName);
		$jobs = array();

		foreach ($serviceNames as $name => $attrs) {
			$job = $this->context->getService($name);
			if(!($job instanceof IJob)) {
				throw new \InvalidArgumentException("Service $name must implements Cron\IJob interface!");
			}
			$jobs[] = $job;
		}

		return new Cron($jobs);
	}

}
