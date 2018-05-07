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
use Illuminate\Support\Collection;

class GroupRepo
{
    private $collection;
    const SUBJECT = "group";

    /**
     * GroupRepo constructor.
     * @param QuickbaseRequester $QBR
     */
    public function __construct(QuickbaseRequester $QBR)
    {
        $this->collection = new Collection();
        $this->populateCollection($QBR);
        //dd($this->collection);
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * @param QuickbaseRequester $QBR
     * @return GroupRepo
     */
    public function populateCollection(QuickbaseRequester $QBR)
    {
        $QBR->setSubject(self::SUBJECT)->setAction('API_DoQuery')->getRequestBuilder()->setQuery('collections','equals','TRUE')->setReturnList(['record ID#','Name'])->buildDoQuery();
        try {
            $results = $QBR->setXMLRequest()->requestXML();
        } catch (\Throwable $e) {
            $pattern = "%s in %s on line %s. \nTRACE: %s";
            logger(sprintf($pattern, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }
        $this->pushFromXml($results->record);
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
     *
     */
    public function pushFromXml($xmlGroups)
    {
        foreach ($xmlGroups as $xmlGroup)
        {
            $group = new Group();
            foreach ($xmlGroup as $key => $value)
            {
                $setParam = 'set' . ucfirst(camel_case($key));
                if (method_exists($group, $setParam))
                {
                    $group->$setParam((string)$value);
                }
            }
            $this->add($group);
        }
    }
}