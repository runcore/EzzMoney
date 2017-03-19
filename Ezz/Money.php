<?php
namespace Ezz;

abstract class Money {

    /**
     * Format \d+(\.\d{1,2})
     * @var mixed
     */
    protected $summa;

    /**
     * Money constructor.
     * @param $summa
     */
    public function __construct( $summa=null ) {
        if (!is_null($summa)) {
            $this->setSumma($summa);
        }
    }

    /**
     * @param $summa
     * @throws \Exception
     */
    protected function setSumma($summa) {
        $summa = trim($summa);
        if (!is_numeric($summa)) {
            throw new \Exception('Expected number');
        }
        if (!preg_match('/^([0-9]){1,12}(\.|$)/', $summa)) {
            throw new \Exception('Expected input values less than 1`000`000`000.00 but ' . number_format($summa, 2, '.', '`'));
        }
        // Save only two digits after separator
        $summa = preg_replace('/^([0-9]+(?:\.[0-9]{1,2})?).*/', '$1', $summa);

        $this->summa = $summa;
    }

    /**
     * @param $n
     * @param $f1
     * @param $f2
     * @param $f5
     * @return mixed
     */
    protected static function morph($n, $f1, $f2=null, $f5=null) {
        if ( is_array($f1) && sizeof($f1)>=3 ) {
            list($f1,$f2,$f5,) = $f1;
        }
        //
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) {
            return $f5;
        }
        $n = $n % 10;
        if ($n>1 && $n<5) {
            return $f2;
        }
        if ($n==1) {
            return $f1;
        }

        return $f5;
    }

    abstract public function asText($summa=null);

}
