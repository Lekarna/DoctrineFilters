<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\Contract;


interface FilterManagerInterface
{

	/**
	 * @param string $name
	 * @param FilterInterface $filter
	 */
	function addFilter($name, FilterInterface $filter);


	/**
	 * Enables filters that meet its conditions.
	 */
	function enableFilters();

}
