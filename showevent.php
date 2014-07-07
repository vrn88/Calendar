<?php
    session_start();
    $day   = $_POST['day'];
    $month = $_SESSION['month'];
    $year  = $_SESSION['year'];
    $user  = $_SESSION['userid'];
    $ev    = array();
    $mysql = mysql_connect("localhost", "root", "");
    mysql_select_db("calendar", $mysql) or die(mysql_error());
    $chkEvents_sql = "SELECT * FROM events WHERE user_id='" . $user . "' AND month(event_date)='" . $month . "' AND dayofmonth(event_date)='" . $day . "'
                     AND(year(event_date)='" . $year . "' OR event_type = 'Birthday' OR event_type = 'Anniversary') ORDER BY event_time";
    $chkEvents_res = mysql_query($chkEvents_sql, $mysql) or die(mysql_error($mysql));
    $num_events = mysql_num_rows($chkEvents_res);
    $table      = "";
    while ($row = mysql_fetch_array($chkEvents_res)) {
        $table .= "<div class=\"showevent\" ><div class=\"eventheading\"><div class=\"eventtype\"> Event :" . $row['event_type'] . "</div>";
        $table .= "<div  id=\"del\" class=\"del\"><form action=\"\" method=\"post\" class=\"deleteform\" ><input type=\"submit\" value=\"\" class=\"delbutton\">"                   ."</input> <input type=\"hidden\" name=\"eid\" value=\"" . $row['event_id'] . "\" /></form></div></div>";
        $table .= "<div class=\"eventdetail\"> Time: " . $row['event_time'] . " Place: " . $row['event_place'] . "Description: " . $row['event_description'] .
                  "</div></div>";
    }
    echo $table;
    mysql_close($mysql);
?>