<?php


use MyApp\src\Parser\PostgresLog\Collector\CollectorFromBegin;
use MyApp\src\Parser\PostgresLog\FindCondition\FinderCondition;
use MyApp\src\Parser\PostgresLog\FindCondition\Wrapper\FinderConditionAndWrapper;
use MyApp\src\Parser\PostgresLog\FinderCollector\FinderCollector;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class FinderCollectorTest extends PHPUnit_Framework_TestCase
{
    public function testCollectedText_Collector_success()
    {
        $str = 'Any to find in this text';
        $finderConditionAndWrapper = (new FinderConditionAndWrapper())
            ->add(new FinderCondition('find'))
        ;
        
        $collector = new CollectorFromBegin('find');
        $finderCollector = new FinderCollector($finderConditionAndWrapper, $collector);
        $found = $finderCollector->getGrepString($str);
        $this->assertEquals('find in this text', $found);
        
        $str = str_replace('find', '', $str);
        $found = $collector->find($str);
        $this->assertEquals(false, $found);
    }
}