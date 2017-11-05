<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\src\Services\CSVFileParser;
use Illuminate\Http\Request;

class FileController extends Controller{

    public function uploadNewBalancesFile(CSVFileParser $CSVFP, Request $request){
        //$parsedFile = new ParsedFile($CSVFP->ParsedFile);
        $CSVFP->setParsedFile($request->file('newBalancesfile'));
        session(['rawUploadedNewBalances'=> $CSVFP->getParsedFile()]);
        dd(session('rawUploadedNewBalances'));
    }
};