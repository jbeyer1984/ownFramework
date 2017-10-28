<?php


namespace MyApp\src\Finance;


class FinanceCalcFunctions
{
    /**
     * to calculate input with tax added you've to pick only
     * last amount of $amountArray
     * @param double $amount
     * @param int $years
     * @param double $inPerYear
     * @param double $taxRate
     * @return array
     */
    public static function taxOfTaxGrow($amount, $years, $inPerYear, $taxRate)
    {
        $amountTemp   = $amount;
        $amountArray  = [];
        $taxRateArray = [];
        for ($i = 0; $i < $years; $i++) {
            $amountTemp          += $inPerYear;
            $taxRateOfAmountTemp = $amountTemp * $taxRate / 100;
            $taxRateArray[]      = $taxRateOfAmountTemp;
            $amountTemp          += $taxRateOfAmountTemp;
            $amountArray[]       = $amountTemp;
        }

        return [$amountArray, $taxRateArray];
    }

    /**
     * to calculate input with tax added you've to pick only
     * last amount of $amountArray + array_sum($taxRateArray)
     * @param double $amount
     * @param int $years
     * @param double $inPerYear
     * @param double $taxRate
     * @return array
     */
    public static function taxOfTaxRedemption($amount, $years, $inPerYear, $taxRate)
    {
        $amountRedemptionTemp = $amount;
        $amountSum = 0;
        $amountArray  = [];
        $taxRateArray = [];
        for ($i = 0; $i < $years; $i++) {
            $taxRateOfAmountTemp  = $amountRedemptionTemp * $taxRate / 100;
            $amountRedemptionTemp -= $inPerYear;
//            if (0 > $amountRedemptionTemp) {
//                $inPerYear += $amountRedemptionTemp; // $amountRedemptionTemp is negative
//                $amountRedemptionTemp = 0;
//            }
            $taxRateArray[]       = $taxRateOfAmountTemp;
            $amountSum            += $inPerYear;
            $amountArray[]        = $amountSum;
        }

        return [$amountArray, $taxRateArray];
    }
}