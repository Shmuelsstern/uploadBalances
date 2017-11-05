<?php

namespace App\src\Services;


use Illuminate\Support\Facades\Storage;
use App\src\Objects\ParsedFile;

class CSVFileParser{

    private $parsedfile;
    //private $expectedColumns;

    public function __construct(){
        //$this->setParsedfile();
        //$this->setExpectedColumns($expectedColumns);
    }

    public function getParsedfile(){
		return $this->parsedfile;
	}

	public function setParsedfile($uploadedfile){
		$handle= fopen($uploadedfile,'r');
        $fileInfo=[];
        while(($data=fgetcsv($handle))!==false){
            $fileInfo[]=$data;
        }
        fclose($handle);
        $this->parsedFile = new ParsedFile($fileInfo);
	}

	/*public function getExpectedColumns(){
		return $this->expectedColumns;
	}

	public function setExpectedColumns($expectedColumns){
		$this->expectedColumns = $expectedColumns;
    }*/
   
};