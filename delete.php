<?php
    session_start();
    $eid   = $_POST['eid'];
    $mysql = mysql_connect("localhost", "root", "");
    mysql_select_db("calendar", $mysql) or die(mysql_error());
    $delEvents_sql = "DELETE FROM events WHERE event_id='" . $eid . "'";
    mysql_query($delEvents_sql, $mysql) or die(mysql_error($mysql));
    mysql_close($mysql);
    header("location:profile.php");
?>