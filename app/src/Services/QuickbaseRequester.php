<?php

namespace App\src\Services;

abstract class QuickbaseRequester{

    private $appToken; 
    private $userToken;
    private $url;
    private $database;
    private $action;
    private $request;
    private $method = 'POST';
    private $header;
    protected $subject;

    public function __construct($subject,$action){
        $this->appToken = env('QB_TEST_APPTOKEN'); 
        $this->userToken = env('QB_USERTOKEN');
        $this->url = env('QB_APP_URL');
        $this->database = env('QB_TEST_'.strtoupper($subject).'TABLE');
        $this->action = $action;
        $this->subject=$subject;
    }

    public function setXMLRequest(){    
        $postString='<qdbapi>';
        $postString.='  <usertoken>'.$this->userToken.'</usertoken>';
        $postString.='  <apptoken>'.$this->appToken.'</apptoken>';
        $postString.= $this->getSpecificXMLRequest();   
        $postString.='</qdbapi>'; 
        $this->request = $postString;
    }

    public function requestXML(){
        $opts = array('http' =>
            array(
                'method'  => $this->method,
                'header'  => ['Content-type: application/xml',
                'QUICKBASE-ACTION: '.$this->action,
                'Content-Length: '.strlen($this->request)],
                'content' => $this->request
            )
        );
        $context = stream_context_create($opts);  
        $result = file_get_contents($this->url.$this->database, false, $context);
        $xml_result = simplexml_load_string($result); 
        return $xml_result;
    }

    public abstract function getSpecificXMLRequest();

    public function setURLRequest(){
        $requestString = $this->url;
        $requestString.= $this->database;
        $requestString.= '?a='.$this->action;
        $requestString.= '&apptoken='.$this->appToken.'&usertoken='.$this->userToken;
        $requestString.= $this->getSpecificURLRequest();
        $this->request=$requestString;
    }
    
    public function requestURL(){
        return simplexml_load_file($this->request);
    }

    public abstract function getSpecificURLRequest();

}