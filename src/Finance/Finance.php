<?php

namespace MyApp\src\Finance;

use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\LoopedCalculations\BankRedemptionLooped;
use MyApp\src\Finance\LoopedCalculations\SaveCostsBeginYearsLooped;
use MyApp\src\Finance\LoopedCalculations\SaveCostsFurtherYearsLooped;

class Finance
{
    /**
     * @var ConstData
     */
    private $constData;

    /**
     * @var array
     */
    private $output;
    
    public function __construct(ConstData $constData)
    {
        $this->constData = $constData;
        $this->init();
    }

    protected function init()
    {
        $this->output = [];
    }

    public function calculation()
    {
        //init
        $payHome = $this->constData->getHomeData()->getWohnung();
        $others = $this->constData->getHomeData()->getOwnMoney();
        $groundTax = $this->constData->getHomeData()->getGrunderwerbssteuer();
        $notar = $this->constData->getHomeData()->getNotar();
        $makler = $this->constData->getHomeData()->getMakler();
        $costsOfAllInstances = $payHome * (1 + ($groundTax + $notar + $makler)/100) + $others;
        $ownMoney = $this->constData->getHomeData()->getOwnMoney();
        $toPayOrigin = $costsOfAllInstances - $ownMoney;
        $this->output['betrag_mit_abzug'] = $toPayOrigin;
        $this->output['betrag_ansparen'] = $toPayOrigin * 0.4;
        
        $this->constData->setPayOrigin($toPayOrigin);
        $monthPerYear = 12;
        // bauspar einzahlen
        $saveCostsBeginYearsLooped = new SaveCostsBeginYearsLooped($this->constData);
        $saveCostsBeginYearsLooped->calculate();
        $calculatedInPerYear = $saveCostsBeginYearsLooped->getVariableCostsProvider()->getInPerYear();
        $calculatedAmount = $saveCostsBeginYearsLooped->getCalculatedAmount();
        $this->output['bauspar_anzahl_summe'] = $calculatedAmount;
        $this->output['bauspar_anzahl_pro_monat'] = $calculatedInPerYear/$monthPerYear;
        
        // bank
        $bankRedemptionLooped = new BankRedemptionLooped($this->constData);
        $bankRedemptionLooped->calculate();
//        $calculatedInPerYear = $bankRedemptionLooped->getVariableCostsProvider()->getInPerYear();
        $calculatedAmount = $bankRedemptionLooped->getCalculatedAmount(); // is now only taxes
        $this->output['bank_anzahl_summe'] = $calculatedAmount;
        $this->output['bank_pro_monat'] = $calculatedAmount/($this->constData->getYearsFirstHalf()*$monthPerYear); // related only to taxes

        $this->output['betrag_auszahlenn'] = $toPayOrigin * 0.6;
        
        // bauspar abzahlen
        $saveCostsFurtherYearsLooped = new SaveCostsFurtherYearsLooped($this->constData);
        $saveCostsFurtherYearsLooped->calculate();
        $calculatedInPerYear = $saveCostsFurtherYearsLooped->getVariableCostsProvider()->getInPerYear();
        $calculatedAmount = $saveCostsFurtherYearsLooped->getCalculatedAmount();
        $this->output['bauspar_zahl_summe'] = $calculatedAmount;
        $this->output['bauspar_zahl_pro_monat'] = $calculatedInPerYear/$monthPerYear;
        
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }
}
