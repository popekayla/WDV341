<?php

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

    if(isset(($_POST['submitButton']))) {
        echo "<h1>FORM HAS BEEN SUBMITTED</h1>";

        //Get form data

        $event_name = $_POST['event_name'];
        $event_description = $_POST['event_description'];
        $event_presenter = $_POST['event_presenter'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];

       // echo "<p>event_name $event_name";
       // echo "<p>event_description $event_description";
       // echo "<p>event_presenter $event_presenter";
       // echo "<p>event_date $event_date";
       // echo "<p>event_time $event_time";

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
        } else {
            //Send back error messages
        }



    } else {
        //echo "<h1>EMPTY FORM</h1>";
    }

?>

<!DOCTYPE html>
<html>

<head>

    <style>
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
    </style>


</head>

<body>

    <div class="container">

        <h1>Create a New Event</h1>

        <form id="eventForm" name="eventForm" method="post" action="">

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

            <p><label>&nbsp;</label>
                <input type="submit" name="submitButton"></p>


        </form>

    </div>


</body>


</html>