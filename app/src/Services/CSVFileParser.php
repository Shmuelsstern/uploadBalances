<?php

namespace App\src\Services;


use Illuminate\Support\Facades\Storage;
use App\src\Objects\SourceFile;
use App\src\Objects\ParsedFile;

class CSVFileParser{

    private $parsedFile;
    //private $expectedColumns;

    public function __construct(SourceFile $file){
        $this->setParsedFile($file->getFile());
        //$this->setExpectedColumns($expectedColumns);
    }

    public function getParsedFile(){
		return $this->parsedFile;
	}

	public function setParsedFile($uploadedfile){
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