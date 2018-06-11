<?php

namespace App\Entities\Repositories;
use App\src\Objects\ParsedFile;
use Illuminate\Support\Collection;
use App\Entities\NewBalance;

Class NewBalanceRepo{

    private $newBalanceCollection;
    private $uniqueFacilitiesCollection;
    private $uniqueResidentsCollection;
    private $uniquePayersCollection;

    /**
     * NewBalanceRepo constructor.
     */
    public function __construct(){
        $this->newBalanceCollection = new Collection();
        $this->uniqueFacilitiesCollection = new Collection();
        $this->uniqueResidentsCollection = new Collection();
        $this->uniquePayersCollection = new Collection();
    }

    /**
     * @param ParsedFile $parsedFile
     */
    public function parsedFileToObject($parsedFile){
        if($parsedFile->hasSeparateDOSColumn()){
            foreach($parsedFile->getIdentifiedColumnsArray() as $newBalanceInfo){
                $balanceWODollarSign = str_replace(['(','$',',',' ',')'],['-',''],$newBalanceInfo['balance']); 
                if(is_numeric($balanceWODollarSign) && $balanceWODollarSign<>0){
                    $newBalance= new NewBalance($this,$newBalanceInfo);
                    $this->add($newBalance);
                }
            }
        }else{
            foreach($parsedFile->getIdentifiedColumnsArray() as $newBalanceInfo){
                foreach($parsedFile->getBalanceDOSArray() as $index=>$DOS){
                    $balanceWODollarSign = str_replace(['(','$',',',' ',')'],['-',''],$newBalanceInfo['balance'.$index]);
                    if(isset($balanceWODollarSign) && (is_numeric($balanceWODollarSign) && $balanceWODollarSign<>0)){
                        $tmpNewBalanceInfo=[];
                        $tmpNewBalanceInfo['DOS']=$DOS;
                        foreach($newBalanceInfo as $key=>$value){
                            if($key=='balance'.$index){
                                $tmpNewBalanceInfo['balance']=$value;
                            }elseif(!strpos($key,'balance')){
                                $tmpNewBalanceInfo[$key]=$value;
                            }
                        }
                    }
                    $newBalance= new NewBalance($this,$tmpNewBalanceInfo);
                    $this->add($newBalance); 
                }
            }
        }
    }

    /**
     * @param NewBalance $newBalance
     */
    public function add($newBalance){
        $uniqueFacilityIdentifier=$newBalance->getGroup()->getName().$newBalance->getUploadedFacilityName();
        if(!$this->uniqueFacilitiesCollection->has($uniqueFacilityIdentifier)){
            $newBalance->setFacility();
            $this->uniqueFacilitiesCollection->put($uniqueFacilityIdentifier,$newBalance->getFacility());
        }else{
            $newBalance->setFacility($this->uniqueFacilitiesCollection->get($uniqueFacilityIdentifier));
        }
        $uniqueResidentIdentifier=$newBalance->getUploadedFacilityName().$newBalance->getPatientId();
        if(!$this->uniqueResidentsCollection->has($uniqueResidentIdentifier)){
            $newBalance->setResident();
            $this->uniqueResidentsCollection->put($uniqueResidentIdentifier,$newBalance->getResident());
        }else{
            $newBalance->setResident($this->uniqueResidentsCollection->get($uniqueResidentIdentifier));
        }       
        $uniquePayersIdentifier=$newBalance->getPayerType();
        if(!$this->uniquePayersCollection->has($uniquePayersIdentifier)){
            $newBalance->setPayer();
            $this->uniquePayersCollection->put($uniquePayersIdentifier,$newBalance->getPayer());
        }else{
            $newBalance->setPayer($this->uniquePayersCollection->get($uniquePayersIdentifier));
        } 
        $this->newBalanceCollection->push($newBalance);
    }

    /**
     * @return Collection
     */
    public function getNewBalanceCollection(){
        return $this->newBalanceCollection;
    }

    /**
     * @return Collection
     */
    public function getUniqueFacilitiesCollection(){
        return $this->uniqueFacilitiesCollection;
    }

    /**
     * @return Collection
     */
    public function getUniqueResidentsCollection(){
        return $this->uniqueResidentsCollection;
    }

    /**
     * @return Collection
     */
    public function getUniquePayersCollection(){
        return $this->uniquePayersCollection;
    }

    /**
     * @param $uniqueResidentsCollection
     * @return mixed
     */
    public function setUniqueResidentsCollection($uniqueResidentsCollection){
        return $this->uniqueResidentsCollection=$uniqueResidentsCollection;
    }
}
