<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\Entities\Repositories\FacilityRepo;

class FacilitiesController extends Controller{

    public function matchFacilities(QuickbaseQuerier $QBQ){
        $subject = 'facility';
        $parsedFile=session('rawUploadedNewBalances');
        $uniqueValues=array_values(array_unique(array_column($parsedFile->getParsedFileArray(), $parsedFile->getMappedColumns()[$subject])));
        $QBQ->setQuery($subject,'GROUP','equals','Symphony');
        $QBQ->setRequest($subject, ['record ID#','SHORT NAME']);
        $response = $QBQ->query();
        $repo = new FacilityRepo();
        foreach($response->record as $record){
            $repo->pushFromXML($record);
        }
        $facilityMatcher = new Matcher($repo->getFacilityCollection(),$uniqueValues);
        $facilityMatcher->match();
        //dd($facilityMatcher->getReferenceCollection(),$facilityMatcher->getToMatchArray(),$facilityMatcher->getMultipleMatcheds());
        //confirm//exportQB unmatched//download new//match//
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    } 
}