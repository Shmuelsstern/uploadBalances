<?php

Namespace App\Entities;
use App\Entities\Repositories\NewBalanceRepo;

class NewBalance{

	private $newBalanceRepo;
	private $recordId;
	private $facility;
	private $facilityInfo=[];
	private $resident;
	private $residentInfo=[];
	private $payer;
	private $payerInfo=[];
	private $insurance;
    private $DOS;
    private $balance;
	private $policyNum;
    private $comments;

    public function __construct(NewBalanceRepo $nbr,$newBalanceRow ){
		$this->newBalanceRepo=$nbr;
		$this->setParams($newBalanceRow);
		$this->setFacility();
		$this->setResident();
		$this->setPayer();
    }

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

	public function getFacility(){
		return $this->facility;
	}

	public function setFacility(){
			$this->facility=new Facility();
			$this->facility->setParams($this->facilityInfo);
	}

	public function getUploadedFacilityName(){
		return $this->facility->getUploadedFacilityName();
	}

	public function setUploadedFacilityName($uploadedFacilityName){
		$this->facilityInfo['uploadedFacilityName']=$uploadedFacilityName;
	}

	public function getPatientId(){
		return $this->resident->getPatientId();
	}

	public function setPatientId($patientId){
		$this->residentInfo['patientId']=$patientId;
	}

	public function getFirstName(){
		return $this->resident->getFirstName();
	}

	public function setFirstName($firstName){
		$this->residentInfo['firstName']=$firstName;
	}

	public function getLastName(){
		return $this->facility->getLastName();
	}

	public function setLastName($lastName){
		$this->residentInfo['lastName']=$lastName;
	}

	public function getDOB(){
		return $this->resident->getDOB();
	}

	public function setDOB($DOB){
		$this->residentInfo['DOB']=$DOB;
	}

	public function getSocialSecurityNum(){
		return $this->resident->getSocialSecurityNum();
	}

	public function setSocialSecurityNum($socialSecurityNum){
		$this->residentInfo['socialSecurityNum']=$socialSecurityNum;
	}

	public function getMedicaidNum(){
		return $this->resident->getMedicaidNum();
	}

	public function setMedicaidNum($medicaidNum){
		$this->residentInfo['medicaidNum']=$medicaidNum;
	}

	public function getMedicareNum(){
		return $this->resident->getMedicareNum();
	}

	public function setMedicareNum($medicareNum){
		$this->residentInfo['medicareNum']=$medicareNum;
	}

	public function getResident(){
		return $this->resident;
	}

	public function setResident(){
			$this->resident=new Resident();
			$this->resident->setParams($this->residentInfo);
			$this->resident->setRelatedFacility($this->facility);
	}

	public function getPayerType(){
		return $this->payer->getPayerType();
	}

	public function setPayerType($payerType){
		$this->payerInfo['payerType']= $payerType;
	}

	public function getPayer(){
		return $this->payer;
	}

	public function setPayer(){
			$this->payer=new Payer();
			$this->payer->setParams($this->payerInfo);
	}

	public function getInsurance(){
		return $this->insurance;
	}

	public function setInsurance($insurance){
		$this->insurance=$insurance;
	}
	public function getDOS(){
		return $this->DOS;
	}

	public function setDOS($DOS){
		$this->DOS = $DOS;
	}

	public function getBalance(){
		return $this->balance;
	}

	public function setBalance($balance){
		$this->balance = $balance;
	}

	public function getPolicyNum(){
		return $this->policyNum;
	}

	public function setPolicyNum($policyNum){
		$this->policyNum = $policyNum;
	}

	public function getComments(){
		return $this->comments;
	}

	public function setComments($comments){
		$this->comments = $comments;
	}
}