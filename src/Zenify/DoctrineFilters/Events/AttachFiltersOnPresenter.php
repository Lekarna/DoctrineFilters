<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineFilters\Events;

use Kdyby\Events\Subscriber;
use Zenify\DoctrineFilters\FilterManager;


class AttachFiltersOnPresenter implements Subscriber
{

	/**
	 * @var FilterManager
	 */
	private $filterManager;


	public function __construct(FilterManager $filterManager)
	{
		$this->filterManager = $filterManager;
	}


	public function onPresenter()
	{
		if (PHP_SAPI === 'cli') {
			return;
		}

		$this->filterManager->attachEnabledFilters();
	}


	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return ['Nette\Application\Application::onPresenter'];
	}

}
