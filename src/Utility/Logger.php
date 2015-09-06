<?php

namespace MyApp\src\Utility;

class Logger
{
  public function __construct()
  {
    
  }
  
  public function log($identifier, $var)
  {
    ob_start();
    print_r($var);
    $print = ob_get_clean();
    error_log("$$identifier = " . $print, 0, '/tmp/error.log');
  }
}
