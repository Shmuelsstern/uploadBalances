<?php

Namespace App\Entities;
use App\Entities\NewBalanceRepo;

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
    private $newBalance;
	private $policyNum;
    private $comments;

    public function __construct(NewBalanceRepo $nbr, $newBalanceRow){
		$this->newBalanceRepo=$nbr;
		$this->setParams($newBalanceRow);
		$this->setFacility();
		$this->resident = new Resident();
		$this->resident->setRelatedFacility($this->facility);
		$this->payer = new Payer();
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
		$uniqueFacilityIdentifier=$this->facilityInfo['uploadedFacilityName'];
		if($this->newBalanceRepo->getUniqueFacilitiesCollection()->has($uniqueFacilityIdentifier)){
			$this->facility=$this->newBalanceRepo->getUniqueFacilitiesCollection()->get($uniqueFacilityIdentifier);
		}else{
			$this->facility=new Facility();
			$this->facility->setParams($this-facilityInfo);
			$this->newBalanceRepo->getUniqueFacilitiesCollection()->put($uniqueFacilityIdentifier,$this->facility);
		}
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
		$uniqueResidentIdentifier=$this->facilityInfo['uploadedFacilityName'].$this->residentInfo['patientId'];
		if($this->newBalanceRepo->getUniqueResidentsCollection()->has($uniqueResidentIdentifier)){
			$this->resident=$this->newBalanceRepo->getUniqueResidentsCollection()->get($uniqueResidentIdentifier);
		}else{
			$this->resident=new Resident();
			$this->resident->setParams($this-residentInfo);
			$this->newBalanceRepo->getUniqueResidentsCollection()->put($uniqueResidentIdentifier,$this->resident);
		}
	}

	public function getPayerType(){
		return $this->payer->getPayerType();
	}

	public function setPayerType($payerType){
		$this->payerType = $payerType;
	}

	public function getInsurance(){
		return $this->insurance;
	}

	public function setInsurance($insurance){
		$this->insurance=$insurance;
	}

	public function getPayer(){
		return $this->payer;
	}

	public function setPayer(){
		$uniquePayerIdentifier=$this->payerInfo['payerType'];
		if($this->newBalanceRepo->getUniquePayersCollection()->has($uniquePayerIdentifier)){
			$this->payer=$this->newBalanceRepo->getUniquePayersCollection()->get($uniquePayerIdentifier);
		}else{
			$this->payer=new Payer();
			$this->payer->setParams($this-residentInfo);
			$this->newBalanceRepo->getUniquePayersCollection()->put($uniquePayerIdentifier,$this->payer);
		}
	}

	public function getDOS(){
		return $this->DOS;
	}

	public function setDOS($DOS){
		$this->DOS = $DOS;
	}

	public function getNewBalance(){
		return $this->newBalance;
	}

	public function setNewBalance($newBalance){
		$this->newBalance = $newBalance;
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