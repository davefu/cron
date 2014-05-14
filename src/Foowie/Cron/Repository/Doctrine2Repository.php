<?php

namespace Foowie\Cron\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Foowie\Cron\Exceptions\CronException;
use Foowie\Cron\JobInfo;
use Nette\Object;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Doctrine2Repository extends Object implements IRepository {

	/**
	 * @var EntityManagerInterface
	 */
	protected $em;

	function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	/**
	 * @param string $name
	 * @return JobInfo
	 */
	function find($name) {
		$jobInfo = $this->em->getRepository(JobInfo::getClassName())->findOneBy(array('name' => $name));

		if ($jobInfo === null) {
			$jobInfo = new JobInfo();
			$jobInfo->setName($name);
			$this->em->persist($jobInfo);
			$this->em->flush();
		}

		return $jobInfo;
	}

	/**
	 * @param string $name
	 * @param \DateTime $time
	 * @throws CronException
	 */
	function updateTime($name, \DateTime $time) {
		$jobInfo = $this->em->getRepository(JobInfo::getClassName())->findOneBy(array('name' => $name));
		if ($jobInfo === null) {
			throw new CronException('Cron job record named [' . $name . '] not found!');
		}
		$jobInfo->setLastRun($time);
	}

}