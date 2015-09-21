<?php

define( 'VAR_WWW', '/var/www/ownFramework' );
require_once(VAR_WWW.'/src/bootstrap.php');
require_once(ROOT_PATH.'/vendor/autoload.php');

use MyApp\src\Utility\Router\Router;
use MyApp\src\Utility\Router\Route;
use \Mockery as m;

class RouteTest extends PHPUnit_Framework_TestCase
{
  /**
   * @var Router
   */
  public $router;

  public $routerConfig;

  /**
   * @var Route
   */
  public $route;

  public function setUp()
  {
    $this->router = new Router();
    $this->route = new Route();
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login';

    // params without get or post parameter
    $this->routerConfig = [
      'blog' => [
        'login' => [
          'params' => [
          ],
          'class' => 'MyApp\src\Tasks\Blog\Blog',
        ]
      ]
    ];
    $this->router->setRoutingConfig($this->routerConfig);
  }

  public function testSubjectAndAction()
  {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getSubject(), 'blog');
    $this->assertEquals($this->route->getAction(), 'login');
  }

  public function testGetRouteWithoutGetParameterInConfig()
  {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  public function testGetRouteWithGetParameterButEmptyString()
  {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $this->routerConfig['blog']['login']['params']['get'] = '';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  public function testGetRouteWithParameters()
  {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login/test@yes.com/success';
    $this->routerConfig['blog']['login']['params']['get'] = 'email/password';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $params = [
      0 => 'test@yes.com',
      1 => 'success'
    ];
    $this->assertEquals($this->route->getParams(), $params);
  }

  public function testPostRouteWithoutPostParameterInConfig()
  {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  public function testPostRouteWithPostParameterButEmptyString()
  {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $this->routerConfig['blog']['login']['params']['post'] = '';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  public function testPostRouteWithParameters()
  {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [
      'email' => 'test@yes.com',
      'password' => 'success'
    ];
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login';
    $this->routerConfig['blog']['login']['params']['post'] = 'email/password';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $params = [
      0 => 'test@yes.com',
      1 => 'success'
    ];
    $this->assertEquals($this->route->getParams(), $params);
  }
}