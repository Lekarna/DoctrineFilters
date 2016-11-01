# Doctrine Filters

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineFilters.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineFilters)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineFilters.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineFilters)
[![Downloads](https://img.shields.io/packagist/dt/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-filters.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-filters)


What are Doctrine Filters? Check [these few slides](https://speakerdeck.com/rosstuck/extending-doctrine-2-for-your-domain-model?slide=15) or see [Usage](#usage) to get the knowledge.

They are present in Doctrine by default. This package only simplifies their use in modular application.


## Install

```sh
$ composer require zenify/doctrine-filters
```

Register extension in `config.neon`:

```yaml
extensions:
	- Zenify\DoctrineFilters\DI\FiltersExtension
	- Symnedi\EventDispatcher\DI\EventDispatcherExtension
	
	# Kdyby\Doctrine or another Doctrine to Nette implementation
```


## Usage

Let's create our first filter, which will hide all deleted items on front.
So called "soft delete" - data remains in database, but are filtered out in frontend.

First, we create class implementing [Zenify\DoctrineFilters\Contract\FilterInterface](src/Contract/FilterInterface.php).

```php
use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Contract\FilterInterface;


final class SoftdeletableFilter implements FilterInterface
{

	public function addFilterConstraint(ClassMetadata $entity, string $alias) : string
	{
		if ($entity->getReflectionClass()->hasProperty('isDeleted')) {
			return "$alias.isDeleted = 0");
		}

		return '';
	}

}
```

Then register as service:


```yaml
# app/config/config.neon
services:
	- SoftdeletableFilter
```


And that's it!


### Limit Access by Role

The management wants to show deleted content only to logged user with *admin* role.

To setup condition, we just implement [Zenify\DoctrineFilters\Contract\ConditionalFilterInterface](src/Contract/ConditionalFilterInterface.php).


```php
use Nette\Security\User;
use Zenify\DoctrineFilters\Contract\ConditionalFilterInterface;


final class SoftdeletableFilter implements ConditionalFilterInterface
{

	/**
	 * @var User
	 */
	private $user;
	

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	
	public function addFilterConstraint(ClassMetadata $entity, string $alias) : string
	{
		// same as above
	}
	
	
	public function isEnabled() : bool
	{
		if ($this->user->isLoggedIn() && $this->user->hasRole('admin')) {
			return FALSE;
		}
		return TRUE;
	}
	
}
```

Voilá!



## Testing

```sh
composer check-cs
vendor/bin/phpunit
```


## Contributing

Rules are simple:

- new feature needs tests
- all tests must pass
- 1 feature per PR

We would be happy to merge your feature then!
