<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\Entities\Repositories\FacilityRepo;

class FacilitiesController extends Controller{

    public function matchFacilities(QuickbaseQuerier $QBQ){
        $subject = 'facility';
        $parsedFile=session('rawUploadedNewBalances');
        $uniqueValues=array_values(array_unique(array_column($parsedFile->getParsedFileArray(), $parsedFile->getMappedColumns()[$subject])));
        $QBQ->setRequest($subject,'GROUP','equals','Symphony', ['record ID#','SHORT NAME']);
        $response =$QBQ->query();
        $facilityRepo= new FacilityRepo();
        foreach($response->record as $record){
            $facilityRepo->pushFromXML($record);
        }
        dd($facilityRepo->getFacilityCollection(),$uniqueValues);
    } 
}