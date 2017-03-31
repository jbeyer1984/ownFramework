<?php

namespace MyApp\src\Tasks\Flexible\Xml;

use SimpleXMLElement;

class Shaper
{
    /**
     * @var
     */
    private $xmlText;

    /**
     * @var SimpleXMLElement
     */
    private $xmlObject;
    
    public function __construct($xmlText)
    {
        $this->xmlText = $xmlText;
        $this->init();
    }

    protected function init()
    {
        $this->xmlObject = null;
    }

    public function parseXml()
    {
        $xmlText = $this->xmlText;
        $xmlData = simplexml_load_string($xmlText);
        $this->xmlObject = $xmlData;
        $recursiveArray = $this->getRecursiveXmlArray($xmlData, []);
        $dump = print_r($recursiveArray, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $recursiveArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }

    /**
     * @param SimpleXMLElement $xmlData
     * @param array $recursiveArray
     * @return array
     */
    protected function getRecursiveXmlArray($xmlData, $recursiveArray)
    {
        foreach ($xmlData as $key => $data) {
            /** @var SimpleXMLElement $data */
            
            if (empty((array)$data->attributes())) {
                $value = (string)$data;
                $recursiveArray[$key] = $value; 
            } else {
                $recursiveArray[$key] = $this->getRecursiveXmlArray($data->attributes(), []);
            }
        }
        
        return $recursiveArray;
    }

    /**
     * @return mixed
     */
    public function getXmlText()
    {
        return $this->xmlText;
    }

    /**
     * @param mixed $xmlText
     * @return Shaper
     */
    public function setXmlText($xmlText)
    {
        $this->xmlText = $xmlText;

        return $this;
    }

    /**
     * @return SimpleXMLElement
     */
    public function getXmlObject()
    {
        return $this->xmlObject;
    }

    /**
     * @param SimpleXMLElement $xmlObject
     * @return Shaper
     */
    public function setXmlObject($xmlObject)
    {
        $this->xmlObject = $xmlObject;

        return $this;
    }
}