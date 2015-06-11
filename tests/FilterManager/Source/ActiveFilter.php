<?php

namespace Zenify\DoctrineFilters\Tests\FilterManager\Source;

use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Contract\FilterInterface;


class ActiveFilter implements FilterInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
	{
		return sprintf('%s.is_active = 1', $targetTableAlias);
	}

}
