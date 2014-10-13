<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters;

use Doctrine;


class FilterCollection extends Doctrine\ORM\Query\FilterCollection
{

	/**
	 * @var int
	 */
	protected $filtersState = self::FILTERS_STATE_CLEAN;

	/**
	 * @var Filter[]
	 */
	protected $enabledFilters = array();

	/**
	 * @var string
	 */
	protected $filterHash;


	public function __construct()
	{
	}


	/**
	 * @param string
	 * @param Filter
	 * @return Filter
	 */
	public function attach($name, Filter $filter)
	{
		if ( ! isset($this->enabledFilters[$name])) {
			$this->enabledFilters[$name] = $filter;

			ksort($this->enabledFilters);

			$this->filtersState = self::FILTERS_STATE_DIRTY;
		}

		return $this->enabledFilters[$name];
	}


	/**
	 * @return array
	 */
	public function getEnabledFilters()
	{
		return $this->enabledFilters;
	}


	/**
	 * @param string $name
	 * @return Doctrine\ORM\Query\Filter\SQLFilter|Filter
	 */
	public function disable($name)
	{
		$filter = $this->getFilter($name);
		unset($this->enabledFilters[$name]);

		$this->filtersState = self::FILTERS_STATE_DIRTY;

		return $filter;
	}


	/**
	 * @param string $name
	 * @return Filter
	 * @throws \InvalidArgumentException
	 */
	public function getFilter($name)
	{
		if ( ! isset($this->enabledFilters[$name])) {
			throw new \InvalidArgumentException("Filter '" . $name . "' is not enabled.");
		}

		return $this->enabledFilters[$name];
	}


	/**
	 * @param string $name
	 * @return bool
	 */
	public function isEnabled($name)
	{
		return isset($this->enabledFilters[$name]);
	}


	/**
	 * @return bool
	 */
	public function isClean()
	{
		return $this->filtersState === self::FILTERS_STATE_CLEAN;
	}


	/**
	 * @return string
	 */
	public function getHash()
	{
		if ($this->filtersState = self::FILTERS_STATE_CLEAN) {
			return $this->filterHash;
		}

		$filterHash = '';
		foreach ($this->enabledFilters as $name => $filter) {
			$filterHash .= $name . $filter;
		}

		return $filterHash;
	}


	public function setFiltersStateDirty()
	{
		$this->filtersState = self::FILTERS_STATE_DIRTY;
	}

}
