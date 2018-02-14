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
        $facilitiesToMatch=$this->getUniqueValuesOfColumn($subject);
        $facilityMatcher = new Matcher($subject,$QBrepo,$facilitiesToMatch);
        $facilityMatcher->match();
        session(['facilityRepo'=>$QBrepo]);
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    } 

    public function updateNewFacilities(Request $request){
        $uniqueValues=$this->getUniqueValuesOfColumn('facility');
        $newFacilities=[];
        $repo = session('facilityRepo');
        foreach($uniqueValues as $facilityName){
            $stripped=strtolower(str_replace(' ','',$facilityName));
            if($request->$stripped=="unmatched"){
                $newFacilities[]=[$facilityName,$this->group['Symphony']];
            }else{
                $repo->findBy('recordId',$request->$stripped)->setParams(['uploadedFacilityName'=>$facilityName,'recordId'=>$request->$stripped]);
            }
        }
        $importCSVRequestor = new API_ImportFromCSVRequester('facility',$newFacilities,'40.44');
        $newRecordIds = $importCSVRequestor->requestXML()->rids;
        $i=0;
        foreach($uniqueValues as $facilityName){
            $stripped=strtolower(str_replace(' ','',$facilityName));
            if($request->$stripped=="unmatched"){
                $currentFacility = new Facility();
                $currentFacility->setParams(['recordId'=>$newRecordIds[$i],'uploadedFacilityName'=>$facilityName,'shortName'=>$facilityName]);
                $repo->getCollection()->put($facilityName,$currentFacility);
                $i++;
            }
        }
        $rawUploadedNewBalances=session('rawUploadedNewBalances');
        return redirect('/matchResidents');
    }

    public function getUniqueValuesOfColumn($subject){
        $fieldName='uploaded'.ucfirst($subject).'Name';
        $parsedFile=session('rawUploadedNewBalances');
        $uniqueValues=array_values(array_unique(array_column($parsedFile->getIdentifiedColumnsArray(), $fieldName)));
        return $uniqueValues;
    }
}