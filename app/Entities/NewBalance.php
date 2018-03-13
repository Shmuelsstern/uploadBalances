<?php

Namespace App\Entities;
use App\Entities\Repositories\NewBalanceRepo;

class NewBalance{

	private $newBalanceRepo;
	private $recordId;
	private $facility;
	private $group;
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
		$this->setgroup();
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

	public function setGroup(){
		if (session('group')){
			$this->group = session('group');
		}else{
			$this->group='nogroup';
		}
	}

	public function getGroup(){
		return $this->group;
	}

	public function getFacility(){
		return $this->facility;
	}

	public function setFacility($facility = false){
		if($facility){
			$this->facility=$facility;
		}else{
			$this->facility=new Facility();
			$this->facility->setParams($this->facilityInfo);
		}
	}

	public function getUploadedFacilityName(){
		return $this->facilityInfo['uploadedFacilityName'];
	}

	public function setUploadedFacilityName($uploadedFacilityName){
		$this->facilityInfo['uploadedFacilityName']=$uploadedFacilityName;
	}

	public function getPatientId(){
		return $this->residentInfo['patientId'];
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
		return $this->resident->getLastName();
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

	public function setResident($resident = false){
		if($resident){
			$this->resident=$resident;
		}else{
			$this->resident=new Resident();
			$this->resident->setParams($this->residentInfo);
			$this->resident->setRelatedFacility($this->facility);
		}
	}

	public function getPayerType(){
		return $this->payerInfo['name'];
	}

	public function setPayerType($payerType){
		$this->payerInfo['name']= $payerType;
	}

	public function getPayer(){
		return $this->payer;
	}

	public function setPayer($payer = false){
		if($payer){
			$this->payer=$payer;
		}else{
			$this->payer=new Payer();
			$this->payer->setParams($this->payerInfo);
		}
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
		$balanceWODollarSign = str_replace(['(','$',',',' ',')'],['-',''],$this->balance);
		return $balanceWODollarSign;
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