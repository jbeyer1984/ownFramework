<?php

namespace MyApp\src\Tasks\Flexible\Xml;

use SimpleXMLElement;

class Parser
{
    /**
     * @var string
     */
    private $xmlText;

    /**
     * @var SimpleXMLElement
     */
    private $xmlObject;

    /**
     * @var array
     */
    private $xmlArray;
    
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
        $json = json_encode($xmlData);
        $recursiveArray = json_decode($json, true);
        $recursiveArray = $this->fixXmlAttributes($recursiveArray);
        $this->xmlArray = $recursiveArray;
    }

    /**
     * @param array $recursiveArray
     * @return array
     */
    protected function fixXmlAttributes(&$recursiveArray)
    {
        foreach ($recursiveArray as $key => $data) {
            if ($key == '@attributes') {
                $recursiveArray['attributes'] = $recursiveArray[$key];
                unset($recursiveArray[$key]);
            } else {
                if (is_array($data)) {
                    $this->fixXmlAttributes($recursiveArray[$key]);
                }    
            }
        }
        
        return $recursiveArray;
    }

    /**
     * @return string
     */
    public function getXmlText()
    {
        return $this->xmlText;
    }

    /**
     * @param string $xmlText
     * @return Parser
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
     * @return Parser
     */
    public function setXmlObject($xmlObject)
    {
        $this->xmlObject = $xmlObject;

        return $this;
    }

    /**
     * @return array
     */
    public function getXmlArray()
    {
        return $this->xmlArray;
    }

    /**
     * @param array $xmlArray
     * @return Parser
     */
    public function setXmlArray($xmlArray)
    {
        $this->xmlArray = $xmlArray;

        return $this;
    }
}