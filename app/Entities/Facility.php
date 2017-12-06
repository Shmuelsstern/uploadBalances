<?php

namespace App\Entities;

class Facility{

    private $recordId;
    private $shortName;
    private $uploadedName;

    public function __construct(){

	}
	
	public function setParams(Array $params){
        foreach($params as $key => $param){
            $setter ='set'.ucfirst($key);
            $this->$setter($param);
        }
	}

    public function getrecordId(){
		return $this->recordId;
	}

	public function setrecordId($recordId){
		$this->recordId = $recordId;
	}

	public function getShortName(){
		return $this->shortName;
	}

	public function setShortName($shortName){
		$this->shortName = $shortName;
	}

	public function getUploadedName(){
		return $this->uploadedName;
	}

	public function setUploadedName($uploadedName){
		$this->uploadedName = $uploadedName;
	}
}