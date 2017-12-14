<?php

namespace App\src\Services;

class Matcher{

    private $matchExact;
    private $referenceCollection;
    private $referenceArray;
    private $toMatchArray;
    private $multipleMatcheds=[];

    public function __construct($referenceCollection, $toMatchArray, $matchExact=false){
        $this->matchExact=$matchExact;
        $this->referenceCollection=$referenceCollection;
        if(!$matchExact){
            $this->setReferenceArray();
        }
        $this->toMatchArray=array_flip($toMatchArray);
    }

    public function setReferenceArray(){
        foreach($this->referenceCollection->all() as $key=>$value){
            $newKey=(str_replace(' ','',$key));
            $this->referenceArray[$newKey]=$value->getRecordId();
        }
    }

    public function match(){
        foreach($this->toMatchArray as $key=>$value){
            if($this->referenceCollection->get($key)){
                $this->toMatchArray[$key]=$this->referenceCollection->get($key)->getRecordId();
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
            $strippedKey = (str_replace(' ','',$toMatchKey));
            if((stripos($referenceKey,$strippedKey)!==false)||(stripos($strippedKey,$referenceKey)!==false)){     
                $matched[]+=$value;
            }
        }
        switch(true){
            case count($matched)==0:
                return 'unmatched';
            case count($matched)==1:
                return $matched[0];
            case count($matched)>1:
                $this->multipleMatcheds[$toMatchKey]=$matched;
                return 'multiple matched';
        }
    }

    public function getToMatchArray(){
        return $this->toMatchArray;
    }

    public function getMultipleMatcheds(){
        return $this->multipleMatcheds;
    }

    public function getMultipleMatched($toMatchKey){
        $newarray=[];
        foreach($this->multipleMatcheds[$toMatchKey] as $value){
            $newarray[$value]= array_search($value,$this->referenceArray);
        }
        return $newarray;
    }

    public function getReferenceCollection(){
        return $this->referenceCollection;
    }

}