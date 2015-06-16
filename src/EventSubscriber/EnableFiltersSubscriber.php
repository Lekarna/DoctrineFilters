<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symnedi\EventDispatcher\NetteApplicationEvents;
use Zenify\DoctrineFilters\Contract\FilterManagerInterface;


class EnableFiltersSubscriber implements EventSubscriberInterface
{

	/**
	 * @var FilterManagerInterface
	 */
	private $filterManager;


	public function setFilterManager(FilterManagerInterface $filterManager)
	{
		$this->filterManager = $filterManager;
	}


	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return [NetteApplicationEvents::ON_PRESENTER => 'onPresenter'];
	}


	public function onPresenter()
	{
		$this->filterManager->enableFilters();
	}

}
