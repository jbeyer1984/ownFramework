<?php

namespace MyApp\src\Parser\BeforeRender\Wrapper\Text\IFace;

interface ManipulatedStringInterface
{
  /**
   * @return string
   */
  public function getManipulatedString();
}