<?php

namespace Zenify\DoctrineFilters\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Application;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


class LoadFiltersSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

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
		$this->container = (new ContainerFactory)->create();
		$this->application = $this->container->getByType(Application::class);
		$this->entityManager = $this->container->getByType(EntityManagerInterface::class);
	}


	public function testDisptachEvent()
	{
		$filters = $this->entityManager->getFilters();
		$this->assertCount(0, $filters->getEnabledFilters());

		$this->application->run();

		$this->assertCount(2, $filters->getEnabledFilters());
	}

}
