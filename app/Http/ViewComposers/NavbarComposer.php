<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/11/2018
 * Time: 10:55 PM
 */

namespace App\Http\ViewComposers;

use App\Entities\Facility;
use App\Entities\Group;
use App\Entities\Repositories\GroupRepo;
use Illuminate\View\View;

class NavbarComposer
{
    private $groups;
    private $sessionVariables=[];

    /**
     * NavbarComposer constructor.
     * @param GroupRepo $groupRepo
     */
    public function __construct(GroupRepo $groupRepo)
    {
        $this->groups = $groupRepo->getCollection();
        $this->setSessionVariables();
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('groups', $this->groups);
        $view->with('sessionGroup',$this->sessionVariables['group']);
        $view->with('sessionFacility',$this->sessionVariables['facility']);
    }

    public function setSessionVariables()
    {
        if(session('group')!== null){
            $this->sessionVariables['group']=session('group');
        }else{
            $group = new Group();
            $group->setRecordId('all')->setName('select a group');
            $this->sessionVariables['group']=$group;
        }
        if(session('facility')!== null){
            $this->sessionVariables['facility']=session('facility');
        }else{
            $facility = new Facility();
            $facility->setRecordId('all')->setShortName('All Facilities');
            $this->sessionVariables['facility']=$facility;
        }
    }
}