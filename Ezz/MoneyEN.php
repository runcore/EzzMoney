<?php
namespace Ezz;

/**
 * Class MoneyEN
 * @package Ezz
 */
class MoneyEN extends Money {

    /**
     * @var string
     */
    protected $zero = 'zero';

    /**
     * @var string
     */
    protected $hundred = 'hundred';

    /**
     * Words of tens in Female and male gender
     * @var array
     */
    protected $digits = [
//        0=>['','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять']
        '','one','two','three','four','five','six','seven', 'eight','nine'
    ];

    /**
     * @var array
     */
    protected $teens = ['ten','eleven','twelve','thirteen','fourteen' ,'fifteen'
        ,'sixteen','seventeen','eighteen','nineteen'];

    /**
     * @var array
     */
    protected $tens = [2=>'twenty','thirty','forty','fifty','sixty','seventy' ,'eighty','ninety'];

    /**
     * Structure of array: One item, two items, five items, gender of word
     * @var array
     */
    protected $units = [
         'billion' // 1-999 000 000 000
        ,'million' // 1-999 000 000
        ,'thousand' // 1 000
        ,'dollar' // 10
        ,'cent' // .00
    ];


    /**
     * @param null $summa
     * @return string
     */
    public function asText($summa=null) {
        if (!is_null($summa)) {
            $this->setSumma($summa);
        }
        $out = array();
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($this->summa)) );
        $unitKeyRub = sizeof($this->units) - 2;
        if (intval($rub)>0) {
            // Triads of Rub
            foreach(str_split($rub,3) as $unitKey=>$triad) {
                if ( !intval($triad)) {
                    continue;
                }
                list($i1,$i2,$i3) = array_map('intval',str_split($triad,1));
                // hundreds from 100 to 900
                if ($i1>0) {
                    $out[] = $this->digits[$i1] . ' ' . $this->hundred;
                }
                // tens from 20 to 99
                if ($i2>1) {
                    $out[]= $this->tens[$i2].' '.$this->digits[$i3];
                } else {
                    // from 10 to 19 | from 1 to 9
                    $out[]= $i2>0 ? $this->teens[$i3] : $this->digits[$i3];
                }
                // units (without kop)
                $suffix = (($unitKeyRub==$unitKey) && 1==intval($triad) ) ? '' : 's';
                $out[] = $this->units[$unitKey] . $suffix;
            } //foreach
        } else {
            $out[] = $this->zero.' '.$this->units[$unitKeyRub].'s';
        }
        // add kop
        $unitKeyKop = sizeof($this->units) - 1;
        $out[] = $kop.' '.$this->units[$unitKeyKop];

        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

}
