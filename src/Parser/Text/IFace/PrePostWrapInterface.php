<?php

namespace MyApp\src\Parser\Text\IFace;

interface PrePostWrapInterface
{

  /**
   * @param array [string] $cssClassArray
   * @return
   */
  public function preWrap($cssClassArray);
  public function postWrap();
}