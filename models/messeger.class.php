<?php

class Messeger {
    
    private $email_address = '';
    private $subject       = '';
    private $message       = '';
    private $headers       = '';
    
    public function __construct($email_address = '',$subject = '',$message = '',$headers = '')
    {
        
        $this->email_address = $email_address;
        $this->subject       = $subject;
        $this->message       = $message;
        $this->headers       = $headers;
        
        $this->sendMessage();     
    }
    
    private function sendMessage()
    {
    
        if( $this->email_address == '' ){
            throw new Exception('Please provide an email address');
            return FALSE;
        }
        
        mail($this->email_address,$this->subject,$this->message,$this->headers);
        
    }
    
}