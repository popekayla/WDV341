<?php
    session_cache_limiter('none');			//This prevents a Chrome error when using the back button to return to this page.
    session_start();

    $_SESSION['validUser'];
    $message = "";

    //If valid user, display admin options

    if($_SESSION['validUser'] == "yes") {
        require 'adminLinks.php';
        $message = "Welcome back $username!";
    } else {

    //If form is submitted
        if(isset($_POST['submitButton'])) {
           // echo "<h1>SUBMITTED</h1>";
        //Pull username and password
        $username = $_POST['event_user_name'];
        $pass = $_POST['event_user_password'];

        //Create SQL select
        try{
            require "dbconnect.php";

            $sql = "SELECT event_user_name, event_user_password 
                    FROM event_user 
                    WHERE event_user_name=:user AND event_user_password=:password";

            //$sql = "SELECT event_user_name, event_user_password FROM event_user";

            $stmt = $conn->prepare($sql);

        // echo "<p>user:$username</p> <p>Pass:$pass</p>";

            $stmt->bindParam(':user', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pass, PDO::PARAM_STR);

            $stmt->execute();
            
            $count = $stmt->rowCount();	
            $row = $stmt->fetch(PDO::FETCH_ASSOC);	
        }

       // echo "found $count rows";
        catch(PDOException $e)
            {
                $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

                error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                error_log($e->getLine());
                error_log(var_dump(debug_backtrace()));				
            }
        
        //If success
            if($count ==1 && !empty($row)) {

            //Set validUser=true
            $_SESSION['validUser'] = "yes";

            //Create welcome message
            $message = "Welcome back $username!";

            //Display admin options
            } else {

        //Else invalid

            //Create error message
            $message = "Sorry there was a problem with your username or password. Please try again.";

            }

            $stmt = null;
            $conn = null;
        } else {
            //echo "<h1>FIRST TIME</h1>";
             //else display login form
        }
    }

?>

<!DOCTYPE html>

<html>

<head>

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
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        li {
            text-align: left;
        }

        h1, h2 {
            text-align: center;
        }

        h1 {
            margin-bottom: 3em;
        }

        .adminOptions {
            display:flex;
            justify-content:center;
        }

        a {
            color: darkslategray;
            text-decoration: none;
        }

        a:hover{
            color:gray;
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
        form input[type="password"]
        {
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
        </style>

</head>

<body>

    <div class="container">


        <h1>Presenting Information Technology</h1>
        <h3><?php echo $message?></h3>

        <?php if($_SESSION['validUser'] == 'yes'){
            ?>
        <h2>Presenters Administrators Options</h2>
        <div class="adminOptions">
            <ul>
                <li><a href="eventsForm.php">Add Event</a></li>
                <li><a href="selectEvents.php">View & Edit Events</a></li>
                <li><a href="logout.php">Logout</a></li>
                
            </ul>
    </div>

        <?php } else { ?>
        <form id="eventForm" name="eventForm" method="post" action="">

            <h2>Login</h2>

            <p><label for="event_user_name">Username: </label>
            <input type="text" id="event_user_name" name="event_user_name" required></p>
            
            <p><label for="event_user_password">Password: </label>
            <input type="password" id="event_user_password" name="event_user_password" required></p>

            <p><label>&nbsp;</label>
            <input type="submit" name="submitButton"></p>

    </form>
        <?php } ?>
</body>

</html>