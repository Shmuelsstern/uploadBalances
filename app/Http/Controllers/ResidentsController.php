<?php

namespace App\Http\Controllers;

use App\Entities\Resident;
use App\Entities\Repositories\ResidentRepo;
use App\src\Services\QuickbaseQuerier;

class ResidentsController extends Controller{

    private $subject = 'resident';

    public function matchResidents(ResidentRepo $residentsToMatchRepo){
        $returnFields=['Record ID#','First Name','Last Name','ID','Medicare #','Related FACILITY','DOB','Medicaid #','SS#'];
        $QBQ= new QuickbaseQuerier($this->subject,'GROUP','equals','Symphony',$returnFields);
        $response = $QBQ->requestURL();
        $QBrepo = new ResidentRepo();
        foreach($response->record as $record){
            $repo->pushFromXML($record);
        }
        $parsedFile = session('rawUploadedNewBalances');
        $facilityRepo = session('facilityRepo');
        foreach($parsedFile->getIdentifiedColumnsArray() as $newBalanceRow){
            $newBalanceRow['relatedFacility'] = $facilityRepo->getMatchedFacilities()[$newBalanceRow['facility']]->getShortName();
            $residentsToMatchRepo->add($newBalanceRow);
        }
        dd($QBrepo,$residentsToMatchRepo);
        $residentsMatcher = new Matcher($QBrepo,$residentsToMatchRepo);
        $residentsMatcher->match();
    }
    
}