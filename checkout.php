<?php
    session_start();

    include 'header.php';

    $id = $_SESSION["user"];
    $total = $_POST["payment_gross"];

    if(isset($_SESSION["user"]))
    {
        include 'connect.php';
        $con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
        if( mysqli_connect_errno() )
        {
            echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
        }
        else
        {
            $q = "INSERT INTO transaction (user_id, amount) VALUES (" . $id . ", " . $total . ");";
            mysqli_query($con,$q);

            $trans_id = mysqli_insert_id($con);

            $q = "UPDATE cart SET checkout=true, transaction_id=" . $trans_id . " WHERE checkout=false AND user_id=" . $id;
            mysqli_query($con,$q);
        }
    }

    mysqli_close($con);

    redirect("store.php");

?>