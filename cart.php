<?php
    session_start();

    include 'header.php';
?>

<html>
    <head>
        <title>Cart</title>

        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="jquery.js"></script>
    </head>
    <body>

        <h1>Cart</h1>

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
                            } // end check if superuser


                            /* DISPLAY ITEMS IN CART */

                            $q = "SELECT * FROM cart, item WHERE cart.checkout=false AND cart.item_id=item.id AND cart.user_id=" . $_SESSION["user"] . " ORDER BY cart.id";
                            $result = mysqli_query($con, $q);
                            if( mysqli_num_rows($result) > 0 )
                            {
                                echo "<form action=\"updateCart.php\" method=\"post\">";
                                echo "<table id=\"cart\">";
                                echo "<tr><td>No.</td><td>Item</td><td>Image</td><td>Price</td><td>Qty</td><td></td></tr>";
                                $i = 1;
                                $total = 0;
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    echo "<tr>";
                                    echo "<td>" . $i . "</td>";
                                    echo "<td><a href=\"item.php?id=" . $row["item_id"] . "\">" . $row["name"] . "</a></td>";
                                    echo "<td><img class=\"image\" src=\"" . $row["image"] . "\"></td>";
                                    echo "<td>P" . $row["price"] . "</td>";
                                    echo "<td><input type=\"number\" name=\"qty[]\" value=\"" . $row["qty"] .  "\"><input type=\"hidden\" name=\"item_id[]\" value=\"" . $row["item_id"] . "\"></td>";
                                    echo "<td><a class=\"button medium\" href=\"removeFromCart.php?item=" . $row["item_id"] . "\">Remove</td>";
                                    echo "</tr>";
                                    $i++;
                                    $total += $row["price"] * $row["qty"];
                                }
                                echo "</table><br>";
                                echo "<div>Total Amount: P" . number_format($total, 2) . "</div><br>";
                                echo "<input type=\"submit\" class=\"button large\" value=\"Update Cart\"> <a class=\"button large\" href=\"store.php\">Back to Store</a>";
                                echo "</form>";
                            ?>
                            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="business" value="oathofrose-facilitator@gmail.com">
                                <input type="hidden" name="cmd" value="_xclick">

                                <input type="hidden" name="item_name" value="Payment">
                                <input type="hidden" name="amount" value="<?php echo $total; ?>">
                                <input type="hidden" name="currency_code" value="PHP">
                                <input type="hidden" name="return" value="http://localhost:81/secudev/cases/checkout.php?user=<?php echo $_SESSION["user"]; ?>">

                                <input type="image" name="submit" border="0"
                                src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
                                alt="PayPal - The safer, easier way to pay online">
                                <img alt="" border="0" width="1" height="1"
                                src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
                            </form>

                            <?php

                            }
                            else
                            {
                                echo "No items in cart!";
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