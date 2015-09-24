<?php


namespace MyApp\src\Entities;

use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Utility\Db;
use MyApp\src\Components\Components;
use MyApp\src\Entities\Product;

class ProductRepository extends Tasks implements ResetInterface
{
  /**
   * @var Db
   */
  private $db;

  /**
   * @var Product
   */
  private $product;

  /**
   * @param $product Product
   * @throws \Exception
   */
  public function __construct($product = null)
  {
    parent::__construct();
    $this->product = $product;
    $this->db = $this->components->get('db');
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public function insertProduct()
  {
    $sql = "insert into Product set name=':name', owner=':owner'";
    $this->db->execute($sql, array(
      'name' => $this->product->getName(),
      'owner' => $this->product->getOwner()
    ));
  }

  public function getProductDataById($id)
  {
    $sql = "select * from Product where id=:id";
    $data = $this->db->execute($sql,array(
      'id' => $id
    ))->getData();
    return $data;
  }

  public function getLastId()
  {
    $sql = "select MAX(id) as maxId from Product";
    $data = $this->db->execute($sql)->getData();
    return $data[0]['maxId'];
  }

  public function getAllProductsData()
  {
    $sql = "select * from Product";
    $data = $this->db->execute($sql)->getData();
    return $data;
  }

  public function updateProduct($id)
  {
    $sql = "update Product set name=':name', owner=':owner' where id=:id";
    $this->db->execute($sql, array(
      'id' => $id,
      'name' => $this->product->getName(),
      'owner' => $this->product->getOwner()
    ));
  }

  public function deleteProduct($id)
  {

  }

  /**
   * @return Db
   */
  public function getDb()
  {
    return $this->db;
  }

  /**
   * @param Db $db
   */
  public function setDb($db)
  {
    $this->db = $db;
  }

  /**
   * @return \MyApp\src\Entities\Product
   */
  public function getProduct()
  {
    return $this->product;
  }

  /**
   * @param \MyApp\src\Entities\Product $product
   */
  public function setProduct($product)
  {
    $this->product = $product;
  }
}