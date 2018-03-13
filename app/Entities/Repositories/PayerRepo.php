<?php

namespace App\Entities\Repositories;
use Illuminate\Support\Collection;
use App\Entities\Payer;

Class PayerRepo{

    private $collection;
    private $neutralKeysCollection;

    public function __construct(){
        $this->collection = new Collection();
        $unmatchedPayer = new Payer();
        $unmatchedPayer->setParams(['recordId'=>'unmatched','name'=>'unmatched']);        
        $this->collection->put('unmatched',$unmatchedPayer);
        $this->neutralKeysCollection = new Collection();
    }

    public function pushFromXml($xmlPayer){
        $payer = new Payer();
        foreach($xmlPayer as $key => $value){
            $setParam='set'.ucfirst(camel_case($key));
            if(method_exists($payer,$setParam)){
                $payer->$setParam((string)$value);
            }
        }
        $this->setCollection($payer);
        $this->setNeutralKeysCollection($payer);
    }

    public function getCollection(){
        return $this->collection;
    }

    public function setCollection(&$payer){
        $this->collection->put($payer->getName(),$payer);
    }

    public function setNeutralKeysCollection(&$payer){
        $this->neutralKeysCollection->put(strtolower(str_replace(' ','',$payer->getName())),$payer);
    }
    public function getNeutralKeysCollection(){
        return $this->neutralKeysCollection;
    }

    public function getArrayForMatched($matched){
        $IdNameArray=$this->getRecordIdNameArray($this->collection);
        unset($IdNameArray[$matched->getRecordId()]); 
        return $IdNameArray;
    }
    public function getArrayForMultipleMatched($matched){
        $IdNameArray=$this->getRecordIdNameArray($this->collection);
        foreach($matched as $key=>$match){
            unset($IdNameArray[$key]); 
        }
        return $IdNameArray;
    }

    public function getRecordIdNameArray($collection){
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
            foreach($this->collection as $payer){
                if($payer->$getProperty()== $value){
                    return $payer;
                }
            } 
        }
    }

    public function getMatchedFacilities(){
        $matchedFacilities=[];
        foreach($this->collection as $payer){
            if($payer->getUploadedName()!==null){
                $matchedFacilities[$payer->getUploadedName()]=$payer;
            }
        }
        return $matchedFacilities;
    }

}