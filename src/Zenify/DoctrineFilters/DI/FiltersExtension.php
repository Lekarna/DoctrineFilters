<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\DI;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Reflection\Property;


class FiltersExtension extends CompilerExtension
{
	const TAG_FILTER = 'zenify.doctrine.filter';


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('manager'))
			->setClass('Zenify\DoctrineFilters\FilterManager');

		$builder->addDefinition($this->prefix('doctrine.filterCollection'))
			->setClass('Zenify\DoctrineFilters\FilterCollection');

		$builder->addDefinition($this->prefix('filter.event'))
			->setClass('Zenify\DoctrineFilters\Events\AttachFiltersOnPresenter')
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition('doctrine.default.entityManager')
			->addSetup('setFilterCollection', array('@Zenify\DoctrineFilters\FilterCollection'));

		$manager = $builder->getDefinition($this->prefix('manager'));
		$configuration = $builder->getDefinition('doctrine.default.ormConfiguration');

		foreach (array_keys($builder->findByTag(self::TAG_FILTER)) as $serviceName) {
			$definition = $builder->getDefinition($serviceName)
				->addSetup('setEm', array('@Kdyby\Doctrine\EntityManager'))
				->setAutowired(FALSE);

			$manager->addSetup('addFilter', array($serviceName, '@' . $serviceName));
			$configuration->addSetup('addFilter', array($serviceName, $definition->getClass()));
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
			$property = new Property('Doctrine\ORM\EntityManager', 'filterCollection');
			$property->setAccessible(TRUE);
			$property->setValue($em, $most);
		});
	}

}
