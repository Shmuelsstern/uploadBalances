<?php

Namespace App\Entities;

class NewBalance{

    private $recordID;
    private $resident;
    private $payer;
    private $DOS;
    private $balance;
    private $insurance;
    private $ssNumber;
    private $medicaidNumber;
    private $medicareNumber;
    private $comments;
    private $otherInfo;

    public function __construct(){

    }

    public function getResident(){
		return $this->resident;
	}

	public function setResident($resident){
		$this->resident = $resident;
	}

	public function getPayer(){
		return $this->payer;
	}

	public function setPayer($payer){
		$this->payer = $payer;
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

	public function getInsurance(){
		return $this->insurance;
	}

	public function setInsurance($insurance){
		$this->insurance = $insurance;
	}

	public function getSsNumber(){
		return $this->ssNumber;
	}

	public function setSsNumber($ssNumber){
		$this->ssNumber = $ssNumber;
	}

	public function getMedicaidNumber(){
		return $this->medicaidNumber;
	}

	public function setMedicaidNumber($medicaidNumber){
		$this->medicaidNumber = $medicaidNumber;
	}

	public function getMedicareNumber(){
		return $this->medicareNumber;
	}

	public function setMedicareNumber($medicareNumber){
		$this->medicareNumber = $medicareNumber;
	}

	public function getComments(){
		return $this->comments;
	}

	public function setComments($comments){
		$this->comments = $comments;
	}

	public function getOtherInfo(){
		return $this->otherInfo;
	}

	public function setOtherInfo($otherInfo){
		$this->otherInfo = $otherInfo;
	}

}