<?php

use MyApp\src\Tasks\ParentChild\ChildModel;
use MyApp\src\Tasks\ParentChild\ExtendedReflectionClass;
use MyApp\src\Tasks\ParentChild\Joiner;
use MyApp\src\Tasks\ParentChild\JoinerTemp;
use MyApp\src\Tasks\ParentChild\Model;
use MyApp\src\Tasks\ParentChild\ParentModel;
use MyApp\src\Tasks\ParentChild\RelationTableOverview;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class ParenChildTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
    }

    public function testParentChild_Model_success()
    {
        $model = new ParentModel();
        $model->getDbData()->fetchById(1);
        
//        $dump = print_r($model->getFetchedData(), true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $model->getFetchedData() ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }

    public function testParentChildRelation_Joiner_success()
    {
        $parentModel = new ParentModel();
        $childModel = new ChildModel();
        $joiner = new Joiner();
        $parentModel->setChildModelArray(
            $joiner
                ->from($parentModel)
                ->joinOneToMany()
                ->to($childModel)
                ->fetch()
        );
        
        foreach ($parentModel->getChildModelArray() as $childModel) {
            /** @var ChildModel $childModel */
            $childModel->setDescription('hallo');
            $childModel->getDbData()->save($childModel);
            $dump = print_r($childModel->getDbData()->getFetchedData(), true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childModel->get ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        }
    }

    public function testParentChildRelation_Registry_success()
    {
        $parentModel = new ParentModel();
        $parentModel->setReceiptId(2);
        $parentModel->getDbData()->fetchById($parentModel);
//        $parentModel->setChildModelArray(
//            []
//        );

        foreach ($parentModel->getChildModelArray() as $childModel) {
            /** @var ChildModel $childModel */
            $childModel->setDescription('hallo');
            $childModel->getDbData()->save($childModel);
//            $dump = print_r($childModel, true);
//            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childModel ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            
            $dump = print_r($childModel->getDbData()->getFetchedData(), true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childModel->get ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        }

//        $parentModel->setChildModelArray(
//            []
//        );

        foreach ($parentModel->getChildModelArray() as $childModel) {
            /** @var ChildModel $childModel */
//            $childModel->setDescription('hallo');
//            $childModel->getDbData()->save($childModel);
            $dump = print_r($childModel->getDbData()->getFetchedData(), true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childModel->get ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        }
        
//        $secondParentModel = new ParentModel();
//        $secondParentModel->setReceiptId(1);
//        foreach ($secondParentModel->getChildModelArray() as $childModel) {
//            $dump = print_r($childModel->getDbData()->getFetchedData(), true);
//            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childModel->getDbData()->getFetchedData() ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//            
//        }
        
        
//        $dump = print_r($childReflected->getMethods(), true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childReflected->getDocComment() ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    }

    public function testChildParent_Registry_success()
    {
        $newChildModel = new ChildModel();
        $newChildModel->setReceiptItemsId(1);
        $newChildModel->setReceiptId(1);
        $newParentModel = $newChildModel->getParentModel();
        $data = $newParentModel->getDbData()->getFetchedData();
        $dump = print_r($data, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $data ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    }

    public function test_ParentChildSqlRelationCreate_Joiner_success()
    {
        $parentModel = new ParentModel();
        $childModel = new ChildModel();
        $joiner = new JoinerTemp();
        $parentModel->setChildModelArray(
            $joiner
                ->from($parentModel, 'receipt_id')
                ->joinOneToMany()
                ->to($childModel, 'receipt_id')
                ->save()
        );

        foreach ($parentModel->getChildModelArray() as $childModel) {
            /** @var ChildModel $childModel */
            $childModel->setDescription('hallo');
            $childModel->getDbData()->save($childModel);
            $dump = print_r($childModel->getDbData()->getFetchedData(), true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $childModel->get ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        }
    }

    public function test_readWrite_RelationTableOverview_success()
    {
        $relationTableOverview = new RelationTableOverview();
    }
}

