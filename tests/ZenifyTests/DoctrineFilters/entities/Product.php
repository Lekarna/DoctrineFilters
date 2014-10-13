<?php

namespace ZenifyTests\DoctrineFilters\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette;


/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 *
 * @method  int     getId()
 * @method  string  getName()
 * @method  boolean isActive()
 * @method  Product setName()
 * @method  Product setActive()
 */
class Product extends Nette\Object
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	public $id;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $name;

	/**
	 * @ORM\Column(type="boolean")
	 * @var boolean
	 */
	protected $active = TRUE;

}
