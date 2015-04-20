<?php

namespace Zenify\DoctrineFilters\Tests\Filters;

use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\AbstractFilter;


class ActiveFilter extends AbstractFilter
{

	/**
	 * {@inheritdoc}
	 */
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
	{
		return "$targetTableAlias.is_active = 1";
	}

}
