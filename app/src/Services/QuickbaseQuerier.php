<?php

namespace App\src\Services;

class QuickbaseQuerier extends QuickbaseRequester{

    private $request;
    private $databases=[];
    private $query;
    private $queryFid=['facility'=>['record ID#'=>'3','SHORT NAME'=>'40','GROUP'=>'45','Related GROUP'=>'44'],
                       'resident'=>['Record ID#'=>3,'First Name'=>6,'Last Name'=>7,'ID'=>8,'Medicare #'=>23,                    'Related FACILITY'=>9,'DOB'=>14,'Medicaid #'=>24,'SS#'=>22,'GROUP'=>30]];
    private $queryOperator=['contains'=>'CT','does not contain'=>'XCT','equals'=>'EX', 'not equal to'=>'XEX'];
    private $queryValue;
    private $includeRids;
    private $CList;
    private $clistMapping=['facility'=>['record ID#'=>'3','SHORT NAME'=>'40','GROUP'=>'45','Related GROUP'=>'44'],
                           'resident'=>['Record ID#'=>3,'First Name'=>6,'Last Name'=>7,'ID'=>8,'Medicare #'=>23,                    'Related FACILITY'=>9,'DOB'=>14,'Medicaid #'=>24,'SS#'=>22,'GROUP'=>30]];//fields to be returned 
    private $slist;//fields used for sorting


    public function __construct($subject,$queryField,$operator,$queryValue,$returnFields){
        parent::__construct($subject,'API_DoQuery');
        $this->setQuery($queryField,$operator,$queryValue);
        $this->setCList($returnFields);
        $this->setURLRequest();
    }

    public function getSpecificURLRequest(){
        $requestString= '&query='.$this->getQuery();
        $requestString.=$this->getCList();
        return $requestString;
    }

    public function setQuery($queryField,$operator,$queryValue){
        $queryString ='{';
        $queryString.= $this->queryFid[$this->subject][$queryField];
        $queryString.= '.';
        $queryString.= $this->queryOperator[$operator];
        $queryString.= '.';
        $queryString.=$queryValue;
        $queryString.='}'; 
        $this->query = $queryString;
    }

    public function setCList($returnFields){
        $CList='';
        $delimiter="&clist=";
        foreach($returnFields as $rf){
            $CList.=$delimiter.$this->clistMapping[$this->subject][$rf];
            $delimiter='.';
        }
        $this->CList=$CList;
    }

    public function getCList(){
        return $this->CList;
    }

    public function getQuery(){
        return $this->query;
    }

    public  function getSpecificXMLRequest(){
    }

}