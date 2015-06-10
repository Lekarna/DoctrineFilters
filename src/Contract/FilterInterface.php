<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\Contract;

use Doctrine\ORM\Mapping\ClassMetadata;


interface FilterInterface
{

	/**
	 * @param ClassMetadata $targetEntity
	 * @param string $targetTableAlias
	 * @return string
	 */
	function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias);

}
