<?php

    //
    session_start();
    if(isset($_SESSION['id'])) {
        echo '<p> Welcome '.$_SESSION['email'].' <a href="/bwp/logout.php">Logout</a></p>';
    } else {
        header("Location: /bwp/login.php");
        exit;
    }

    //Connect to MySQL
    $conn = mysqli_connect("localhost", "root", "", "blog");
    if (! $conn) {
        echo mysqli_connect_error();
        exit;
    }

    //Select all users
    $query = "SELECT * FROM `users`";

    // Search by the name or the email
    if (isset($_GET['search'])) {
        $search = mysqli_escape_string($conn, $_GET['search']);
        $query .= " WHERE `users`.`name` LIKE '%".$search."%' OR `users`.`email` LIKE '%".$search."%'";
    }

    //Execute the query
    $result = mysqli_query($conn, $query);
?>

<html>
    <head>
        <title>Admin :: List Users</title>
    </head>
    <body>
        <h1>List Users</h1>
        <form method="GET">
            <input type="text" name="search" placeholder="Enter {Name} or {Email} to search" />
            <input type="submit" value="search">
        </form>

        <!-- Display a table containing all users -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Loop on the rowset
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?php if($row['avatar']) { ?><img src="../../uploads/<?= $row['name'] . "." . $row['avatar'] ?>" style="width: 100px; height: 100px"/><?php } else {  ?> <img src="../../uploads/noimage.jpg?>" style="width: 100px; height: 100px"/> <?php } ?></td>
                    <td><?= ($row['admin']) ? 'Yes' : 'No' ?></td>
                    <td><a href="edit.php?id=<?=$row['id'] ?>">Edit</a> | <a href="delete.php?id=<?= $row['id'] ?>">Delete</a></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: center"><?= mysqli_num_rows($result) ?> Users</td>
                    <td colspan="3" style="text-align: center"><a href="add.php">Add User</a></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>

<?php
    //Close the Connection
    mysqli_free_result($result);
    mysqli_close($conn);
?>