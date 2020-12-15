<?php
  session_start();
    try {
	  
        require "database/dbConnect.php";	//CONNECT to the database

        $sql = "SELECT *
                FROM recipe";
                    
              $stmt = $conn->prepare($sql);

              $stmt->execute();	
              
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
<!DOCTYPE HTML>
<html>
    
    <head>
      
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link rel="stylesheet" type="text/css" href="css/style.css">

      <script src="js/hamburger.js"></script>

    </head>

    <body onload="onLoad()">
   
      <header>
      <?php require "php/navBar.php"?>
        <div class="container">
            <h1>Home</h1>
        </div>
      </header>
    <div class="topSection">
      <div class="container">
      <p>Recipes</p>
        </div>
    </div>
    <div class="container">
      <div class="mainSection flex justify-space-between">
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {?>
          <div class="recipeCard">
            <div class="recipeTitle" style="background-image:url(images/<?php echo $row['recipe_img'] ?>)";>
            <div class="title"><h2><?php echo $row['recipe_name'];?></h2></div>
              <div class="overlay"></div>
            </div>
            <div class="info">
              <p><strong>Servings: </strong><?php echo $row['recipe_servings'];?></span></p>
              <p><strong>Prep Time: </strong><?php echo "$row[recipe_prep_time_hours] hours $row[recipe_prep_time_minutes] minutes";?></p>
              <p><strong>Cook Time: </strong><?php echo "$row[recipe_cook_time_hours] hours $row[recipe_cook_time_minutes] minutes";?></p>
              <p><strong>Difficulty: </strong><?php echo $row['recipe_difficulty'];?></p>
              <a href="recipe.php?recID=<?php echo $row['recipe_ID'];?>"><button>View Recipe</button></a>
          </div>
          </div>
        <?php }?>
        </div>
        </div>
      <?php require 'php/footer.php'?>
    </body>
</html>