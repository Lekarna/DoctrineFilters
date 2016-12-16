<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\Contract;

use Doctrine\ORM\Mapping\ClassMetadata;


interface FilterInterface
{

	public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias) : string;

}
