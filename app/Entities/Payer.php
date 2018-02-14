<?php

namespace App\Entities;

class Payer{

    private $recordId;
    private $payerType;

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

	public function getPayerType(){
		return $this->payerType;
	}

	public function setPayerType($payerType){
		$this->payerType = $payerType;
	}

	public function getInsurance(){
		return $this->insurance;
	}

	public function setInsurance($insurance){
		$this->insurance = $insurance;
	}
}