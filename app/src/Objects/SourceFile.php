<?php

namespace App\src\Objects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class SourceFile{

    private $file;

    public function __construct(Request $request){
        $this->setFile($request->file());
    }

    public function setFile($requestFile){
        if (isset($requestFile)&&!empty($requestFile)){
            $this->file=end($requestFile);
        }else{
            $this->file='testclaims.csv';
        }
    }

    public function getFile(){
        return $this->file;
    }
    
}