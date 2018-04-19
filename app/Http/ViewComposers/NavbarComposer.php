<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/11/2018
 * Time: 10:55 PM
 */

namespace App\Http\ViewComposers;

use App\Entities\Repositories\GroupRepo;
use Illuminate\View\View;

class NavbarComposer
{
    private $groups;

    /**
     * NavbarComposer constructor.
     * @param GroupRepo $groupRepo
     */
    public function __construct(GroupRepo $groupRepo)
    {
        $this->groups = $groupRepo->getCollection();
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('groups', $this->groups);
    }
}