<?php

class SeattleLightApi {
    
    public $currentOutageAreas = NULL;
    
    public function __construct($url = '')
    {
        if( $url == '' ){
            throw new Exception('URL not defined');
        }
        
        $this->setApiData($url);
    }
    
    private function setApiData($url)
    {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        
        $this->currentOutageAreas = simplexml_load_string($data);
        
        return TRUE;
        
    }
    
}