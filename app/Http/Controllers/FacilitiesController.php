<?php

namespace App\Http\Controllers;

use App\Entities\Repositories\NewBalanceRepo;
use App\Entities\Resident;
use App\src\Services\QuickbaseRequester;
use App\src\Services\Matcher;
use App\Entities\Repositories\FacilityRepo;
use Illuminate\Http\Request;
use App\src\Services\API_ImportFromCSVRequester;
use App\Entities\Facility;
use Illuminate\Support\Collection;

class FacilitiesController extends Controller{

    private $group;
    const SUBJECT = 'facility';
    const QB_FACILITY_RECORD_ID = 'record ID#';
    const QB_FACILITY_NAME = 'SHORT NAME';
    const QB_FACILITY_RELATED_GROUP_ID = 'Related GROUP';
    const QB_FACILITY_RELATED_GROUP_NAME = 'GROUP';
    const QB_FACILITY_RECORD_ID_FIELD = 3;
    const QB_FACILITY_NAME_FIELD = 40;
    const QB_FACILITY_RELATED_GROUP_ID_FIELD = 44;
    const QB_FACILITY_RELATED_GROUP_NAME_FIELD = 45;



    /**
     * @param $variable
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     * @throws \Exception
     */
    private function getSessionVariable($variable)
    {
        if (session($variable)!== null) {
            return session($variable);
        } else {
            throw new \Exception('no group initialized');
        }
    }


    /**
     * @param QuickbaseRequester $QBR
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function matchFacilities(QuickbaseRequester $QBR)
    {
        try {
            $this->group = $this->getSessionVariable('group');
        } catch (\Exception $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }
        $QBR->setSubject(self::SUBJECT)->setAction('API_DoQuery')->getRequestBuilder()->setQuery
        (self::QB_FACILITY_RELATED_GROUP_NAME_FIELD,'equals',/*$this->group->getRecordID()*/'Symphony')->setReturnList
        ([self::QB_FACILITY_RECORD_ID_FIELD,self::QB_FACILITY_NAME_FIELD,self::QB_FACILITY_RELATED_GROUP_ID_FIELD])->buildRequest();
        try {
            $results = $QBR->setXMLRequest()->requestXML();
        } catch (\Throwable $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }
        $QBRepo = new FacilityRepo();
        foreach($results->record as $record){
            $QBRepo->pushFromXML($record);
        }
        try {
            $newBalanceRepo = $this->getSessionVariable('newBalanceRepo');
        } catch (\Exception $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }
        $facilitiesToMatch=$newBalanceRepo->getUniqueFacilitiesCollection();
        $facilityMatcher = new Matcher(self::SUBJECT,$QBRepo,$facilitiesToMatch);
        $facilityMatcher->match();
        return view('confirmMatchedFacilities',['facilityMatcher'=>$facilityMatcher]);
    }


    /**
     * @param Request $request
     * @param QuickbaseRequester $QBR
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateNewFacilities(Request $request, QuickbaseRequester $QBR)
    {
        try {
            $newBalanceRepo = $this->getSessionVariable('newBalanceRepo');
            $group = $this->getSessionVariable('group');
        } catch (\Exception $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }

        $facilitiesToMatch=$newBalanceRepo->getUniqueFacilitiesCollection()->all();
        $newFacilities=[];
        /** @var Facility $facility */
        foreach($facilitiesToMatch as $facility){
            $stripped=strtolower(str_replace(' ','',$facility->getUniqueIdentifier()));
            if($request->$stripped=="unmatched"){
                $newFacilities[]=[$facility->getUploadedFacilityName(),$this->group->getName()];
            }else{
                $facility->setRecordId($request->$stripped);
            }
        }
        if(!empty($newFacilities)&&isset($newFacilities)){
            $QBR->setSubject(self::SUBJECT)->setAction('API_ImportFromCSV')->getRequestBuilder()->setCSVString
            ($newFacilities)->setColumnFields([self::QB_FACILITY_NAME_FIELD,
                self::QB_FACILITY_RELATED_GROUP_NAME_FIELD])->buildRequest();
            try {
                $newRecordIds = $QBR->setXMLRequest()->requestXML();
            } catch (\Throwable $e) {
                $pattern = "%s in %s on line %s. \nTRACE: %s";
                logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
            }
            foreach($newRecordIds->fields as $field){
                $facility=$newBalanceRepo->getUniqueFacilitiesCollection()->get((string)$field->field[1].(string)$field->field[0]);
                $facility->setRecordId((int)$field->attributes()[0]);
                $facility->setRelatedGroup();
            }
        }
        /** @var NewBalanceRepo $newBalanceRepo */
        $newBalanceRepo->SetUniqueResidentsCollection($newBalanceRepo->getUniqueResidentsCollection()->mapWithKeys(/**
         * @param Resident $uniqueResident
         * @param $key
         * @return array
         */
            function($uniqueResident, $key){
            return [$uniqueResident->getRelatedFacility()->getRecordId().$uniqueResident->getPatientId()=>$uniqueResident];
        }));
        return redirect('/matchResidents');
    }
}