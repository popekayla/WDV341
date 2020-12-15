<?php
session_start();
$message = "";
$nameError = "";
$emailError = "";
$phoneError = "";
$messageError = "";

if(isset($_POST['submitButton'])){

  //Data Validation
  
    $name = $_POST['contact_name'];
    $to = $_POST['contact_email'];
    $phone = $_POST['contact_phone'];
    $contactMessage = $_POST['contact_message'];
    $subject = "Thanks for reaching out!";
    $txt = "Thanks for the message $name! We'll get back to you as soon as possible.";
    $headers = "From: contact@kmpope.com";

    
  $isValid = true;

  if($name == "") {
    $isValid = false;
    $nameError = "Please enter your name";
  }

  if($to == "" || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    $isValid = false;
    $emailError = "Please enter a valid email";
  }

  $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
  // Remove "-" from number
  $phone_to_check = str_replace("-", "", $filtered_phone_number);

  if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
    $isValid = false;
    $phoneError = "Please enter a valid number";
 }

 if($contactMessage == "") {
   $isValid = false;
   $messageError = "Please enter a message";
 }


    if(!mail($to,$subject,$txt,$headers)) {
      $message = "Sorry there was an issue. Please try later.";
    } else if($isValid) {      
      $message = "Thanks for your message! We'll get back to you shortly";
    }
}
?>
<!DOCTYPE HTML>
<html>
    
    <head>

      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link rel="stylesheet" type="text/css" href="css/style.css">
      <link rel="stylesheet" type="text/css" href="css/contactStyle.css">

      <script src="js/hamburger.js"></script>
      <script src="js/contactValidations.js"></script>

    </head>

    <body onload="onLoad(), contactValidations()">
   
      <header>
      <?php require "php/navBar.php"?>
        <div class="container">
            <h1>Get in Touch</h1>
        </div>
      </header>
    <div class="topSection">
      <div class="container">
      <p>&nbsp;</p>
      
      <h2>Contact us</h2>
        </div>
    </div>
        <div class="contactSection container">
                <?php echo $message;?>
                <form id="contactForm" name="contactForm" method="post" action="" onsubmit="return submitValidation(event)">
                        <p><label for="contact_name">Name: </label>
                        <input type="text" id="contact_name" name="contact_name" required>
                        <p class="nameValidation validation"><?php echo $nameError?></p></p>
                        
                        <p><label for="contact_email">Email: </label>
                        <input type="email" id="contact_email" name="contact_email" required>
                        <p class="emailValidation validation"><?php echo $emailError?></p></p>

                        <p><label for="contact_phone">Phone: </label>
                        <input type="phone" id="contact_phone" name="contact_phone">
                        <p class="phoneValidation validation"><?php echo $phoneError?></p></p>
                        
                        <p><label for="contact_message">Message: </label>
                        <textarea id="contact_message" name="contact_message" rows="6" required></textarea>
                        <p class="messageValidation validation"><?php echo $messageError?></p></p>

                        <p>
                        <input type="text" name="test" id="test" class="hidden" />
                        </p>
        
                        <p><label>&nbsp;</label>
                        <button type="submit" name="submitButton">Submit</button></p>
                </div>
      <?php require 'php/footer.php'?>
    </body>
</html>