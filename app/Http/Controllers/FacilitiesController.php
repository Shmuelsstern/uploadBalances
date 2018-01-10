<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\Entities\Repositories\FacilityRepo;
use Illuminate\Http\Request;

class FacilitiesController extends Controller{

    public function matchFacilities(QuickbaseQuerier $QBQ){
        $uniqueValues=$this->getUniqueValluesOfColumn('facility');
        $QBQ->setQuery($subject,'GROUP','equals','Symphony');
        $QBQ->setRequest($subject, ['record ID#','SHORT NAME']);
        $response = $QBQ->query();
        $repo = new FacilityRepo();
        foreach($response->record as $record){
            $repo->pushFromXML($record);
        }
        $facilityMatcher = new Matcher($repo,$uniqueValues);
        $facilityMatcher->match();
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    } 

    public function updateNewFacilities(Request $request){
        $uniqueValues=$this->getUniqueValluesOfColumn('facility');
        $newFacilities=[];
        foreach($uniqueValues as $facilityName){
            $stripped=strtolower(str_replace(' ','',$facilityName));
            if($request->$stripped=="unmatched"){
                $newFacilities[]=$facilityName;
            }
        }
            /*dd($uniqueValues,$request->all(),$newFacilities,session('rawUploadedNewBalances'));*/
    }

    public function getUniqueValluesOfColumn($subject){
        $parsedFile=session('rawUploadedNewBalances');
        $uniqueValues=array_values(array_unique(array_column($parsedFile->getParsedFileArray(), $parsedFile->getMappedColumns()[$subject])));
        return $uniqueValues;
    }
}