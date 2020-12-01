<?php 
        
        try{
            require "dbconnect.php";

            $sql = "SELECT event_user_name 
                    FROM event_user";

            $userstmt = $conn->prepare($sql);
            $userstmt->execute();
            $userrow = $userstmt->fetch(PDO::FETCH_ASSOC);	
            $username = $userrow['event_user_name'];
        }

       // echo "found $count rows";
        catch(PDOException $e)
            {
                $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

                error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                error_log($e->getLine());
                error_log(var_dump(debug_backtrace()));				
            }
            
        $userstmt = null;
        $userconn = null;

            ?>
            
        <nav>
    <p>Welcome, <a href="login.php"><?php echo $username; ?></a>
    <ul>
        <li><a href="eventsForm.php">Add Event</a></li>
        <li><a href="selectEvents.php">View & Edit Events</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

</nav>