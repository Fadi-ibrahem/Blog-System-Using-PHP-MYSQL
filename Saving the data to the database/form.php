<?php

    //check for errors
    $errors_arr = array();
    if (isset($_GET['error_fields'])) {
        $errors_arr = explode(",", $_GET['error_fields']);
    }

?>

<html>
    <body>
        <form method="post" action="process_db.php">
            <label for="name">Name</label>
            <input type="text" name="name" id="name"><?php if (in_array("name", $errors_arr)) echo "* please enter Your name"; ?> <br />
            <label for="email">Email</label>
            <input type="email" name="email" id="email"/><?php if (in_array("email", $errors_arr)) echo "* please enter a calid email"; ?> <br/>
            <labe for="password">Password</labe>
            <input type="password" name="password" id="password"/><?php if (in_array("password", $errors_arr)) echo "* please enter a password not less than 6 characters"; ?> <br/>
            <label for="gender">Gender</label>
            <input type="radio" name="gender" value="male"/>Male
            <input type="radio" name="gender" value="male"/>Female <br/>
            <input type="submit" name="submit" value="Register"/>
        </form>
    </body>
</html>