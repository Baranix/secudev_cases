<?php
    session_start();

    include 'header.php';

    $id = $_GET["id"];
    $id = $purifier->purify($id);
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="content">
        <?php
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
                    if (mysqli_num_rows($result) > 0)
                    {
                        while($row = mysqli_fetch_assoc($result))
                        {
                            $is_superuser = $row["is_superuser"];
                        }

                        $q = "SELECT name, price, description, image
                            FROM item
                            WHERE item.id =" . $id;

                        $result = mysqli_query($con, $q);

                        if (mysqli_num_rows($result) > 0)
                        {
                            while($row = mysqli_fetch_assoc($result))
                            {
                                $name = $row["name"];
                                $price = $row["price"];
                                $desc = $row["description"];
                                $image = $row["image"];

                            }
                        }
                    }
                    else
                    {
                        echo "An error has occurred. Please <a href=\"login.html\">log in</a> again.<br>";
                    }
                }
            }
            else
            {
                echo "Please <a href=\"login.html\">log in</a> again.<br>";
            }
        ?>
            <div id="viewItem">
                <div class="title"><?php echo $name; ?></div><br>
                <div><img class="image" src="<?php echo $image; ?>"></div><br>
                <div class="desc"><?php echo $desc; ?></div><br>
                <div class="price">P<?php echo $price; ?></div><br>
                <div class="addToCart"><a class="button medium" href="addToCart.php?item=<?php echo $id; ?>">Add to Cart</a></div><br>
                <br>
                <?php
                    if(!isset($is_superuser) || $is_superuser)
                    {
                ?>
                    <div class="admin">
                    <a href='editItem.php?id=<?php echo $id; ?>' class='button medium'>Edit</a>
                    <a href='deleteItem.php?id=<?php echo $id; ?>' class='button medium'>Delete</a>
                    </div>
                <?php
                    }

                ?>

            </div>

        </div>

    </body>
</html>