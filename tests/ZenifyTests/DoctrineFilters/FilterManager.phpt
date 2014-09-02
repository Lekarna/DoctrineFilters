<?php

/**
 * @testCase
 */

namespace ZenifyTests\DoctrineFilters;

use Kdyby\Doctrine\EntityManager;
use Nette;
use Tester\Assert;
use Tester\TestCase;
use Zenify;


$container = require_once __DIR__ . '/../bootstrap.php';


class FilterManagerTest extends TestCase
{
	/** @var Nette\DI\Container */
	private $container;

	/** @var EntityManager */
	private $em;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
		$this->em = $container->getByType('Kdyby\Doctrine\EntityManager');
	}


	public function testEnabledFilters()
	{
		$filterCollection = $this->em->getFilters();
		Assert::type('Zenify\DoctrineFilters\FilterCollection', $filterCollection);
		Assert::count(0, $filterCollection->getEnabledFilters());

		/** @var Zenify\DoctrineFilters\FilterManager $filterManager */
		$filterManager = $this->container->getByType('Zenify\DoctrineFilters\FilterManager');
		$filterManager->attachEnabledFilters();

		$count = 1;
		$date = new \DateTime;
		if ($date->format('H') >= 12) {
			$count++;
		}
		Assert::same($count, count($filterCollection->getEnabledFilters()));
		foreach ($filterCollection->getEnabledFilters() as $filter) {
			Assert::type('Zenify\DoctrineFilters\Filter', $filter);
		}
	}

}


\run(new FilterManagerTest($container));
