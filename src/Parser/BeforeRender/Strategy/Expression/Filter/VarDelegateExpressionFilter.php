<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\Expression\Filter;

use MyApp\src\Parser\BeforeRender\Strategy\Expression\Expression;

class VarDelegateExpressionFilter extends ExpressionFilterAbstract
{

  public function __construct()
  {
  }

  /**
   * @param Expression $expression
   * @return bool
   */
  public function filter(Expression $expression)
  {
    if (false !== strpos($expression->getRight(), '->')) {
      return true;
    }
    
    return false;
  }
}