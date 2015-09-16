<?php

namespace MyApp\src\Utility;

use \Exception;

class Vars
{
  /**
   * @var []
   */
  private $vars = [];
  
  public function __construct()
  {
    
  }
  
  public function get($identifier)
  {
    if (!isset($this->vars[$identifier])) {
      throw new Exception("var " . $identifier . " not set in " .__CLASS__ . ":". __FUNCTION__);
    }
    return $this->vars[$identifier];
  }
  
  public function set($identifier, $obj)
  {
    if (isset($this->vars[$identifier])) {
      throw new Exception("overwriting " . $identifier . " in vars");
    }
    $this->vars[$identifier] = $obj;
  }
}