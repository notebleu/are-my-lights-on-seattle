<?php

class PointInPolygon {
    
    private $checkPointOnVertex = TRUE;
    private $coordinatesToCheck = '';
    
    public function __construct($coordinateString,$checkPointOnVertex = TRUE)
    {
        $this->coordinatesToCheck = $this->pointStringToCoordinates($coordinateString);
        $this->checkPointOnVertex = $checkPointOnVertex;
        
    }
    
    public function areCoordinatesInPolygon($polygon)
    {
        
        $vertices = array(); 
        
        # define vertices
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates(trim($vertex)); 
        }
                
        # are the coordinates on the vertex?
        if ($this->checkPointOnVertex === TRUE and $this->pointOnVertex($this->coordinatesToCheck, $vertices) == true) {
            return 2;
        }
        
        $intersections = 0; 
        $vertices_count = count($vertices);
        
        # if there are an even number of intersections, the coordinates are outside the polygon
        # if there are an odd number, the coordinates are inside the polygon
        # if the point is on a boundary, return
        
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            
            # are the coordinates on a horizontal boundary?
            
            if ($vertex1['y'] == $vertex2['y'] && $vertex1['y'] == $this->coordinatesToCheck['y'] && $this->coordinatesToCheck['x'] > min($vertex1['x'], $vertex2['x']) && $this->coordinatesToCheck['x'] < max($vertex1['x'], $vertex2['x'])) { 
                return 3;
            }
            
            # are the coordinates on a non-horizontal boundary?
            
            if ($this->coordinatesToCheck['y'] > min($vertex1['y'], $vertex2['y']) && $this->coordinatesToCheck['y'] <= max($vertex1['y'], $vertex2['y']) && $this->coordinatesToCheck['x'] <= max($vertex1['x'], $vertex2['x']) && $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($this->coordinatesToCheck['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $this->coordinatesToCheck['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return 3;
                }
                if ($vertex1['x'] == $vertex2['x'] || $this->coordinatesToCheck['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        }  
        
        if ($intersections % 2 != 0) {
            return 1;
        } else {
            return 0;
        }        
    }


    private function pointOnVertex($coordinates, $vertices) {
        
        foreach($vertices as $vertex) {
            if ($coordinates == $vertex) {
                return TRUE;
            }
        }
    
    }
        
    private function pointStringToCoordinates($coordinateString) {

        $coordinates = explode(" ", $coordinateString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
        
    }
    
    
}