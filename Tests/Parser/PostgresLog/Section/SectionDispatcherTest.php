<?php

use MongoDB\Operation\Find;
use MyApp\src\Parser\PostgresLog\Collector\CollectorFromBegin;
use MyApp\src\Parser\PostgresLog\Collector\CollectorToEnd;
use MyApp\src\Parser\PostgresLog\Collector\Wrapper\CollectorOrWrapper;
use MyApp\src\Parser\PostgresLog\FindCondition\FinderCondition;
use MyApp\src\Parser\PostgresLog\FindCondition\FinderConditionEmpty;
use MyApp\src\Parser\PostgresLog\FindCondition\Wrapper\FinderConditionAndWrapper;
use MyApp\src\Parser\PostgresLog\FinderCollector\FinderCollector;
use MyApp\src\Parser\PostgresLog\Section\Section;
use MyApp\src\Parser\PostgresLog\Section\SectionDispatcher;
use MyApp\src\Parser\PostgresLog\Section\SectionRow;
use MyApp\src\Parser\PostgresLog\Section\SectionRowDispatcher;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class SectionDispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SectionDispatcher
     */
    private $sectionDispatcher;

    /**
     * @var string
     */
    private $txt;

    public function setUp()
    {
        $this->txt = <<<TXT
11:20:30 ipsum pseudo statement: SELECT
Sql Select Line one
Sql Select Line two
Sql Select Line three;
13:40:55 ipsum pseudo
13:40:55 ipsum pseudo
13:40:56 ipsum pseudo statement: INSERT INTO
Sql Insert Line one
Sql Insert Line two
Sql Insert Line three;
11:20:30 ipsum pseudo statement: SELECT
Sql Select Line one
Sql Select Line two
Sql Select Line three;
13:40:55 ipsum pseudo
13:40:55 ipsum pseudo
13:41:01 ipsum pseudo statement: UPDATE TABLE One LINE;
13:40:55 ipsum pseudo
13:40:55 ipsum pseudo
TXT;
        $lineArray = explode(PHP_EOL, $this->txt);

        $this->sectionDispatcher = new SectionDispatcher($lineArray);
    }
    
    public function testDispatching_SectionDispatcher_easy_success()
    {
        $section = $this->getCreatedSection('SELECT|INSERT|UPDATE');
        
        $this->sectionDispatcher->dispatch($section);
        $linesArray = $this->sectionDispatcher->getGrepLines();
        $dump = print_r($linesArray, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $linesArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    }

    public function testDispatching_SectionRowDispatcher_easy_success()
    {
        $sectionSelect = $this->getCreatedSection('SELECT');
        $sectionInsert = $this->getCreatedSection('INSERT');
        
        $sectionRow = new SectionRow();
        $sectionRow
            ->add($sectionSelect)
            ->add($sectionInsert)
        ;

        $lineArray = explode(PHP_EOL, $this->txt);
        
        $sectionRowDispatcher = new SectionRowDispatcher($lineArray);
        $sectionRowDispatcher->dispatch($sectionRow);
        $foundSqlArray = $sectionRowDispatcher->getSectionTextArray();
        $dump = print_r($foundSqlArray, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $foundSqlArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        
    }

    /**
     * @param $typeOfSql
     * @return Section
     */
    private function getCreatedSection($typeOfSql)
    {
        $explodedTypeOfSql = explode('|', $typeOfSql);
        $section = new Section();

        // pre condition
        $preCondition = new FinderConditionAndWrapper();
        $preCondition->add(new FinderCondition('statement:'));
        // collector begin
        $conditionRow = new FinderConditionAndWrapper();
        $conditionRow->add(new FinderCondition('statement:'));
        $collector = new CollectorFromBegin($typeOfSql);
        if (1 < count($explodedTypeOfSql)) {
            $collector = new CollectorOrWrapper($typeOfSql);
            foreach ($explodedTypeOfSql as $typeOfSql) {
                $collector
                    ->add(new CollectorFromBegin($typeOfSql))
                ;    
            }
        }
        $collectorBegin = new FinderCollector($conditionRow, $collector);
        // collector end
        $conditionRow = new FinderConditionAndWrapper();
        $conditionRow->add(new FinderConditionEmpty(''));
        $collector    = new CollectorToEnd(';');
        $collectorEnd = new FinderCollector($conditionRow, $collector);
        // post condition
        $postCondition = new FinderConditionAndWrapper();
        $postCondition->add(new FinderCondition('ipsum'));

        $section
            ->applyPreCondition($preCondition)
            ->applyCollectorBegin($collectorBegin)
            ->applyCollectorEnd($collectorEnd)
            ->applyPostCondition($postCondition)
        ;

        return $section;
    }
}