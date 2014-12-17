<?php

namespace ZenifyTests\DoctrineFilters;

use Kdyby\Doctrine\EntityManager;
use Nette;
use PHPUnit_Framework_TestCase;
use Zenify;
use Zenify\DoctrineFilters\Filter;
use Zenify\DoctrineFilters\FilterCollection;
use Zenify\DoctrineFilters\FilterManager;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


class FilterManagerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var EntityManager
	 */
	private $entityManager;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		$this->entityManager = $this->container->getByType(EntityManager::class);
	}


	public function testEnabledFilters()
	{
		$filterCollection = $this->entityManager->getFilters();
		$this->assertInstanceOf(FilterCollection::class, $filterCollection);
		$this->assertCount(0, $filterCollection->getEnabledFilters());

		/** @var FilterManager $filterManager */
		$filterManager = $this->container->getByType(FilterManager::class);
		$filterManager->attachEnabledFilters();

		$count = 1;
		$date = new \DateTime;
		if ($date->format('H') >= 12) {
			$count++;
		}
		$this->assertSame($count, count($filterCollection->getEnabledFilters()));
		foreach ($filterCollection->getEnabledFilters() as $filter) {
			$this->assertInstanceOf(Filter::class, $filter);
		}
	}

}
