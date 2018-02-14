<?php

namespace App\Entities;

class Resident{

	private $recordId;
    private $relatedFacility;
    private $patientId;
    private $firstName;
    private $lastName;
    private $DOB;
    private $socialSecurityNum;
    private $medicareNum;
    private $medicaidNum;

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
    public function getRelatedFacility(){
		return $this->relatedFacility;
	}

	public function setRelatedFacility($relatedFacility){
		$this->relatedFacility = $relatedFacility;
	}

	public function getPatientId(){
		return $this->patientId;
	}

	public function setPatientId($patientId){
		$this->patientId = $patientId;
	}

	public function getFirstName(){
		return $this->firstName;
	}

	public function setFirstName($firstName){
		$this->firstName = $firstName;
	}

	public function getLastName(){
		return $this->lastName;
	}

	public function setLastName($lastName){
		$this->lastName = $lastName;
	}

	public function getDOB(){
		return $this->DOB;
	}

	public function setDOB($DOB){
		$this->DOB = $DOB;
	}

	public function getSocialSecurityNum(){
		return $this->socialSecurityNum;
	}

	public function setSocialSecurityNum($socialSecurityNum){
		$this->socialSecurityNum = $socialSecurityNum;
	}

	public function getMedicareNum(){
		return $this->medicareNum;
	}

	public function setMedicareNum($medicareNum){
		$this->medicareNum = $medicareNum;
	}

	public function getMedicaidNum(){
		return $this->medicaidNum;
	}

	public function setMedicaidNum($medicaidNum){
		$this->medicaidNum = $medicaidNum;
	}

}