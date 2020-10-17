<?php

try {
	  
        require "dbConnect.php";	

        $sql = "SELECT * FROM wdv341_events
                WHERE event_id='1'";
        
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();		
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    }

    catch(PDOException $e){
        $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

            error_log($e->getMessage());	
            error_log($e->getLine());
            error_log(var_dump(debug_backtrace()));				
        }

    while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {

    $outputObject = new stdClass(); 

    $outputObject->event_name = $row['event_name'];
    $outputObject->event_description = $row['event_description'];
    $outputObject->event_presenter = $row['event_presenter'];
    $outputObject->event_date = $row['event_date'];
    $outputObject->event_time = $row['event_time'];
    }

    $returnObj = json_encode($outputObject); 

    echo $returnObj;
?>

