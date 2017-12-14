<?php

namespace App\Entities\Repositories;
use Illuminate\Support\Collection;
use App\Entities\Facility;

Class FacilityRepo{

    private $collection;
    private $neutralKeysArray;

    public function __construct(){
        $this->collection = new Collection();
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

    public function setCollection($facility){
        $this->collection->put($facility->getShortName(),$facility);
        $this->collection->put($facility->getrecordId(),$facility);
    }

    public function setNeutralKeysCollection($facility){
        $this->neutralKeysCollection->put(strtolower(str_replace(' ','',$facility->getShortName())),$facility);
    }
    public function getNeutralKeysCollection(){
        return $this->neutralKeysCollection;
    }
}