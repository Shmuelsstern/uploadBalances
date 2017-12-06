<?php

namespace App\src\Services;

class QuickbaseQuerier{

    private $appToken; 
    private $userToken;
    private $request;
    //private $a='API_DoQuery';
    private $databases=['facility'=> 'bk2ymn44w'];
    private $query;
    private $queryFid=['facility'=>['record ID#'=>'3','SHORT NAME'=>'40','GROUP'=>'45']];
    private $queryOperator=['contains'=>'CT','does not contain'=>'XCT','equals'=>'EX', 'not equal to'=>'XEX'];
    private $queryValue;
    private $includeRids;
    private $clist=['facility'=>['record ID#'=>'3','SHORT NAME'=>'40','GROUP'=>'45']];//fields to be returned 
    private $slist;//fields used for sorting


    public function __construct(){
        $this->appToken = env('QB_APPTOKEN'); 
        $this->userToken = env('QB_USERTOKEN');
    }

    public function setRequest($subject,$queryField,$operator,$queryValue, $returnFields){
        $this->setQuery($subject,$queryField,$operator,$queryValue);
        $requestString = 'https://scs.quickbase.com/db/';
        $requestString.= $this->databases[$subject];
        $requestString.= '?a=API_DoQuery';
        $requestString.= '&apptoken='.$this->appToken.'&usertoken='.$this->userToken;
        $requestString.= '&query='.$this->getQuery();
        $delimiter="&clist=";
        foreach($returnFields as $rf){
            $requestString.=$delimiter.$this->clist[$subject][$rf];
            $delimiter='.';
        }
        $this->request = $requestString; 
    }

    public function getRequest(){
        return $this->request;
    }

    public function setQuery($subject,$queryField,$operator,$queryValue){
        $queryString ='{';
        $queryString.= $this->queryFid[$subject][$queryField];
        $queryString.= '.';
        $queryString.= $this->queryOperator[$operator];
        $queryString.= '.';
        $queryString.=$queryValue;
        $queryString.='}'; 
        $this->query = $queryString;
    }

    public function getQuery(){
        return $this->query;
    }

    public function query(){
        return simplexml_load_file($this->getRequest());
    }
}