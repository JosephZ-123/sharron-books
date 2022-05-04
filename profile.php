<!-- 
    Author - Drew Jenkins  Created Mar 28,22
    Showing the current user their homepage with account details and registered books
-->
<?php
	include_once ('includes/create-home-header.php');
	include_once ('includes/create-hotbar.php');
	include_once ('includes/start-session.php');
    require_once ('includes/config.php');

    if ( !isset($_SESSION['login_user']) && $_SESSION['login_admin'] == false )
    {
        header('Location:login-form.php');
        exit;
    } // if

    // find user for checking their reserved books
    $sql = $db->prepare("SELECT * FROM users WHERE Username = ?");
    $sql->bind_param( "s", $_SESSION['login_username'] ); //binding to prevent sql injection
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;
    if ( $count != 0 ) // if count is not 0 there was a user with the name
    {                
        $error = "does not exist";
    } // if
    else 
    {
        // find the title of the first book
        $book1 = $result -> fetch_column(6);
        $book2 = $result -> fetch_column(7);

        $sql = "SELECT Title FROM books WHERE BookId = $book1;";
        $result = mysqli_query($db, $sql);
        $count = mysqli_num_rows($result); 
        if ( $count != 0 ) // if count is not 0 there was a user with the name
        {                
            $title1 = "no registered book";
        } // if
        else 
        {
            $title1 = mysqli_fetch_column( $result, 0 );
        } // else
        
        // find the title of the second book
        $sql = "SELECT Title FROM books WHERE BookId = $book2;";
        $result = mysqli_query($db, $sql);
        $count = mysqli_num_rows($result); 
        if ( $count != 0 ) // if count is not 0 there was a user with the name
        {                
            $title2 = "no registered book";
        } // if
        else 
        {
            $title2 = mysqli_fetch_column( $result, 0 );
        } // else
    } // else
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>Profile | Sharron Books</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="css/main.css">
</head>
<script src=""></script>
<body>
	
    <?php
        /* Hotbar at the top of each page that will display a searchbar and login info */
        create_hotbar();
        /* Header bar at the top of the each page containing a series of links and the logo */
        create_home_header();
    ?>
	
	<div class="content">
        <h3 class = "heading" style="text-decoration: underline;"> Profile </h3>
            <div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['login_username']?></td>
					</tr>
                    <tr>
						<td>User ID:</td>
						<td><?=$_SESSION['login_user']?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$_SESSION['login_email']?></td>
					</tr>
                    <tr>
						<td>Name:</td>
						<td><?=$_SESSION['login_firstname']?> <?=$_SESSION['login_lastname']?></td>
					</tr>
				</table>
			</div>
        </form>
	</div>
	
	<br>

    <div class="content">
        <h3 class = "heading" style="text-decoration: underline;"> Reserved List </h3>
				<p>Your reserved books are below:</p>
				if(isset($_SESSION['login_user']))
		{
			$UserID = $_SESSION['login_user'];
			echo "<br><br>";
			echo "<div class='Form'><h1>Hello " . $UserID . "<br></h1></div>";
			echo "<div class='Form'><h3><a href='includes/logout.php'>Not you? Logout.</a> <br></h3></div>";
			echo "<br><br>";
			
			$Query = $db->Query(sprintf("SELECT books.BookID, books.Title 
											FROM bookreserve 
											INNER JOIN books 
											ON bookreserve.BookID=books.BookID 
											WHERE bookreserve.UserID = '%s'", $_SESSION['login_user']));
			
			if ($Query->num_rows == 0) 
			{
				echo "<div class='Form2'><h2>No books have been reserved.</h2></div>"; 
			} // if
			
			
			//If books match with what the user wants, then display the results.
			while($Row = mysqli_fetch_array($Query, MYSQL_BOTH))
			{
				echo "<table border=\"2\"align=\"center\"width=\"600\">";
				echo("</td><td>");
				echo "<div class=\"Form2\">";
				echo '<br /> BookID:       ' .$Row['BookID'];  
				echo '<br /> Book Title: ' .$Row['Title'];  
				echo '<br /> <br />';
				echo("</tr>\n");
				echo "</div>";
				echo "<br>";
			} // while
			echo "</table>\n";
			
			echo "</select><br><br>";
			
			echo "<div class=\"Form2\">";
			echo "<form action=\"Unreserve.php\" method=\"POST\">";
			echo "The Book's ID:<br>";
			echo "<input type=\"text\" name=\"BookID\" placeholder=\"434-343-23\" required ><br>";
			echo "<input type=\"submit\" value=\"Submit\">";
			echo "</form>";
			echo "</div>";
		} // if
        </form>
	</div>
    
    <br>
	
	<div class="main-footer">
		<p> Sharron Books | 2022 </p>
		<p> Carson, Kylie, Joseph, Drew </p>
	</div>
	
</body>
</html> 
