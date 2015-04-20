<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;


/**
 * The base class that user defined filters should extend.
 * Handles the setting and escaping of parameters.
 */
abstract class AbstractFilter
{

	/**
	 * @var EntityManager
	 */
	protected $entityManager;


	public function setEntityManager(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * Resolves conditions that are required to enable filter.
	 * Filters are active by default.
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return TRUE;
	}


	/**
	 * @param ClassMetaData $targetEntity
	 * @param string $targetTableAlias
	 * @return string
	 */
	abstract public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias);


	/**
	 * @param string $text
	 * @return string
	 */
	protected function escape($text)
	{
		return $this->entityManager->getConnection()->quote($text);
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return serialize($this);
	}

}
