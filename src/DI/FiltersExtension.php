<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\DI;

use Doctrine\ORM\Configuration;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;
use Zenify\DoctrineFilters\Contract\FilterInterface;
use Zenify\DoctrineFilters\Contract\FilterManagerInterface;
use Zenify\DoctrineFilters\Exception\DefinitionForTypeNotFoundException;


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

		$filterManagerDefinition = $this->getDefinitionByType(FilterManagerInterface::class);
		$ormConfigurationDefinition = $this->getDefinitionByType(Configuration::class);

		foreach ($containerBuilder->findByType(FilterInterface::class) as $name => $filterDefinition) {
			// once to filter manager to run conditions and enable allowed only
			$filterManagerDefinition->addSetup('addFilter', [$name, '@' . $name]);
			// second for Doctrine itself
			$ormConfigurationDefinition->addSetup('addFilter', [$name, $filterDefinition->getClass()]);
		}
	}


	/**
	 * @param string $type
	 * @return ServiceDefinition
	 */
	private function getDefinitionByType($type)
	{
		$containerBuilder = $this->getContainerBuilder();
		if ($name = $containerBuilder->getByType($type)) {
			return $containerBuilder->getDefinition($name);
		}

		foreach ($containerBuilder->findByType($type) as $definition) {
			return $definition;
		}

		throw new DefinitionForTypeNotFoundException(
			sprintf('Definition for type "%s" was not found.', $type)
		);
	}

}
