<?php
    mysql_connect("localhost", "root", "") or die("Couldn't select database.");
    mysql_select_db("calendar") or die("Couldn't select database.");
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql1="select * from user where email='".$username."'" ;
    $chkEvents_res = mysql_query($sql1) or die(mysql_error($mysql));
    $num_events= mysql_num_rows($chkEvents_res);
    if($num_events!=0){
        echo "Username already exists";
    } else {
        $sql="INSERT INTO user (email,first_name,last_name, password)
              VALUES ('$username', '$fname','$lname','$password') ";
        if (mysql_query($sql)) {
            echo "User record added";
        }
    }
 // mysql_close($mysql);
?>
