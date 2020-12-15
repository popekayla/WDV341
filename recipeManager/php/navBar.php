<nav>

    <div class="container flex align-center justify-space-between desktop">
        
        <div class="logo">
            <a href="index.php">Recipe Manager</a>
        </div>

        <div class="links">
            <ul>    
                <?php if($_SESSION['validUser'] == "yes") { ?>
                    <a href="viewRecipes.php"><li>View Recipes</li></a>
                    <a href="addRecipe.php"><li>Add Recipe</li></a>
                    <a href="login.php"><li>Admin</li></a>
                    <a href="contact.php"><li>Contact</li></a>
                    <a href="logout.php"><li>Logout</li></a>
                <?php } else { ?>
                    <a href="contact.php"><li>Contact</li></a>
                    <a href="login.php"><li>Login</li></a>
                <?php }?>
            </ul>
        </div>

                </div>

    <div class="mobileNav">

        <div class="container flex space-between align-center">


            <div class="logo">
                <a href="index.php">Recipe Manager</a>
            </div>

            <a class="menuIcon"><span></span></a>
        </div>

        <div class="menu mobile hidden">
            
            <ul>    
                <?php if($_SESSION['validUser'] == "yes") { ?>
                    <a class="menuItem" href="viewRecipes.php"><li>View Recipes</li></a>
                    <a class="menuItem" href="addRecipe.php"><li>Add Recipe</li></a>
                    <a class="menuItem" href="login.php"><li>Admin</li></a>
                    <a class="menuItem" href="contact.php"><li>Contact</li></a>
                    <a class="menuItem" href="logout.php"><li>Logout</li></a>
                <?php } else { ?>
                    <a class="menuItem" href="contact.php"><li>Contact</li></a>
                    <a class="menuItem" href="login.php"><li>Login</li></a>
                <?php }?>
            </ul>
        </div>


    </div>

</nav>