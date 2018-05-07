<?php

namespace App\Entities;

class Facility{

    private $recordId;
    private $shortName;
	private $uploadedFacilityName;
	private $relatedGroup;

    public function __construct(){
		$this->setRelatedGroup();
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

    /**
     * @param $recordId
     * @return $this
     */
    public function setRecordId($recordId){
		$this->recordId = $recordId;

		return $this;
	}

	public function getShortName(){
		return $this->shortName;
	}

	public function setShortName($shortName){
		$this->shortName = $shortName;

		return $this;
	}

	public function getUploadedFacilityName(){
		return $this->uploadedFacilityName;
	}

	public function setUploadedFacilityName($uploadedFacilityName){
		$this->uploadedFacilityName = $uploadedFacilityName;
	}

	public function setRelatedGroup(){
		$relatedGroup= session('group');
		if($relatedGroup){
			$this->relatedGroup=$relatedGroup;
		}else{
			$this->relatedGroup='nogroup';
		}
	}

	public function getRelatedGroup(){
		return $this->relatedGroup;
	}

	public function getUniqueIdentifier(){
		return $this->relatedGroup.$this->getName();		
	}

	public function getName(){
		if(isset($this->uploadedFacilityName)){
			return $this->uploadedFacilityName;
		}else{
			return $this->shortName;
		}
	}
}