<?php

/**
 * @testCase
 */

namespace ZenifyTests\DoctrineFilters;

use Kdyby\Doctrine\Connection;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tester\Assert;
use Tester\TestCase;
use Zenify;
use ZenifyTests\DoctrineFilters\Entities\Product;


$container = require_once __DIR__ . '/../bootstrap.php';


class QueryTest extends TestCase
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


	public function testFindOneBy()
	{
		/** @var Zenify\DoctrineFilters\FilterManager $filterManager */
		$filterManager = $this->container->getByType('Zenify\DoctrineFilters\FilterManager');
		$filterManager->attachEnabledFilters();

		$this->prepareDbData();

		$productDao = $this->em->getDao('ZenifyTests\DoctrineFilters\Entities\Product');


		$product = $productDao->findOneBy(array('id' => 1));
		Assert::type('ZenifyTests\DoctrineFilters\Entities\Product', $product);
		Assert::true($product->isActive());

		$product = $productDao->find(1);
		Assert::type('ZenifyTests\DoctrineFilters\Entities\Product', $product);
		Assert::true($product->isActive());


		$product2 = $productDao->findOneBy(array('id' => 2));
		Assert::null($product2);

		// this should be NULL; this appears only in CLI
		$product2 = $productDao->find(2);
		Assert::type('ZenifyTests\DoctrineFilters\Entities\Product', $product2);
		Assert::false($product2->isActive());


		$product3 = $productDao->findOneBy(array('id' => 3));
		Assert::null($product3);
	}


	private function prepareDbData()
	{
		/** @var Connection $connection */
		$connection = $this->container->getByType('Doctrine\DBAL\Connection');
		$connection->query('CREATE TABLE product (id INTEGER NOT NULL, name string, active int NULL, PRIMARY KEY(id))');

		$product = new Product;
		$product->setName('Brand new ruler');
		$product->setActive(1);
		$this->em->persist($product);

		$product2 = new Product;
		$product2->setName('Black screw');
		$product2->setActive(0);
		$this->em->persist($product2);

		$this->em->flush();
	}
}


\run(new QueryTest($container));
