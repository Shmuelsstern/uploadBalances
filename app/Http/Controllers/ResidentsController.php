<?php

namespace App\Http\Controllers;

use App\Entities\Resident;
use App\Entities\Repositories\ResidentRepo;
use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\src\Services\API_ImportFromCSVRequester;

class ResidentsController extends Controller{

    private $subject = 'resident';

    public function matchResidents(){
        $returnFields=['Record ID#','First Name','Last Name','ID','Medicare #','Related FACILITY','DOB','Medicaid #','SS#'];
        $QBQ= new QuickbaseQuerier($this->subject,'FACILITY - GROUP','equals','Symphony',$returnFields);
        $response = $QBQ->requestURL();
        $QBrepo = new ResidentRepo();
        foreach($response->record as $record){
            $QBrepo->pushFromXML($record);
        }
        $newBalanceRepo=session('newBalanceRepo');
        $residentsToMatch=$newBalanceRepo->getUniqueResidentsCollection();
        $residentMatcher = new Matcher($this->subject,$QBrepo,$residentsToMatch,true);
        $residentMatcher->match();
        session(['matchedResidents'=>$residentMatcher->getMatcheds()]);
        return redirect('/updateNewResidents');
    }

    public function updateNewResidents(){
        $matchedResidents=session('matchedResidents');
        $newBalanceRepo=session('newBalanceRepo');
        $residentsToMatch=$newBalanceRepo->getUniqueResidentsCollection()->all();
        $newResidents=[];
        foreach($residentsToMatch as $resident){
            $uniqueIdentifier=$resident->getUniqueIdentifier();
            if($matchedResidents[$uniqueIdentifier]['object']->getRecordId()=="unmatched"){
                $newResidents[]=[$resident->getPatientId(),$resident->getRelatedFacility()->getRecordId(),$resident->getFirstName(),$resident->getLastName(),$resident->getMedicareNum(),$resident->getDOB(),$resident->getMedicaidNum(),$resident->getSocialSecurityNum()];
            }else{
                $resident->setRecordId($matchedResidents[$uniqueIdentifier]['object']->getRecordId());
            }
        }
        if(!empty($newResidents)){
            $importCSVRequestor = new API_ImportFromCSVRequester($this->subject,$newResidents,'8.9.6.7.23.14.24.22');
            $newRecordIds = $importCSVRequestor->requestXML()->rids;
            foreach($newRecordIds->fields as $field){
                $resident=$newBalanceRepo->getUniqueResidentsCollection()->get((string)$field->field[1].(string)$field->field[0]);
                $resident->setRecordId((int)$field->attributes()[0]);
            } 
        }
        return redirect('/matchPayers');
    }
    
}