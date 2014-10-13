<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kdyby\Doctrine\Connection;
use Nette;


/**
 * The base class that user defined filters should extend.
 * Handles the setting and escaping of parameters.
 *
 * @method  Filter  setEm(object)
 */
abstract class Filter extends Nette\Object
{

	/**
	 * @var EntityManager
	 */
	protected $em;


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
	 * @param string $s
	 * @return string
	 */
	protected function escape($s)
	{
		return $this->em->getConnection()->quote($s);
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return serialize($this);
	}

}
