<?php

	try {
	  
	  require "dbConnect.php";	//CONNECT to the database

	  $sql = "SELECT * FROM wdv341_events";
	  
	  //PREPARE the SQL statement
	  $stmt = $conn->prepare($sql);
	  
	  //EXECUTE the prepared statement
	  $stmt->execute();		
	  
	  //Prepared statement result will deliver an associative array
	  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  }
  
  catch(PDOException $e)
  {
	  $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

	  error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
	  error_log($e->getLine());
	  error_log(var_dump(debug_backtrace()));				
  }

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Presenting Information Technology</title>

	<style>

  		body {
			  font-family: Verdana, Geneva, Tahoma, sans-serif;
			  line-height: 1.75em;
			  text-align: center;
		  }

  		.container {
			width:  80%;
			margin: 0 auto;
			display: flex;
			justify-content: space-around;
		  }

		.eventBlock {
			width: 30%;
			margin: 4em 1em;
			padding: 1em;
			border-radius: 10px;
			box-shadow: 0px 0px 10px 3px #efefef;
			display: flex;
			flex-wrap: wrap;
			text-align: left;
		}

		p {
			margin: 0.5em;
		}

		.eventTitle {
			font-size: 1.2em;
		}

		.eventTitle, .eventDescription {
			font-weight: bold;
		}

		.eventDescription {
			margin-bottom: 2em;
		}

		.row2 {
			align-self: flex-end;
		}

	</style>

</head>

<body>

<div id="container">

	<header>
    	<h1>Presenting Information Technology</h1>
    </header>
    

    
    <main>
    
        <h1>Display Available Events</h1>
		
		<div class="container">
        <?php 
			while( $row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
		?>		
				<div class="eventBlock">
					<div class="row">
						<p class="eventTitle"><?php echo $row['event_name']; ?></p>
					</div>
					<div class="row">
						<p class="eventDescription"><?php echo $row['event_description'] ?></p>
					</div>                    
					<div class="row2">
						<p class="eventDate">Dates: <?php echo $row['event_date']  ?></p>
					</div>
					<div class="row2">
						<p class="eventPresenter">Presenter: <?php echo $row['event_presenter']  ?></p>
					</div>             
				</div><!-- Close Event Block -->
        <?php
			}
		?>	
		</div>
  	
        
	</main>
    
	<footer>
    	<p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());</script> All Rights Reserved</p>
    
    </footer>




</div>
</body>
</html>
