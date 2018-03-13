<?php

namespace App\Http\Controllers;
use App\Entities\Repositories\NewBalanceRepo;

use Illuminate\Http\Request;
use App\src\Services\API_ImportFromCSVRequester;

class NewBalancesController extends Controller{

    private $subject = 'newBalances';

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

    public function uploadNewBalancesToQuickbase(){
        $newBalances = session('newBalanceRepo')->getNewBalanceCollection()->all();
        $newBalancesArray = [];
        foreach($newBalances as $newBalance){
            $newBalancesArray[]=[$newBalance->getResident()->getRecordId(),$newBalance->getPayer()->getRecordId(),$newBalance->getBalance(),$newBalance->getInsurance(),$newBalance->getDOS(),$newBalance->getPolicyNum()];
        }
        $uploadNewBalancesImportFromCSVRequester = new API_ImportFromCSVRequester($this->subject,$newBalancesArray,'26.56.9.10.8.22');
        $newRecordIds = $uploadNewBalancesImportFromCSVRequester->requestXML()->rids;
        dd($newRecordIds);
    }
}
