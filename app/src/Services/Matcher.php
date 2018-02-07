<?php

namespace App\src\Services;

use App\Entities\Facility;

class Matcher{

    private $matchExact;
    private $referenceRepo;
    private $toMatchArray;
    private $multipleMatcheds=[];
    private $objectType;

    public function __construct($objectType,$referenceRepo, $toMatchArray,$matchExact=false){
        $this->objectType = $objectType;
        $this->matchExact=$matchExact;
        $this->referenceRepo=$referenceRepo;
        $this->toMatchArray=array_flip($toMatchArray);
    }

    public function match(){
        foreach($this->toMatchArray as $toMatch=>$v){
            $strippedName=strtolower(str_replace(' ','',$toMatch));
            $exactMatch=$this->referenceRepo->getNeutralKeysCollection()->get($strippedName);
            if($exactMatch){
                $this->toMatchArray[$toMatch]=['object'=>$exactMatch,'strippedName'=>$strippedName];
                continue;
            }
            if($this->matchExact){
                $unmatchedObject=$this->createObjectType();
                $unmatchedObject->setParams(['recordId'=>'unmatched','shortName'=>'unmatched']);
                $this->toMatchArray[$toMatch]=['object'=>$unmatchedObject,'strippedName'=>$strippedName];
                continue;
            }
            $this->toMatchArray[$toMatch]=['object'=>$this->getFuzzyMatch($toMatch),'strippedName'=>$strippedName];
        }
    }

    public function getFuzzyMatch($toMatch){
        $matched=[];
        foreach($this->referenceRepo->getNeutralKeysCollection() as $referenceToMatch=>$object){
            $strippedKey = strtolower(str_replace(' ','',$toMatch));
            if(strpos($referenceToMatch,$strippedKey)!==false||strpos($strippedKey,$referenceToMatch)!==false){
                $matched[]=$object;
            }
        }
        switch(true){
            case count($matched)==0:
                $unmatchedObject=$this->createObjectType();
                $unmatchedObject->setParams(['recordId'=>'unmatched','shortName'=>'unmatched']);
                return $unmatchedObject;
            case count($matched)==1:
                return $matched[0];
            case count($matched)>1:
                $this->multipleMatcheds[$toMatch]=$matched;
                $multipleMatchedObject=$this->createObjectType();
                $multipleMatchedObject->setParams(['recordId'=>'multiple matched','shortName'=>'multiple matched']);
                return $multipleMatchedObject;
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
        foreach($this->multipleMatcheds[$key] as $object){
            $newArray[$object->getRecordId()]=$object->getShortName();
        }
        return $newArray;
    }
    public function getReferenceRepo()
    {
       return $this->referenceRepo;
    }

    public function getObjectType(){
        return $this->objectType;
    }

    public function createObjectType(){
        return new ucfirst($this->objectType());
    }
}
