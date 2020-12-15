<?php
    session_start();
    $updateRecID = $_GET['recID'];

    try {
	  
        require "database/dbConnect.php";	//CONNECT to the database

        $sqlRec = "SELECT *
                    FROM recipe
                    WHERE recipe_ID=:id";
                    
              $stmtRec = $conn->prepare($sqlRec);
              $stmtRec->bindParam(':id', $updateRecID);
              $stmtRec->execute();	
              
              $stmtRec->setFetchMode(PDO::FETCH_ASSOC);

              $rowRec = $stmtRec->fetch(PDO::FETCH_ASSOC);

              $recipe_img=$rowRec['recipe_img'];
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

              $sqlIns = "SELECT * 
                  FROM instruction       
                  WHERE recipe_ID=:id";

              $stmtIns = $conn->prepare($sqlIns);
              $stmtIns->bindParam(':id', $updateRecID);
              $stmtIns->execute();	
              
              $stmtIns->setFetchMode(PDO::FETCH_ASSOC);
    }
    
    catch(PDOException $e)
  {
	 $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

	  error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
	  error_log($e->getLine());
	  error_log(var_dump(debug_backtrace()));				
  }
?>
<html>

  <head>
  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="js/recipeFunctions.js"></script>

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/recipeStyle.css">

    <script src="js/hamburger.js"></script>

  </head>

  <body onload="recipeFunctions(), onLoad()">
  <header>
      <?php require "php/navBar.php"?>
        <div class="container">
            <h1><?php echo $recipe_name?></h1>
        </div>
      </header>
      <div class="topSection">
          <p>&nbsp;<p>
    </div>
        <div class="container mainSection">
            <div class="recipe">
            <div class="flex">
                <img src="images/<?php echo $recipe_img?>">
                <div class="recipeInfo">
                    <h2>Recipe Info</h2>
                    <p><strong>Servings:</strong> <span class="serving"><?php echo $recipe_servings?></span></p>
                    <p><strong>Prep Time:</strong> <?php echo $recipe_prep_time_hours?> hours <?php echo $recipe_prep_time_minutes?> minutes</p>
                    <p><strong>Cook Time:</strong> <?php echo $recipe_cook_time_hours?> hours <?php echo $recipe_cook_time_minutes?> minutes</p>
                    <p><strong>Difficulty:</strong> <?php echo $recipe_difficulty?></p>
                </div>
            </div>
            <div class="otherInfo">
                <p><strong>Servings:</strong> <input type="number" min="1" value="<?php echo $recipe_servings?>" id="servings"></p>
            <h3>Ingredients</h3>
            <ul>
        <?php 
			while( $row=$stmtIng->fetch(PDO::FETCH_ASSOC) ) {
		?>	
            <?php echo "<li>"; if($row['ingredient_amount'] > 0) {echo"<span class='amount'> $row[ingredient_amount] </span>"; } echo " $row[ingredient_measurement] $row[ingredient_name] </li>" ?>
            <?php } ?>
            </ul>
            
            <h3>Instructions</h3>
            <ol>
            <?php 
			while( $row=$stmtIns->fetch(PDO::FETCH_ASSOC) ) {
		?>	
            <?php echo "<li> $row[instruction_description] </li>" ?>
            <?php } ?>
            </ul>
            </div>
            </div>
        </div>
        <?php require 'php/footer.php'?>
    </body>

</html>