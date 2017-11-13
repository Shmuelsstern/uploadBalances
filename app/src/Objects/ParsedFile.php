<?php 

namespace App\src\Objects;//unsure about the naming convention

class ParsedFile{

    private $parsedFileArray; //array array
    private $mappedColumns;

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