<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\Entities\Repositories\PayerRepo;
use Illuminate\Http\Request;
use App\src\Services\API_ImportFromCSVRequester;
use App\Entities\Payer;

class PayersController extends Controller{

    private $group=['Symphony'=>1];

    public function matchPayers(){
        $subject='payer';
        $returnFields=['Record ID#','Name'];
        $QBQ= new QuickbaseQuerier($subject,'Name','not equal to','xxx',$returnFields);
        $XMLresponse = $QBQ->requestURL();
        $QBrepo = new PayerRepo();
        foreach($XMLresponse->record as $record){
            $QBrepo->pushFromXML($record);
        }
        $newBalanceRepo=session('newBalanceRepo');
        $payersToMatch=$newBalanceRepo->getUniquePayersCollection();
        $payerMatcher = new Matcher($subject,$QBrepo,$payersToMatch);
        $payerMatcher->match();
        return view('confirmMatchedPayers',['payerMatcher'=>$payerMatcher]);
    } 

    public function updateNewPayers(Request $request){
        $newBalanceRepo=session('newBalanceRepo');
        $payersToMatch=$newBalanceRepo->getUniquePayersCollection()->all();
        $newPayers=[];
        foreach($payersToMatch as $payer){
            $stripped=strtolower(str_replace(' ','',$payer->getUniqueIdentifier()));
            if($request->$stripped=="unmatched"){
                $newPayers[]=[$payer->getName()];
            }else{
                $payer->setRecordId($request->$stripped);
            }
        }
        if(!empty($newPayers)){
            $importCSVRequestor = new API_ImportFromCSVRequester('payer',$newPayers,'6');
            $newRecordIds = $importCSVRequestor->requestXML()->rids;
            foreach($newRecordIds->fields as $field){
                $payer=$newBalanceRepo->getUniquePayersCollection()->get((string)$field->field[0]);
                $payer->setRecordId((int)$field->attributes()[0]);
            }
        }
        return redirect('/uploadNewBalancesToQuickbase');
    }
}