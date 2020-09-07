<!doctype HTML>
<html>

    <head>

        <title>WDV 341 PHP Basics</title>

    </head>

    <body>

        <div>
            <?php
                $yourName = "Kayla Pope";
                echo "<h1>" . $yourName . "</h1>";
            ?>

            <h2><?php echo $yourName; ?></h2>

            <?php
                $number1 = 3;
                $number2 = 7;
                $total = $number1 + $number2;
            ?>

            <p><?php echo "The sum of " . $number1 . " and " .$number2 . " is " . $total; ?></p>
            
            <script>
                
                <?php 
                    $programmingLanguagesList = "'PHP', 'HTML', 'Javascript'";
                ?>

                let programmingLanguages = [ <?php echo $programmingLanguagesList;?> ];

                document.write("<p>" + programmingLanguages + "</p>");

            </script>
        </div>


    </body>

</html>