# Doctrine Filters

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFilters.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFilters)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)


Don't know Doctrine Filters? Check [these few slides](https://speakerdeck.com/rosstuck/extending-doctrine-2-for-your-domain-model?slide=15) to get the knowledge.

They are present in Doctrine by default. **This package simplifies their use in modular application.**


## Install

Via Composer:

```sh
$ composer require zenify/doctrine-filters
```

Register extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFilters\DI\FiltersExtension
```


## Usage

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


## Limit Access by Role or Module

Use case 1: show deleted content only to logged user with *admin* role.
So we can turn on the filtering when all these conditions are not met.

```php
use Nette\Security\User;


class SoftdeletableFilter extends Zenify\DoctrineFilters\Filter
{

	/**
	 * @var User
	 */
	private $user;
	

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	
	/**
	 * @return string
	 */
	public function addFilterConstraint(ClassMetadata $entity, $alias)
	{
		// same as above
	}
	
	
	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		if ($this->user->isLoggedIn() && $this->user->hasRole('admin')) {
			return FALSE;
		}
		return TRUE;
	}
	
}
```

---

Use case 2: show deleted content only in *admin* module. 

```php
use Nette\Application\Application;
use Nette\Application\UI\Presenter;


class SoftdeletableFilter extends Zenify\DoctrineFilters\Filter
{

	/**
	 * @var Application
	 */
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
		// same as above
	}
	
	
	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		/** @var Presenter $presenter */
	    $presenter = $this->application->getPresenter();
		if (strpos($presenter->getReflection()->name, 'AdminModule') !== FALSE) {
			return FALSE;
		}
		return TRUE;
	}
	
}
```
