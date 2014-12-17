# Zenify/DoctrineFilters

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFilters.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFilters)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)


To learn more about filters, [check Ross Tuck's presentation](https://speakerdeck.com/rosstuck/extending-doctrine-2-for-your-domain-model?slide=15).
This package simplifies their use and enables DI approach.


## Installation

Install the latest version via composer:

```sh
$ composer require zenify/doctrine-filters
```


Register extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFilters\DI\FiltersExtension
```


## Doctrine Filters

Let's create your first filter, which will hide all deleted items on front.
This is usually called soft delete - data remains in database, but are filtered out for normal user.

**1. Extend from `Zenify\DoctrineFilters\Filter` and complete required methods.**

```php
use Doctrine\ORM\Mapping\ClassMetadata;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;


class SoftdeletableFilter extends Zenify\DoctrineFilters\Filter
{

	/**
	 * @return string
	 */
	public function addFilterConstraint(ClassMetadata $entity, $alias)
	{
		if ($entity->getReflectionClass()->hasProperty('isDeleted')) {
			return "$alias.isDeleted = 0");
		}

		return '';
	}

}
```

**2. Register service with tag**

`config.neon`

```yaml
services:
	- {class: SoftdeletableFilter, tags: [zenify.doctrine.filter]}
```


That's it!
