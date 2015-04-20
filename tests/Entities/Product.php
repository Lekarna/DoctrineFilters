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
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	private $isActive = TRUE;


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return bool
	 */
	public function isActive()
	{
		return $this->isActive;
	}


	/**
	 * @param bool $isActive
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

}
