<?php

namespace Zenify\DoctrineFilters\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit_Framework_TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symnedi\EventDispatcher\Event\ApplicationRequestEvent;
use Symnedi\EventDispatcher\NetteApplicationEvents;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


class LoadFiltersSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var EventDispatcherInterface
	 */
	private $eventDispatcher;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->eventDispatcher = $container->getByType(EventDispatcherInterface::class);
		$this->entityManager = $container->getByType(EntityManagerInterface::class);
	}


	public function testDispatchEvent()
	{
		$filters = $this->entityManager->getFilters();
		$this->assertCount(0, $filters->getEnabledFilters());

		$eventMock = $this->prophesize(ApplicationRequestEvent::class);
		$this->eventDispatcher->dispatch(NetteApplicationEvents::ON_REQUEST, $eventMock->reveal());

		$this->assertCount(2, $filters->getEnabledFilters());
	}

}
