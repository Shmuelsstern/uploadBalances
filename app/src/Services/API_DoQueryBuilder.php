<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/15/2018
 * Time: 10:28 PM
 */

namespace App\src\Services;


class API_DoQueryBuilder
{
    private $subject;
    private $query;
    const MAPPED_FIELDS = ['group'=>['record ID#'=>'3','Name'=>6,'collections'=>'24'],
        'facility'=>['record ID#'=>'3','SHORT NAME'=>'40','GROUP'=>'45','Related GROUP'=>'44'],
        'resident'=>['Record ID#'=>3,'First Name'=>6,'Last Name'=>7,'ID'=>8,'Medicare #'=>23,'Related FACILITY'=>9,'DOB'=>14,'Medicaid #'=>24,'SS#'=>22,'FACILITY - GROUP'=>30],
        'payer'=>['Record ID#'=>3,'Name'=>6]];
    const QUERY_OPERATOR = ['contains'=>'CT','does not contain'=>'XCT','equals'=>'EX', 'not equal to'=>'XEX'];
    private $returnList;
    private $sortList;
    private $doQuery;


    /**
     * API_DoQueryBuilder constructor.
     * @param $subject
     */
    private function __construct($subject  )
    {
        $this->subject = $subject;
    }

    /**
     * @param $queryField
     * @param $operator
     * @param $queryValue
     * @return $this
     */
    private function setQuery($queryField, $operator, $queryValue)
    {
        $queryField = self::MAPPED_FIELDS[$this->subject][$queryField];
        $operator = self::QUERY_OPERATOR[$operator];
        $this->query = '<query>{'.$queryField.".".$operator.".".$queryValue.'}</query>';

        return $this;
    }

    /**
     * @param $returnFields
     * @return $this
     */
    public function setReturnList($returnFields)
    {
        $CList='';
        $delimiter="<clist>=";
        foreach($returnFields as $rf){
            $CList.=$delimiter.self::MAPPED_FIELDS[$this->subject][$rf];
            $delimiter='.';
        }
        $CList.='</clist>';
        $this->returnList = $CList;

        return $this;
    }

    public function setSortList($sortFields)
    {
        $SList='';
        $delimiter="<slist>=";
        foreach($sortFields as $sf){
            $SList.=$delimiter.self::MAPPED_FIELDS[$this->subject][$sf];
            $delimiter='.';
        }
        $SList.='</slist>';
        $this->sortList = $SList;

        return $this;
    }

    public function buildDoQuery()
    {
        $this->doQuery = $this->query.$this->returnList.$this->sortList;
    }

    public function getBuild()
    {
        return $this->doQuery;
    }

}