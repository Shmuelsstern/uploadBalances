<?php

namespace App\src\Services;

class Matcher{

    private $matchExact;
    private $repo;
    private $referenceCollection;
    private $toMatchArray;
    private $multipleMatcheds=[];

    public function __construct($repo, $toMatchArray,$matchExact=false){
        $this->matchExact=$matchExact;
        $this->repo=$repo;
        $this->toMatchArray=array_flip($toMatchArray);
    }

    public function match(){
        foreach($this->toMatchArray as $toMatch=>$v){
            if($this->toMatchArray[$toMatch]=$this->repo->getCollection()->get($toMatch)['recordId']){
                continue;
            }
            if($this->matchExact){
                $this->toMatchArray[$toMatch]='unmatched';
                continue;
            }
            $this->toMatchArray[$toMatch]=$this->getFuzzyMatch($toMatch);
        }
    }

    public function getFuzzyMatch($toMatch){
        $matched=[];
        foreach($this->repo->getNeutralKeysCollection() as $referenceToMatch=>$value){
            $strippedKey = strtolower(str_replace(' ','',$toMatch));
            if(strpos($referenceToMatch,$strippedKey)||strpos($strippedKey,$referenceToMatch)){
                $matched+=[$toMatch=>$value];
            }
        }
        switch(true){
            case count($matched)==0:
                return 'unmatched';
            case count($matched)==1:
                return $matched[$toMatch];
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