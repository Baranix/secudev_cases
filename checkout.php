<?php
    session_start();

    include 'header.php';

    $id = $_SESSION["user"];
    //$total = $_POST["payment_gross"];
    $tx = $_GET["tx"];

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
            // request payment details from paypal using tx id
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://www.sandbox.paypal.com/cgi-bin/webscr");
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                'cmd' => '_notify-synch',
                'tx' => $tx,
                'at' => '-pChIE70JdobDMnbhzMyO5NOESfp3PyYispLEgDREdely2lic6oB7MfpS58'
            )));
            $result  = curl_exec ($curl);

            // parse response string and extract payment status
            $token = strtok($result, "\n");
            while ( $token !== false )
            {
                $token = strtok("\n");

                $params = explode('=', $token);
                if ( $params[0] == 'payment_status' )
                {
                    $status = $params[1];
                }
            }

            // 1: Completed, 2: Pending, 3: Cancelled
            if ( $status == 'Completed' )
            {
                $status_id = 1;
            }
            else if ( $status == 'Pending' )
            {
                $status_id = 2;
            }
            else if ( $status == 'Cancelled' )
            {
                $status_id = 3;
            }

            // compute total amount of transaction
            $q = "SELECT price, qty FROM cart, item WHERE cart.paid=false AND cart.item_id=item.id AND cart.user_id=" . $id;
            $result = mysqli_query($con, $q);
            $total = 0;
            if( mysqli_num_rows($result) > 0 )
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    $total += $row["price"] * $row["qty"];
                }
            }

            // save transaction
            $q = "INSERT INTO transaction (user_id, amount, status) VALUES (" . $id . ", " . $total . ", " . $status_id . ");";
            mysqli_query($con,$q);

            $trans_id = mysqli_insert_id($con);

            // update cart based on payment status
            if ( $status_id == 1 )
            {
                $q = "UPDATE cart SET paid=true, transaction_id=" . $trans_id . " WHERE paid=false AND user_id=" . $id;
            }
            else if ( $status_id == 2 )
            {
                $q = "UPDATE cart SET transaction_id=" . $trans_id . " WHERE paid=false AND user_id=" . $id;
            }

            mysqli_query($con,$q);
        }
    }

    mysqli_close($con);

    redirect("store.php");

?>
