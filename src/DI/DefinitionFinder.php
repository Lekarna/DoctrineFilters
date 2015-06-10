<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\DI;

use Nette\DI\ContainerBuilder;
use Zenify\DoctrineFilters\Contract\DI\DefinitionFinderInterface;
use Zenify\DoctrineFilters\Exception\DefinitionForTypeNotFoundException;


class DefinitionFinder implements DefinitionFinderInterface
{

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;


	public function __construct(ContainerBuilder $containerBuilder)
	{
		$this->containerBuilder = $containerBuilder;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefinitionByType($type)
	{
		$this->containerBuilder->prepareClassList();

		if ($name = $this->containerBuilder->getByType($type)) {
			return $this->containerBuilder->getDefinition($name);
		}

		foreach ($this->containerBuilder->findByType($type) as $definition) {
			return $definition;
		}

		throw new DefinitionForTypeNotFoundException(
			sprintf('Definition for type "%s" was not found.', $type)
		);
	}

}
