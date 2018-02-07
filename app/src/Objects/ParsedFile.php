<?php 

namespace App\src\Objects;//unsure about the naming convention

class ParsedFile{

	private $parsedFileArray;//array array
	private $identifiedColumnsArray;//array array
	private $headerRow; 
	private $mappedColumns;
	private $hasSeparateDOSColumn;
	private $balanceDOSArray;
	static $COLUMNS_TO_CHOOSE = ['newBalances'=>['balance'=>'balance','facility'=>'facility','patientId'=>'patient ID','firstName'=>'first name','lastName'=>'last name','DOB'=>'DOB','socialSecurityNum'=>'social security #','medicaidNum'=>'Medicaid #','medicareNum'=>'Medicare #','payerType'=>'payer type','insurance'=>'insurance','policyNum'=>'policy #','DOS'=>'DOS','newBalance'=>'new balance','comments'=>'comments']];

    public function __construct($parsedFileArray, $headerRow){
		$this->setParsedFileArray($parsedFileArray);
		$this->setHeaderRow($headerRow);
    }

	public function getParsedFileArray(){
		return $this->parsedFileArray;
	}

	public function setHeaderRow($headerRow){
		$this->headerRow = $headerRow;
	}

	public function getHeaderRow(){
		return $this->headerRow;
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

	public function setIdentifiedColumnsArray(){
		$identifiedColumnsArray=[];
		foreach($this->parsedFileArray as $row){
			$balanceInfo=[];
			foreach($this->mappedColumns as $columnName=>$columnNumber){
				$balanceInfo[$columnName]=$row[$columnNumber];
			}
			$identifiedColumnsArray[]=$balanceInfo;
		}
		$this->identifiedColumnsArray=$identifiedColumnsArray;
	}

	public function getIdentifiedColumnsArray(){
		return $this->identifiedColumnsArray;
	}
};