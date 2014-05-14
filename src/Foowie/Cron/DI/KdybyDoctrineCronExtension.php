<?php

namespace Foowie\Cron\DI;

use Kdyby\Doctrine\DI\IEntityProvider;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class KdybyDoctrineCronExtension extends CronExtension implements IEntityProvider {

	public function getEntityMappings() {
		return array(
			'JobInfo' => (object)array('value' => 'xml', 'attributes' => __DIR__ . '/../metadata'),
		);
	}
} 