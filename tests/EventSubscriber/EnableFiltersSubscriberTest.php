<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Application;
use Nette\Application\IPresenter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symnedi\EventDispatcher\Event\ApplicationPresenterEvent;
use Symnedi\EventDispatcher\NetteApplicationEvents;
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
		$presenterMock = $this->prophesize(IPresenter::class);
		$applicationPresenterEvent = new ApplicationPresenterEvent(
			$applicationMock->reveal(), $presenterMock->reveal()
		);
		$this->eventDispatcher->dispatch(NetteApplicationEvents::ON_PRESENTER, $applicationPresenterEvent);

		$this->assertCount(2, $filters->getEnabledFilters());
	}

}
