<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters;

use Doctrine\ORM\EntityManagerInterface;
use Zenify\DoctrineFilters\Contract\ConditionalFilterInterface;
use Zenify\DoctrineFilters\Contract\FilterInterface;
use Zenify\DoctrineFilters\Contract\FilterManagerInterface;


class FilterManager implements FilterManagerInterface
{

	/**
	 * @var FilterInterface[]
	 */
	private $filters = [];

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;


	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addFilter($name, FilterInterface $filter)
	{
		$this->filters[$name] = $filter;
	}


	/**
	 * {@inheritdoc}
	 */
	public function enableFilters()
	{
		$filterCollection = $this->entityManager->getFilters();
		foreach ($this->filters as $name => $filter) {
			if ($filter instanceof ConditionalFilterInterface && ! $filter->isEnabled()) {
				continue;
			}

			$filterCollection->enable($name);
		}
	}

}
