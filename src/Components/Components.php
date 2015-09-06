<?php

namespace MyApp\src\Components;

use MyApp\src\Utility\Db;
use MyApp\src\Utility\Vars;
use MyApp\src\Utility\Logger;
use \Exception;

class Components
{
  /**
   * @var Components
   */
  private static $instance;

  /**
   * @var
   */
  private $components = array();
  
  private function __construct(){}
  private function __clone(){}
  
  public static function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new self;
    }
    return self::$instance;
  }
  
  public function get($identifier)
  {
    if (isset($this->components[$identifier])) {
      return $this->components[$identifier];  
    }
    switch ($identifier) {
      case 'vars' :
        $this->components[$identifier] = new Vars();
        break;
      case 'logger' :
        $this->components[$identifier] = new Logger();
        break;
      case 'db' :
        $this->components[$identifier] = new Db();
        break;
      default :
        throw new Exception ("component " . $identifier . " not set in " . __CLASS__ . ":". __FUNCTION__);
    }
    return $this->components[$identifier];
  }
}
