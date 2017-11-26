<?php

use MyApp\src\Parser\PostgresLog\CollectorCondition\FinderCollector;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class CollectorTest extends PHPUnit_Framework_TestCase
{
    public function testCollectedText_Collector_success()
    {
        $str = 'Any to find in this text';
        $collector = new FinderCollector('find');
        $found = $collector->find($str);
        $this->assertEquals(true, $found);
        
        $str = str_replace('find', '', $str);
        $found = $collector->find($str);
        $this->assertEquals(false, $found);
    }
}