<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters;

use Doctrine;
use InvalidArgumentException;
use Doctrine\ORM\Query\FilterCollection as BaseFilterCollection;


class FilterCollection extends BaseFilterCollection
{

	/**
	 * @var int
	 */
	protected $filtersState = self::FILTERS_STATE_CLEAN;

	/**
	 * @var AbstractFilter[]
	 */
	protected $enabledFilters = [];

	/**
	 * @var string
	 */
	protected $filterHash;


	/**
	 * Intentionally empty to release parent dependencies.
	 */
	public function __construct()
	{
	}


	/**
	 * @param string
	 * @param AbstractFilter
	 * @return AbstractFilter
	 */
	public function attach($name, AbstractFilter $filter)
	{
		if ( ! isset($this->enabledFilters[$name])) {
			$this->enabledFilters[$name] = $filter;

			ksort($this->enabledFilters);

			$this->filtersState = self::FILTERS_STATE_DIRTY;
		}

		return $this->enabledFilters[$name];
	}


	/**
	 * {@inheritdoc}
	 */
	public function getEnabledFilters()
	{
		return $this->enabledFilters;
	}


	/**
	 * {@inheritdoc}
	 */
	public function disable($name)
	{
		$filter = $this->getFilter($name);
		unset($this->enabledFilters[$name]);

		$this->filtersState = self::FILTERS_STATE_DIRTY;

		return $filter;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFilter($name)
	{
		if ( ! isset($this->enabledFilters[$name])) {
			throw new InvalidArgumentException("Filter '" . $name . "' is not enabled.");
		}

		return $this->enabledFilters[$name];
	}


	/**
	 * {@inheritdoc}
	 */
	public function isEnabled($name)
	{
		return isset($this->enabledFilters[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isClean()
	{
		return $this->filtersState === self::FILTERS_STATE_CLEAN;
	}


	/**
	 * {@inheritdoc}
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


	/**
	 * {@inheritdoc}
	 */
	public function setFiltersStateDirty()
	{
		$this->filtersState = self::FILTERS_STATE_DIRTY;
	}

}
