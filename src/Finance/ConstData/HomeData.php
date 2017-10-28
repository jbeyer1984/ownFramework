<?php


namespace MyApp\src\Finance\ConstData;


class HomeData
{
    /**
     * @var double
     */
    private $wohnung;

    /**
     * @var double
     */
    private $ownMoney;

    /**
     * @var double
     */
    private $grunderwerbssteuer;

    /**
     * @var double
     */
    private $notar;

    /**
     * @var double
     */
    private $makler;

    /**
     * @var double
     */
    private $sonstiges;

    /**
     * @return float
     */
    public function getWohnung()
    {
        return $this->wohnung;
    }

    /**
     * @param float $wohnung
     * @return HomeData
     */
    public function setWohnung($wohnung)
    {
        $this->wohnung = $wohnung;

        return $this;
    }

    /**
     * @return float
     */
    public function getOwnMoney()
    {
        return $this->ownMoney;
    }

    /**
     * @param float $ownMoney
     * @return HomeData
     */
    public function setOwnMoney($ownMoney)
    {
        $this->ownMoney = $ownMoney;

        return $this;
    }

    /**
     * @return float
     */
    public function getGrunderwerbssteuer()
    {
        return $this->grunderwerbssteuer;
    }

    /**
     * @param float $grunderwerbssteuer
     * @return HomeData
     */
    public function setGrunderwerbssteuer($grunderwerbssteuer)
    {
        $this->grunderwerbssteuer = $grunderwerbssteuer;

        return $this;
    }

    /**
     * @return float
     */
    public function getNotar()
    {
        return $this->notar;
    }

    /**
     * @param float $notar
     * @return HomeData
     */
    public function setNotar($notar)
    {
        $this->notar = $notar;

        return $this;
    }

    /**
     * @return float
     */
    public function getMakler()
    {
        return $this->makler;
    }

    /**
     * @param float $makler
     * @return HomeData
     */
    public function setMakler($makler)
    {
        $this->makler = $makler;

        return $this;
    }

    /**
     * @return float
     */
    public function getSonstiges()
    {
        return $this->sonstiges;
    }

    /**
     * @param float $sonstiges
     * @return HomeData
     */
    public function setSonstiges($sonstiges)
    {
        $this->sonstiges = $sonstiges;

        return $this;
    }
}