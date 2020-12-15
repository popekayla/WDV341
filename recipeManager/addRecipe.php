<?php
session_start();
if($_SESSION['validUser'] !== "yes") {
    header('Location: index.php');
}

$recipe_name ="";
$recipe_servings ="";
$recipe_img ="";
$recipe_prep_time_hours ="";
$recipe_prep_time_minutes ="";
$recipe_cook_time_hours ="";
$recipe_cook_time_minutes ="";
$recipe_difficulty ="";
$ingredient_amount_arr[] ="";
$ingredient_measurement ="";
$ingredient_name ="";
$instruction_description ="";
$submissionSuccess = false;
$recID = "";
$imgError ="";
$nameErr = "";
$servingErr = "";
$prepErr = "";
$cookErr = "";
$difficultyErr = "";
$ingErr = "";
$insErr = "";


if(isset(($_POST['submitBtn']))) {

    //Get form data

    $recipe_name = $_POST['recipe_name'];
    $recipe_servings = $_POST['recipe_servings'];
    $recipe_img = $_FILES["recipe_img"]["name"];
    $recipe_prep_time_hours = $_POST['recipe_prep_time_hours'];
    $recipe_prep_time_minutes = $_POST['recipe_prep_time_minutes'];
    $recipe_cook_time_hours = $_POST['recipe_cook_time_hours'];
    $recipe_cook_time_minutes = $_POST['recipe_cook_time_minutes'];
    $recipe_difficulty = $_POST['recipe_difficulty'];
    foreach($_POST['ingredient_name_arr'] as $ingredient_name){$ingredient_name = $_POST['ingredient_name_arr'];};
    foreach($_POST['ingredient_amount_arr'] as $ingredient_amount){$ingredient_amount = $_POST['ingredient_amount_arr'];};
    foreach($_POST['ingredient_measurement_arr'] as $ingredient_measurement){$ingredient_measurement = $_POST['ingredient_measurement_arr'];};
    foreach($_POST['instruction_description_arr'] as $instruction_description){$instruction_description = $_POST['instruction_description_arr'];};
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["recipe_img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    //Data Validation

    $check = getimagesize($_FILES['recipe_img']["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;

            if ($_FILES['recipe_img']["size"] > 500000) {
            $imgError = "Sorry, your file is too large.";
            $uploadOk = 0;
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $imgError =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        } else {
            $uploadOk = 0;
            $imgError = "Please select an image";
        }
    }

    
    if($uploadOk = 1) {
        move_uploaded_file($_FILES["recipe_img"]["tmp_name"], $target_file);
    }

    $isValid = true;

    if($recipe_name == "") {
        $isValid = false;
        $nameErr = "Please enter a recipe name";
    }

    if($recipe_servings == "") {
        $isValid = false;
        $nameErr = "Please enter a serving size";
    }

    if($recipe_prep_time_hours == "" || $recipe_prep_time_minutes == "") {
        $isValid = false;
        $prepErr = "Please enter a prep time";
    }

    if($recipe_cook_time_hours == "" || $recipe_cook_time_minutes == "") {
        $isValid = false;
        $cookErr = "Please enter a cook time";
    }

    if($recipe_difficulty == "" || $recipe_difficulty == 'Please Select') {
        $isValid = false;
        $difficultyErr = "Please enter a difficulty";
    }

    if($ingredient_name == "" || $ingredient_amount =="") {
        $isValid = false;
        $ingErr = "Please enter a valid ingredient";
    }

    if($instruction_description == "") {
        $isValid = false;
        $insErr = "Please enter a valid instruction";
    }

    if($isValid) {
     try {

            
           require "database/dbConnect.php";	//CONNECT to the database

           //Insert to recipe table
           $sql = "INSERT INTO recipe(recipe_name, recipe_servings, recipe_img, recipe_prep_time_hours, recipe_prep_time_minutes, recipe_cook_time_hours, recipe_cook_time_minutes, recipe_difficulty) 
                   VALUES(:name, :servings, :img, :prep_hours, :prep_minutes, :cook_hours, :cook_minutes, :difficulty)";
    
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':name', $recipe_name);
            $stmt->bindParam(':servings', $recipe_servings);
            $stmt->bindParam(':img', $recipe_img);
            $stmt->bindParam(':prep_hours', $recipe_prep_time_hours);
            $stmt->bindParam(':prep_minutes', $recipe_prep_time_minutes);
            $stmt->bindParam(':cook_hours', $recipe_cook_time_hours);
            $stmt->bindParam(':cook_minutes', $recipe_cook_time_minutes);
            $stmt->bindParam(':difficulty', $recipe_difficulty);
            
            $stmt->execute();

            $recID = $conn->lastInsertId(); //get the recipe ID to use as foreign key

            //Insert into ingredient table
            $sql = "INSERT INTO ingredient(ingredient_name)
                        VALUE(:name)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $ingredient_names);

            foreach ($_POST['ingredient_name_arr'] as $ingredient_names) { //loops through each ingredient name and executes the prepared statement for each ingredient
                $stmt->execute();
                $ingredient_ID[] = $conn->lastInsertId(); //Gets each ingredient ID and stores in an array to use as foreign keys
             }
            
            for($i=0; $i<count($ingredient_ID); $i++){ //Loops through the number of ingredients to pair the proper ingredient amt/measurement to the proper ID
                
                $sql = "INSERT INTO recipe_ingredient(ingredient_amount, ingredient_measurement, ingredient_ID, recipe_ID)
                    VALUE(:amt, :measurement, :ing_ID, :rec_ID)";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':amt', $ingredient_amount[$i]);
                $stmt->bindParam(':measurement', $ingredient_measurement[$i]);
                $stmt->bindParam(':ing_ID', $ingredient_ID[$i]);
                $stmt->bindParam(':rec_ID', $recID);

                $stmt->execute();

            }
            
            $sql = "INSERT INTO instruction(instruction_description, recipe_ID)
            VALUE(:desc, :rec_ID)";
    
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':desc', $instruction_descriptions);
            $stmt->bindParam(':rec_ID', $recID);
            
            foreach ($_POST['instruction_description_arr'] as $instruction_descriptions) { $stmt->execute(); } 	
            

            $submissionSuccess = true;
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
    } 

?>

<html>

    <head>

        <title>Recipe Submission</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/formStyle.css">

        
        <script src="js/validationFunctions.js"></script>
        <script src="js/formControl.js"></script>
        <script src="js/hamburger.js"></script>

    </head>

    <body onload="onLoad(), validations(), formControl()">

        

    <header>
      <?php require "php/navBar.php"?>
        <div class="container">
            <h1>Add a Recipe</h1>
        </div>
      </header>
      <div class="topSection">
      <div class="container">
      <p>&nbsp;</p>
        </div>
    </div>
        <div class="mainSection container">

            <?php
                if($submissionSuccess) {
            ?>

            <h2>Success!</h2>
            <p>Your recipe has been submitted successfully!</p>

            <p><a href="recipe.php?recID=<?php echo $recID ?>">View Recipe &rarr;</a></p>

            <?php
                } else {
            ?>

            <form id="addRecipe" name="addRecipe" action="addRecipe.php" method="POST" enctype="multipart/form-data" onsubmit="return isValid(event), return submitValidation(event)">

                <h3>Recipe Info:</h3>
                    <div class="formSection">
                        <p><label for="name">Name:</label>
                            <input type="text" name="recipe_name" id="name" value="<?php echo $recipe_name; ?>" required></p>
                        <p class="validation" ><span id="nameValidation"><?php echo $nameErr?></span></p>
                        <p class="servings"><label for="servings">Servings:</label>
                            <input type="number" name="recipe_servings" id="servings" min="1" value="<?php echo $recipe_servings; ?>" required></p>
                            <p class="validation" ><span id="servingValidation"><?php echo $servingErr?></span></p>
                        <p class="img"><label for="img">Upload Image:</label>
                            <input type="file" id="img" name="recipe_img" accept="image/*" required>
                            <p  class="validation" ><span id="imgValidation"><?php echo $imgError?></span></p>
                        </p>
                        <p class="time"><label for="prep">Prep Time:</label>
                            <input type="number" name="recipe_prep_time_hours" id="prepHours" placeholder="hours" min="0" max="60" value="<?php echo $recipe_prep_time_hours; ?>" required>
                            <input type="number" name="recipe_prep_time_minutes" id="prepMins" placeholder="minutes" min="0" max="60" value="<?php echo $recipe_prep_time_minutes; ?>" required>
                            <p  class="validation" ><span id="prepValidation"><?php echo $prepErr?></span></p>
                        <p class="time"><label for="cook">Cook Time:</label>
                            <input type="number" name="recipe_cook_time_hours" id="cookHours" placeholder="hours" min="0" max="60" value="<?php echo $recipe_cook_time_hours; ?>" required>
                            <input type="number" name="recipe_cook_time_minutes" id="cookMins" placeholder="minutes" min="0" max="60" value="<?php echo $recipe_cook_time_minutes; ?>" required>
                            </select>
                        </p>
                        <p class="validation" ><span id="cookValidation"><?php echo $cookErr?></span></p>
                        <p><label for="difficulty">Difficulty:</label>
                            <select id="difficulty" name="recipe_difficulty" required>
                                <option>Please Select</option>
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                            </select>
                        </p>
                        <p class="validation"><span id="difficultyValidation"><?php echo $difficultyErr?></span></p>
                    </div>

                <h3>Ingredients:</h3>
                    <div class="formSection">
                            <ol id="ingredientList">
                                <?php if(isset($_POST['submitBtn'])){ for($i=0; $i<count($ingredient_name); $i++) {?>
                                <li>
                                    <label for="ingredients">Amount:</label> <input type="number" step="any" name="ingredient_amount_arr[]" id="ingredientAmt1" min="0" class="amt" value="<?php echo $ingredient_amount[$i]; ?>" required>
                                    <label for="measurent">Measurement:</label> <input type="text" name="ingredient_measurement_arr[]" id="ingredientMeasurement1" value="<?php echo $ingredient_measurement[$i]; ?>">
                                    <label for="ingredientName">Ingredient:</label> <input type="text" name="ingredient_name_arr[]" id="ingredientName1" class="ingredient" value="<?php echo $ingredient_name[$i]; ?>" required> 
                                </li>
                                <p class="validation" ><span id="ingredientValidation"><?php echo $ingErr?></span></p>
                                <?php }} else { ?>
                                    <li>
                                    <label for="ingredients">Amount:</label> <input type="number" step="any" name="ingredient_amount_arr[]" id="ingredientAmt1" min="0" class="amt" required>
                                    <label for="measurent">Measurement:</label> <input type="text" name="ingredient_measurement_arr[]" id="ingredientMeasurement1">
                                    <label for="ingredientName">Ingredient:</label> <input type="text" name="ingredient_name_arr[]" id="ingredientName1" class="ingredient" required> 
                                </li>
                                <p class="validation" ><span id="ingredientValidation"><?php echo $ingErr?></span></p>
                                <?php }?>
                            </ol>
                            <button type="button" id="ingredients">Add +</button>
                    </div>
                    
                <h3>Instructions:</h3>
                    <div class="formSection">
                            <ol id="instructionList">
                            <?php if(isset($_POST['submitBtn'])){ for($x=0; $x<count($instruction_description); $x++) {?>
                                <li>
                                    <input type="text" name="instruction_description_arr[]" id="instruction1" class="instruction" value="<?php echo $instruction_description[$x];?>" required>
                                </li>
                                <p class="validation" ><span id="instructionValidation"><?php echo $insErr?></span></p>
                                <?php } } else {?>
                                    <li>
                                    <input type="text" name="instruction_description_arr[]" id="instruction1" class="instruction" required>
                                </li>
                                <p class="validation" ><span id="instructionValidation"><?php echo $insErr?></span></p>
                                <?php }?>
                            </ol>
                            <button type="button" id="instructions">Add +</button>
                    </div>

                <p>
                <input type="text" name="test" id="test" class="hidden" />
                </p>

                <button type="submit" name="submitBtn">Submit</button>
                <button type="reset">Reset</button>

            </form>
            <?php
                }
            ?>
        </div>
        <?php require 'php/footer.php'?>
    </body>

</html>