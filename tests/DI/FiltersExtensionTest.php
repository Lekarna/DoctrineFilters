<?php

namespace Zenify\DoctrineFilters\Tests\DI;

use Nette;
use PHPUnit_Framework_TestCase;
use Zenify;
use Zenify\DoctrineFilters\Events\AttachFiltersOnPresenter;
use Zenify\DoctrineFilters\FilterCollection;
use Zenify\DoctrineFilters\FilterManager;
use Zenify\DoctrineFilters\Tests\ContainerFactory;


class FiltersExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testExtension()
	{
		$this->assertInstanceOf(
			FilterCollection::class,
			$this->container->getByType(FilterCollection::class)
		);
		$this->assertInstanceOf(
			FilterManager::class,
			$this->container->getByType(FilterManager::class)
		);
		$this->assertInstanceOf(
			AttachFiltersOnPresenter::class,
			$this->container->getByType(AttachFiltersOnPresenter::class)
		);
	}

}
