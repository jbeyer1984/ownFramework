<?php

namespace MyApp\src\Parser\Wrapper\IFace;

interface WrapperInterface
{
  public function addWrapper($wrapper, $str);
  public function deleteWrapper($str);
}