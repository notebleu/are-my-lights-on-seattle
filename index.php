<?php

# local configuration
require './local.php';

if($debug = TRUE){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

# pull in the required files
foreach (glob('./models/*.class.php', GLOB_NOCHECK) as $model) {
    require $model;
}

# get the XML of current outtages
try{
    $seattleLightApi = new SeattleLightApi('http://www.seattle.gov/light/sysstat/data/outage.kml');
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage();
}

$currentOutageAreas = $seattleLightApi->currentOutageAreas;

# create the polygons
try{
    $PolygonCollection = new Polygons($currentOutageAreas);
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage();
}

# grab the locations in the local.php file and run through each of them
foreach( $locations as $location )
{
    # language file
    include './lang/' . $location['language'] . '/lang.php';
    
    $pointInPolygonClass = new PointInPolygon($location['longitude'] . ' ' . $location['latitude'], TRUE);
    
    $i = 0;
    $outage = FALSE;
    foreach( $PolygonCollection->polygons as $polygon )
    {
        $coordinateStatus = $pointInPolygonClass->areCoordinatesInPolygon($polygon);
        
        if( $coordinateStatus > 0 ){
    	    
    	    $outage  = TRUE;
    	    
    	    # check to see if there was an outage anytime in the 24 hour period
            # if so, no need to send another message, if not, send message and
            # add to history 
    	    try{
                $history = new Storage($location['history_file']);
            } catch (Exception $e) {
                echo 'Error: ', $e->getMessage();
            }
    		
    		if( $history->inHistory(date('Y-m-d')) === FALSE ){
        		
                $message = <<<EOT
{$lang_array['power_may_be_out']} ({$location['address_name']})
http://www.seattle.gov/light/sysstat/
EOT;
                 
                try{
                    $message = new Messeger($location['email_address'],$lang_array['email_subject'],$message);
                } catch (Exception $e) {
                    echo 'Error: ', $e->getMessage();
                }
                
                try{
                    $history->prependToHistory(date('Y-m-d') . " - power out\r\n");	
                } catch (Exception $e) {
                    echo 'Error: ', $e->getMessage();
                }

                	
    		}
    		
    		echo $location['address_name'] , ' - ' , $lang_array['power_may_be_out'] , "\n";
            
            break;
        }
        
    	$i++;
    }   
    
    if( $outage === FALSE ){
    
        echo $location['address_name'] , ' - ' , $lang_array['all_is_well'] , "\n";
        
        # check to see if there was an outage anytime in the 24 hour period
        # if so, update the history to show that it has been resolved  
        try{
            $history = new Storage($location['history_file']);
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
            continue;
        }
        
        $current_contents = $history->getHistory();
		
		if( $history->inHistory(date('Y-m-d')) !== FALSE ){
		    $updated_contents = str_replace(date('Y-m-d') . ' - power out', date('Y-m-d') . ' - power restored', $current_contents);
		    $history->overwriteHistory($updated_contents); 
		}

    } 
    
}