<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/11/2018
 * Time: 9:13 PM
 */

namespace App\Entities;


class Group
{
    private $name;
    private $recordId;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @param mixed $recordId
     */
    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;
    }


}