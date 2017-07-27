var clicked = false;
var clickedItem = false;

var xhr = null;


$(document).ready(function() { 
    instantJump = window.location.hash.replace("#", ""); 
    if(instantJump) {
        clicked = true;
        searchBarClick();
        showDetails(instantJump);
    }
    $("#search").fadeIn(300);

    $("#search #bar").hover(function() {
        $("#search #bar").addClass('transition300');
        $("#search").addClass('transition300');
        if(clicked === false) {
            $("#search #bar").css('background-color', 'rgba(255, 255, 255, 0.6)');
            $("#search #bar").css('color', 'rgba(0, 0, 0, 0.4)');
        } else {
            $("#search #bar").css('background-color', 'rgba(255, 255, 255, 0)');
            $("#search #bar").css('color', 'rgba(0, 0, 0, 1)');
        }
    }, function() {
        if(clicked === false) {
            $("#search #bar").css('background-color', 'rgba(255, 255, 255, 0.6)');
            $("#search #bar").css('color', 'rgba(50, 50, 50, 0.2)');
        } else {
            $("#search #bar").css('background-color', 'rgba(255, 255, 255, 0)');
            if(clickedItem === false) {
                $("#search #bar").css('color', 'rgba(50, 50, 50, 1)');
            } else {
                $("#search #bar").css('color', 'rgba(50, 50, 50, 0.2)');
            }
        }
    }); 
    
    $("#search #bar").click(function() {
        clicked = true;
        searchBarClick();
    }); 
    
    $("#search #bar").focusout(function() {
        $("#search #bar").css('color', 'rgba(50, 50, 50, 0.2)');
    });
    
    $("#search #bar").keyup(function() {
        clickedItem = false;
        $("#results").html("<br /><br /><center>Searching...</center>");
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }
        xhr = $.ajax({
            url : "search.php",
            type: "POST",
            data: {
                courseName: $("#search #bar").val()
            },
            success : function (data) {
              $("#results").html(data)
            }
        });
        hideDetails();
    });
    
    $("#home").hover(function() {
        if(clicked)
            $("#homeUnderline").css('width', '15em');
    }, function() {
        if(clicked) {
            $("#homeUnderline").css('width', '0em');
            $("#homeUnderline").css('opacity', '0');
            setTimeout(function() {
                $("#homeUnderline").css('opacity', '1');
            }, 300);
        }
    });
    
    $("#home").click(function() {
        if(clicked) {
            window.location = window.location.href.split("#")[0];
        }
    });
    
    $("#header span").click(function() {
        $("#login").fadeIn(300);
    })
    
    $(document).mouseup(function(e)  {
        var container = $("#login");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.fadeOut(300);
        }
    });
    
    loginScreen();
});

    
function createAccountScreen() {
    $("#login").html(
        `<center><b><span style='font-size: 20px;'>Create Account</span></b></center>
         <br />
         <table>
            <tr>
                <td>zID: </td>
                <td><input id='zID' type='text' name='zID' autocomplete="off" /></td>
                <td>@unsw.edu.au</td>
            </tr>
            <tr>
                <td>Password: </td>
                <td colspan='2'><input id='zPass' type='password' name='zPass' autocomplete="off" /></td>
            </tr>
        </table>
        <br />
        <span id="errorsDiv"></span>
        <br />
        <button name='back' class='loginButton btnBlue floatLeft' onclick="loginScreen()">Back</button>
        <button name='register' class='loginButton btnRed floatRight' onclick="sentEmailScreen()">Create Account</button>`
    );
}

function sentEmailScreen() {
    $("#errorsDiv").text("Creating account...");
    $.ajax({
        url : "sendMail.php",
        type: "POST",
        async: false,
        data: {
            zID: $("#zID").val(),
            password: $("#zPass").val()
        },
        success : function (data) {
            if(data.split("|")[0] != E) {
                $("#login").html(
                    `<center><b><span style='font-size: 20px;'>Email Sent</span></b></center>
                    <br />
                    Please check your email for the confirmation link then click the button below to login
                    <br />
                    <br />
                    Email haven't arrived after 10 minutes? Check your spam folder or <span id="resendEmail" onclick="resendEmail()">Resend Email</span>
                    <br />
                    <br />
                    <br />
                    <button class='loginButton btnRed floatRight' onclick="loginScreen()">Log In</button>`
                );
            } else {
                $("#errorsDiv").html(data);
            }
        }
    });
}

function loginScreen() {
    $("#login").html(
       `<center><b><span style="font-size: 20px;">Login into Untangle</span></b></center>
        <br />
        <table>
            <tr>
                <td>zID: </td>
                <td><input type="text" name="username" /></td>
            </tr>
            <tr>
                <td>Password: </td>
                <td><input type="password" name="password" /></td>
            </tr>
        </table>
        <br />
        <button name="create" class="loginButton btnBlue floatLeft" onclick="createAccountScreen()">Create Account</button>
        <button name="login" class="loginButton btnRed floatRight">Log In</button>`
    );
}

function resendEmail() {
    
}

function searchBarClick() {
    $("#search #bar").val("");
    $("#search").css("transform", "translateY(-30vh)");
    $("#search #bar").css('color', 'black');
    $("#search #bar").css('background-color', 'transparent');
    $("#search #bar").css('border-bottom', '3px solid black');
    $("#search #bar").css('padding', '0');
    $("#search #bar").css('border-radius', '0');
    $("#content").fadeIn(300);
}

function hideDetails() {
    $("#content #details").fadeOut(300);
    setTimeout(function() {
        $("#content #results").fadeIn(300);
    }, 300);
}

function showDetails(courseID) { 
    clickedItem = true;
    $.ajax({
        url: 'getCourseDetails.php',
        type: 'POST',
        data: {
            courseName: courseID
        },
        async:false,
        success: function (data) {
            $("#content #details").html(data);
            $("#content #results").fadeOut(300);
            $('html, body').animate({
                scrollTop: $("html").offset().top
            }, 600);
            setTimeout(function() {
                $("#content #details").fadeIn(300);
            }, 300);
        }
    });
}