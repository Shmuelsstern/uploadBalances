<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\src\Services\CSVFileParser;
use Illuminate\Http\Request;

class FileController extends Controller{

    public function parseFileIntoArray(CSVFileParser $CSVFP, Request $request){ 
        $parsedFile=$CSVFP->getParsedFile();
        session(['rawUploadedNewBalances'=> $parsedFile]);
        $uploadType = array_keys($request->file())[0];
        switch($uploadType){
            case 'newBalancesFile':
            return view('chooseUploadedNewBalancesColumns',['uploadedFile'=>$parsedFile, 'uploadType'=>$uploadType]);
        }
        
    }
};