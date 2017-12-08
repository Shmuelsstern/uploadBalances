<?php

namespace App\Entities\Repositories;
use Illuminate\Support\Collection;
use App\Entities\Facility;

Class FacilityRepo{

    private $facilityCollection;

    public function __construct(){
        $this->facilityCollection = new Collection();
    }

    public function pushFromXml($xmlFacility){
        $facility = new Facility();
        foreach($xmlFacility as $key => $value){
            $setParam='set'.ucfirst(camel_case($key));
            if(method_exists($facility,$setParam)){
                $facility->$setParam((string)$value);
            }
        }
        $this->facilityCollection->put($facility->getShortName(),$facility);
    }

    public function getFacilityCollection(){
        return $this->facilityCollection;
    }
}