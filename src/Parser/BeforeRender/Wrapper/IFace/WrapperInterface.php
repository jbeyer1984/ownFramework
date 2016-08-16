<?php

namespace MyApp\src\Parser\BeforeRender\Wrapper\IFace;

interface WrapperInterface
{
  public function addWrapper($wrapper);
  public function deleteWrapper($str);
}