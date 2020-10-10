<?php

	try {
	  
	  require "dbConnect.php";	//CONNECT to the database

	  $sql = "SELECT * FROM wdv341_products
	  			ORDER BY product_name DESC";
	  
	  //PREPARE the SQL statement
	  $stmt = $conn->prepare($sql);
	  
	  //EXECUTE the prepared statement
	  $stmt->execute();		
	  
	  //Prepared statement result will deliver an associative array
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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	
	<style>
		
		body {
			  font-family: Verdana, Geneva, Tahoma, sans-serif;
			  line-height: 1.75em;
			  text-align: center;
		  }

  		.container {
			width:  80%;
			margin: 0 auto;
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
		  }

		.productBlock	{
			width:300px;
			background-color: aquamarine;
			border:thin solid black;
			margin: 4em 1em;
			padding: 1em;
			border-radius: 10px;
			box-shadow: 0px 0px 10px 3px #efefef;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			justify-content: space-between;
			flex-direction: column;
		}
		
		.productImage img {
			display:block;
			margin-left:auto;
			margin-right:auto;
			width:280px;
			height:280px;				
		}
	
		.productName {
			text-align:center;
			font-size: large;
		}	
		
		.productDesc {
			margin-left:10px;
			margin-right:10px;
			text-align:justify;
		}
		
		.productPrice {
			text-align: center;
			font-size:larger;
			color:blue;
		}
		
		.productStatus {
			text-align:center;
			font-weight:bolder;
			color:darkslategray;
		}
		
		.productInventory {
			text-align:center;
		}
		
		.productLowInventory {
			color:red;
		}
		
	</style>
</head>

<body>
	
	
	<h1>DMACC Electronics Store!</h1>
	<h2>Products for your Home and School Office</h2>
		<div class="container">
		<?php 
			while( $row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
		?>	

			<div class="productBlock">

				<div class="productImage">
					<image src="productImages/<?php echo $row['product_image']; ?>">
				</div>
				<p class="productName"><?php echo $row['product_name']; ?></p>	

				<p class="productDesc"><?php echo $row['product_description']; ?></p>

				<p class="productPrice"><?php echo $row['product_price']; ?></p>
				
				<!--If there is content, display-->
				<p class="productStatus">
					<?php
						if($row['product_status'] !== "") {
						echo $row['product_status']; 
						}
					?>
				</p>

				<!--If stock is < 10 add low stock class-->
				
					<?php 
						if($row['product_inStock'] < 10) {
							echo '<p class="productInventory productLowInventory">' . $row['product_inStock'] . '</p>'; 
						} else {
							echo '<p class="productInventory">' . $row['product_inStock'] . '</p>';
						}
					?>
				</p>

			</div>

			<?php
				}
			?>
	</div>
	
</body>
</html>