<?php
echo '<html><head><meta htmltp-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
header('Content-Type: text/html; charset=utf8');

require_once('./Ezz/autoexec.php');

// Examples

$summs = [
    0
    ,1.01
    ,11.159
    ,1234
    ,'  1111.0999  '
    ,000000015.2234
    ,123456789123.019
    ,1111111111.11
    ,230000000.09
];
$money = new Ezz\MoneyEN();
foreach($summs as $summ) {
    //Ezz\pr( trim($summ) . ' - '.(new Ezz\MoneyRU( $summ ))->asText() );
    // syntax sugar
    //Ezz\pr( Ezz\moneyAsString($summ) );
    Ezz\pr( trim($summ) .' - ' . $money->asText($summ) );
}




