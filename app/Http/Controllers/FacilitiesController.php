<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\Entities\Repositories\FacilityRepo;
use Illuminate\Http\Request;
use App\src\Services\API_ImportFromCSVRequester;
use App\Entities\Facility;

class FacilitiesController extends Controller{

    public function matchFacilities(QuickbaseQuerier $QBQ){
        $subject='facility';
        $uniqueValues=$this->getUniqueValuesOfColumn($subject);
        $QBQ->setQuery($subject,'GROUP','equals','Symphony');
        $QBQ->setRequest($subject, ['record ID#','SHORT NAME']);
        $response = $QBQ->query();
        $repo = new FacilityRepo();
        foreach($response->record as $record){
            $repo->pushFromXML($record);
        }
        $facilityMatcher = new Matcher($repo,$uniqueValues);
        $facilityMatcher->match();
        session(['facilityRepo'=>$repo]);
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    } 

    public function updateNewFacilities(Request $request){
        $uniqueValues=$this->getUniqueValuesOfColumn('facility');
        $newFacilities=[];
        $repo = session('facilityRepo');
        foreach($uniqueValues as $facilityName){
            $stripped=strtolower(str_replace(' ','',$facilityName));
            if($request->$stripped=="unmatched"){
                $newFacilities[]=$facilityName;
            }else{
                $repo->findBy('recordId',$request->$stripped)->setParams(['uploadedName'=>$facilityName,'recordId'=>$request->$stripped]);
            }
        }
        $importCSVRequestor = new API_ImportFromCSVRequester('facility',$newFacilities,'40.44');
        $importCSVRequestor->setXMLRequest();   
        $newRecordIds = $importCSVRequestor->requestXML()->rids;
        $i=0;
        foreach($uniqueValues as $facilityName){
            $stripped=strtolower(str_replace(' ','',$facilityName));
            if($request->$stripped=="unmatched"){
                $currentFacility = new Facility();
                $currentFacility->setParams(['recordId'=>$newRecordIds[$i],'uploadedName'=>$facilityName,'shortName'=>$facilityName]);
                $repo->getCollection()->put($facilityName,$currentFacility);
                $i++;
            }
        }
        dd($repo->getMatchedFacilities());
        /*dd($uniqueValues,$request->all(),$newFacilities,session('rawUploadedNewBalances'));*/
    }

    public function getUniqueValuesOfColumn($subject){
        $parsedFile=session('rawUploadedNewBalances');
        $uniqueValues=array_values(array_unique(array_column($parsedFile->getParsedFileArray(), $parsedFile->getMappedColumns()[$subject])));
        return $uniqueValues;
    }
}