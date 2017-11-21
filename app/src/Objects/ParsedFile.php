<?php 

namespace App\src\Objects;//unsure about the naming convention

class ParsedFile{

    private $parsedFileArray; //array array
	private $mappedColumns;
	private $hasSeparateDOSColumn;
	private $balanceDOSArray;
	static $COLUMNS_TO_CHOOSE = ['newBalances'=>['balance'=>'balance','facility'=>'facility','patient_ID'=>'patient ID','first_name'=>'first name','last_name'=>'last name','DOB'=>'DOB','social_security'=>'social security #','Medicaid'=>'Medicaid #','Medicare'=>'Medicare #','payer_type'=>'payer type','insurance'=>'insurance','policy'=>'policy #','DOS'=>'DOS','new_balance'=>'new balance','comments'=>'comments']];

    public function __construct($parsedFileArray){
		$this->setParsedFileArray($parsedFileArray);
    }

	public function getParsedFileArray(){
		return $this->parsedFileArray;
	}

	public function setParsedFileArray($parsedFileArray){
		$this->parsedFileArray = $parsedFileArray;
	}

	public function getMappedColumns(){
		return $this->mappedColumns;
	}

	public function setMappedColumns($POSTData){
		$mappedColumns=[];
		if(in_array('DOS',$POSTData)){
			$this->hasSeparateDOSColumn = true;
		}else{
			$this->hasSeparateDOSColumn = false;
		}
		for($columnIndex=0;$columnIndex<count($this->parsedFileArray[0]);$columnIndex++){
			$currentData=$POSTData['column'.$columnIndex];
			if(isset($currentData)&&!empty($currentData)){
				if($currentData=='balance' && !$this->hasSeparateDOSColumn){
					$this->balanceDOSArray[$columnIndex]=$POSTData['DOS'.$columnIndex];				
				}else{
					$mappedColumns[$currentData]=$columnIndex;
				}
			}
		}
		$this->mappedColumns = $mappedColumns;
	}
};