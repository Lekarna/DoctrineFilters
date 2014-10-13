<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters;

use Nette;


class FilterManager extends Nette\Object
{

	/**
	 * @var Filter[]
	 */
	private $filters;

	/**
	 * @var FilterCollection
	 */
	private $filterCollection;


	public function __construct(FilterCollection $filterCollection)
	{
		$this->filterCollection = $filterCollection;
	}


	/**
	 * @param string $name
	 * @param Filter $filter
	 */
	public function addFilter($name, Filter $filter)
	{
		$this->filters[$name] = $filter;
	}


	public function attachEnabledFilters()
	{
		foreach ($this->filters as $name => $filter) {
			if ($filter->isEnabled()) {
				$this->filterCollection->attach($name, $filter);
			}
		}
	}

}
