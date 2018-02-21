<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\Entities\Repositories\FacilityRepo;
use Illuminate\Http\Request;
use App\src\Services\API_ImportFromCSVRequester;
use App\Entities\Facility;

class FacilitiesController extends Controller{

    private $group=['Symphony'=>1];

    public function matchFacilities(){
        $subject='facility';
        $returnFields=['record ID#','SHORT NAME','Related GROUP'];
        $QBQ= new QuickbaseQuerier($subject,'GROUP','equals','Symphony',$returnFields);
        $XMLresponse = $QBQ->requestURL();
        $QBrepo = new FacilityRepo();
        foreach($XMLresponse->record as $record){
            $QBrepo->pushFromXML($record);
        }
        $newBalanceRepo=session('newBalanceRepo');/*dd($newBalanceRepo);*/
        $facilitiesToMatch=$newBalanceRepo->getUniqueFacilitiesCollection()->keys()->all();
        $facilityMatcher = new Matcher($subject,$QBrepo,$facilitiesToMatch);
        $facilityMatcher->match();
        session(['facilityRepo'=>$QBrepo]);
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    } 

    public function updateNewFacilities(Request $request){
        $newBalanceRepo=session('newBalanceRepo');
        $facilitiesToMatch=$newBalanceRepo->getUniqueFacilitiesCollection()->all();
        $newFacilities=[];
        foreach($facilitiesToMatch as $facility){
            $stripped=strtolower(str_replace(' ','',$facility->getUploadedFacilityName()));
            if($request->$stripped=="unmatched"){
                $newFacilities[]=[$facility->getUploadedFacilityName(),$this->group['Symphony']];
            }else{
                $facility->setRecordId($request->$stripped);
            }
        }
        $importCSVRequestor = new API_ImportFromCSVRequester('facility',$newFacilities,'40.44');
        $newRecordIds = $importCSVRequestor->requestXML()->rids;
        $i=0;
        $testarray=[];
    foreach($newRecordIds->fields as $field){
        $facility=$newBalanceRepo->getUniqueFacilitiesCollection()->get((string)$field->field[0]);
        $facility->setRecordId((int)$field->attributes()[0]);
    }
        session(['newBalanceRepo'=>$newBalanceRepo]);
        return redirect('/matchResidents');
    }

    public function getUniqueValuesOfColumn($subject){
        $fieldName='uploaded'.ucfirst($subject).'Name';
        $parsedFile=session('rawUploadedNewBalances');
        $uniqueValues=array_values(array_unique(array_column($parsedFile->getIdentifiedColumnsArray(), $fieldName)));
        return $uniqueValues;
    }
}