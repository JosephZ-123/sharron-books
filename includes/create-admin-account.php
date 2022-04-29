<!-- 
    Author - Drew Jenkins  Created Apr 21,22
    Basic Admin Account creation for use with relevant html. requires an admin to be logged in for best practices
-->
<?php
    require("config.php");
    if( !isset($_SESSION['login_user']) && _SESSION['user_type'] == "true" ) // check for admin session
    {
        header( 'location:login-admin.php' ); // if not go to login
    } // if
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {           
        $userIn = $_POST['username'];
        $passIn = $_POST['password']; 
        $chkPass = $_POST['confirm-password'];
            
        if( $chkPass != $passIn ) //verify user input
            die( 'Password mismatch');
                
        $sql = $db->prepare("SELECT Username FROM Administrators WHERE Username = ?");
        $sql->bind_param( "s", $userIn ); //binding to prevent sql injection
        $sql->execute();
        $result = $sql->get_result();
        $count = $result->num_rows;
        if ( $count != 0 ) // if count is not 0 there was a user with the name
        {                
            $error = "Username is already in use.";
        } // if
        else // no discovered user safe to make
        {
            $sql = $db->prepare( "INSERT INTO Administrators (Username, Password) VALUES (?,?)" );
            $hash = password_hash( $passIn, PASSWORD_DEFAULT ); //hashing
            $sql->bind_param( "ss", $userIn, $hash );
            $sql->execute();
            header("location:../html/logout.html");
            } // else
        } // if
    ?>