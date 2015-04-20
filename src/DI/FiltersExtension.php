<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\DI;

use Doctrine;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Reflection\Property;
use Zenify\DoctrineFilters\Events\AttachFiltersOnPresenter;
use Zenify\DoctrineFilters\AbstractFilter;
use Zenify\DoctrineFilters\FilterCollection;
use Zenify\DoctrineFilters\FilterManager;


class FiltersExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('manager'))
			->setClass(FilterManager::class);

		$builder->addDefinition($this->prefix('doctrine.filterCollection'))
			->setClass(FilterCollection::class);

		$builder->addDefinition($this->prefix('filter.event'))
			->setClass(AttachFiltersOnPresenter::class)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition('doctrine.default.entityManager')
			->addSetup('setFilterCollection', ['@' . FilterCollection::class]);

		$manager = $builder->getDefinition($this->prefix('manager'));
		$configuration = $builder->getDefinition('doctrine.default.ormConfiguration');

		foreach ($builder->findByType(AbstractFilter::class) as $name => $filterDefinition) {
			$filterDefinition->addSetup('setEntityManager', ['@' . EntityManager::class])
				->setAutowired(FALSE);

			$manager->addSetup('addFilter', [$name, '@' . $name]);
			$configuration->addSetup('addFilter', [$name, $filterDefinition->getClass()]);
		}
	}


	public function afterCompile(ClassType $class)
	{
		$methods = $class->getMethods();
		$init = $methods['initialize'];
		$init->addBody(__CLASS__ . '::registerEmSetFilterCollection();');
	}


	/**
	 * Replace native getFilter where filterCollection is instanced statically
	 * by DI service approach
	 */
	public static function registerEmSetFilterCollection()
	{
		EntityManager::extensionMethod('setFilterCollection', function (EntityManager $em, $most) {
			$property = new Property(Doctrine\ORM\EntityManager::class, 'filterCollection');
			$property->setAccessible(TRUE);
			$property->setValue($em, $most);
		});
	}

}
