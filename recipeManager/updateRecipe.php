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

$updateRecID = $_GET['recID'];

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
    foreach($_POST['ingredient_name_arr'] as $ingredient_names){$ingredient_names = $_POST['ingredient_name_arr'];};
    foreach($_POST['ingredient_amount_arr'] as $ingredient_amounts){$ingredient_amounts = $_POST['ingredient_amount_arr'];};
    foreach($_POST['ingredient_measurement_arr'] as $ingredient_measurements){$ingredient_measurements = $_POST['ingredient_measurement_arr'];};
    foreach($_POST['instruction_description_arr'] as $instruction_descriptions){$instruction_descriptions = $_POST['instruction_description_arr'];};

    $uploadOk = 1;
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["recipe_img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    //Data Validation

    if(!$_FILES["recipe_img"]["tmp_name"] == "") {
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
            $imgError = "";
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

    if($ingredient_names == "" || $ingredient_amounts =="") {
        $isValid = false;
        $ingErr = "Please enter a valid ingredient";
    }

    if($instruction_descriptions == "") {
        $isValid = false;
        $insErr = "Please enter a valid instruction";
    }

    if($isValid) {


    try {
            
           require "database/dbConnect.php";	//CONNECT to the database

           //Update recipe table
           if(empty($recipe_img)) {
                $sql = "UPDATE recipe
                SET recipe_name = :name, recipe_servings = :servings, recipe_prep_time_hours = :prep_hours, recipe_prep_time_minutes = :prep_minutes, recipe_cook_time_hours = :cook_hours, recipe_cook_time_minutes = :cook_minutes, recipe_difficulty = :difficulty
                WHERE recipe_ID = :recID";
                
                $stmt = $conn->prepare($sql);
            
           } else {
                 $sql = "UPDATE recipe
                    SET recipe_name = :name, recipe_servings = :servings, recipe_img = :img, recipe_prep_time_hours = :prep_hours, recipe_prep_time_minutes = :prep_minutes, recipe_cook_time_hours = :cook_hours, recipe_cook_time_minutes = :cook_minutes, recipe_difficulty = :difficulty
                    WHERE recipe_ID = :recID";

                    $stmt = $conn->prepare($sql);
                    
                    $stmt->bindParam(':img', $recipe_img);
           }

            $stmt->bindParam(':name', $recipe_name);
            $stmt->bindParam(':servings', $recipe_servings);
            $stmt->bindParam(':prep_hours', $recipe_prep_time_hours);
            $stmt->bindParam(':prep_minutes', $recipe_prep_time_minutes);
            $stmt->bindParam(':cook_hours', $recipe_cook_time_hours);
            $stmt->bindParam(':cook_minutes', $recipe_cook_time_minutes);
            $stmt->bindParam(':difficulty', $recipe_difficulty);
            $stmt->bindParam(':recID', $updateRecID);
            
            $stmt->execute();

            //Update ingredient table

             $sql = "SELECT ingredient_ID
                    FROM recipe_ingredient
                    WHERE recipe_ID = :rec_ID";

             $stmt = $conn->prepare($sql);
             $stmt->bindParam(':rec_ID', $updateRecID);
             $stmt->execute();
            
             $stmt->setFetchMode(PDO::FETCH_ASSOC);
             $count = $stmt->rowCount();
             while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                 $ingredient_ID[] = $row['ingredient_ID'];
             }
            
            for($i=0; $i<$count; $i++){ //Loops through the number of ingredients to pair the proper ingredient amt/measurement to the proper ID
               
                $sql = "UPDATE recipe_ingredient
                        SET ingredient_amount = :amt, ingredient_measurement = :measurement
                        WHERE ingredient_ID = :ing_ID";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':amt', $ingredient_amounts[$i]);
                $stmt->bindParam(':measurement', $ingredient_measurements[$i]);
                $stmt->bindParam(':ing_ID', $ingredient_ID[$i]);

                $stmt->execute();

                 $sql = "UPDATE ingredient
                    SET ingredient_name = :name
                    WHERE ingredient_ID = :ing_ID";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $ingredient_names[$i]);
            $stmt->bindParam(':ing_ID', $ingredient_ID[$i]);

            
            $stmt->execute();
             
            }

           if(count($_POST['ingredient_name_arr']) > $count) { //Checks to see if there are new ingredients and inserts them into the database
 
               for($a=$count; $a<count($_POST['ingredient_name_arr']); $a++) { 

               $sql = "INSERT INTO ingredient(ingredient_name)
                VALUE(:name)";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $ingredient_names[$a]);

                $stmt->execute();
                
                $ingredient_ID[] = $conn->lastInsertId();
        
                $sql = "INSERT INTO recipe_ingredient(ingredient_amount, ingredient_measurement, ingredient_ID, recipe_ID)
                VALUE(:amt, :measurement, :ing_ID, :rec_ID)";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':amt', $ingredient_amounts[$a]);
                $stmt->bindParam(':measurement', $ingredient_measurements[$a]);
                $stmt->bindParam(':ing_ID', $ingredient_ID[$a]);
                $stmt->bindParam(':rec_ID', $updateRecID);

                $stmt->execute();
               }
            }
            
            $sql = "SELECT instruction_num
                    FROM instruction
                    WHERE recipe_ID = :rec_ID";

             $stmt = $conn->prepare($sql);
             $stmt->bindParam(':rec_ID', $updateRecID);
             $stmt->execute();
            
             $stmt->setFetchMode(PDO::FETCH_ASSOC);
             $count = $stmt->rowCount();
             while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                 $instruction_ID[] = $row['instruction_num'];
             }
             for($n=0; $n<$count; $n++) {
            $sql = "UPDATE instruction 
                    SET instruction_description = :desc
                    WHERE instruction_num = :ins_ID";
    
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':desc', $instruction_descriptions[$n]);
            $stmt->bindParam(':ins_ID', $instruction_ID[$n]);
            
            $stmt->execute(); 
             }

             if(count($_POST['instruction_description_arr']) > $count) {

                for($b=$count; $b<count($_POST['instruction_description_arr']); $b++) {
                    $sql = "INSERT INTO instruction(instruction_description, recipe_ID)
                    VALUE(:desc, :rec_ID)";
            
                    $stmt = $conn->prepare($sql);
        
                    $stmt->bindParam(':desc', $instruction_descriptions[$b]);
                    $stmt->bindParam(':rec_ID', $updateRecID);
                    
                    $stmt->execute();
                }
            } 

            $submissionSuccess = true;
        }
        
        catch(PDOException $e)
        {
           $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
      
            error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
            error_log($e->getLine());
            error_log(var_dump(debug_backtrace()));				
        }
    }
        $stmt = null;
        $conn = null;
        
    } else {
        $updateRecID = $_GET['recID'];

        try {
          
            require "database/dbConnect.php";	//CONNECT to the database
            if(isset($_GET['deleteIng'])) {
                $updateDeleteID = $_GET['deleteIng'];
            
                    $sql = "DELETE FROM recipe_ingredient
                    WHERE ingredient_ID = :ID";
            
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':ID', $updateDeleteID);
            
                    $stmt->execute();	

                    $sql = "DELETE FROM ingredient
                    WHERE ingredient_id = :ID";
            
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':ID', $updateDeleteID);
                    
                    $stmt->execute();	
                }

                if(isset($_GET['deleteIns'])) {
                    $updateDeleteID = $_GET['deleteIns'];

                    $sql = "DELETE FROM instruction
                     WHERE instruction_num = :ID";

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':ID', $updateDeleteID);

                    $stmt->execute();	
                }
    
            $sqlRec = "SELECT *
                        FROM recipe
                        WHERE recipe_ID=:id";
                        
                  $stmtRec = $conn->prepare($sqlRec);
                  $stmtRec->bindParam(':id', $updateRecID);
                  $stmtRec->execute();	
                  
                  $stmtRec->setFetchMode(PDO::FETCH_ASSOC);
    
                  $rowRec = $stmtRec->fetch(PDO::FETCH_ASSOC);
                  
                  $recipe_ID=$rowRec['recipe_ID']; 
                  $recipe_name=$rowRec['recipe_name'];
                  $recipe_servings=$rowRec['recipe_servings'];
                  $recipe_prep_time_hours=$rowRec['recipe_prep_time_hours'];
                  $recipe_prep_time_minutes=$rowRec['recipe_prep_time_minutes'];
                  $recipe_cook_time_hours=$rowRec['recipe_cook_time_hours'];
                  $recipe_cook_time_minutes=$rowRec['recipe_cook_time_minutes'];
                  $recipe_difficulty=$rowRec['recipe_difficulty'];
      
            $sqlIng = "SELECT * 
                      FROM recipe_ingredient ri
                      JOIN ingredient i ON ri.ingredient_ID=i.ingredient_ID        
                      WHERE ri.recipe_ID=:id";
    
                  $stmtIng = $conn->prepare($sqlIng);
                  $stmtIng->bindParam(':id', $updateRecID);
                  $stmtIng->execute();	
                  
                  $stmtIng->setFetchMode(PDO::FETCH_ASSOC);
                 while( $rowIng = $stmtIng->fetch(PDO::FETCH_ASSOC)) {
                  $ingredient_amount[] = $rowIng['ingredient_amount'];
                  $ingredient_measurement[] = $rowIng['ingredient_measurement'];
                  $ingredient_name[] = $rowIng['ingredient_name'];
                  $ingredient_IDs[] = $rowIng['ingredient_ID'];
                 }
    
                  $sqlIns = "SELECT * 
                      FROM instruction       
                      WHERE recipe_ID=:id";
    
                  $stmtIns = $conn->prepare($sqlIns);
                  $stmtIns->bindParam(':id', $updateRecID);
                  $stmtIns->execute();	
                  
                  $stmtIns->setFetchMode(PDO::FETCH_ASSOC);
                  while( $rowIns = $stmtIns->fetch(PDO::FETCH_ASSOC)) {
                      $instruction_IDs[] = $rowIns['instruction_num'];
                    $instruction_description[] = $rowIns['instruction_description'];
                   }
        }
        
        catch(PDOException $e)
      {
         $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
    
          error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
          error_log($e->getLine());
          error_log(var_dump(debug_backtrace()));				
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
            <h1>Update Recipe</h1>
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
            <p>Your recipe has been updated successfully!</p>

            <p><a href="recipe.php?recID=<?php echo $updateRecID ?>">View Recipe &rarr;</a></p>

            <?php
                } else {
            ?>

            <form id="updateRecipe" name="updateRecipe" action="" method="POST" enctype="multipart/form-data" onsubmit="return isValid(event), return submitValidation(event)">

            <h3>Recipe Info:</h3>
                    <div class="formSection">
                    <p><label for="name">Name:</label>
                            <input type="text" name="recipe_name" id="name" value="<?php echo $recipe_name; ?>" required></p>
                        <p class="validation" ><span id="nameValidation"><?php echo $nameErr?></span></p>
                        <p class="servings"><label for="servings">Servings:</label>
                            <input type="number" name="recipe_servings" id="servings" min="1" value="<?php echo $recipe_servings; ?>" required></p>
                            <p class="validation" ><span id="servingValidation"><?php echo $servingErr?></span></p>
                        <p class="img"><label for="img">Upload Image:</label>
                            <input type="file" id="img" name="recipe_img" accept="image/*">
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
                                <option value="<?php echo $recipe_difficulty?>" selected><?php echo $recipe_difficulty?></option>
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
                            <?php for($y=0; $y<count($ingredient_name); $y++) {?>
                                <li>
                                    <label for="ingredients">Amount:</label> <input type="number" step="any" name="ingredient_amount_arr[]" id="ingredientAmt1" min="0" class="amt" value="<?php echo $ingredient_amount[$y]; ?>" required>
                                    <label for="measurent">Measurement:</label> <input type="text" name="ingredient_measurement_arr[]" id="ingredientMeasurement1" value="<?php echo $ingredient_measurement[$y]; ?>">
                                    <label for="ingredientName">Ingredient:</label> <input type="text" name="ingredient_name_arr[]" id="ingredientName1" class="ingredient" value="<?php echo $ingredient_name[$y]; ?>" required> 
                                    <button type="button" class="delete"><a href="?recID=<?php echo $recipe_ID?>&deleteIng=<?php echo $ingredient_IDs[$y]?>">x</a></button>
                                </li>
                                <p class="validation" ><span id="ingredientValidation"><?php echo $ingErr?></span></p>
                                <?php } ?>
                            </ol>
                            <button type="button" id="ingredients">Add +</button>
                    </div>
                    
                <h3>Instructions:</h3>
                    <div class="formSection">
                            <ol id="instructionList">
                            <?php for($x=0; $x<count($instruction_description); $x++) {?>
                                <li>
                                    <input type="text" name="instruction_description_arr[]" id="instruction1" class="instruction" value="<?php echo $instruction_description[$x];?>" required>
                                    <button type="button" class="delete"><a href="?recID=<?php echo $recipe_ID?>&deleteIns=<?php echo $instruction_IDs[$x]?>">x</a></button>
                                </li>
                            <p class="validation" ><span id="instructionValidation"><?php echo $insErr?></span></p>
                                <?php } ?>
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