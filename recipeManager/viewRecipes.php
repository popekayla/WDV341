<?php
session_start();
if($_SESSION['validUser'] !== "yes") {
    header('Location: index.php');
}
if(isset($_GET['deleteID'])) {
    $updateDeleteID = $_GET['deleteID'];
   
    try {
  
        require "database/dbConnect.php";	//CONNECT to the database
  
        $sql = "SELECT ingredient_ID
        FROM recipe_ingredient
        WHERE recipe_ID = :rec_ID";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':rec_ID', $updateDeleteID);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ingredient_ID[] = $row['ingredient_ID'];
        }

        $sql = "DELETE FROM recipe_ingredient
        WHERE recipe_id = :ID";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $updateDeleteID);

        $stmt->execute();	

        for($i=0; $i<$count; $i++) {
            $sql = "DELETE FROM ingredient
            WHERE ingredient_id = :ID";
    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ID', $ingredient_ID[$i]);
            
            $stmt->execute();	
        }

        $sql = "DELETE FROM instruction
                WHERE recipe_id = :ID";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $updateDeleteID);

        $stmt->execute();	
        
        $sql = "DELETE FROM recipe
                WHERE recipe_id = :ID";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $updateDeleteID);
        
        $stmt->execute();	


        $message = 'Event has been deleted';	
    }
    
    catch(PDOException $e)
    {
       $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
  
        error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
        error_log($e->getLine());
        error_log(var_dump(debug_backtrace()));				
    }
}
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
            <h1>Edit Recipes</h1>
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
              <a href="recipe.php?recID=<?php echo $row['recipe_ID'];?>"><button>View</button></a>
              <a href="updateRecipe.php?recID=<?php echo $row['recipe_ID'];?>"><button>Edit</button></a>
                <a href="?deleteID=<?php echo $row['recipe_ID'];?>"><button type="button" onclick="return confirm('Are you sure you want to delete?')">Delete</button></a>
          </div>
          </div>
        <?php }?>
        </div>
        </div>
      <?php require 'php/footer.php'?>
    </body>
</html>

