<?php
    $error_fields = array();

    //Connect to DB
    $conn = mysqli_connect("localhost", "root", "", "blog");
    if (!$conn) {
        echo mysqli_connect_error();
        exit;
    }

    //Select the user
    //edit.php?id=1 => $_GET['id']
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    $select = "SELECT * FROM `users` WHERE `users`.`id`=" . $id . " LIMIT 1";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //Validation 
        if (! (isset($_POST['name']) && !empty($_POST['name']))) {
            $error_fields[] = "name";
        }
        if (! (isset($_POST['email']) && filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))) {
            $error_fields[] = "email";
        }
        if (! (isset($_POST['password']) && strlen($_POST['password']) > 5)) {
            $error_fields[] = "password";
        }

        if (! $error_fields) {

            //Escape any special characters to avoid SQL Injection
            // $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);    //3amltlo comment 3ashan ana already m3arfo fo2 fe mar7lt el select
            $name = mysqli_escape_string($conn, $_POST['name']);
            $email = mysqli_escape_string($conn, $_POST['email']);
            $password = (!empty($_POST['password'])) ? sha1($_POST['password']) : $row['password'];
            $admin = (isset($_POST['admin'])) ? 1 : 0 ;

            //Update the data
            $query = "UPDATE `users` SET `name` = '".$name."', `email` = '".$email."', `password` = '".$password."', `admin` = " . $admin . " WHERE `users`.`id` = ".$id;

            if (mysqli_query($conn, $query)) {
                header("Location: list.php");
                exit;
            } else {
                // echo $query
                echo mysqli_error($conn);
            }
        }
    }

    // Close the connection
    mysqli_free_result($result);
    mysqli_close($conn);
?>

<html>
    <head>
        <title>Admin :: Edit User</title>
    </head>
    <body>
    <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?= (isset($row['name'])) ? $row['name'] : '' ?>" /><?php if(in_array("name", $error_fields)) echo "* Please enter your name"; ?>
            <br/>

            <input type="hidden" name="id" id="id" value="<?= (isset($row['id'])) ? $row['id'] : '' ?>" />

            <label for="name">Email</label>
            <input type="email" name="email" id="email" value="<?= (isset($row['email'])) ? $row['email'] : '' ?>" /><?php if(in_array("email", $error_fields)) echo "* Please enter a valid email"; ?>
            <br/>

            <label for="name">Password</label>
            <input type="password" name="password" id="password"/><?php if(in_array("password", $error_fields)) echo "* Please enter a password not less than 6 characters"; ?>
            <br/>

            <input type="checkbox" name="admin" <?= (isset($row['admin']) && $row['admin'] == 1) ? 'checked' : '' ?> />Admin
            <br/>

            <input type="submit" name="submit" value="Edit User" />
        </form>
    </body>
</html>