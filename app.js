
var currentDate;

function updateClock() {
    var currentTime = new Date();
    var currentHours = currentTime.getHours();
    var currentMinutes = currentTime.getMinutes();
    var currentSeconds = currentTime.getSeconds();
    var currentMonth = currentTime.getMonth();
    currentDate = currentTime.getDate();
    var currentYear = currentTime.getFullYear();
    var days = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    var months = new Array("January", "February", "March", "April", "May", "June",
                 "July", "August", "September","October", "November", "December");
    var weekday = days[currentTime.getDay()];
    // Pad monthes, minutes and seconds with leading zeros, if required
    //currentMonth=(currentMonth < 9 ? "0" : "" ) + (currentMonth+1);
    cMonth = months[currentMonth];
    currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
    currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
    // Choose either "AM" or "PM" as appropriate
    var timeOfDay = (currentHours < 12) ? "AM" : "PM";
    // Convert the hours component to 12-hour format if needed
    currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
    // Convert an hours component of "0" to "12"
    currentHours = (currentHours == 0) ? 12 : currentHours;
    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay + ", "
                            + weekday + ", " + cMonth + " " + currentDate + ", " + currentYear;
    $(".timer").text(currentTimeString);
}

// display the lightbox

function lightbox(insertContent, ajaxContentUrl) {
    // add lightbox/shadow <divf/>'s if not previously added
    if ($('#lightbox').size() == 0) {
        var theLightbox = $('<div id="lightbox"/>');
        var theShadow = $('<div id="lightbox-shadow"/>');
        $(theShadow).click(function (e) {
            closeLightbox();
        });
        $('body').append(theShadow);
        $('body').append(theLightbox);
    }
    // remove any previously added content
    $('#lightbox').empty();
    // insert HTML content
    if (insertContent != null) {
        $('#lightbox').append(insertContent);
    }
    // insert AJAX content
    if (ajaxContentUrl != null) {
        // temporarily add a "Loading..." message in the lightbox
        $('#lightbox').append('<p class="loading">Loading...</p>');
        // request AJAX content
        $.ajax({
            type: 'GET',
            url: ajaxContentUrl,
            success: function (data) {
                // remove "Loading..." message and append AJAX content
                $('#lightbox').empty();
                $('#lightbox').append(data);
            },
            error: function () {
                alert('AJAX Failure!');
            }
        });
    }
    // move the lightbox to the current window top + 100px
    $('#lightbox').css('top', $(window).scrollTop() + 100 + 'px');
    // display the lightbox
    $('#lightbox').show();
    $('#lightbox-shadow').show();
}
// close the lightbox

function closeLightbox() {
    // hide lightbox and shadow <div/>'s
    $('#lightbox').hide();
    $('#lightbox-shadow').hide();
    location.reload(true);
    // remove contents of lightbox in case a video or other content is actively playing
    $('#lightbox').empty();
}
var event_registration_form = "<div id=\"registerevent\">" + "<div id=\"header\">Events of the Day</div>" + "<div class=\"dayevents\">" + " </div>" + "<form method=\"post\" action=\"addevent.php \" id=\"eventaddform\" style=\" margin:0 auto\">" + "<div class=\"eventinfo\"> " + "<div class=\"eventtime\"> " + "<label>Event Time:</label><input type=\"time\" name=\"time\" id=\"t1\" style=\"width:155px;\">" + "</div>       " + "<div class=\"eventtype1\">" + "<label> Event Type:</label><select id=\"se1\" name=\"type\"> <option>Anniversary </option>" + "<option>Appointment with Doctor</option> <option>Birthday</option> <option>Marriage</option>" + "<option>Meeting </option>  <option>Others</option>" + "</select>" + "</div> " + "<div class=\"eventplace\">  " + " <label>Event Place:</label><input type=\"text\" id=\"place\" placeholder=\"Place\" name=\"place\" /> " + "</div>          " + "<div class=\"eventdesc\" ><label>Description:</label><textarea id=\"ta1\" name=\"description\" row=\"4\"></textarea></div>" + "<div id=\"addevent\" >" + "<input type=\"submit\" id=\"submitbutton\" value=\"Add Event\"/>" +
//"<div id=\"addsuccess\"><img src=\"eventadd.png\" height=\"30px\" width=\"30px\"><\div>"+
"</div>" + "</div>" + " </form>" + "<div id=\"addbar\"> Add Events</div>" + "<div id=\"footer\" ></div>" + "</div>";

