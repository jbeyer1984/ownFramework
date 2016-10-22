<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\Expression\Filter;

use MyApp\src\Parser\BeforeRender\Strategy\Expression\Expression;

abstract class ExpressionFilterAbstract
{
  /**
   * @var Expression
   */
  protected $parent;

  /**
   * @var Expression
   */
  protected $left;

  /**
   * @var Expression
   */
  protected $right;

  /**
   * @return Expression
   */
  public function getParent()
  {
    return $this->parent;
  }

  /**
   * @param Expression $parent
   * @return ExpressionFilterAbstract
   */
  public function setParent($parent)
  {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return Expression
   */
  public function getLeft()
  {
    return $this->left;
  }

  /**
   * @param Expression $left
   * @return ExpressionFilterAbstract
   */
  public function setLeft($left)
  {
    $this->left = $left;
    return $this;
  }

  /**
   * @return Expression
   */
  public function getRight()
  {
    return $this->right;
  }

  /**
   * @param Expression $right
   * @return ExpressionFilterAbstract
   */
  public function setRight($right)
  {
    $this->right = $right;
    return $this;
  }

  public abstract function filter(Expression $expression);
}