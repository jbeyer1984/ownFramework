<?php


namespace MyApp\src\Parser\BeforeRender\Strategy\Expression;


abstract class ExpressionAbstract
{
  /**
   * @var string
   */
  protected $left;
  /**
   * @var string
   */
  protected $right;

  /**
   * @var bool
   */
  protected $marked;
  

  /**
   * @return string
   */
  public function getLeft()
  {
    return $this->left;
  }

  /**
   * @param string $left
   * @return Expression
   */
  public function setLeft($left)
  {
    $this->left = $left;
    
    return $this;
  }

  /**
   * @return string
   */
  public function getRight()
  {
    return $this->right;
  }

  /**
   * @param string $right
   * @return Expression
   */
  public function setRight($right)
  {
    $this->right = $right;
    
    return $this;
  }

  /**
   * @return boolean
   */
  public function isMarked()
  {
    return $this->marked;
  }

  /**
   * @param boolean $marked
   * @return ExpressionAbstract
   */
  public function setMarked($marked)
  {
    $this->marked = $marked;
    
    return $this;
  }
}