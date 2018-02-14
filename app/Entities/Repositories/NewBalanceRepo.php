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
                $balanceWODollarSign = str_replace('$','',$newBalanceInfo['balance']);
                if(is_numeric($balanceWODollarSign) && $balanceWODollarSign<>0){
                    $newBalance= new NewBalance($newBalanceInfo);
                    $this->add($newBalance);
                }
            }
        }else{
            foreach($parsedFile->getIdentifiedColumnsArray() as $newBalanceInfo){
                foreach($parsedFile->getBalanceDOSArray() as $index=>$DOS){
                    $balanceWODollarSign = str_replace('$','',$newBalanceInfo['balance'.$index]);
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
                    $newBalance= new NewBalance($tmpNewBalanceInfo);
                    $this->add($newBalance); 
                }
            }
        }
    }

    public function add($newBalance){
        $uniquePayersIdentifier=$newBalanceCollection->getPayer()->getPayerType();
        if(!$this->uniquePayersCollection->has($uniquePayersIdentifier)){
            $this->uniquePayersCollection->put($uniquePayersIdentifier,$newBalance->getPayer());
        }
        $this->$newBalanceCollection->add($newBalance);
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
