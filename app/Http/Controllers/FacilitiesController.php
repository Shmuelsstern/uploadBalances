<?php

namespace App\Http\Controllers;

use App\src\Services\QuickbaseQuerier;
use App\src\Services\Matcher;
use App\Entities\Repositories\FacilityRepo;
use Illuminate\Http\Request;
use App\src\Services\API_ImportFromCSVRequester;
use App\Entities\Facility;

class FacilitiesController extends Controller{

    private $group;
    private $subject = 'facility';

    private function __construct()
    {
        try {
            $this->group = $this->getSessionGroup();
        } catch (\Exception $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }
    }

    /**
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     * @throws \Exception
     */
    private function getSessionGroup()
    {
        if (session('group')!== null) {
            $group = session('group');
        } else {
            throw new \Exception('no group selected');
        }

        return $group;
    }


    public function matchFacilities(){
        $subject='facility';
        $returnFields=['record ID#','SHORT NAME','Related GROUP'];
        $QBQ= new QuickbaseQuerier($subject,'GROUP','equals','Symphony',$returnFields);
        $XMLResponse = $QBQ->requestURL();
        $QBrepo = new FacilityRepo();
        foreach($XMLResponse->record as $record){
            $QBrepo->pushFromXML($record);
        }
        $newBalanceRepo=session('newBalanceRepo');
        $facilitiesToMatch=$newBalanceRepo->getUniqueFacilitiesCollection();
        $facilityMatcher = new Matcher($subject,$QBrepo,$facilitiesToMatch);
        $facilityMatcher->match();
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    } 

    public function updateNewFacilities(Request $request){
        $newBalanceRepo=session('newBalanceRepo');
        $facilitiesToMatch=$newBalanceRepo->getUniqueFacilitiesCollection()->all();
        $newFacilities=[];
        foreach($facilitiesToMatch as $facility){
            $stripped=strtolower(str_replace(' ','',$facility->getUniqueIdentifier()));
            if($request->$stripped=="unmatched"){
                $newFacilities[]=[$facility->getUploadedFacilityName(),$this->group['Symphony']];
            }else{
                $facility->setRecordId($request->$stripped);
            }
        }
        if(!empty($newFacilities)&&isset($newFacilities)){
            $importCSVRequestor = new API_ImportFromCSVRequester('facility',$newFacilities,'40.45');
            $newRecordIds = $importCSVRequestor->requestXML()->rids;
            foreach($newRecordIds->fields as $field){
                $facility=$newBalanceRepo->getUniqueFacilitiesCollection()->get((string)$field->field[1].(string)$field->field[0]);
                $facility->setRecordId((int)$field->attributes()[0]);
                $facility->setRelatedGroup((string)$field->field[1]);
            }
        }
        $newBalanceRepo->SetUniqueResidentsCollection($newBalanceRepo->getUniqueResidentsCollection()->mapWithKeys(function($uniqueResident,$key){
            return [$uniqueResident->getRelatedFacility()->getRecordId().$uniqueResident->getPatientId()=>$uniqueResident];
        }));
        return redirect('/matchResidents');
    }
}