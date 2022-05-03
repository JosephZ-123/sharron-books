<?php
    /*
     * Author - Carson Jones
     * Displays the details of some book in the library database.
     * This file READS from the database only.
     */
    require_once ('includes/config.php');
    require_once ('includes/sanitize.php');
    require_once ('includes/delete-book.php');
    include_once ('includes/create-books-display.php');
    include_once ('includes/create-hotbar.php');
    include_once ('includes/create-home-header.php');
	include_once ('includes/create-footer.php');
    require_once ('includes/start-session.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>Book Details | Sharron Books</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="css/main.css">
</head>
<script src=""></script>
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

		<div class="content">
			<div class="wrapper">
			<?php
		
				// Set form variables
				if (isset($_GET["bookID"]))
				{
					$bookID = sanitize_input($_GET["bookID"]);
					$stmt = $db->prepare("SELECT * FROM books WHERE BookID = ?");
					$stmt->bind_param("i", $bookID); //binding to prevent sql injection

					# process query
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($title,
									$author,
									$publisher,
									$genre,
									$yearPubbed,
									$description,
									$bookID,
									$checkedOut,
									$imageLocation);
					
					
					# Process and display results
					$stmt->fetch();
					echo "<h3 class=\"heading\"> \"" . $title . "\" </h1>";
					echo "<h3 class=\"subheading\"> by " . $author . "</h3>";
					
					echo "<img ";
					echo "src=\"images/covers/" . $imageLocation . "\"";
					# If the image isn't found in images/covers, replace image with none.jpg
					echo "onerror=\"if (this.src != 'images/covers/none.jpg') this.src = 'images/covers/none.jpg';\" ";
					echo "alt=\"" . $imageLocation . "\"";
					echo "width=\"300\"";
					echo ">";

					# Reservation button
					echo "<form action=\"reserve.php\" method=\"POST\">";
					echo "<input type=\"hidden\" name=\"bookID\" value=\"" . $bookID . "\">";
					echo "<input type=\"submit\" value=\"reserve\" name=\"reserve\">";
					echo "</form>";

					# Book Description
					echo "<p class = \"bookdescription\">" . $description . "</p>";

					# Info
					echo "<p class=\"book-info\">";
					echo "Publisher: " . $publisher . "<br>";
					echo "Year of original publication: " . $yearPubbed . "<br>";
					echo "Barcode Number: " . $bookID . "<br>";
					echo "</p>";

				}
				else
				{
					# bookID variable is not set 
					echo("<p class=\"error\"> error: no book selected. <p>");
				}
			?>
			</div>
		</div>
	</div>
	
	<?php
		/* Footer at the end of the page that displays some basic website info */
		create_footer();
	?>
</body>
</html> 
