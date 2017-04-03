<?php

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Tasks\Flexible\Xml\Creator;
use MyApp\src\Tasks\Flexible\Xml\Parser;

class CreatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Creator
     */
    private $creator;

    public function setUp()
    {
        $xmlText = <<<TXT
<overview>
    <receipt bookingTime="2016-06-24T08:09:17.722+01:00" cashDeskVersion="ERROR" customerCounter="1" discountAmount="0" memory="real" receiptAmount="2.5" receiptPrinted="false" receiptStamp="3-4368" receivedMiles="0" redeemedMiles="0" rfidTag="" tip="0" type="refund" userId="user-54">
        <receiptPositions articleID="article-32" discountAmount="0.00" distributionChannel="3" distributionChannelName="Delivery" milesPayment="0" price="2.5" quantity="1" receivedMiles="0" redeemedMiles="0" serviceId="user-45" sum="2.5" tax="0.4" taxOriginal="0.4" taxRate="19.00" taxRateCode="A" taxRateId="taxrate-6" turnoverType="sales">
            <article id="32" name="Coffee"/>
            <service firstName="Ben" id="54" lastName="Naish"/>
        </receiptPositions>
        <receiptPositions articleID="article-33" discountAmount="0.00" distributionChannel="3" distributionChannelName="Delivery" milesPayment="0" price="2.5" quantity="1" receivedMiles="0" redeemedMiles="0" serviceId="user-45" sum="2.5" tax="0.4" taxOriginal="0.4" taxRate="19.00" taxRateCode="A" taxRateId="taxrate-6" turnoverType="sales">
            <article id="32" name="Coffee"/>
            <service firstName="Ben" id="54" lastName="Naish"/>
        </receiptPositions>
        <seatNumber>-1</seatNumber>
        <header>
            ![CDATA[!I!!N!!C!Delivery!N!!C!Große Elbstrasse 212!N!!C!22767 Hamburg!N!!N!]]
        </header>
        <footer>
            ![CDATA[!C!Ust. ID: DE 194 933 114!N!!N!!C!!B!www.sushi-factory.com]]
        </footer>
        <journal>
            05.12.2016 - 12:08 - von Laffert, Bodo: ChangeSetAmountPrinted, 05.12.2016 - 12:08 - von Laffert, Bodo: ChangeSetAmountPrinted, 05.12.2016 - 12:08 - von Laffert, Bodo: ChangeSetAmountPrinted, Buchungsmodus gewechselt: real, Buchungsmodus gewechselt: sales, 05.12.2016 - 12:08 - von Laffert, Bodo: Sitz hinzugefügt: -1.-1, Buchungsmodus gewechselt: sales, 05.12.2016 - 12:08 - von Laffert, Bodo: Artikel hinzugefügt: 1 x Kat. F (Item 1001), 05.12.2016 - 12:08 - von Laffert, Bodo: Artikel hinzugefügt: 1 x Kat. E (Item 1002), 05.12.2016 - 12:08 - von Laffert, Bodo: Artikel hinzugefügt: 1 x Small Box (Item 1003), 05.12.2016 - 12:08 - von Laffert, Bodo: Anzahl Kunden geändert: 1, 05.12.2016 - 12:08 - von Laffert, Bodo: Nummer vergeben (automatisch): 1-1000, 05.12.2016 - 12:08 - von Laffert, Bodo: ChangeOrderName, 05.12.2016 - 12:08 - von Laffert, Bodo: Zahlungsweg: Cash, 05.12.2016 - 12:08 - von Laffert, Bodo: ChangeRemoveTip, 05.12.2016 - 12:08 - von Laffert, Bodo: ChangeIsFinished,
        </journal>
    </receipt>
</overview>
TXT;

        $this->parser = new Parser($xmlText);
    }

    public function testRecursiveArrayToXml_Creator_success()
    {
//        $xmlArray = $this->parser->getParsedData();
        $creator = new Creator($this->parser);
        $xmlString = $creator->getConvertedData();
//        $dump = print_r($xmlString, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $xmlString ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        file_put_contents(VAR_WWW . '/public/files/parsedXml.xml', $xmlString);
    }
}