$(document).ready(function () {

    $('.navleft').click(function () {

        var send = $.post("profile.php", {
            formleft: '1'
        });
        send.done(function (data) {
            $('logo').text('hi');
        });
        ('.calendar').hide();
        $('.calendar').slideDown('fast');
    });

    $('.navright').click(function () {
        var send = $.post("profile.php", {
            formright: '1'
        });
        send.done(function (data) {
            $('logo').text('hi');
        });
        $('.calendar').hide();
        $('.calendar').slideToggle('slow');
    });

    $('.day-number').click(function () {
        // loader
        // ajax request
        var day = $(this).text();
        var month = $('.monthdisplay').text();
        var year = $('logo').text();
        var date = month + " " + day;
        if (day == currentDate) {
            var send = $.post("update.php");
            send.done(function (data) {
            });
        }
        var send = $.post("showevent.php", {
            day: day
        });
        send.done(function (data) {
            $('.dayevents').append(data);
            $('.del').on('click', function (event) {
                event.preventDefault();
                var hidden = $(this).find("input[name='eid']").val();
                var send = $.post("delete.php", {
                    eid: hidden
                });
                send.done(function (data) { });
                $(this).parent().parent().remove();
            });
        });
        // result
        // loader hide
        lightbox(event_registration_form);
        $('#footer').text(date);
        $('.eventinfo').hide();
        $('#addbar').click(function () {
            $('.eventinfo').slideToggle('fast');
            // $('#addsuccess').hide();
            $(this).hide();
        });

        $('#footer').click(function () {
            $('.eventinfo').slideUp('fast');
            $('#addbar').show();
        });

        $('#eventaddform').submit(function (event) {
            event.preventDefault();
            var event_type = $(this).find("select[name='type']").val();
            var event_time = $(this).find("input[name='time']").val();
            var event_descr = $('#ta1').val();
            var event_place = $(this).find("input[name='place']").val();
            //  var enent_id=  $(this).find("input[name='place']").val();
            var table = "<div class=\"showevent\" ><div class=\"eventheading\"><div class=\"eventtype\"> Event :" + event_type + "</div>";
            table += "<div  id=\"del\" class=\"del\"><form action=\"\" method=\"post\" class=\"deleteform\" ><input type=\"submit\" value=\"\"" +
                     "class=\"delbutton\"></input> <input type=\"hidden\" name=\"eid\" value=\"" + "\" /></form></div></div>";
            table += "<div class=\"eventdetail\"> Time: " + event_time + " Place: " + event_place + "Description: " + event_descr + "</div></div>";

            send1 = $.post("addevent.php", {
                day: day,
                place: event_place,
                type: event_type,
                description: event_descr
            });
            send1.done(function (data) {
                $('.dayevents').append(table);
            });
            $('#eventaddform').each(function () {
                this.reset();
            });
            $('.eventinfo').show();

        });
    });
});

function changecolor() {
    $('.currentday-event').fadeToggle(1000);
    $('.currentday-event').css('background', '#be4b49');
}

$(document).ready(function () {

    $(document).ready(function () {
        setInterval('updateClock()', 1000);
        setInterval('changecolor()', 1000);
    });

    $("#login_form").submit(function (event) {
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = $(this);
        var user = $form.find("input[name='email']").val();
        var password = $form.find("input[name='pass']").val();
        var url = $form.attr("action");
        //checking the input fields
        $("#result").text('');
        var posting = $.post(url, {
            username: user,
            password: password
        });
        posting.done(function (data) {
            var i = data.localeCompare("Wrong username or password");
            if (i == 0) {
                $('#login_form').each(function () {
                    this.reset();
                });
                $("#result").text(data);
            } else window.location.assign("profile.php");
        });
    });
});
$(document).ready(function () {
    $("#signupbutton").click(function (event) {
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = $(this);
        var fname = $form.find("input[name='firstname']").val();
        var lname = $form.find("input[name='lastname']").val();
        var user = $form.find("input[name='email']").val();
        var password = $form.find("input[name='password']").val();
        var cpassword = $form.find("input[name='cpassword']").val();
        var url = $form.attr("action");
        var j = cpassword.localeCompare(password);
        //checking the input fields
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!filter.test(user)) {
            var alertmessage = "Invalid email-id";
            $("#result1").text(alertmessage);
            $("#password").reset();
            $("#cpassword").reset();
        } else if (j != 0) {
            var alertmessage = "Password does not match";
            $("#result1").text(alertmessage);
            $("#password").reset();
            $("#cpassword").reset();
            passwordflag = 0;
        } else {
            $("#result1").text('');
            document.getElementById("name1").value = "";
            document.getElementById("name2").value = "";
            document.getElementById("email1").value = "";
            document.getElementById("password").value = "";
            document.getElementById("cpassword").value = "";
            // Send the data using post
            var posting = $.post(url, {
                fname: fname,
                lname: lname,
                username: user,
                password: password
            });
            // Put the results in a div
            posting.done(function (data) {
                $("#result1").html(data);
                $('#signup_form').each(function () {
                    this.reset();
                });

            });

        }


    });


});