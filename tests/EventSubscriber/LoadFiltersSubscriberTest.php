<?php

namespace Zenify\DoctrineFilters\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Application;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


class LoadFiltersSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->application = $container->getByType(Application::class);
		$this->entityManager = $container->getByType(EntityManagerInterface::class);
	}


	public function testDisptachEvent()
	{
		$filters = $this->entityManager->getFilters();
		$this->assertCount(0, $filters->getEnabledFilters());

		$this->application->run();

		$this->assertCount(2, $filters->getEnabledFilters());
	}

}
