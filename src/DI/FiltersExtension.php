<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\DI;

use Doctrine\ORM\Configuration;
use Nette\DI\CompilerExtension;
use Zenify\DoctrineFilters\Contract\FilterInterface;
use Zenify\DoctrineFilters\Contract\FilterManagerInterface;


class FiltersExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);
	}


	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();

		$definitionFinder = new DefinitionFinder($containerBuilder);
		$filterManagerDefinition = $definitionFinder->getDefinitionByType(FilterManagerInterface::class);
		$ormConfigurationDefinition = $definitionFinder->getDefinitionByType(Configuration::class);

		foreach ($containerBuilder->findByType(FilterInterface::class) as $name => $filterDefinition) {
			// 1) to filter manager to run conditions and enable allowed only
			$filterManagerDefinition->addSetup('addFilter', [$name, '@' . $filterDefinition->getClass()]);
			// 2) to Doctrine itself
			$ormConfigurationDefinition->addSetup('addFilter', [$name, '@' . $filterDefinition->getClass()]);
		}
	}

}
