<?php

namespace App\src\Services;

use App\src\Services\QuickbaseRequester;

class API_ImportFromCSVRequester extends QuickbaseRequester{

    private $CSVFileString;
    private $clist;
    
    public function __construct($subject,$CSVData,$clist){
        parent::__construct($subject,'API_ImportFromCSV');
        $this->setCSVFileString($CSVData);
        $this->setClist($clist);
        $this->setXMLRequest();
    }

    public function setCSVFileString($CSVData){
        if(is_array($CSVData)){
            foreach($CSVData as $row){
                if(is_array($row)){
                    $this->CSVFileString.= implode(',',$row);
                }else{
                    $this->CSVFileString.= $row;
                }
                $this->CSVFileString.="\n";
            }
        }else{
            dd('csv not in array format');
        }
    }

    public function getCSVFileString(){
        return $this->CSVFileString;
    }

    public function setClist($clist){
        $this->clist = $clist;
    }

    public function getClist(){
        return $this->clist;
    }

    public function getSpecificXMLRequest(){
        $specificRequest = '  <records_csv>
                                <![CDATA[';
        $specificRequest.= $this->CSVFileString;
        $specificRequest.='             ]]>
                              </records_csv>';
        $specificRequest.='  <clist>'.$this->clist.'</clist>
                             <clist_output>'.$this->clist.'</clist_output>';
        return $specificRequest;
    }

    public function getSpecificURLRequest(){
        //unsble to be done to the best of my knowledge
    }

}