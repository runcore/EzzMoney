<?php
namespace Ezz;

/**
 * Class MoneyRU
 * @package Ezz
 */
class MoneyRU extends Money {

    /**
     * @var string
     */
    protected $zero = 'ноль';

    /**
     * Words of tens in Female and male gender
     * @var array
     */
    protected $toTen = [
         0=>['','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять']
        ,1=>['','один','два','три','четыре','пять','шесть','семь', 'восемь','девять']
    ];

    /**
     * @var array
     */
    protected $toTwenty = ['десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать'
        ,'шестнадцать','семнадцать','восемнадцать','девятнадцать'];

    /**
     * @var array
     */
    protected $tens = [2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто'];

    /**
     * @var array
     */
    protected $hundreds = ['','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот'];

    /**
     * Structure of array: One item, two items, five items, gender of word
     * @var array
     */
    protected $units = [
//         ['триллион','триллиона','триллионов',1] // 1-999 000 000 000 000
         ['миллиард','милиарда' ,'миллиардов',1] // 1-999 000 000 000
        ,['миллион' ,'миллиона' ,'миллионов' ,1] // 1-999 000 000
        ,['тысяча'  ,'тысячи'   ,'тысяч'     ,0] // 1 000
        ,['рубль'   ,'рубля'    ,'рублей'    ,1] // 10
        ,['копейка' ,'копейки'  ,'копеек'    ,0] // .00
    ];

    /**
     * Convert Money value to Text
     * @param null $summa
     * @return string
     */
    public function asText($summa=null) {
        if (!is_null($summa)) {
            $this->setSumma($summa);
        }
        $out = array();
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($this->summa)) );
        if (intval($rub)>0) {
            // Triads of Rub
            foreach(str_split($rub,3) as $unitKey=>$triad) {
                if ( !intval($triad)) {
                    continue;
                }
                $gender = $this->units[$unitKey][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($triad,1));
                // hundreds from 100 to 900
                $out[] = $this->hundreds[$i1];
                // tens from 20 to 99
                if ($i2>1) {
                    $out[]= $this->tens[$i2].' '.$this->toTen[$gender][$i3];
                } else {
                    // from 10 to 19 | from 1 to 9
                    $out[]= $i2>0 ? $this->toTwenty[$i3] : $this->toTen[$gender][$i3];
                }
                // units (without kop)
                $out[]= $this->morph($triad, $this->units[$unitKey]);
            } //foreach
        } else {
            $unitKeyRub = sizeof($this->units) - 2;
            $out[] = $this->zero.' '.$this->morph(0,$this->units[$unitKeyRub]);
        }
        // add kop
        $unitKeyKop = sizeof($this->units) - 1;
        $out[] = $kop.' '.$this->morph($kop,$this->units[$unitKeyKop]);

        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

}
