<?php

namespace MyApp\src\Tasks;


use MyApp\src\Components\Components;

class Tasks
{
  protected $components;
  
  public function __construct()
  {
    $this->components = Components::getInstance();
  }

  /**
   * @return Components
   */
  public function getComponents()
  {
    return $this->components;
  }

  /**
   * @param Components $components
   */
  public function setComponents($components)
  {
    $this->components = $components;
  }
}