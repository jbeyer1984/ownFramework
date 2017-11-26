<?php


namespace MyApp\src\Parser\PostgresLog\Collector;


use MyApp\src\Parser\PostgresLog\FinderInterface;

class CollectorToEnd extends Collector implements FinderInterface
{
    protected function init()
    {
        $this->grepString = '';
    }

    /**
     * @param string $str
     * @return bool
     */
    public function find($str)
    {
        $found = (false !== stripos($str, $this->strToFind));

        if ($found) {
            $this->determineGrepString($str);
        }

        return $found;
    }

    /**
     * @param $str
     */
    protected function determineGrepString($str)
    {
        $foundPos = stripos($str, $this->strToFind);
        $stringGrep = substr($str, 0, $foundPos+1);

        $this->grepString = $stringGrep;
    }
}