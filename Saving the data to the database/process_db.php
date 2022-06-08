<?php

    // Validation 
    $error_fields = array();

    // law mawgod we mosh empty kda mafish error 3aks kda yb2a fe error
    if (! (isset($_POST['name']) && !empty($_POST['name']))) {
        $error_fields[] = "name";
    }

    // law mawgod we maktob bel tare2a el sa7e7a lel email kda mafish error 3aks kda fe error
    if (! (isset($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL))) {
        $error_fields[] = "email";
    }

    // law mawgod we 3add 7rofo akbar mn 5 kda mafish error 3aks kda error
    if (! (isset($_POST['password']) && strlen($_POST['password']) > 5)) {
        $error_fields[] = "password";
    }

    // law fe errors fel $error_fields array ha7wlhom le string we a3mlhom redirect 3al link da we awa2f el script
    if ($error_fields) {
        header("Location: form.php?error_fields=".implode(",", $error_fields));
        exit;
    }

    // Connect to DB
    $conn = mysqli_connect("localhost", "root", "", "blog");

    // Check if there's an error print it and stop script execution
    if (! $conn) {
        echo mysqli_connect_error();
        exit;
    }

    // Escape any special characters to avoid SQL Injection
    $name = mysqli_escape_string($conn, $_POST['name']);
    $email = mysqli_escape_string($conn, $_POST['email']);
    $password = mysqli_escape_string($conn, $_POST['password']);

    // Define the Query whatever it is
    $query = "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('".$name."', '".$email."', '".$password."')";

    // check whether the query done proberly or there's an error
    if (mysqli_query($conn, $query)) {
        echo "Thank you?, your information has been saved";
    } else {
        // echo $query;
        echo mysqli_errno($conn);
    }
    
    // Close the connection
    mysqli_close($conn);