<?php

namespace App\src\Services;


use Illuminate\Support\Facades\Storage;
use App\src\Objects\SourceFile;
use App\src\Objects\ParsedFile;

class CSVFileParser{

    private $parsedFile;

    public function __construct(SourceFile $file){
        $this->setParsedFile($file->getFile());
    }

    public function getParsedFile(){
		return $this->parsedFile;
	}

	public function setParsedFile($uploadedfile,$hasHeaderRow=true){
        $handle= fopen($uploadedfile,'r');
        $headerRow=null;
        if($hasHeaderRow){
            $headerRow = fgetcsv($handle);
        }
        $fileInfo=[];        
        while(($data=fgetcsv($handle))!==false){
            $fileInfo[]=$data;
        }
        fclose($handle);
        $this->parsedFile = new ParsedFile($fileInfo, $headerRow);
	}
   
};