<?php

namespace Zenify\DoctrineFilters\Tests\DI;

use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use stdClass;
use Zenify\DoctrineFilters\Contract\DI\DefinitionFinderInterface;
use Zenify\DoctrineFilters\DI\DefinitionFinder;
use Zenify\DoctrineFilters\Exception\DefinitionForTypeNotFoundException;


class DefinitionFinderTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;

	/**
	 * @var DefinitionFinderInterface
	 */
	private $definitionFinder;


	protected function setUp()
	{
		$this->containerBuilder = new ContainerBuilder;
		$this->definitionFinder = new DefinitionFinder($this->containerBuilder);
	}


	public function testAutowired()
	{
		$definition = $this->containerBuilder->addDefinition('some')
			->setClass(stdClass::class);

		$this->assertSame($definition, $this->definitionFinder->getDefinitionByType(stdClass::class));
	}


	public function testNonAutowired()
	{
		$definition = $this->containerBuilder->addDefinition('some')
			->setClass(stdClass::class)
			->setAutowired(FALSE);

		$this->assertSame($definition, $this->definitionFinder->getDefinitionByType(stdClass::class));
	}


	public function testMissing()
	{
		$this->setExpectedException(DefinitionForTypeNotFoundException::class);
		$this->definitionFinder->getDefinitionByType(stdClass::class);
	}

}
