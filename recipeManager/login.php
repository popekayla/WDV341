<?php
    session_cache_limiter('none');			//This prevents a Chrome error when using the back button to return to this page.
    session_start();

    $_SESSION['validUser'] == "no";
    $message = "";

    if($_SESSION['validUser'] == "yes") {
        
    } else {
        if(isset($_POST['submitButton'])) {

            $user = $_POST['recipe_user_name'];
            $pass = $_POST['recipe_user_password'];

            try{
                require "database/dbConnect.php";

                $sql = "SELECT recipe_user_name, recipe_user_password
                        FROM recipe_user
                        WHERE recipe_user_name=:user AND recipe_user_password=:password";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':user', $user);
                $stmt->bindParam(':password', $pass);

                $stmt->execute();

                $count = $stmt->rowCount();	
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $user = $row['recipe_user_name'];


        } catch(PDOException $e)
        {
            $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

            error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
            error_log($e->getLine());
            error_log(var_dump(debug_backtrace()));				
        }

        if($count ==1 && !empty($row)) {

            //Set validUser=true
            $_SESSION['validUser'] = "yes";

            //Create welcome message
            $message = "Welcome back $user!";

            //Display admin options
            } else {

        //Else invalid

            //Create error message
            $message = "Sorry there was a problem with your username or password. Please try again.";

            }

            $stmt = null;
            $conn = null;
        }
    }


?>

<!DOCTYPE html>

<html>

<head>

    <title>Recipe Manager - Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/loginStyle.css">

    <script src="js/hamburger.js"></script>

</head>

<body onload="onLoad()">

    <header>
      <?php require "php/navBar.php"?>
        <div class="container">
            <h1>Login</h1>
        </div>
      </header>
      <div class="topSection">
      <div class="container">
        <h3><?php echo $message?></h3>

        <?php if($_SESSION['validUser'] == 'yes'){
            ?>
        <h2>Recipe Manager Admin Options</h2>
        </div>
        </div>
        <div class="adminSection container flex justify-space-around">
            <a href="addRecipe.php"><button>Add Recipe</button></a></button>
            <a href="viewRecipes.php"><button>View & Edit Recipes</button></a>
            <a href="logout.php"><button>Logout</button></a>
        </div>

        <?php } else { ?>

            <h2>Login</h2>
            </div>
            </div>
            
            <div class="adminSection container">
                
        <form id="recipeForm" name="recipeForm" method="post" action="">
                <p><label for="recipe_user_name">Username: </label>
                <input type="text" id="recipe_user_name" name="recipe_user_name" required></p>
                
                <p><label for="recipe_user_password">Password: </label>
                <input type="password" id="recipe_user_password" name="recipe_user_password" required></p>

                <p><label>&nbsp;</label>
                <button type="submit" name="submitButton">Login</button></p>
        </div>

    </form>
        <?php } ?>
        </div>
        <?php require 'php/footer.php'?>
</body>

</html>