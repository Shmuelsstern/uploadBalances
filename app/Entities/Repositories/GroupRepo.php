<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/11/2018
 * Time: 9:43 PM
 */

namespace App\Entities\Repositories;


use App\Entities\Group;
use App\src\Services\QuickbaseRequester;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GroupRepo
{
    private $collection;
    const SUBJECT = "Group";

    public function __construct(QuickbaseRequester $QBR)
    {
        $this->collection = new Collection();
        $QBR->setSubject(self::SUBJECT)->setAction('API_DoQuery')->getRequestBuilder()->setQuery('collections','equals','TRUE')->setReturnList(['record ID#','Name']);
        try {
            $result = $QBR->setXMLRequest()->requestXML();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        $this->pushFromXml($result);
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * @param Collection $collection
     * @return GroupRepo
     */
    public function setCollection(Collection $collection): GroupRepo
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @param Group $group
     */
    public function add(Group $group)
    {
        $this->collection->push($group);
    }

    /**
     * @param $xmlGroup
     */
    public function pushFromXml($xmlGroup)
    {
    $group = new Group();
        foreach($xmlGroup as $key => $value)
        {
            $setParam='set'.ucfirst(camel_case($key));
            if(method_exists($group,$setParam)){
                $group->$setParam((string)$value);
            }
        }
    $this->add($group);
    }
}