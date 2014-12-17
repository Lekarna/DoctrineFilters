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
use Zenify\DoctrineFilters\FilterCollection;
use Zenify\DoctrineFilters\FilterManager;


class FiltersExtension extends CompilerExtension
{

	const TAG_FILTER = 'zenify.doctrine.filter';


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

		foreach (array_keys($builder->findByTag(self::TAG_FILTER)) as $serviceName) {
			$definition = $builder->getDefinition($serviceName)
				->addSetup('setEm', ['@' . EntityManager::class])
				->setAutowired(FALSE);

			$manager->addSetup('addFilter', [$serviceName, '@' . $serviceName]);
			$configuration->addSetup('addFilter', [$serviceName, $definition->getClass()]);
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
