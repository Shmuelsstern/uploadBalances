<?php 

namespace App\src\Objects;//unsure about the naming convention

class ParsedFile{

    private $parsedFile;
    private $mappedColumns;

    public function __construct($parsedFile){
		$this->setParsedFile($parsedFile);
    }

	public function getParsedFile(){
		return $this->uploadedFile;
	}

	public function setParsedFile($parsedFile){
		$this->parsedFile = $parsedFile;
	}

	public function getMappedColumns(){
		return $this->mappedColumns;
	}

	public function setMappedColumns($mappedColumns){
		$this->mappedColumns = $mappedColumns;
	}
};