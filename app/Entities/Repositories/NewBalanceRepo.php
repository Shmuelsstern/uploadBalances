<?php

namespace App\Entities\Repositories;
use Illuminate\Support\Collection;
use App\Entities\NewBalance;

Class NewBalanceRepo{

    private $newBalanceCollection;
    private $uniqueFacilitiesCollection;
    private $uniqueResidentsCollection;
    private $uniquePayersCollection;

    public function __construct(){
        $this->newBalanceCollection = new Collection();
        $this->uniqueFacilitiesCollection = new Collection();
        $this->uniqueResidentsCollection = new Collection();
        $this->uniquePayersCollection = new Collection();
    }

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
                                $tmpNewBalanceInfo[$key]==$value;
                            }
                        }
                    }
                    $newBalance= new NewBalance($this,$tmpNewBalanceInfo);
                    $this->add($newBalance); 
                }
            }
        }
    }

    public function add($newBalance){
        $uniqueFacilityIdentifier=$newBalance->getUploadedFacilityName();
        if(!$this->uniqueFacilitiesCollection->has($uniqueFacilityIdentifier)){
            $this->uniqueFacilitiesCollection->put($uniqueFacilityIdentifier,$newBalance->getFacility());
        }
        $uniqueResidentIdentifier=$newBalance->getUploadedFacilityName().$newBalance->getPatientId();
        if(!$this->uniqueResidentsCollection->has($uniqueResidentIdentifier)){
            $this->uniqueResidentsCollection->put($uniqueResidentIdentifier,$newBalance->getResident());
        }       
        $uniquePayersIdentifier=$newBalance->getPayerType();
        if(!$this->uniquePayersCollection->has($uniquePayersIdentifier)){
            $this->uniquePayersCollection->put($uniquePayersIdentifier,$newBalance->getPayer());
        }
        $this->newBalanceCollection->push($newBalance);
    }

    public function getUniqueFacilitiesCollection(){
        return $this->uniqueFacilitiesCollection;
    }

    public function getUniqueResidentsCollection(){
        return $this->uniqueResidentsCollection;
    }

    public function getUniquePayersCollection(){
        return $this->uniquePayersCollection;
    }
}
