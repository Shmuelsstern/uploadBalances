<?php

namespace App\Entities\Repositories;
use Illuminate\Support\Collection;
use App\Entities\Resident;

class ResidentRepo {

    
    const RESIDENTFIELDS = ['patientId','relatedFacility','firstName','lastName','DOB','socialSecurityNum','medicareNum','medicaidNum'];
    const RESIDENTFIELDSMAPPED = ['ID'=>'patientId','Related FACILITY'=>'relatedFacility','First Name'=>'firstName','Last Name'=>'lastName','DOB'=>'DOB','SS#'=>'socialSecurityNum','Medicare #'=>'medicareNum','Medicaid #'=>'medicaidNum'];
    private $collection;
    private $neutralKeysCollection;

    public function __construct(){
        $this->collection = new Collection();
        $this->neutralKeysCollection = new Collection();
    }

    public function pushFromXml($xmlResident){
        $resident = new Resident();
        foreach($xmlResident as $key => $value){
            $setParam='set'.ucfirst(camel_case($key));
            if(method_exists($resident,$setParam)){
                $resident->$setParam((string)$value);
            }
        }
        $this->setCollection($resident);
    }

    public function add($newBalance){
        $resident = new Resident();
        $uniqueIdentifier = $newBalance['relatedFacility'].$newBalance['patientId'];
        if(!$this->collection->has($uniqueIdentifier)){
            $residentParams=[];
            foreach(self::RESIDENTFIELDS as $field){
                if(array_key_exists($field,$newBalance)){
                    $residentParams[$field]=$newBalance[$field];
                }
            }
            $resident->setParams($residentParams);
            $this->collection->put($uniqueIdentifier,$resident);
        }
    }

    public function setCollection(&$resident){
        $this->neutralKeysCollection->put($resident->getUniqueIdentifier(),$resident);
        //$this->collection->put($facility->getRecordId(),$facility);
    }

    public function getNeutralKeysCollection(){
        return $this->neutralKeysCollection;
    }
}