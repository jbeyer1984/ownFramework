<?php
use MyApp\src\Tasks\PostFilter\PostFilter;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class PostFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PostFilter
     */
    private $postFilter;
    
    public function setup()
    {
        $this->postFilter = new PostFilter('');    
    }

    public function testPostFilterTextLikeCode_Task_success()
    {
        $postmanBulkEdit = <<<TXT
items[0][article_id]:530
items[0][article_name]:Scheisse mit Erdbeeren
items[0][basefactor]:1
items[0][consumptiontype_id]:1
items[0][document_id]:370
items[0][item_id]:
items[0][quantity]:100
items[0][unit_id]:20
items[0][dry][hot]:clean
TXT;
        $this->postFilter->setOriginPostFilter($postmanBulkEdit);
        $this->postFilter->execute();
        $dump = print_r($this->postFilter->getPostFilter(), true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $this->postFilter->getPostFilter() ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }
}