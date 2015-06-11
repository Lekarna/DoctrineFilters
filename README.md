# Doctrine Filters

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFilters.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFilters)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)


What are Doctrine Filters? Check [these few slides](https://speakerdeck.com/rosstuck/extending-doctrine-2-for-your-domain-model?slide=15) to get the knowledge.

They are present in Doctrine by default. This package only simplifies their use in modular application.


## Install

Via Composer:

```sh
$ composer require zenify/doctrine-filters
```

Register extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFilters\DI\FiltersExtension
	- Symnedi\EventDispatcher\DI\EventDispatcherExtension
	- Kdyby\Doctrine\DI\OrmExtension
```


## Usage

Let's create our first filter, which will hide all deleted items on front.
So called "soft delete" - data remains in database, but are filtered out in frontend.

First, we create class implementing [Zenify\DoctrineFilters\Contract\FilterInterface](src/Contract/FilterInterface.php).

```php
use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Contract\FilterInterface;


class SoftdeletableFilter extends FilterInterface
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

Then register as service:

`config.neon`

```yaml
services:
	- SoftdeletableFilter
```


That's it!


### Limit Access by Role

The management wants to show deleted content only to logged user with *admin* role.

To setup condition, we just implement [Zenify\DoctrineFilters\Contract\ConditionalFilterInterface](src/Contract/ConditionalFilterInterface.php).


```php
use Nette\Security\User;
use Zenify\DoctrineFilters\Contract\ConditionalFilterInterface;


class SoftdeletableFilter extends ConditionalFilterInterface
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

Voilá!
