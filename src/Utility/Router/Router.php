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
      ],
      'blog' => [
        'login' => [
          'params' => [
            'get' => '',
            'post' => 'email/password'
          ],
          'class' => 'MyApp\src\Tasks\Blog',
        ],
        'show' => [
          'params' => [
            'get' => ''
//            'post' => 'email/password'
          ],
          'class' => 'MyApp\src\Tasks\Blog',
        ],
        'logout' => [
          'params' => [
//            'get' => ''
            'post' => ''
          ],
          'class' => 'MyApp\src\Tasks\Blog',
        ]
      ]
    ];
    return $this;
  }
  
  public function route()
  {
    $route = new Route();
    $route->generate($this);
//    Components::getInstance()->get('logger')->log('$route', $route);

    $routeSettings = $this->routingConfig[$route->getSubject()][$route->getAction()];
    $class = $routeSettings['class'];
    $obj = new $class();

    Components::getInstance()->get('logger')->log('$route', $route);
    call_user_func_array(array($obj, $route->getAction()), $route->getParams());
    
//    Components::getInstance()->get('logger')->log('$params', $route->getParams());
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