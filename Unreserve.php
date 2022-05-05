<!-- 
    Author - Joseph Zheng  Created Apr 18,22
    Allows the user to unreserve any of the reserved books that 
    are associated with their account, and will make the books
    available to be reserved again by anyone.
    This page READS and DELETES from the database.
-->

<?php
	include_once ('includes/create-home-header.php');
	include_once ('includes/create-hotbar.php');
	include_once ('includes/create-footer.php');
	include_once ('includes/start-session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    	<title>About | Sharron Books</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="css/main.css">
	<title>Unreserve</title>
</head>
	
<body>

	<?php
        /* Hotbar at the top of each page that will display a searchbar and login info */
        create_hotbar();
    ?>
    
	<div class="linedUp">
		<?php
			/* Header bar at the top of the each page containing a series of links and the logo */
			create_home_header();
		?>
	
	<div class="backgroundfix4">
		<div class="container">  
			<div class="main">
				<h1>Unreserved</h1>
				<?php
				echo "<div class='Form2'><h2>Book has been unreserved if code was correct.</h2></div>";
				?>
			</div>
		</div>
	</div>
				
	<?php
									
		require('includes/config.php');
												
		$Query = $db->Query(sprintf("DELETE FROM bookreserve 
										WHERE `bookreserve`.`BookID` = '%s' HAVING UserID='%s'", $_POST['bookID'], $_SESSION['login_user']
										$db->escape_string($_POST['BookID'])));
											
		echo "<br>";
		echo "<div class='Form2'><h2>Check your account if the book has been unreserved.</h2></div>";
		echo "<br>";
		
		echo "<div class='Form'><h3><a href='profile.php'>View your account</a> <br></h3></div>";
		echo "<div class='Form'><h3><a href='includes/logout.php'>Want to log out?</a> <br></h3></div>";

	?>
	
	<br><br>
	
	<?php
		/* Footer at the end of the page that displays some basic website info */
		create_footer();
	?>
	
</body>
</html>
