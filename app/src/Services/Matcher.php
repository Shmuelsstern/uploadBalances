<?php

namespace App\src\Services;

use App\Entities\Facility;

class Matcher{

    private $matchExact;
    private $repo;
    private $toMatchArray;
    private $multipleMatcheds=[];

    public function __construct($repo, $toMatchArray,$matchExact=false){
        $this->matchExact=$matchExact;
        $this->repo=$repo;
        $this->toMatchArray=array_flip($toMatchArray);
    }

    public function match(){
        foreach($this->toMatchArray as $toMatch=>$v){
            $strippedName=strtolower(str_replace(' ','',$toMatch));
            $exactMatch=$this->repo->getNeutralKeysCollection()->get($strippedName);
            if($exactMatch){
                $this->toMatchArray[$toMatch]=['facility'=>$exactMatch,'strippedName'=>$strippedName];
                continue;
            }
            if($this->matchExact){
                $unmatchedFacility=new Facility();
                $unmatchedFacility->setParams(['recordId'=>'unmatched','shortName'=>'unmatched']);
                $this->toMatchArray[$toMatch]=['facility'=>$unmatchedFacility,'strippedName'=>$strippedName];
                continue;
            }
            $this->toMatchArray[$toMatch]=['facility'=>$this->getFuzzyMatch($toMatch),'strippedName'=>$strippedName];
        }
    }

    public function getFuzzyMatch($toMatch){
        $matched=[];
        foreach($this->repo->getNeutralKeysCollection() as $referenceToMatch=>$facility){
            $strippedKey = strtolower(str_replace(' ','',$toMatch));
            if(strpos($referenceToMatch,$strippedKey)!==false||strpos($strippedKey,$referenceToMatch)!==false){
                $matched[]=$facility;
            }
        }
        switch(true){
            case count($matched)==0:
                $unmatchedFacility=new Facility();
                $unmatchedFacility->setParams(['recordId'=>'unmatched','shortName'=>'unmatched']);
                return $unmatchedFacility;
            case count($matched)==1:
                return $matched[0];
            case count($matched)>1:
                $this->multipleMatcheds[$toMatch]=$matched;
                $multipleMatchedFacility=new Facility();
                $multipleMatchedFacility->setParams(['recordId'=>'multiple matched','shortName'=>'multiple matched']);
                return $multipleMatchedFacility;
        }
    }

    public function getToMatchArray(){
        return $this->toMatchArray;
    }

    public function getMultipleMatcheds(){
        return $this->multipleMatcheds;
    }

    public function getMultipleMatchedsArray($key){
        $newArray = [];
        foreach($this->multipleMatcheds[$key] as $facility){
            $newArray[$facility->getRecordId()]=$facility->getShortName();
        }
        return $newArray;
    }
    public function getRepo()
    {
       return $this->repo;
    }
}
