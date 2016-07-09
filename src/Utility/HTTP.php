<?php

namespace MyApp\src\Utility;

class HTTP
{
  private static $method;

  public function __construct()
  {
  }

  public static function reset()
  {
    self::$method = null;
  }

  public function init()
  {

  }

  public static function redirect($path)
  {
    $path = str_replace('.*/index.php/', '', $path);
    $path = str_replace('index.php/', '', $path);
    $host = $_SERVER['HTTP_HOST'];
    
    $url = self::getRedirectUri($path);
    
    if ('get' == strtolower($_SERVER['REQUEST_METHOD']) || !isset($_POST['ajaxCall'])) {
      header('Location: http://'.$host.$url);
    } elseif (isset($_POST['ajaxCall'])) {
      header('Location: http://'.$host.$url.'?ajax=true');
    }
    die();
  }

  public static function getMethod()
  {
    if (!self::$method) {
      if (isset($_POST['method'])) {
        self::$method = strtolower($_POST['method']);
      } else {
        self::$method = strtolower($_SERVER['REQUEST_METHOD']);
      }
    }
    return self::$method;
  }
  
  private static function getRedirectUri($path = '')
  {
    $uri = $_SERVER['REQUEST_URI'];
    
    $uriPieces = explode('/', $uri);
    $pathPieces = explode('/', $path);

    $newUri = array();
    $foundPiece = 0;
    foreach ($uriPieces as $key => $piece) {
      $newUri[] = $piece;
      
      if ('index.php' == $piece) {
        break;
      }
      
      $foundPiece++;
    }
    
    foreach ($pathPieces as $key => $piece) {
      $newUri[] = $piece; 
    }
    
    return implode('/', $newUri);
  }
}