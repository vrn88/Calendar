<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("Location:profile.php");
    }
    if ((!isset($_POST['month'])) || (!isset($_POST['year']))){
        $nowArray = getdate();
        $month = $nowArray['mon'];
        $year = $nowArray['year'];
    }else {
        $month = $_POST['month'];
        $year  = $_POST['year'];
    }
?>


<html>
    <head>
        <title>Calendar</title>
        <link href="css\style.css" rel="stylesheet">
        <script src="js\jquery.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="//code.jquery.com/jquery-1.9.1.js"></script>
        <script src="js\app.js"></script>
    </head>
    <body>
        <div class="maincontainer">
            <div class="topbar" >
                <div class="logo">
                    <?php
                       echo 'Calendar - '. $year;
                    ?>
                </div>
                <div class="login">
                    <form id="login_form" action="login_check1.php"  method="post">
                        <table cellspacing="0" style="margin-top:25px; margin-right:30px;">
                            <tr>
                                <td class="label1"> <label for="email1" style="margin-left:3px;" >Email</label> </td>
                                <td class="label1"> <label for="pass1" style="margin-left:3px;">Password</label> </td>
                            </tr>
                            <tr>
                                <td> <input type="text" class="inputtext" name="email" id="email" required/> </td>
                                <td> <input type="password" class="inputtext" name="pass" id="pass" required/> </td>
                                <td> <label class="uiButton" id="loginbutton">
                                    <input value="Log In" tabindex="4" type="submit" id="login_button" /> </label> </td>
                            </tr>
                            <tr>
                                <td  style="color:#98a9ca;font-weight:normal">
                                    <input id="chech_box" type="checkbox" value="1" tabindex="3"/>
                                    <label>Keep me logged in</label> </td>
                                <td id="result" class="error"> </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <div class="leftcontainer">
                <div id="cal-browse">
                    <h3>Select a Month/Year</h3>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <select name="month">
<?php
    $months = Array("January", "February", "March", "April", "May", "June", "July",
              "August","September","October", "November","December");
    for ($x=1; $x<=count($months); $x++){
        echo "<option value=\"$x\"";
        if ($x == $month){
            echo " selected";
        }
        echo ">".$months[$x-1]."</option>";
    }
?>
                        </select>
                        <select name="year">
<?php
    for ($x=1970; $x<=2999; $x++){
        echo "<option";
        if ($x == $year){
            echo " selected";
        }
        echo ">$x</option>";
    }
?>
                        </select>
                        <input type="submit" name="submit" value="Go!">
                    </form>
                </div>
                <div id="signup">
                    <form method="post" action="signup_check.php" id="signup_form">
                        <h1>Sign Up</h1>
                        <div class="user">
                            <input type="text" id="name1" placeholder="First Name" name="firstname" required/>
                            <input type="text" id="name2" placeholder="Last Name" name="lastname" required/>
                            <input type="text" id="email1"  class="field" placeholder="Email" name="email" required/>
                            <input type="password" class="field" placeholder="Password" name="password" id="password" required/>
                            <input type="password" class="field" placeholder="Confirm Password" name="cpassword" id="cpassword" required/>
                            <input type="submit" id="signupbutton" value="Sign Up"/>
                            <div id="result1" class="error"></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="rightcontainer">
                <div class= calcontainer>
<?php
/* draw table */
    $mysql = mysql_connect("localhost", "root", "");
    $month_events = array();
    $ev = array();
    mysql_select_db("calendar", $mysql) or die(mysql_error());
    $chkEvents_sql = "SELECT dayofmonth(event_date) FROM international WHERE month(event_date)='".$month."' ORDER BY event_date";
    $chkEvents_res = mysql_query($chkEvents_sql, $mysql) or die(mysql_error($mysql));
    $num_events= mysql_num_rows($chkEvents_res);
    $row=NULL;
    while($row = mysql_fetch_array($chkEvents_res)){
        $ev[] = $row[0]  ;
    }
    for($i=0; $i<=31; $i++){
        $month_events[$i] = 0;
    }
    $i=0;
    while($i<$num_events){
        $month_events[$ev[$i]] += 1;
        $i++;
    }
    $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

/* table headings */
    $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

/* days and weeks vars now ... */
    $running_day = date('w',mktime(0,0,0,$month,1,$year));
    $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();
    $rows=0;

/* row for week one */
    $calendar.= '<tr class="calendar-row">';

/* print "blank" days until the first of the current week */
    for($x = 0; $x < $running_day; $x++){
        $calendar.= '<td class="calendar-day-np"> </td>';
        $days_in_this_week++;
    }
/* keep going with days.... */
    for($list_day = 1; $list_day <= $days_in_month; $list_day++){
        if($month_events[$list_day]>0){
            $calendar.= '<td class="calendar-eventday" style="border-bottom:1px solid #4c66a4; border-right:1px solid #4c66a4;">';
            $query =mysql_query( "SELECT event_type as descr FROM international WHERE dayofmonth(event_date)='".$list_day."'");
            $numrows = mysql_num_rows($query);
            if ($numrows!=0){
                while ($row = mysql_fetch_assoc($query)){
                    $calendar.= '<div class="day-event">'.$row['descr'].'</div>';
                }
            }
        } else {
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
        for($x=1; $x<8; $x++)
        {
            $calendar.= '<td class="calendar-day-np"> </td>';
        }
        $calendar.= '</tr>';
    }

/* end the table */
    $calendar.= '</table>';
    echo $calendar;

?>
                </div>
                <div class= "timer">

                </div>
            </div>
        </div>
    </body>
</html>