<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/23/2018
 * Time: 10:35 PM
 */

namespace App\Http\Controllers;


use App\Entities\Facility;
use App\Entities\Group;
use App\src\Services\QuickbaseRequester;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    const QB_GROUP_RECORD_ID = 'record ID#';
    const QB_GROUP_NAME = 'Name';
    const QB_GROUP_COLLECTIONS_CHECKBOX = 'collections';
    const QB_GROUP_RECORD_ID_FIELD = 3;
    const QB_GROUP_NAME_FIELD = 6;
    const QB_GROUP_COLLECTIONS_CHECKBOX_FIELD = 24;
    const QB_FACILITY_RECORD_ID = 'record ID#';
    const QB_FACILITY_NAME = 'SHORT NAME';
    const QB_FACILITY_RELATED_GROUP_ID = 'Related GROUP';
    const QB_FACILITY_RELATED_GROUP_NAME = 'GROUP';
    const QB_FACILITY_RECORD_ID_FIELD = 3;
    const QB_FACILITY_NAME_FIELD = 40;
    const QB_FACILITY_RELATED_GROUP_ID_FIELD = 44;
    const QB_FACILITY_RELATED_GROUP_NAME_FIELD = 45;

    public function groupSettings(Request $request, QuickbaseRequester $QBR)
    {

        $groupID= $request->id;
        $groupName = $request->name;
        $group = new Group();
        $group->setName($groupName)->setRecordId($groupID);
        session(['group'=>$group]);
        $facility = new Facility();
        $facility->setRecordId('all')->setShortName('All Facilities');
        session(['facility'=>$facility]);
        $QBR->setSubject('facility')->setAction('API_DoQuery')->getRequestBuilder()->setQuery(self::QB_FACILITY_RELATED_GROUP_ID,'equals',
            $groupID)->setReturnList([self::QB_FACILITY_RECORD_ID_FIELD,self::QB_FACILITY_NAME_FIELD])->buildRequest();
        try {
            $results = $QBR->setXMLRequest()->requestXML();
        } catch (\Throwable $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }
        $facilitiesArray[]=['short_name'=>'All Facilities','record_id_'=>'all'];
        foreach ($results->record as $key=>$facility)
        {
            $facilityArray = [];
            foreach ($facility as  $attribute=>$information) {
                $facilityArray[$attribute]= (string)$information;
            }
            $facilitiesArray[]= $facilityArray;
        }
        return json_encode($facilitiesArray);
    }

    public function facilitySettings(Request $request){

        $facilityID = $request->id;
        $facilityName = $request->name;
        $facility = new Facility();
        $facility->setRecordId($facilityID)->setShortName($facilityName);
        session(['facility'=>$facility]);

        return json_encode(['done']);
    }
}