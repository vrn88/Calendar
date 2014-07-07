<?php
    session_start();
    $mysql = mysql_connect("localhost", "root", "");
    mysql_select_db("calendar", $mysql) or die(mysql_error());
    $date        = $_SESSION['year'] . "-" . $_SESSION['month'] . "-" . $_POST['day'];
    $time        = $_POST['time'];
    $place       = $_POST['place'];
    $type        = $_POST['type'];
    $user        = $_SESSION['userid'];
    $description = $_POST['description'];
    $addEvent    = "INSERT INTO events(user_id,event_date,event_time,event_place,event_type,event_description)
                  VALUES('$user','$date','$time', '$place', '$type', '$description')";
    $chkEvents_res = mysql_query($addEvent, $mysql) or die(mysql_error($mysql));
    $selecteid="select max(event_id) as eid from events where user_id='".$user."'";
    $chkEvents_res = mysql_query($selecteid, $mysql) or die(mysql_error($mysql));
    $num_events = mysql_num_rows($chkEvents_res);
    $row = mysql_fetch_array($chkEvents_res);
    $maxeid=$row['eid'];
    echo $maxeid;
    mysql_close($mysql);
    //header('Location: profile.php');
?>