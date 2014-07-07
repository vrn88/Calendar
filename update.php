<?php
    session_start();
    $date     = getdate();
    $year     = $date['year'];
    $username = $_SESSION['userid'];
    $month    = $date['mon'];
    //$month=$_SESSION['year'];
    $day      = $date['mday'];
    $sql = mysql_connect("localhost", "root", "") or die("Couldn't select database.");
    mysql_select_db("calendar") or die("Couldn't select database.");
    $sql1 = "update events set flag='" . $year . "' where user_id='" . $username . "' and dayofmonth(event_date)='" .             $day . "' and month(event_date)='" . $month . "'";
    echo "hii";
    mysql_query($sql1) or die(mysql_error($mysql));
    mysql_close($sql);
?>