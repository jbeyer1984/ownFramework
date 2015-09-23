<?php


namespace MyApp\src\Factories;

use MyApp\src\Entities\ProductRepository;
use MyApp\src\Entities\Product;

class ProductFactory
{
  private static $instance;

  private function __construct()
  {
  }

  private function __clone()
  {
  }

  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * @param int $id
   * @return Product
   */
  public function retCreatedProduct($id = 0) // 0 for dump user
  {
    $product = new Product();
    $product->setId($id);
    $userRepository = new ProductRepository($product);
    $product->setRepository($userRepository);
    $data = $product->getRepository()->getProductDataById($id);
    foreach ($data[0] as $identifier => $value) {
      $func = 'set'.ucfirst($identifier);
      $product->$func($value);
    }
    return $product;
  }

  /**
   * @return ProductRepository
   */
  public function retCreatedProductRepository()
  {
    $productRepository = new ProductRepository();
    return $productRepository;
  }
}