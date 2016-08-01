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
      'docparser' => [
        'start' => [
          'params' => [
            'get' => '',
            'post' => 'input_string'
          ],
          'class' => 'MyApp\src\Tasks\Parser\DocParserController',
        ],
      ],
      'evalparser' => [
        'start' => [
          'params' => [
            'get' => '',
            'post' => 'input_string'
          ],
          'class' => 'MyApp\src\Tasks\Parser\EvalParserController',
        ],
      ],
      'tools' => [
        'overview' => [
          'params' => [
            'get' => '',
            'post' => ''
          ],
          'class' => 'MyApp\src\Tasks\Tools\ToolsController',
        ],
      ],
      'blog' => [
        'login' => [
          'params' => [
            'get' => '',
            'post' => 'email/password'
          ],
          'class' => 'MyApp\src\Tasks\Blog\Blog',
        ],
        'show' => [
          'params' => [
            'get' => ''
//            'post' => 'email/password'
            
          ],
          'class' => 'MyApp\src\Tasks\Blog\Blog',
        ],
        'logout' => [
          'params' => [
            'get' => '',
            'post' => ''
          ],
          'class' => 'MyApp\src\Tasks\Blog\Blog',
        ],
      ],
      'restcrud' => [
        'product' => [
          'params' => [
            'get' => 'id',
            'post' => 'name/owner',
            'put' => 'id/name/owner',
            'delete' => 'id'
          ],
          'class' => 'MyApp\src\Tasks\RestCrud\Product',
          'rest' => true,
        ],
        'showproducts' => [
          'params' => [
          ],
          'class' => 'MyApp\src\Tasks\RestCrud\Product',
        ],
      ],
      'message' => [
        'create' => [
          'params' => [
//            'get' => ''
            'post' => 'message'
          ],
          'class' => 'MyApp\src\Tasks\Blog\Message',
        ]
      ]
    ];
    return $this;
  }
  
  public function route()
  {
    $route = new Route();
    
    $uri = $_SERVER['REQUEST_URI'];
    $route->generate($this);
//    Components::getInstance()->get('logger')->log('$route', $route);

    $routeSettings = $this->routingConfig[$route->getSubject()][$route->getAction()];
//    Components::getInstance()->get('logger')->log('$route->getSubject()', $route->getSubject());
//    Components::getInstance()->get('logger')->log('$route->getAction()', $route->getAction());
//    Components::getInstance()->get('logger')->log('$routeSettings', $routeSettings);
    $class = $routeSettings['class'];
    $obj = new $class();

//    Components::getInstance()->get('logger')->log('$route', $route);
    
    if (isset($routeSettings['rest']) && $routeSettings['rest']) {
      $action = $route->getAction();
      $obj->$action($route->getParams());
    } else {
      call_user_func_array(array($obj, $route->getAction()), $route->getParams());
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