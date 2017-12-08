<?php

namespace App\src\Services;

class Matcher{

    private $matchExact;
    private $referenceCollection;
    private $referenceArray;
    private $toMatchArray;
    private $multipleMatcheds=[];

    public function __construct($referenceCollection, $toMatchArray,$matchExact=false){
        $this->matchExact=$matchExact;
        $this->referenceCollection=$referenceCollection;
        if(!$matchExact){
            $this->setReferenceArray();
        }
        $this->toMatchArray=array_flip($toMatchArray);
    }

    public function setReferenceArray(){
        foreach($this->referenceCollection->all() as $key=>$value){
            $newKey=strtolower(str_replace(' ','',$key));
            $this->referenceArray[$newKey]=$value->getRecordId();
        }
    }

    public function match(){
        foreach($this->toMatchArray as $key=>$value){
            if($this->toMatchArray[$key]=$this->referenceCollection->get($key)['recordId']){
                continue;
            }
            if($this->matchExact){
                $this->toMatchArray[$key]='unmatched';
                continue;
            }
            $this->toMatchArray[$key]=$this->getFuzzyMatch($key);
        }
    }

    public function getFuzzyMatch($toMatchKey){
        $matched=[];
        foreach($this->referenceArray as $referenceKey=>$value){
            $strippedKey = strtolower(str_replace(' ','',$toMatchKey));
            if(strpos($referenceKey,$strippedKey)||strpos($strippedKey,$referenceKey)){
                $matched+=[$toMatchKey=>$value];
            }
        }
        switch(true){
            case count($matched)==0:
                return 'unmatched';
            case count($matched)==1:
                return $matched[$toMatchKey];
            case count($matched)>1:
                $this->multipleMatcheds+=$matched;
                return 'multiple matched';
        }
    }

    public function getToMatchArray(){
        return $this->toMatchArray;
    }

    public function getMultipleMatcheds(){
        return $this->multipleMatcheds;
    }
}