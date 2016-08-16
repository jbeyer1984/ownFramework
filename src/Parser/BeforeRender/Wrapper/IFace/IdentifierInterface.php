<?php

namespace MyApp\src\Parser\BeforeRender\Wrapper\IFace;

interface IdentifierInterface
{
  /**
   * @param string $identifier
   * @return void
   */
  public function setIdentifier($identifier);
}