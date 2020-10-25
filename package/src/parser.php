<?php

namespace rallisf1\PhpSaracakisPricelists;

Class Parser{
    private $file;
    public $result;
    private $wrapper;
    private $filter;
    private $fields = array(
        'number' => array(6,4),
        'dealer' => array(3,3),
        'dateY' => array(15,4),
        'dateM' => array(19,2),
        'dateD' => array(21,2),
        'dateH' => array(25,2),
        'datei' => array(27,2),
        'dates' => array(29,2),
        'type'  => array(149,4),
        'manufacturer'  => array(22,3),
        'pn'  => array(25,15),
        'name'  => array(40,40),
        'alternate_pn'  => array(80,15),
        'cost'  => array(95,11),
        'product_group'  => array(106,5),
        'function_group'  => array(111,5),
        'price'  => array(116,11),
        'commercial_code'  => array(127,5),
        'category'  => array(133,5),
        'price_group'  => array(142,3),
        'discount_group'  => array(145,4)
    );
    private $manufacturers = array(
        '006' => 'Honda',
        '008' => 'Mitsubishi'
    );

    function __construct($filename, $filter = 0){
        if(!is_readable($filename)){
            throw new Exception('Cannot access file '.$filename);
        }
        $this->result = new \stdClass();

        $splfile = new \SplFileObject($filename,"r");
        $splfile->seek(PHP_INT_MAX);
        $this->result->rows = $splfile->key() - 1; //excluding header and last empty row
        $splfile = null; // in order to close the file
        
        $this->file = fopen($filename,"r");
        $this->wrapper = new \ZBateson\MbWrapper\MbWrapper();
        
        switch($filter){
            case 1:
                // only cars
                $this->filter = array('HMC', 'HPP');
                break;
            case 2:
                // only motorcycles
                $this->filter = array('HMA', 'HPP', 'MMC');
                break;
            case 3:
                // only powertools
                $this->filter = array('HMA', 'HMC', 'MMC');
                break;
            default:
                // return all
                break;
        }

        $this->parse();
    }

    private function parse(){

        $row = $this->nextRow(); // header row
        
        $this->result->number = $this->parseField($row, 'number');
        $this->result->dealer_code = $this->parseField($row, 'dealer');
        
        $this->result->date = date('d/m/Y H:i', strtotime($this->parseField($row, 'dateY').'-'.$this->parseField($row, 'dateM').'-'.$this->parseField($row, 'dateD').' '.$this->parseField($row,'dateH').':'.$this->parseField($row, 'datei').':'.$this->parseField($row, 'dates')));
        
        $this->result->products = Array();

        while(! feof($this->file)){
            $product = array();
            $row = $this->nextRow();
            $product['type'] = $this->parseField($row, 'type');
            
            // check if product is filtered out
            if(!empty($this->filter) && in_array($product['type'], $this->filter)) continue;

            $mfg = $this->parseField($row, 'manufacturer');
            $product['manufacturer'] = isset($this->manufacturers[$mfg]) ? $this->manufacturers[$mfg] : 'Unknown';
            $product['pn'] = $this->parseField($row, 'pn');
            $product['name'] = $this->parseField($row, 'name');
            $product['alternate_pn'] = $this->parseField($row, 'alternate_pn');
            $product['cost'] = round(intval($this->parseField($row, 'cost')) / 100, 2);
            $product['product_group'] = $this->parseField($row, 'product_group');
            $product['function_group'] = $this->parseField($row, 'function_group');
            $product['price'] = round(intval($this->parseField($row, 'price')) / 100, 2);
            $product['commercial_code'] = $this->parseField($row, 'commercial_code');
            $product['category'] = $this->parseField($row, 'category');
            $product['price_group'] = $this->parseField($row, 'price_group');
            $product['discount_group'] = $this->parseField($row, 'discount_group');
            $product['available'] = (strpos($product['name'], 'ΤΕΛΟΣ ΣΤΟΚ') === false);
            
            if(!empty($product['pn'])) $this->result->products[] = $product;
        }
    }

    private function nextRow(){
        $rawRow = fgets($this->file);
        if ($this->wrapper->checkEncoding($rawRow, "CP737")) {
            return $this->wrapper->convert($rawRow, "CP737", "UTF-8");
        } else {
            throw new Exception($filename. ' has wrong encoding. This script only handles Greek CP737 encoding');
        }
    }

    private function parseField($row, $field){
        return trim($this->wrapper->getSubstr($row, 'UTF-8', $this->fields[$field][0], $this->fields[$field][1]));
    }
}