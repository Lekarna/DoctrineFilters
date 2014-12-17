<?php

namespace Zenify\DoctrineFilters\Tests\Filters;

use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Filter;


class ConditionFilter extends Filter
{

	/**
	 * @param ClassMetaData $targetEntity
	 * @param string $targetTableAlias
	 * @return string
	 */
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
	{
		return '';
	}


	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		$date = new \DateTime;
		if ($date->format('H') >= 12) {
			return TRUE;
		}

		return FALSE;
	}

}
