<?php
    session_start();

    include 'header.php';
?>

<html>
    <head>
        <title>Management Console</title>

        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="jquery.js"></script>
    </head>
    <body>

        <h1>Management Console</h1>

        <div class="content">

                <?php

                    if( isset($_SESSION["user"]) )
                    {

                        include 'connect.php';
                        $con = mysqli_connect("localhost", $db_username, $db_password, $db_name);

                        if( mysqli_connect_errno() )
                        {
                            echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
                        }
                        else
                        {
                            $q = "SELECT is_superuser FROM user WHERE id=" . $_SESSION["user"];
                            $result = mysqli_query($con, $q);

                            if( mysqli_num_rows($result) > 0 )
                            {
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $superuser = $row["is_superuser"];
                                }
                            }

                            if( $superuser )
                            {
                                echo "<div id=\"managementConsole\">";
                                echo "<div class=\"title\">Transactions</div><br>";
                                echo "<form action=\"managementConsole.php\" method=\"GET\">";
                                echo "From <input type=\"date\" name=\"dateFrom\"> To <input type=\"date\" name=\"dateTo\"> <button type=\"submit\" class=\"button medium\">Filter Dates</button>";
                                echo "</form>";

                                echo "<table id=\"transactions\">";
                                echo "<tr class=\"label\"><td class=\"date\">Date</td><td class=\"username\">Username</td><td class=\"fullName\">Full Name</td><td class=\"amount\">Amount</td><td class=\"items\">Items</td><td class=\"status\">Status</td></tr>";

                                if( isset($_GET["dateFrom"]) && isset($_GET["dateTo"]) )
                                {
                                    $q = "SELECT transaction.id as transaction_id, timestamp, username, first_name, last_name, amount, status
                                        FROM user, transaction
                                        WHERE user.id = transaction.user_id
                                        AND (timestamp BETWEEN '" . $_GET["dateFrom"] . "' AND '" . $_GET["dateTo"] ."')
                                        ORDER BY timestamp";
                                }
                                else
                                {
                                    $q = "SELECT transaction.id as transaction_id, timestamp, username, first_name, last_name, amount, status
                                        FROM user, transaction
                                        WHERE user.id = transaction.user_id
                                        ORDER BY timestamp";
                                }
                                $result = mysqli_query($con, $q);
                                if( mysqli_num_rows($result) > 0 )
                                {
                                    while($row = mysqli_fetch_assoc($result))
                                    {
                                        $q = "SELECT * FROM cart, item WHERE cart.item_id = item.id AND cart.transaction_id=" . $row["transaction_id"];
                                        $result2 = mysqli_query($con, $q);

                                        $items = "";
                                        while($row2 = mysqli_fetch_assoc($result2))
                                        {
                                            $items .= $row2["name"] . " x " . $row2["qty"] . "<br>";
                                        }

                                        if( $row["status"] == 1 )
                                        {
                                            $status = "Paid";
                                        }
                                        else if( $row["status"] == 2 )
                                        {
                                            $status = "Not Paid";
                                        }
                                        else
                                        {
                                            $status = "Cancelled";
                                        }

                                        echo "<tr>";
                                        echo "<td class=\"date\">" . $row["timestamp"] . "</td>";
                                        echo "<td class=\"username\">" . $row["username"] . "</td>";
                                        echo "<td class=\"fullName\">" . $row["first_name"] . " "  . $row["last_name"] . "</td>";
                                        echo "<td class=\"amount\">P" . number_format($row["amount"], 2) . "</td>";
                                        echo "<td class=\"items\">" . $items . "</td>";
                                        echo "<td class=\"status\">" . $status . "</td>";
                                    }
                                }
                                echo "</table>";
                                echo "</div>";
                            }
                            else
                            {
                                mysqli_close($con);

                                redirect("profile.php");
                            }
                        ?>
            </div>
            </form>
                        <?php

                            mysqli_close($con);
                        } // end secure database connection

                    } // end check if user is logged in
                ?>
        </div>
    </body>
</html>