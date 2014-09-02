<?php

/**
 * @testCase
 */

namespace ZenifyTests\DoctrineFilters;

use Nette;
use Tester\Assert;
use Tester\TestCase;
use Zenify;


$container = require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends TestCase
{
	/** @var Nette\DI\Container */
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function testExtension()
	{
		$collection = $this->container->getByType('Zenify\DoctrineFilters\FilterCollection');
		Assert::type('Zenify\DoctrineFilters\FilterCollection', $collection);

		$manager = $this->container->getByType('Zenify\DoctrineFilters\FilterManager');
		Assert::type('Zenify\DoctrineFilters\FilterManager', $manager);

		$event = $this->container->getByType('Zenify\DoctrineFilters\Events\AttachFiltersOnPresenter');
		Assert::type('Zenify\DoctrineFilters\Events\AttachFiltersOnPresenter', $event);
	}

}


\run(new ExtensionTest($container));
