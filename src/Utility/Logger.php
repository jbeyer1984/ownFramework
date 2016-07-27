<?php

namespace MyApp\src\Utility;

class Logger
{
  public function __construct()
  {
    
  }
  
  public function log($identifier, $var)
  {
    $print = print_r($var, true);
    error_log("$$identifier = " . $print);
  }
}
