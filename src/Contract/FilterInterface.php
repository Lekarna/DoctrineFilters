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
	 * Resolves conditions that are required to enable filter.
	 * Filters are active by default.
	 *
	 * @return bool
	 */
	function isEnabled();


	/**
	 * @param ClassMetadata $targetEntity
	 * @param string $targetTableAlias
	 * @return string
	 */
	function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias);


	/**
	 * Some internal sorting?
	 *
	 * @return string
	 */
	function __toString();

}
