<?php

namespace App\Entities;

class Payer{

    private $recordId;
    private $name;

    public function setParams(Array $params){
        foreach($params as $key => $param){
            $setter ='set'.ucfirst($key);
            $this->$setter($param);
        }
    }
    
    public function getRecordId(){
		return $this->recordId;
	}

	public function setRecordId($recordId){
		$this->recordId = $recordId;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	//hack for matcher
	public function setShortName($name){
		$this->name = $name;
	}

	public function getInsurance(){
		return $this->insurance;
	}

	public function setInsurance($insurance){
		$this->insurance = $insurance;
	}

	public function getUniqueIdentifier(){
		return $this->name;
	}
}