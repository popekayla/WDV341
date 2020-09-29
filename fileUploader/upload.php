<!DOCTYPE html>

<html>

<head>
  <title>WDV 341 - PHP File Uploader</title>

  <style>
      body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 2em;
            text-align: center;
            margin: 3em;
        }
  </style>
</head>

<body>

<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "<p>File is an image - " . $check["mime"] . ". </p>";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}
if (file_exists($target_file)) {
    echo "<p>Sorry, file already exists.</p>";
    $uploadOk = 0;
  }
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "<p>Sorry, your file is too large.</p>";
    $uploadOk = 0;
  }
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
  $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "<p>Sorry, your file was not uploaded.</p>";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";
    } else {
      echo "<p>Sorry, there was an error uploading your file.</p>";
    }
  }
?>
</body>
</html>