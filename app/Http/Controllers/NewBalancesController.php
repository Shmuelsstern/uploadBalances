<?php

namespace App\Http\Controllers;
use App\Entities\Repositories\NewBalanceRepo;

use Illuminate\Http\Request;

class NewBalancesController extends Controller{

    public function setColumns(Request $request,NewBalanceRepo $newBalanceRepo){
        $parsedFile = session('rawUploadedNewBalances');
        $parsedFile->setMappedColumns($request->all());
        $parsedFile->setIdentifiedColumnsArray();
        $newBalanceRepo->parsedFileToObject($parsedFile);
        session(['newBalanceRepo'=>$newBalanceRepo]);
        return redirect('/matchFacilities');
    } 
    
    public function setNewBalances(){
        $facility = new \App\Entities\Facility(['uploadedName'=>'testing']);
        dd( $facility->getUploadedName());
    }
}
