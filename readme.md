# Zenify/DoctrineFilters

[![Build Status](https://travis-ci.org/Zenify/DoctrineFilters.svg?branch=master)](https://travis-ci.org/Zenify/DoctrineFilters)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-filters.svg)](https://packagist.org/packages/zenify/doctrine-filters)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-filters.svg)](https://packagist.org/packages/zenify/doctrine-filters)


To learn more about filters, [check Ross Tuck's presentation](https://speakerdeck.com/rosstuck/extending-doctrine-2-for-your-domain-model?slide=15).
This package simplifies their use and enables DI approach.


## Installation

The best way to install is via [Composer](http://getcomposer.org/).

```sh
$ composer require zenify/doctrine-filters:~1.0
```


Register extensions you need in `config.neon`:

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
	/** @var Application */
	private $application;


	public function __construct(Application $application)
	{
		$this->application = $application;
	}


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


	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		/** @var Presenter $presenter */
		$presenter = $this->application->getPresenter();
		if (Strings::contains($presenter->getReflection()->name, 'FrontModule')) {
			return TRUE;
		}

		return FALSE;
	}

}
```

**2. Register service with tag**

`config.neon`

```yaml
services:
	-
		class: SoftdeletableFilter
		tags: [zenify.doctrine.filter]
```


That's it!
