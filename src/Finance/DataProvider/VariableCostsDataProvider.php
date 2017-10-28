<?php


namespace MyApp\src\Finance\DataProvider;


class VariableCostsDataProvider
{
    /**
     * @var double
     */
    private $toPayOrigin;

    /**
     * @var double
     */
    private $inPerYear;

    public function __construct($toPayOrigin, $inPerYear)
    {
        $this->toPayOrigin = $toPayOrigin;
        $this->inPerYear = $inPerYear;
        $this->init();
    }

    protected function init()
    {
    }

    /**
     * @return float
     */
    public function getToPayOrigin()
    {
        return $this->toPayOrigin;
    }

    /**
     * @return float
     */
    public function getInPerYear()
    {
        return $this->inPerYear;
    }
}