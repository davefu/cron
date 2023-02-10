<?php

namespace Foowie\Cron\DI;

use Davefu\KdybyContributteBridge\DI\Helper\MappingHelper;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class NettrineDoctrineCronExtension extends CronExtension {

	public function beforeCompile(): void {
		parent::beforeCompile();
		MappingHelper::of($this)
			->addXml('Foowie\Cron', __DIR__ . '/../metadata');
	}
} 