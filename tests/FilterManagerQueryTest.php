<?php

namespace Zenify\DoctrineFilters\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineFilters\FilterManager;
use Zenify\DoctrineFilters\Tests\Entities\Product;


class FilterManagerQueryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var EntityManager
	 */
	private $entityManager;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
		$this->entityManager = $this->container->getByType(EntityManager::class);
		$this->prepareDbData();
	}


	public function testFindOneBy()
	{
		/** @var FilterManager $filterManager */
		$filterManager = $this->container->getByType(FilterManager::class);
		$filterManager->attachEnabledFilters();

		/** @var  $productDao */
		$productDao = $this->entityManager->getRepository(Product::class);

		$product = $productDao->findOneBy(['id' => 1]);
		$this->assertInstanceOf(Product::class, $product);
		$this->assertTrue($product->isActive());

		$product2 = $productDao->findOneBy(['id' => 2]);
		$this->assertNull($product2);

		// this should be NULL; this appears only in CLI
		$product2 = $productDao->find(2);
		$this->assertInstanceOf(Product::class, $product2);
		$this->assertFalse($product2->isActive());
	}


	private function prepareDbData()
	{
		/** @var Connection $connection */
		$connection = $this->container->getByType(Connection::class);
		$connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, active int NULL, PRIMARY KEY(id))');

		$product = new Product;
		$product->setName('Brand new ruler');
		$product->setActive(1);
		$this->entityManager->persist($product);

		$product2 = new Product;
		$product2->setName('Black screw');
		$product2->setActive(0);
		$this->entityManager->persist($product2);
		$this->entityManager->flush();
	}

}
