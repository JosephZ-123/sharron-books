<?php
    /* Author - Carson Jones
     * Given a database connection and an SQL Query, creates an html table
     * of rows in the "books" table of the database with headers. Makes
     * use of session variables to display certain info, so a session needs
     * to be started by the caller.
     * 
     * This function READS from the database only. If the delete button is pressed,
     * the deletion form is sent to the caller.
     *  
     * @param $db - a database connection 
     * @param $sql - an SQL select statement
     */
    function create_books_display($db,$sql)
    {
        $result = mysqli_query($db, $sql);   
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0)
        {
            if (isset($_SESSION['login_admin']) && $_SESSION['login_admin'] == "true")
            {
                $displayAdminColumns = "true";
            }
            else
            {
                $displayAdminColumns = "false";
            }
            echo "<table class=\"results-table\">";
            
            // update table headers manually
            echo "<th> Title </th>";
            echo "<th> Author </th>";
            echo "<th> Publisher </th>";
            echo "<th> Genre </th>";
            echo "<th> Year Published </th>";
            echo "<th> Description </th>";
            echo "<th> Availability </th>";
            echo "<th>  </th>";
            echo "<th> Book Cover </th>";
            if ($displayAdminColumns == "true")
            {
                echo "<th> Held by </th>";
                echo "<th> Delete </th>";
            }
            
            while ($row = mysqli_fetch_assoc($result))
            {
                # Each attribute of the $row array will be processed as a 
                # row in a table.
                echo "<tr class=\"dotted\">";
                
                #Book title
                echo "<td class=\"dotted\">";
                echo $row['Title'];
                echo "</td>";
                
                #Book author
                echo "<td class=\"dotted\">";
                echo $row['Author'];
                echo "</td>";
                
                #Book Publisher (Fix Issue for no publisher)
                echo "<td class=\"dotted\">";
                echo $row['Publisher'];
                echo "</td>";
                
                #Book Genre
                echo "<td class=\"dotted\">";
                echo $row['Genre'];
                echo "</td>";
                
                #Year of Publication
                echo "<td class=\"dotted\">";
                echo $row['YearPubbed'];
                echo "</td>";
                
                #Book desription
                echo "<td class=\"dotted\">";
                    echo "<p class=\"book-description\">";
                        echo $row['Description'];
                    echo "</p>";
                echo "</td>";
                
                #Book availability
                echo "<td class=\"dotted\">";
                if ($row['CheckedOut'] == 0)
                {
                    echo "available";
                }
                else
                {
                    echo "unavailable";
                }
                
                #Buttons
                #Contains a hidden form that will send info to reserve.php
                echo "<td class=\"dotted\">";
                echo "<form action=\"reserve.php\" method=\"POST\">";
                echo "<input type=\"hidden\" name=\"bookID\" value=\"" . $row['BookID'] . "\">";
                echo "<input type=\"hidden\" name=\"userID\" value=\"" . $row['UserID'] . "\">";
                echo "<input type=\"submit\" value=\"reserve\" name=\"reserve\">";
                echo "</form>";
                #Contains a hidden form that will send bookID to details.php
                echo "<form action=\"details.php\" method=\"GET\">";
                echo "<input type=\"hidden\" name=\"bookID\" value=\"" . $row['BookID'] . "\">";
                echo "<input type=\"submit\" value=\"view details\" name=\"details\">";
                echo "</form>";

                ### If you plan on adding a button to favorite a book, here's a good place for it ###

                echo "</td>";
                    
                #Cover image
                echo "<td class=\"dotted\">";
                echo "<img ";
                echo "src=\"images/covers/" . $row['ImageLocation'] . "\"";
                # If the image isn't found in images/covers, replace image with none.jpg
                echo "onerror=\"if (this.src != 'images/covers/none.jpg') this.src = 'images/covers/none.jpg';\" ";
                echo "alt=\"" . $row['ImageLocation'] . "\"";
                echo "width=\"100\"";
                echo "height=\"120\"";
                echo ">";
                echo "</td>";

                #Admin Only Columns
                if ($displayAdminColumns == "true")
                {
                    # Delete book button
                    echo "<td class=\"dotted\">";
                    echo "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"POST\">";
                    echo "<input type=\"hidden\" name=\"bookID\" value=\"" . $row['BookID'] . "\">";
                    echo "<input type=\"submit\" value=\"delete\" name=\"delete\">";
                    echo "</form>";
                    echo "</td>";
                }
            }
            echo "</table>";
        }
        else
        {
            echo "<h3 class = \"alert error\"> No results found for this query. </h3>";
        }
    }

    /* Author - Carson Jones
     * Given a database connection and an SQL query, creates a simple html table
     * of rows in the "books" table of the database with headers. This is a 
     * simplified version of create_books_display that doesn't create as many rows.
     * This function omits genre, publisher, year published, admin columns, 
     * and the reservation button.
     *  
     * @param $db - a database connection 
     * @param $sql - an SQL select statement
     */
    function create_books_display_short($db,$sql)
    {
        $result = mysqli_query($db, $sql);   
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0)
        {
           
            echo "<table class=\"results-table\">";
 
            // update table headers manually
            echo "<th> Title </th>";
            echo "<th> Author </th>";
            echo "<th> Genre </th>";
            echo "<th>  </th>";
            echo "<th> Book Cover </th>";
            
            while ($row = mysqli_fetch_assoc($result))
            {
                # Each attribute of the $row array will be processed as a 
                # row in a table.
                echo "<tr>";
                
                #Book title
                echo "<td>";
                echo $row['Title'];
                echo "</td>";
                
                #Book author
                echo "<td>";
                echo $row['Author'];
                echo "</td>";
                
                #Book desription
                echo "<td>";
                echo $row['Description'];
                echo "</td>";

                #Contains a hidden form that will send bookID to details.php
                echo "<td>";
                echo "<form action=\"details.php\" method=\"GET\">";
                echo "<input type=\"hidden\" name=\"bookID\" value=\"" . $row['BookID'] . "\">";
                echo "<input type=\"submit\" value=\"view details\" name=\"details\">";
                echo "</form>";
                echo "</td>";

                #Cover image
                echo "<td>";
                echo "<img ";
                echo "src=\"images/covers/" . $row['ImageLocation'] . "\"";
                # If the image isn't found in images/covers, replace image with none.jpg
                echo "onerror=\"if (this.src != 'images/covers/none.jpg') this.src = 'images/covers/none.jpg';\" ";
                echo "alt=\"" . $row['ImageLocation'] . "\"";
                echo "width=\"100\"";
                echo "height=\"120\"";
                echo ">";
                echo "</td>";
            }
            echo "</table>";
        }
        else
        {
            echo "<h3 class = \"alert error\"> No results found for this query. </h3>";
        }
    }
?>
