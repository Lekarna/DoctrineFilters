<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\Contract;


interface FilterManagerInterface
{

	public function addFilter(string $name, FilterInterface $filter);


	/**
	 * Enables all filters that meet its conditions.
	 */
	public function enableFilters();

}
