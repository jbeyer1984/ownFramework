<?php


namespace MyApp\src\Entities;

use MyApp\src\Entities\ProductRepository;

class Product
{

  /**
   * @var ProductRepository
   */
  private $repository;


  /**
   * @var number
   */
  private $id;

  /**
   * @var string
   */
  private $name;

  /**
   * @var string
   */
  private $owner;

  public function __construct()
  {
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  /**
   * @return ProductRepository
   */
  public function getRepository()
  {
    return $this->repository;
  }

  /**
   * @param ProductRepository $repository
   */
  public function setRepository($repository)
  {
    $this->repository = $repository;
  }

  /**
   * @return number
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param number $id
   */
  public function setId($id)
  {
    $this->id = $id;
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

  /**
   * @return string
   */
  public function getOwner()
  {
    return $this->owner;
  }

  /**
   * @param string $owner
   */
  public function setOwner($owner)
  {
    $this->owner = $owner;
  }
}