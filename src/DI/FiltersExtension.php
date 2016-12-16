<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\DI;

use Doctrine\ORM\Configuration;
use Nette\DI\CompilerExtension;
use Zenify\DoctrineFilters\Contract\FilterInterface;
use Zenify\DoctrineFilters\Contract\FilterManagerInterface;
use Zenify\DoctrineFilters\EventSubscriber\EnableFiltersSubscriber;


final class FiltersExtension extends CompilerExtension
{

	/**
	 * @var DefinitionFinder
	 */
	private $definitionFinder;


	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/../config/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);

		$this->definitionFinder = new DefinitionFinder($containerBuilder);
	}


	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();

		$definitionFinder = new DefinitionFinder($containerBuilder);
		$filterManagerDefinition = $definitionFinder->getDefinitionByType(FilterManagerInterface::class);
		$ormConfigurationDefinition = $definitionFinder->getDefinitionByType(Configuration::class);

		foreach ($containerBuilder->findByType(FilterInterface::class) as $name => $filterDefinition) {
			// 1) to filter manager to run conditions and enable allowed only
			$filterManagerDefinition->addSetup('addFilter', [$name, '@' . $name]);
			// 2) to Doctrine itself
			$ormConfigurationDefinition->addSetup('addFilter', [$name, $filterDefinition->getClass()]);
		}

		$this->passFilterManagerToListener();
	}


	/**
	 * Prevents circular reference.
	 */
	private function passFilterManagerToListener()
	{
		$enableFiltersSubscriberDefinition = $this->definitionFinder->getDefinitionByType(
			EnableFiltersSubscriber::class
		);

		$filterManagerServiceName = $this->definitionFinder->getServiceNameByType(FilterManagerInterface::class);
		$enableFiltersSubscriberDefinition->addSetup(
			'setFilterManager',
			['@' . $filterManagerServiceName]
		);
	}

}
