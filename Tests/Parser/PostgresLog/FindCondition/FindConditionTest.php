<?php

use MyApp\src\Parser\PostgresLog\FindCondition\FinderInterface;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class FindConditionTest extends PHPUnit_Framework_TestCase
{
    public function testFindStr_FindCondition_success()
    {
        $str = 'Any to find in this text';
        $findCondition = new FinderInterface('find');
        $found = $findCondition->find($str);
        $this->assertEquals(true, $found);
        
        $str = str_replace('find', '', $str);
        $found = $findCondition->find($str);
        $this->assertEquals(false, $found);
    }
}