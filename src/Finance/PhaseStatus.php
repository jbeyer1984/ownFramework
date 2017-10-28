<?php


namespace MyApp\src\Finance;


class PhaseStatus
{
    /**
     * @var AmountTaxStatus
     */
    private $savePhase;

    /**
     * @var AmountTaxStatus
     */
    private $costPhase;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->savePhase = new AmountTaxStatus();
        $this->costPhase = new AmountTaxStatus();
    }

    /**
     * @return AmountTaxStatus
     */
    public function getSavePhase()
    {
        return $this->savePhase;
    }

    /**
     * @param AmountTaxStatus $savePhase
     * @return PhaseStatus
     */
    public function setSavePhase($savePhase)
    {
        $this->savePhase = $savePhase;

        return $this;
    }

    /**
     * @return AmountTaxStatus
     */
    public function getCostPhase()
    {
        return $this->costPhase;
    }

    /**
     * @param AmountTaxStatus $costPhase
     * @return PhaseStatus
     */
    public function setCostPhase($costPhase)
    {
        $this->costPhase = $costPhase;

        return $this;
    }
}