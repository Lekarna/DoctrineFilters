<?php

namespace Zenify\DoctrineFilters\Tests\FilterManager\Source;

use DateTime;
use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Contract\ConditionalFilterInterface;


final class DisabledFilter implements ConditionalFilterInterface
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
		return FALSE;
	}

}
