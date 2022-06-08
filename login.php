<?php

    // We will use it for storing the signed in user data
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Connect to DB
        $conn = mysqli_connect("localhost", "root", "", "blog");
        if (!$conn) {
            echo mysqli_connect_error();
            exit;
        }

        // Escape any special characters to avoid SQL Injection
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = sha1($_POST['password']);

        // Sha1 collison https://security.googleblog.com/2017/02/announcing-first-sha1-collision.html
        // password_has($password, PASSWORD_DEFAULT);
        // then check with password_verify()
        // The password column in db should be changed to char(60) if you decided to use password_verify

        //Select
        $query = "SELECT * FROM `users` WHERE `email` = '".$email."' and `password` = '".$password."' LIMIT 1 ";

        $result = mysqli_query($conn, $query);

        /**de ma3naha en law rge3le 7aga mn el database yb2a kda el user da already mawgod 3andi fel database fa
         * fe hasgl el byanat el rg3t de fe variable esmo $row we law mafish 7aga rg3t men el database yb2a kda
         * el user da msh 3andi ay fe 7aga 8alat, we bkda ab2a ana 3amlt check we assign fel $row variable fe 
         * 5atwa wa7da goa el paranthese bta3t el if.
         */
        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: admin/users/list.php");
            exit;
        } else {
            $error = 'Invalid email or password';
        }

        //Close the connection
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>

<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <?php if(isset($error)) echo $error; ?>
        <form method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : '' ?>" />
            <br />

            <label for="password">Password</label>
            <input type="password" name="password" id="password" />
            <br />

            <input type="submit" name="submit" value="Login">
        </form>
    </body>
</html>