<?php

namespace App\src\Services;

class QuickbaseRequester{

    private $appToken; 
    private $userToken;
    private $url;
    private $database;
    private $action;
    private $request;
    private $method = 'POST';
    private $requestBuilder;
    protected $subject;

    public function __construct($apptoken,$usertoken, $url)
    {
        $this->appToken = $apptoken;
        $this->userToken = $usertoken;
        $this->url = $url;
    }

    /**
     * @param mixed $subject
     * @return QuickbaseRequester
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        $this->database = env('QB_TEST_'.strtoupper($subject).'TABLE');

        return $this;
    }

    /**
     * @param mixed $action
     * @return QuickbaseRequester
     */
    public function setAction($action)
    {
        $this->action = $action;
        $builder = 'App\src\Services\\'.$action.'Builder';
        $this->requestBuilder = new $builder($this->subject);

        return $this;
    }

    public function getRequestBuilder()
    {
        return $this->requestBuilder;
    }

    public function setXMLRequest()
    {
        $postString='<qdbapi>';
        $postString.='  <usertoken>'.$this->userToken.'</usertoken>';
        $postString.='  <apptoken>'.$this->appToken.'</apptoken>';
        $postString.= $this->requestBuilder->getBuild();
        $postString.='</qdbapi>'; 
        $this->request = $postString;

        return $this;
    }

    /**
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function requestXML()
    {
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
        if(!$result = file_get_contents($this->url.$this->database, false, $context))
        {
            throw new \Exception('did not get contents');
        }elseif(!empty($result->errcode)&& $result->errcode<>0){
            throw new \Exception($result->errmessage);
        }


        $xml_result = simplexml_load_string($result); 
        return $xml_result;
    }

    //public abstract function getSpecificXMLRequest();

    /*public function setURLRequest()
    {
        $requestString = $this->url;
        $requestString.= $this->database;
        $requestString.= '?a='.$this->action;
        $requestString.= '&apptoken='.$this->appToken.'&usertoken='.$this->userToken;
        $requestString.= $this->getSpecificURLRequest();
        $this->request=$requestString;
    }
    
    public function requestURL(){
        return simplexml_load_file($this->request);
    }*/

    //public abstract function getSpecificURLRequest();

}