<?php

namespace Zenify\DoctrineFilters\Tests\Entities;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	private $isActive = TRUE;


	/**
	 * @param string $name
	 * @param bool $isActive
	 */
	public function __construct($isActive)
	{
		$this->isActive = $isActive;
	}


	/**
	 * @return bool
	 */
	public function isActive()
	{
		return $this->isActive;
	}

}
