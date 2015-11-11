<?php
    session_start();

    include 'header.php';

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
            $q = "SELECT is_superuser FROM user WHERE id=" . $_SESSION["user"];

            $result = mysqli_query($con, $q);

            if( mysqli_num_rows($result) == 0 )
            {
                mysqli_close($con);
                redirect("login.html");
            }
            else
            {
                $row = mysqli_fetch_assoc($result);
                $superuser = $row["is_superuser"];
            }
        }
    }
    else
    {
        mysqli_close($con);
        redirect("login.html");
    }


    if ($superuser)
    {
        $dir = "img/";
        $file_name = basename($_FILES["image"]["name"]);
        $image = $dir . $file_name;
        $file_type = pathinfo($image, PATHINFO_EXTENSION);

        $id = $_POST["id"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $desc = $_POST["desc"];

        if(isset($_POST["submit"]))
        {
            if(trim($file_name) != '')
            {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false)
                {
                    move_uploaded_file($_FILES["image"]["tmp_name"], $image);

                    $q = "UPDATE item SET name='" . $name . "', price=" . $price . ", description='" . $desc. "', image='" . $image . "' WHERE id=". (int) $id;
                    mysqli_query($con,$q);

                    mysqli_close($con);

                    redirect("store.php");
                }
                else
                {
                    redirect("store.php");
                }
            }
            else
            {
                $q = "UPDATE item SET name='" . $name . "', price=" . $price . ", description='" . $desc. "' WHERE id=". (int) $id;
                mysqli_query($con,$q);

                mysqli_close($con);

                redirect("store.php");
            }
        }
    }
    else
    {
        redirect("store.php");
    }
?>