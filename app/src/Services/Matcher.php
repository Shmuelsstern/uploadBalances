<?php

namespace App\src\Services;

use App\Entities\Facility;

class Matcher{

    private $matchExact;
    private $referenceRepo;
    private $toMatchCollection;
    private $multipleMatcheds=[];
    private $objectType;
    private $matcheds=[];

    public function __construct($objectType,$referenceRepo, $toMatchCollection,$matchExact=false){
        $this->objectType = $objectType;
        $this->matchExact=$matchExact;
        $this->referenceRepo=$referenceRepo;
        $this->toMatchCollection=$toMatchCollection;
    }

    public function match(){
        foreach($this->toMatchCollection as $collectionKey=>$collectionItem){
            $strippedName=strtolower(str_replace(' ','',$collectionKey));
            $exactMatch=$this->referenceRepo->getNeutralKeysCollection()->get($strippedName);
            if($exactMatch){
                $this->matcheds[$collectionItem->getUniqueIdentifier()]=['object'=>$exactMatch,'strippedName'=>$strippedName];
                continue;
            }
            if($this->matchExact){
                $unmatchedObject=$this->createObjectType();
                $unmatchedObject->setParams(['recordId'=>'unmatched','shortName'=>'unmatched']);
                $this->matcheds[$collectionItem->getUniqueIdentifier()]=['object'=>$unmatchedObject,'strippedName'=>$strippedName];
                continue;
            }
            $this->matcheds[$collectionItem->getUniqueIdentifier()]=['object'=>$this->getFuzzyMatch($collectionKey),'strippedName'=>$strippedName];
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

    public function gettoMatch(){
        return $this->toMatch;
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
        $class="App\\Entities\\".ucfirst($this->getObjectType());
        return new $class();
    }

    public function getMatcheds(){
        return $this->matcheds;
    }

    public function setMatcheds($matcheds){
        $this->matcheds=$matcheds;
    }
}
