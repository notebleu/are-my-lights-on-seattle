<?php

class Storage {
    
    private $history_record      = '';
    private $history_storage_loc = '';
    
    public function __construct($history_storage_loc)
    {
        $this->history_storage_loc = $history_storage_loc;
        $this->setHistoryRecord();
    }
    
    private function setHistoryRecord()
    {
		$handle = fopen($this->history_storage_loc,'r');
		
		if( $handle === FALSE ){
    		throw new Exception('Please provide a valid file path');
            return FALSE;
		}

		$history_size = filesize($this->history_storage_loc);

		$this->history_record = ( ( $history_size > 0 ) ) ? fread($handle, $history_size) : '';
		
		fclose($handle);
		
    }

    public function getHistory()
    {
        return $this->history_record;
    }
    
    public function inHistory($date)
    {
        return strpos($this->history_record, $date . ' - power out');
    }
    
    public function prependToHistory($message)
    {
        $handle = fopen($this->history_storage_loc,'r+');
        if( $handle === FALSE ){
    		throw new Exception('Please provide a valid file path');
            return FALSE;
		}
        fwrite($handle,$message);
        fclose($handle);
    }

    public function overwriteHistory($message)
    {
        $handle = fopen($this->history_storage_loc,'w');
        if( $handle === FALSE ){
    		throw new Exception('Please provide a valid file path');
            return FALSE;
		}
        fwrite($handle,$message);
        fclose($handle);
    }
    
}