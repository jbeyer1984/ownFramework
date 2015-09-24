<?php

namespace MyApp\src\Tasks\RestCrud;

use MyApp\src\Factories\ProductFactory;
use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Components\Components;
use MyApp\src\Utility\HTTP;
use MyApp\src\Tasks\Tasks;


class Product extends Tasks implements ResetInterface
{
  public function __construct()
  {
    parent::__construct();
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public function product($data) //$id = 0, $productName = '', $productOwner = '')
  {
    Components::getInstance()->get('logger')->log('product --------  xxxxxxx -------- $data', $data);
    $id = (isset($data['id'])) ? $data['id'] : 0;
    $productName = (isset($data['name'])) ? $data['name'] : '';
    $productOwner = (isset($data['owner'])) ? $data['owner'] : '';

    $strategy = HTTP::getMethod();
    switch ($strategy) {
      case 'get':
        $this->show($id);
        break;
      case 'post':
        $this->create($productName, $productOwner);
        break;
      case 'put':
        $this->update($id, $productName, $productOwner);
        break;
      case 'delete':
        $this->delete($id);
        break;
    }
  }

  public function showproducts()
  {
    $product = ProductFactory::getInstance()->retCreatedProduct(); // dump user with id = 0
    $repository = $product->getRepository();
    $productsData = $repository->getAllProductsData();
    array_shift($productsData);

    $template = 'RestCrud/'.strtolower(__FUNCTION__).'/'.strtolower(__FUNCTION__);

    $serverRequestMethod = HTTP::getMethod();
    Components::getInstance()->get('logger')->log('$serverRequestMethod', $serverRequestMethod);
    if ('post' == $serverRequestMethod || isset($_GET['ajax'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }
    echo $this->components->get('view')->render($template, array(
      'products' => $productsData,
      'templateContext' => 'showproducts'
    ));
  }

  public function show($id)
  {
    $product = ProductFactory::getInstance()->retCreatedProduct($id); // dump user with id = 0
    $repository = $product->getRepository();
    $productsData = $repository->getProductDataById($id);

    $template = 'RestCrud/product/product';

    $serverRequestMethod = HTTP::getMethod();
    Components::getInstance()->get('logger')->log('$serverRequestMethod', $serverRequestMethod);
    if ('post' == $serverRequestMethod || isset($_GET['ajax'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }
    echo $this->components->get('view')->render($template, array(
      'products' => $productsData,
      'templateContext' => 'product'
    ));
  }

  public function create($productName, $productOwner)
  {
    $product = ProductFactory::getInstance()->retCreatedProduct();
    $product->setName($productName);
    $product->setOwner($productOwner);
    $repository = $product->getRepository();
    $repository->insertProduct();
    $lastId = $repository->getLastId();
    Components::getInstance()->get('logger')->log('$lastId', $lastId);
    HTTP::redirect('restcrud/product/'.$lastId);
  }

  public function update($id, $productName, $productOwner)
  {
    $product = ProductFactory::getInstance()->retCreatedProduct($id);
    $product->setName($productName);
    $product->setOwner($productOwner);
    $repository = $product->getRepository();
    $repository->updateProduct($id);
    HTTP::redirect('restcrud/product/'.$id);

  }

  public function delete($id)
  {
    $repository = ProductFactory::getInstance()->retCreatedProductRepository();
    $repository->deleteProduct($id);
    HTTP::redirect('restcrud/showproducts');
  }


}