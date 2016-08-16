<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\IFace;

use MyApp\src\Parser\BeforeRender\Wrapper\AbstractWrapper;
use MyApp\src\Parser\BeforeRender\Wrapper\IFace\WrapperInterface;

interface ParserStrategyInterface
{
  /**
   * @param array[string] $allLines
   * @return void
   */
  public function buildParserArrays($allLines);

  /**
   * @param string $varName
   * @param int $lineNum
   * @param AbstractWrapper $wrapper
   * @return
   */
  public function wrapVar($varName, $lineNum, AbstractWrapper $wrapper);
}