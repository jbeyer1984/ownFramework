<?php

use MyApp\src\Parser\PostgresLog\Collector\CollectorFromBegin;
use MyApp\src\Parser\PostgresLog\Collector\CollectorToEnd;
use MyApp\src\Parser\PostgresLog\Collector\CollectorWholeLine;
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
2017-12-05 15:06:20 CET LOG:  Anweisung: DEALLOCATE pdo_stmt_00000009
2017-12-05 15:06:20 CET LOG:  Ausführen pdo_stmt_0000000a: SELECT "article".* FROM "mm"."article" WHERE (id = 507) LIMIT 1
2017-12-05 15:06:20 CET LOG:  Anweisung: DEALLOCATE pdo_stmt_0000000a
2017-12-05 15:06:20 CET LOG:  Ausführen pdo_stmt_0000000b:
                    SELECT mm.group.id, name, incomeaccount_id,
                    sum(ri.quantity)::integer AS sales_quantity,
                    (ahr.baseunitfactor * ahr.conversionvalue)::numeric(12,4), ahr.step + 1
                    FROM mm.group
                    INNER JOIN  mm.article_group
                    ON mm.group.id = mm.article_group.group_id
                    WHERE mm.article_group.article_id = 507
2017-12-05 15:06:20 CET LOG:  Anweisung: DEALLOCATE pdo_stmt_0000000b
2017-12-05 15:06:20 CET LOG:  Ausführen pdo_stmt_0000000c:
                    SELECT *
                    FROM mm.category
                    INNER JOIN  mm.article_category
                    ON mm.category.id = mm.article_category.category_id
                    WHERE mm.article_category.article_id = 507
2017-12-05 15:06:20 CET LOG:  Anweisung: DEALLOCATE pdo_stmt_0000000c
2017-12-05 15:06:20 CET LOG:  Ausführen pdo_stmt_0000000d:
2017-12-06 11:07:10 CET LOG:  Ausführen pdo_stmt_00000010:  SELECT mm.insert_copy_articlerow(680, 681, 'mm', 'article_supplier', 'article_id');
2017-12-06 11:07:10 CET LOG:  Anweisung: DEALLOCATE pdo_stmt_00000010
2017-12-06 11:07:10 CET LOG:  Ausführen pdo_stmt_00000011:
            WITH oldareas AS (
        SELECT * FROM pp.article_area AS aa0
        JOIN pp.area AS a ON a.name = 'DEFAULT'
        WHERE aa0.area_id != a.id  AND aa0.article_id = $1
    )

    UPDATE pp.article_area AS aa
    SET area_id = oa.area_id
    FROM oldareas AS oa
    WHERE
    oa.division_id = aa.division_id
    AND oa.article_id = $1
    AND aa.article_id = $2


2017-12-06 11:07:10 CET DETAIL:  Parameter: $1 = '680', $2 = '681'
2017-12-06 11:07:10 CET LOG:  Anweisung: DEALLOCATE pdo_stmt_00000010

TXT;
//        $this->txt = file_get_contents('/var/log/postgresql/postgresql-9.4-main.log');
        $lineArray = explode(PHP_EOL, $this->txt);

//        $lastLogCountOfLines = 0;
//        $propertiesFileName = '/tmp/postgresLogProperties';
//        $propertiesArray = [];
//        $countLineArray      = count($lineArray);
//        $propertiesArray = [
//            'countOfLines' => $countLineArray
//        ];
//        if (file_exists($propertiesFileName)) {
//            $propertiesArrayRead = json_decode(file_get_contents($propertiesFileName), true);
//            $lastLogCountOfLines = $propertiesArrayRead['countOfLines'];
//            if ($countLineArray > $lastLogCountOfLines) {
//                $propertiesArray = [
//                    'countOfLines' => $lastLogCountOfLines + (count($lineArray) - $lastLogCountOfLines)
//                ];
//            }
//        }
//        $dump = print_r($lastLogCountOfLines, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' -> ' . __METHOD__ . PHP_EOL . '*** $lastLogCountOfLines ***' . PHP_EOL . " = " . $dump . PHP_EOL, 3, '/home/jens/error.log');
//
//        file_put_contents($propertiesFileName, json_encode($propertiesArray));
//
//        $lineArray = array_slice($lineArray, $lastLogCountOfLines);
//
//        $dump = print_r(count($lineArray), true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' -> ' . __METHOD__ . PHP_EOL . '*** count($lineArray) ***' . PHP_EOL . " = " . $dump . PHP_EOL, 3, '/home/jens/error.log');


        $this->sectionDispatcher = new SectionDispatcher($lineArray);
    }
    
    public function testDispatching_SectionDispatcher_easy_success()
    {
        $section = $this->getCreatedSection('SELECT|INSERT|UPDATE|WITH');
        
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
        $preCondition->add(new FinderCondition('CET LOG:'));
        // collector begin
        $conditionRow = new FinderConditionAndWrapper();
        $conditionRow->add(new FinderCondition(' '));
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
        $conditionRow->add(new FinderConditionEmpty(' '));
        $collector    = new CollectorWholeLine(' ');
        $collectorEnd = new FinderCollector($conditionRow, $collector);
        // post condition
        $postCondition = new FinderConditionAndWrapper();
        $postCondition->add(new FinderCondition('CET LOG'));

        $section
            ->applyPreCondition($preCondition)
            ->applyCollectorBegin($collectorBegin)
            ->applyCollectorEnd($collectorEnd)
            ->applyPostCondition($postCondition)
        ;

        return $section;
    }
}