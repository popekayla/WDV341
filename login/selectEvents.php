<?php

	session_start();

	if($_SESSION['validUser'] !== "yes") {
		header('Location: login.php');
	}

	$message='';

	if(isset($_GET['recID'])) {
		$updateRecID = $_GET['recID'];

		try {
	  
			require "dbConnect.php";	//CONNECT to the database
	  
			$sql = "DELETE FROM wdv341_events
					WHERE event_id = :ID";
			
			
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':ID', $updateRecID);
			
			$stmt->execute();	
			$message = 'Event has been deleted';	
		}
		
		catch(PDOException $e)
		{
		   $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
	  
			error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
			error_log($e->getLine());
			error_log(var_dump(debug_backtrace()));				
		}
	}
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

		nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: whitesmoke;
            padding: 0 1em;
        }

        nav ul {
            list-style-type: none;
        }

        nav ul li {
            display: inline-block;
            background-color: darkslategray;
            padding:1em;
        }

        nav ul li a {
            color:white;
            text-decoration: none;
        }
        nav ul li:hover {
            background-color: gray;
        }

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
			wrap: wrap;
			/*display: flex;
			flex-wrap: wrap;
			text-align: left;*/
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
	
<?php 
            if($_SESSION['validUser'] == "yes") {
                require 'adminLinks.php';
             } ?>

	<header>
    	<h1>Presenting Information Technology</h1>
    </header>
    

    
    <main>
    
		<h2>Display Available Events</h2>
		<h3><?php echo $message;?></h3>
		
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
					<div class="row2">
						<p class="eventUpdate"><a href="updateEvent.php?recId=<?php echo $row['event_id']?>"> <button>Update</button></a>
						<a href="?recID=<?php echo $row['event_id']?>"><button type="button" onclick="return confirm('Are you sure you want to delete?')">Delete</button></a>
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
