<?php

namespace Zenify\DoctrineFilters\Tests\Filters;

use DateTime;
use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\AbstractFilter;


class ConditionFilter extends AbstractFilter
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
		$date = new DateTime;
		if ($date->format('H') >= 12) {
			return TRUE;
		}

		return FALSE;
	}

}
