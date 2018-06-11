<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 5/9/2018
 * Time: 9:57 PM
 */

namespace App\src\Services;


use App\src\Interfaces\RequestBuilder;

class API_ImportFromCSVBuilder implements RequestBuilder
{

    private $subject;
    private $CSVString;
    private $clist;
    private $clist_output;
    private $importFromCSV;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getCSVString()
    {
        return $this->CSVString;
    }

    /**
     * @param $CSVData
     * @return $this
     * @throws \Exception
     */
    public function setCSVString($CSVData)
    {
        $this->CSVString = '  <records_csv>
                                <![CDATA[';
        if(is_array($CSVData))
        {
            foreach($CSVData as $row){
                if(is_array($row)){
                    $this->CSVString.= implode(',',$row);
                }else{
                $this->CSVString.= $row;
            }
            $this->CSVString.="\n";
            }
        }else{
            throw new \Exception('csv not in array format');
        }
        $this->CSVString .= '             ]]>
                              </records_csv>';

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClist()
    {
        return $this->clist;
    }

    /**
     * @param array $columnFields
     * @return $this
     */
    public function setColumnFields($columnFields)
    {
        $clist='';
        $delimiter="<clist>";
        foreach($columnFields as $cf){
            $clist.=$delimiter.$cf;
            $delimiter='.';
        }
        $clist.= '</clist>';
        $this->clist = $clist;

        return $this;
    }

    /**
     * @param array $returnFields
     * @return API_ImportFromCSVBuilder
     */
    public function setClistOutput($returnFields)
    {
        $clist_output='';
        $delimiter="<clist_output>";
        foreach($returnFields as $rf){
            $clist_output.=$delimiter.$rf;
            $delimiter='.';
        }
        $clist_output.= '</clist_output>';
        $this->clist_output = $clist_output;

        return $this;
    }

    public function buildRequest()
    {
        $this->importFromCSV = $this->CSVString.$this->clist.$this->clist_output;
    }

    public function getBuild()
    {
        return $this->importFromCSV;
    }

}