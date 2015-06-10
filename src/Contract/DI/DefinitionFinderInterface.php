<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\Contract\DI;

use Nette\DI\ServiceDefinition;


interface DefinitionFinderInterface
{

	/**
	 * @param string $type
	 * @return ServiceDefinition
	 */
	function getDefinitionByType($type);

}
