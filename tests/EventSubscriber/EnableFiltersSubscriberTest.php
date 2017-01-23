<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Application;
use Nette\Application\IPresenter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symplify\SymfonyEventDispatcher\Adapter\Nette\Event\ApplicationStartupEvent;
use Symplify\SymfonyEventDispatcher\Adapter\Nette\Event\PresenterCreatedEvent;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


final class EnableFiltersSubscriberTest extends TestCase
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

		$applicationMock = $this->prophesize(Application::class);
		$applicationStartupEvent = new ApplicationStartupEvent($applicationMock->reveal());
		$this->eventDispatcher->dispatch(ApplicationStartupEvent::NAME, $applicationStartupEvent);

		$this->assertCount(2, $filters->getEnabledFilters());
	}

}
