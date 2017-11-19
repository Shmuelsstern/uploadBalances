<?php 

namespace App\src\Objects;//unsure about the naming convention

class ParsedFile{

    private $parsedFileArray; //array array
	private $mappedColumns;
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

	public function setMappedColumns($mappedColumns){
		$this->mappedColumns = $mappedColumns;
	}
};