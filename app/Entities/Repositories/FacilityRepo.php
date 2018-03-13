<?php

namespace App\Entities\Repositories;
use Illuminate\Support\Collection;
use App\Entities\Facility;

Class FacilityRepo{

    private $collection;
    private $neutralKeysCollection;

    public function __construct(){
        $this->collection = new Collection();
        $unmatchedFacility = new Facility();
        $unmatchedFacility->setParams(['recordId'=>'unmatched','shortName'=>'unmatched']);        
        $this->collection->put('unmatched',$unmatchedFacility);
        $this->neutralKeysCollection = new Collection();
    }

    public function pushFromXml($xmlFacility){
        $facility = new Facility();
        foreach($xmlFacility as $key => $value){
            $setParam='set'.ucfirst(camel_case($key));
            if(method_exists($facility,$setParam)){
                $facility->$setParam((string)$value);
            }
        }
        $this->setCollection($facility);
        $this->setNeutralKeysCollection($facility);
    }

    public function getCollection(){
        return $this->collection;
    }

    public function setCollection(&$facility){
        $this->collection->put($facility->getShortName(),$facility);
        //$this->collection->put($facility->getRecordId(),$facility);
    }

    public function setNeutralKeysCollection(&$facility){
        $this->neutralKeysCollection->put(strtolower(str_replace(' ','',$facility->getUniqueIdentifier())),$facility);
    }
    public function getNeutralKeysCollection(){
        return $this->neutralKeysCollection;
    }

    public function getArrayForMatched($matched){
        $IdNameArray=$this->getRecordIdShortNameArray($this->collection);
        unset($IdNameArray[$matched->getRecordId()]); 
        return $IdNameArray;
    }
    public function getArrayForMultipleMatched($matched){
        $IdNameArray=$this->getRecordIdShortNameArray($this->collection);
        foreach($matched as $key=>$match){
            unset($IdNameArray[$key]); 
        }
        return $IdNameArray;
    }

    public function getRecordIdShortNameArray($collection){
        $newArray=[];
        foreach($collection->all() as $key=>$item){
            $newArray[$item->getRecordId()]=$key;
        }
        return $newArray;
    }

    public function findBy($property, $value){
        if($property=='shortName'){
            return $this->collection->get($value);
        }else{
            $getProperty = 'get'.ucfirst($property);
            foreach($this->collection as $facility){
                if($facility->$getProperty()== $value){
                    return $facility;
                }
            } 
        }
    }

    public function getMatchedFacilities(){
        $matchedFacilities=[];
        foreach($this->collection as $facility){
            if($facility->getUploadedName()!==null){
                $matchedFacilities[$facility->getUploadedName()]=$facility;
            }
        }
        return $matchedFacilities;
    }

}