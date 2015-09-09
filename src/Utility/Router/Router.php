<?php


namespace MyApp\src\Utility\Router;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Router\Route;
use \Exception;

class Router
{
  /**
   * @var array
   */
  private $routingConfig;

  public function __construct()
  {
  }

  public function reset()
  {
    $this->routingConfig = [];
  }
  
  public function initRoutingConfig()
  {
    $this->routingConfig = [
      'task1' => [
        'show' => [
          'params' => [
            'get' => 'id/str',
            'post' => 'id/str'
          ],
          'class' => 'MyApp\src\Tasks\Task1',
        ]
      ]
    ];
    return $this;
  }
  
  public function route()
  {
    $route = new Route();
    $route->generate($this);

    $this->verifyRouting($route);

    $routeSettings = $this->routingConfig[$route->getSubject()][$route->getAction()];
    $class = $routeSettings['class'];
    $obj = new $class();

    call_user_func_array(array($obj, $route->getAction()), $route->getParams());
    
    Components::getInstance()->get('logger')->log('$params', $route->getParams());
  }

  /**
   * @param $route Route
   * @throws Exception
   */
  private function verifyRouting($route)
  {
    if (!isset($this->routingConfig[$route->getSubject()])) {
      throw new Exception("subject does not exist for route: " . $this->subject . ":" . $this->action);
    }
    if (!isset($this->routingConfig[$route->getSubject()][$route->getAction()])) {
      throw new Exception("action does not exist for route: " . $this->subject . ":" . $this->action);
    }
  }

  /**
   * @return array
   */
  public function getRoutingConfig()
  {
    return $this->routingConfig;
  }

  /**
   * @param array $routingConfig
   */
  public function setRoutingConfig($routingConfig)
  {
    $this->routingConfig = $routingConfig;
  }


}