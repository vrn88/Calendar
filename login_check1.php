<?php
    $mysql = mysql_connect("localhost", "root", "") or die("Couldn't select database.");
    mysql_select_db("calendar") or die("Couldn't select database.");
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql      = "SELECT * FROM user WHERE email = '$username' AND password = '$password' ";
    $result = mysql_query($sql) or die(mysql_error());
    $numrows = mysql_num_rows($result);
    if ($numrows > 0) {
        $row = mysql_fetch_assoc($result);
        session_start();
        $_SESSION['username'] = $row['first_name'];
        $_SESSION['userid']   = $row['email'];
    } else {
        echo "Wrong username or password";
    }
    mysql_close($mysql);
?>