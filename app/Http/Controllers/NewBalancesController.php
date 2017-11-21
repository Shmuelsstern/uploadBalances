<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewBalancesController extends Controller{

    public function setColumns(Request $request){
        $parsedFile = session('rawUploadedNewBalances');
        $parsedFile->setMappedColumns($request->all());
    }   
}
