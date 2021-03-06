<?php

namespace MyApp\src\Components;

use MyApp\src\Tasks\PhpUnit\EntityManager;
use MyApp\src\Utility\Db;
use MyApp\src\Utility\Router\Router;
use MyApp\src\Utility\Session;
use MyApp\src\Utility\Vars;
use MyApp\src\Utility\Logger;
use \Exception;

require_once ROOT_PATH . '/vendor/autoload.php';

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
      case 'entity_manager':
        $this->compoents[$identifier] = new EntityManager();
        break;
      case 'vars' :
        $this->components[$identifier] = new Vars();
        break;
      case 'logger' :
        $this->components[$identifier] = new Logger();
        break;
      case 'db' :
        $this->components[$identifier] = new Db();
        break;
      case 'router' :
        $router = new Router();
        $router->initRoutingConfig();
        $this->components[$identifier] = $router;
        break;
      case 'session' :
        $session = new Session();
        $session->init();
        $this->components[$identifier] = $session;
        break;
      case 'view' :
        // twig loading
        $loader = new \Twig_Loader_Filesystem(ROOT_PATH . 'view');
        $twig = new \Twig_Environment($loader, array(
//          'cache' => ROOT_PATH . '/cache/compilation_cache',
          'cache' => false,
          'auto_reload' => true
        ));
        $this->components[$identifier] = $twig;
        break;
      default :
        throw new Exception ("component " . $identifier . " not set in " . __CLASS__ . ":". __FUNCTION__);
    }
    return $this->components[$identifier];
  }
}
