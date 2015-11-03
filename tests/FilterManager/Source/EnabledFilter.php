<?php

namespace Zenify\DoctrineFilters\Tests\FilterManager\Source;

use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Contract\ConditionalFilterInterface;


final class EnabledFilter implements ConditionalFilterInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
	{
		return '';
	}


	/**
	 * {@inheritdoc}
	 */
	public function isEnabled()
	{
		return TRUE;
	}

}
