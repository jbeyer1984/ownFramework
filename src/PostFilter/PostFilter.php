<?php
/**
 * Created by jbeyer
 * Date: 24.03.2017
 * Time: 17:30
 */

namespace MyApp\src\PostFilter;

class PostFilter
{
    /**
     * @var string
     */
    private $originPostFilter;

    /**
     * @var string
     */
    private $postFilter;

    /**
     * @var string
     */
    private $postFilterText;
    
    private $tabSpace = '';

    /**
     * @param string $originPostFilter
     */
    public function __construct($originPostFilter)
    {
        $this->originPostFilter = $originPostFilter;
        $this->init();
    }

    protected function init()
    {
        $this->postFilter = []; // tab space = 4
        $this->tabSpace = '    ';
    }

    public function execute()
    {
        if (empty($this->originPostFilter)) {
            return;
        }

        $parsedFilterArray = $this->getParsedPostFilterArray($this->originPostFilter);
        $postFilterText = '[' . PHP_EOL;
        $space = [
            $this->tabSpace
        ];
        $postFilterText .= $this->getCreatedRecursiveArrayText($parsedFilterArray, $postFilterText, $space);
        $postFilterText .= "]";
        
        $this->postFilterText = $postFilterText;
    }

    /**
     * @param $postFilterText
     * @return array
     */
    protected function getParsedPostFilterArray($postFilterText)
    {
        $postFilterArray = explode(PHP_EOL, $postFilterText);
        $itemData = [];

        foreach ($postFilterArray as $line) {
            $secondToLastLevelWithValue = explode(':', explode('items', $line)[1]);
            $secondToLastLevel = $secondToLastLevelWithValue[0];
            $value = $secondToLastLevelWithValue[1];
            $keys = explode(']', $secondToLastLevel); // create nested identifiers
            $keys = array_filter($keys, function ($row) {
                if ('' != $row) {
                    return true;
                }
                return false;
            });
            $keys = array_map(function($row) {
                return str_replace('[', '', $row);
            }, $keys);

            $beginItemData = &$itemData;

            $count = 0;
            $countKeys = count($keys);
            foreach ($keys as $key) { // go through nested identifiers and equip them to array
                if ($countKeys-1 > $count) { // if not last nested
                    if (!isset($beginItemData[$key])) {
                        $beginItemData[$key] = [];
                    }
                    $beginItemData = &$beginItemData[$key];
                } else {
                    $beginItemData[$key] = trim($value);
                }
                $count++;
            }
        }
        
        return $itemData;
    }

    /**
     * @param array $parsedFilterArray
     * @param string $text
     * @param array $space
     * @return string
     */
    protected function getCreatedRecursiveArrayText($parsedFilterArray, $text, $space)
    {
        $nestedTabSpaces = implode('', $space);
        foreach ($parsedFilterArray as $key => $nestedData) {
            $identifier = $key;
            if (!is_numeric($key)) {
                $identifier = "'" . $key . "'";
            }

            if (!is_array($nestedData)) {
                $value = $nestedData;

                if (!is_numeric($value)) {
                    $value = "'" . $value . "'";
                }

                $text .= $nestedTabSpaces . $identifier . ' => ' . $value . ',' . PHP_EOL;
            } else {

                $text .= $nestedTabSpaces . "[" . $identifier . "]" . ' => ' . "[" . PHP_EOL;
                $space[] = $this->tabSpace;
                $text .= $this->getCreatedRecursiveArrayText($nestedData, '', $space);
                array_pop($space);
                $text .= $nestedTabSpaces . "]";
                $text .= PHP_EOL;
            }
        }
        
        return $text;
    }

    /**
     * @return string
     */
    public function getOriginPostFilter()
    {
        return $this->originPostFilter;
    }

    /**
     * @param string $originPostFilter
     * @return PostFilter_PostFilter
     */
    public function setOriginPostFilter($originPostFilter)
    {
        $this->originPostFilter = $originPostFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getPostFilter()
    {
        return $this->postFilter;
    }

    /**
     * @param array $postFilter
     * @return PostFilter_PostFilter
     */
    public function setPostFilter($postFilter)
    {
        $this->postFilter = $postFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostFilterText()
    {
        return $this->postFilterText;
    }

    /**
     * @param string $postFilterText
     * @return $this
     */
    public function setPostFilterText($postFilterText)
    {
        $this->postFilterText = $postFilterText;

        return $this;
    }
}
