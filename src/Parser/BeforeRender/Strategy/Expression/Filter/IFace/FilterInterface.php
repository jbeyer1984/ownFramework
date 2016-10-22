<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\Expression\Filter\IFace;

use MyApp\src\Parser\BeforeRender\Strategy\Expression\Expression;

interface FilterInterface
{
  public function filter(Expression $expression);
}