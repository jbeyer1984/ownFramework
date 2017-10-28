<?php


namespace MyApp\src\Finance\ConstData;


class SaveBauData
{
    /**
     * @var double
     */
    private $ansparphase;

    /**
     * @var double
     */
    private $zahlphase;

    /**
     * @var double
     */
    private $schaetzungperjahr;

    /**
     * @return float
     */
    public function getAnsparphase()
    {
        return $this->ansparphase;
    }

    /**
     * @param float $ansparphase
     * @return SaveBauData
     */
    public function setAnsparphase($ansparphase)
    {
        $this->ansparphase = $ansparphase;

        return $this;
    }

    /**
     * @return float
     */
    public function getZahlphase()
    {
        return $this->zahlphase;
    }

    /**
     * @param float $zahlphase
     * @return SaveBauData
     */
    public function setZahlphase($zahlphase)
    {
        $this->zahlphase = $zahlphase;

        return $this;
    }

    /**
     * @return float
     */
    public function getSchaetzungperjahr()
    {
        return $this->schaetzungperjahr;
    }

    /**
     * @param float $schaetzungperjahr
     * @return SaveBauData
     */
    public function setSchaetzungperjahr($schaetzungperjahr)
    {
        $this->schaetzungperjahr = $schaetzungperjahr;

        return $this;
    }
}