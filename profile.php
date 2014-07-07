<?php 
    session_start();
    $nowArray = getdate();
    if ( isset($_POST['currtime']) || (!isset($_SESSION['month'])) || (!isset($_SESSION['year'])) ){
        $_SESSION['month'] = $nowArray['mon'];
        $_SESSION['year'] = $nowArray['year'];
    }
    if((isset($_POST['month'])) && (isset($_POST['year']))){
        $_SESSION['month']  = $_POST['month'];
        $_SESSION['year']   = $_POST['year'];
    }
    if(isset($_POST['formleft'])){
        $_SESSION['month'] -=1;
        if($_SESSION['month']<1){
            $_SESSION['month']=12;
            $_SESSION['year']-=1;
        }
    } else if(isset($_POST['formright'])){
        $_SESSION['month'] +=1;
        if($_SESSION['month']>12){
            $_SESSION['month']=1;
            $_SESSION['year']+=1;
        }
    }
?>

<html>
    <head>
        <title>Calendar</title>
        <link href="css\style1.css" rel="stylesheet">
        <script src="js\jquery.js"></script>
        <script src="js\app.js"></script>
    </head>
    <body>
        <div class="maincontainer">
            <div class="topbar" >
                <div class="welcome">
<?php echo "Welcome ". $_SESSION['username']; ?>
                </div>
                <div align="right" >
                    <a href="logout.php" style="color: #ffffff">LOGOUT</a>
                </div>
                <div class="logo">
<?php
    echo 'Calendar - '. $_SESSION['year'];
?>
                </div>
            </div>
            <div class="innercontainer">
                <div class="monthdisplay">
                    <div class="navleft">
                        <form method="post" action=" ">
                            <input type="image" src="images\left.png" alt="submit" class="navleft">
                        </form>
                    </div>
                    <div class="navright">
                        <form method="post" action=" ">
                            <input type="image" src="images\right.png" alt="submit" class="navright">
                        </form>
                    </div>
<?php
    $months = Array("January", "February", "March", "April", "May", "June", "July", "August","September",
                       "October", "November", "December");
    echo $months[$_SESSION['month']-1];
?>
                </div>
                <div class= calcontainer>
<?php
    $user=$_SESSION['userid'];
    $mysql = mysql_connect("localhost", "root", "");
    $month_events = array();
    $ev = array();
    mysql_select_db("calendar", $mysql) or die(mysql_error());
    $chkEvents_sql = "SELECT dayofmonth(event_date) as dayofmonth,flag FROM events where user_id='".$user."' AND  month(event_date)='".$_SESSION['month']."' AND
                     (year(event_date)='".$_SESSION['year']."' OR event_type = 'Birthday' OR event_type = 'Anniversary')";
    $chkEvents_res = mysql_query($chkEvents_sql, $mysql) or die(mysql_error($mysql));
    $num_events= mysql_num_rows($chkEvents_res);
    while($row = mysql_fetch_array($chkEvents_res)){
        $ev[] = $row['dayofmonth']  ;
        if($row['dayofmonth']==$nowArray['mday']){
            $flag=$row['flag'];
        }
    }
    mysql_close($mysql);
    for($i=0; $i<=31; $i++){
        $month_events[$i] = 0;
    }
    $i=0;
    while($i<$num_events){
        $month_events[$ev[$i]] += 1;
        $i++;
    }
/* draw table */
    $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
/* table headings */
    $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
/* days and weeks vars now ... */
    $running_day = date('w',mktime(0,0,0,$_SESSION['month'],1,$_SESSION['year']));
    $days_in_month = date('t',mktime(0,0,0,$_SESSION['month'],1,$_SESSION['year']));
    $days_in_this_week = 1;
    $day_counter = 0;
    $rows=0;
    $dates_array = array();
/* row for week one */
    $calendar.= '<tr class="calendar-row">';
/* print "blank" days until the first of the current week */
    for($x = 0; $x < $running_day; $x++){
        $calendar.= '<td class="calendar-day-np"> </td>';
        $days_in_this_week++;
    }
/* keep going with days.... */
    for($list_day = 1; $list_day <= $days_in_month; $list_day++){
        if($month_events[$list_day] > 0){
            $calendar.= '<td class="calendar-eventday" style="border-bottom:1px solid #4c66a4; border-right:1px solid #4c66a4;">';
            if($list_day == $nowArray['mday'] && $flag!=$nowArray['year'] &&$_SESSION['year']==$nowArray['year']){
                $calendar.= '<div class="currentday-event">'.$month_events[$list_day].'</div>';
            }else {
                $calendar.= '<div class="day-event">'.$month_events[$list_day].'</div>';
            }
        }else {
            $calendar.= '<td class="calendar-day">';
        }
/* add in the day number */
        $calendar.= '<div class="day-number">'.$list_day.'</div>';
        $calendar.= '</td>';
        if($running_day == 6){
            $calendar.= '</tr>';$rows++;
            if(($day_counter+1) != $days_in_month){
                $calendar.= '<tr class="calendar-row">';
            }
            $running_day = -1;
            $days_in_this_week = 0;
        }
        $days_in_this_week++; $running_day++; $day_counter++;
    }

/* finish the rest of the days in the week */
    if($days_in_this_week < 8){
        for($x = 1; $x <= (8 - $days_in_this_week); $x++){
            $calendar.= '<td class="calendar-day-np"> </td>';
        }
    }

/* final row */
    $calendar.= '</tr>';$rows++;
    if($rows<6){
        $calendar.= '<tr class="calendar-row">';
        for($x=1; $x<8; $x++) {
            $calendar.= '<td class="calendar-day-np"> </td>';
        }
            $calendar.= '</tr>';
        }

/* end the table */
    $calendar.= '</table>';
    echo $calendar;
?>
                </div>
                <div class= "bottombar">
                    <div id="cal-browse">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <select name="month">
<?php
    $months = Array("January", "February", "March", "April", "May", "June", 
                "July", "August","September","October", "November", "December");
    for ($x=1; $x<=count($months); $x++){
        echo "<option value=\"$x\"";
        if ($x == $_SESSION['month']){
            echo " selected";
        }
        echo ">".$months[$x-1]."</option>";
    }
?>
                            </select>
                            <select name="year" id="yearcalbrowser">
<?php
    for ($x=1970; $x<=2999; $x++){
        echo "<option";
        if ($x == $_SESSION['year']){
            echo " selected";
        }
        echo ">$x</option>";
    }       
?>
                            </select>
                            <input type="submit" name="submit" value="Go!"/>
                        </form>
                    </div>
                    <div class= "timer ctime" >
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>