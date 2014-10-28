<?php

class Polygons {

    public $polygons       = array();
    public $outageReasons  = array();
    public $restoreTimes   = array(); 
    public $numberEffected = array(); 
    public $information    = array(); 
    
    public function __construct($currentOutageAreas = '')
    {
        if( $currentOutageAreas == '' ){
            throw new Exception('Outage areas object not defined');
            return FALSE;
        }
        
        $this->setPolygons($currentOutageAreas);
    }
    
    private function setPolygons($currentOutageAreas)
    {             
        
        $currOutageNum = $currentOutageAreas->Document->Placemark->count();
        $polygons = $outageReasons = $restoreTimes = $numberEffected = $information = array();
        $n = 0;
        for($i = 0; $i < $currOutageNum; $i++)
        {
            if( isset( $currentOutageAreas->Document->Placemark[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates ) ){
                $coordinate_string = $currentOutageAreas->Document->Placemark[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates;
            } else {
                continue;
            }
        	
        
        	if($coordinate_string)
        	{
        	
        	    $find    = array(
        	        ',0',
        	        ','  
        	    );
        	    
        	    $replace = array(
        	        "'::'",
        	        ' '  
        	    );
        		
        		# clean the string of the trailing ,0 and format it for the polygon processor
        		$coordinate_string  = str_replace($find, $replace, $coordinate_string);
        
                # clear the string of the trailing ','\s
        		$coordinate_string  = substr($coordinate_string,0,-4);
        
        		# set the array of coordinates for this polygon
        		$polygons[$n]       = explode("'::'",$coordinate_string);
        				        
        		$outageReasons[$n]  = $currentOutageAreas->Document->Placemark[$i]->ExtendedData->Data[3]->value;
        		
        		$restoreTimes[$n]   = str_replace("Est. Restoration:", "",$currentOutageAreas->Document->Placemark[$i]->ExtendedData->Data[2]->value);
        		
        		$numberEffected[$n] = $currentOutageAreas->Document->Placemark[$i]->ExtendedData->Data[1]->value;
        		
        		$information[$n]    = $currentOutageAreas->Document->Placemark[$i];
	
        		$n++;
        	}	
        	
        }
            
        $this->polygons       = $polygons;
        $this->outageReasons  = $outageReasons;
        $this->restoreTimes   = $restoreTimes; 
        $this->numberEffected = $numberEffected; 
        $this->information    = $information;        
        
    }
}