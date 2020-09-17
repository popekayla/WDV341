<!doctype html>

<html>

    <head>

        <title>WDV 341 PHP Functions</title>

    </head>

    <body>

        <h1>WDV 341 PHP Functions</h1>

        <p>
            Format date to mm/dd/yyyy:
            <?php 
        
                function displayDate($inDate) {
                    echo date("m/d/Y", strtotime($inDate)); 
                }
    
                displayDate("September 22 2020");

            ?>
        </p>

        <p>
            Format date to dd/mm/yyyy:
            <?php 
        
                function displayInternationalDate($inDate) {
                    echo date("d/m/Y", strtotime($inDate)); 
                }

                displayInternationalDate("9/22/2020");

            ?>
        </p>

        <p>
            <?php

                function stringInfo($inString) {

                    $lowerCaseString = strtolower($inString);

                    echo "<p>The string length is " . strlen($inString) . "characters.</p>";
                    echo "<p>" . trim($lowerCaseString) . "</p>";

                    if(strpos($lowerCaseString, 'dmacc') !== false) {
                        echo "<p>The string contains the word DMACC!</p>";
                    } else {
                        echo "<p>The string does not contain the word DMACC.</p>";
                    }
                }

                stringInfo(" I attend Dmacc ");

            ?>
        </p>

        <p>
            Format a number:
            <?php

                function formatNumber($inNumber) {
                    echo number_format($inNumber);
                }

                formatNumber(123456);

            ?>
        </p>

        <p>
            Format currency:
            <?php
                function formatCurrency($inNumber) {
                    echo "$" . number_format($inNumber, 2);
                }

                formatCurrency(12345.6578);
            ?>
        </p>



    </body>

</html>