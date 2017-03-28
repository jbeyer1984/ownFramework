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
items[0][0][division_id]:7
items[0][0][article_id]:510
items[0][0][value]:5
items[0][0][division_price_id]:29398
items[0][0][distributionchannel_id]:3
items[0][0][taxrate_id]:7
items[0][0][issideorder]:false
items[0][0][dp_set]:true
TXT;
        $this->postFilter->setOriginPostFilter($postmanBulkEdit);
        $this->postFilter->execute();
        $dump = print_r($this->postFilter->getPostFilter(), true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $this->postFilter->getPostFilter() ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }
}