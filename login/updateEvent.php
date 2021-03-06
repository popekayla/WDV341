<?php
session_start();

if($_SESSION['validUser'] !== "yes") {
    header('Location: login.php');
}

    $event_name = "";
    $event_description = "";
    $event_presenter = "";
    $event_date = "";
    $event_time = "";
    $event_nameError = "";
    $event_descriptionError = "";
    $event_presenterError = "";
    $event_dateError = "";
    $event_timeError = "";
    $updateRecID = 1;

    $updateRecID = $_GET['recId'];
    if(isset(($_POST['submitButton']))) {

        //Get form data

        $event_name = $_POST['event_name'];
        $event_description = $_POST['event_description'];
        $event_presenter = $_POST['event_presenter'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];

        //Data Validation

        $isValid = true;

        if($event_name == "") {
            $isValid = false;
            $event_nameError =  "<p class='error'>Please enter a name</p>";
        }

        if($event_description == "") {
            $isValid = false;
            $event_descriptionError =  "<p class='error'>Please enter a description</p>";
        }

        if($event_presenter == "") {
            $isValid = false;
            $event_presenterError =  "<p class='error'>Please enter a presenter</p>";
        }

        if($event_date == "") {
            $isValid = false;
            $event_dateError =  "<p class='error'>Please enter a date</p>";
        }

        if($event_time == "") {
            $isValid = false;
            $event_timeError =  "<p class='error'>Please enter a time</p>";
        }

        if($isValid) {
            //Send data to database

            try {
	  
                require "dbConnect.php";	//CONNECT to the database
          
                $sql = "UPDATE wdv341_events
                        SET event_name = :name, event_description = :description, event_presenter = :presenter, event_date = :date, event_time = :time
                        WHERE event_ID = $updateRecID";
                
                //PREPARE the SQL statement
                $stmt = $conn->prepare($sql);

                //BIND the values to the input parameters of the prepared statement
                $stmt->bindParam(':name', $event_name);
                $stmt->bindParam(':description', $event_description);
                $stmt->bindParam(':presenter', $event_presenter);
                $stmt->bindParam(':date', $event_date);
                $stmt->bindParam(':time', $event_time);
                
                //EXECUTE the prepared statement
                $stmt->execute();		
                

                echo "<h2>Form has been submitted!</h2>";
            }
            
            catch(PDOException $e)
            {
               $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
          
                error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                error_log($e->getLine());
                error_log(var_dump(debug_backtrace()));				
            }
            $stmt = null;
            $conn = null;
        } else {
            //Send back error messages
        }



    } else {
        try {
	  
            require "dbConnect.php";	//CONNECT to the database
      
            $sql = "SELECT event_name, event_description, event_presenter, event_date, event_time
                    FROM  wdv341_events
                    WHERE event_id=:id";
            
            //PREPARE the SQL statement
            $stmt = $conn->prepare($sql);

            //BIND the values to the input parameters of the prepared statement
            $stmt->bindParam(':id', $updateRecID);
            
            //EXECUTE the prepared statement
            $stmt->execute();	
            
            $stmt->setFetchMode(PDO::FETCH_ASSOC);	
		  
		    $row=$stmt->fetch(PDO::FETCH_ASSOC);

            $event_name = $row['event_name'];
            $event_description = $row['event_description'];
            $event_presenter = $row['event_presenter'];
            $event_date = $row['event_date'];
            $event_time = $row['event_time'];
            
        }
        
        catch(PDOException $e)
        {
           $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
      
            error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
            error_log($e->getLine());
            error_log(var_dump(debug_backtrace()));				
        }
        $stmt = null;
        $conn = null;
    }

?>

<!DOCTYPE html>
<html>

<head>

    <script>



    function submitValidation(e) {
    if (test.value != "") {
        e.preventDefault();
        return false;
    };
    };

    </script>

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
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 3em;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form p {
            width: 70%;
            margin: 0 auto;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        form label {
            width: 25%;
            margin: 1em 0;
            text-align: left;
        }

        form input[type="text"],
        form input[type="time"],
        form input[type="date"],
        textarea {
            width: 75%;
            padding: 0.5em;
            margin: 1em 0;
            border: 1px solid gray;
            box-shadow: 0 0 0.3em 0 rgba(0, 0, 0, 0.15);
        }


        form input[type="submit"] {
            width: 25%;
            margin: 0;
            padding: 0.5em;
            margin: 1em 0;
            border: 1px solid gray;
            box-shadow: 0 0 0.3em 0 rgba(0, 0, 0, 0.15);
            transition: all .2s;
        }

        form input[type="submit"]:hover {
            box-shadow: 0 0 0.5em 0 rgba(0, 0, 0, 0.25);
            background-color: white;
        }

        .error{
            color: red;
        }

        .hidden{
            display: none;
        }
    </style>


</head>

<body>

    <div class="container">
        
    <?php 
            if($_SESSION['validUser'] == "yes") {
                require 'adminLinks.php';
             } ?>

        <h1>Update Event <?php echo $event_name?></h1>

        <form id="eventForm" name="eventForm" method="post" action="" onsubmit="return submitValidation(event)">

            <!--Event Name-->
            <p><label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" value="<?php echo $event_name ?>">
                <?php echo $event_nameError ?>
            </p>

            <!--Event Description-->
            <p><label for="event_description">Event Description:</label>
                <textarea rows="4" id="event_description" name="event_description"><?php echo $event_description ?></textarea>
                <span class="error"><?php echo $event_descriptionError ?></span>
            </p>

            <!--Event Presenter-->
            <p><label for="event_presenter">Event Presenter:</label>
                <input type="text" id="event_presenter" name="event_presenter" value="<?php echo $event_presenter ?>">
                <span class="error"><?php echo $event_presenterError ?></span>
            </p>

            <!--Event Date-->
            <p><label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo $event_date ?>">
                <span class="error"><?php echo $event_dateError ?></span>
            </p>

            <!--Event Time-->
            <p><label for="event_time">Event time:</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo $event_time ?>">
                <span class="error"><?php echo $event_timeError ?></span>
            </p>

            <p>
            <input type="text" name="test" id="test" class="hidden" />
            </p>

            <p><label>&nbsp;</label>
                <input type="submit" name="submitButton"></p>


        </form>

    </div>


</body>


</html>