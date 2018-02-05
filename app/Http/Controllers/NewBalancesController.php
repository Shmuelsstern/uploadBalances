<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewBalancesController extends Controller{

    public function setColumns(Request $request){
        $parsedFile = session('rawUploadedNewBalances');
        $parsedFile->setMappedColumns($request->all());
        $parsedFile->setIdentifiedColumnsArray();
        return redirect('/matchFacilities');
    } 
    
    public function setNewBalances(){
        $facility = new \App\Entities\Facility(['uploadedName'=>'testing']);
        dd( $facility->getUploadedName());
    }
}
