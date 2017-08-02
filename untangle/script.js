var clicked = false;
var clickedItem = false;

var xhr = null;

//Set navigator.browser to display "BrowserName BrowserVersion"
navigator.browser = (function(){
    var ua= navigator.userAgent, tem,
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
    return M.join(' ');
})();

window.onload = function() { 
    instantJump = window.location.hash.replace("#", ""); 
    if(instantJump) {
        clicked = true;
        searchBarClick();
        showDetails(instantJump);
    }

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
        if(clicked == false) {
            $("#search #bar").val("");
        }
        clicked = true;
        $("#creator").html("");
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
    
    $(document).mouseup(function(e)  {
        var container = $("#login");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.fadeOut(300);
            
        }
        
        container = $("#courseRatings");
        
        if ((!container.is(e.target) && !$("#ratingsSlider").is(e.target)) && container.has(e.target).length === 0) 
        {
            container.fadeOut(300);
        }
    });
    
    
    $("#search #home").fadeIn(2500);
    setTimeout(function() {
        $("#search #bar").fadeIn(1500);
    }, 750);
    
    setLoginText();
    setLoginScreen();
    userReturns();
}

function userReturns() {
    var session = getCookie("session");
    if(session) {
        loginUser(session);
    }
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function showLoginScreen() {
    $("#login").fadeIn(300);
}

function setLoginText() {
    $("#login").html(
        `<center><b><span style='font-size: 20px;'>You are now logged out</span></b></center><br /><br />
        <button name="login" class="loginButton btnRed floatRight" onclick="setLoginText();setLoginScreen()">Log In</button>
        `
    );
}
    
function createAccountScreen() {
    $("#login").html(
        `<center><b><span style='font-size: 20px;'>Create Account</span></b></center>
         <br />
         <table>
            <tr>
                <td>Student ID: </td>
                <td><input id='zID' type='text' name='zID' autocomplete="off" /></td>
                <td>@</td>
                <td>
                <select id='emailSuffix' name="cars">
                    <option value="unsw.edu.au">unsw.edu.au</option>
                    <option value="uni.sydney.edu.au">uni.sydney.edu.au</option>
                </select>
                </td>
            </tr>
            <tr>
                <td>Password: </td>
                <td colspan='3'><input id='zPass' type='password' name='zPass' autocomplete="off" /></td>
            </tr>
        </table>
        <br />
        <span id="errorsDiv"></span>
        <br />
        <button name='back' class='loginButton btnBlue floatLeft' onclick="setLoginScreen()">Back</button>
        <button name='register' class='loginButton btnRed floatRight' onclick="sentEmailScreen()">Create Account</button>`
    );
}

function sentEmailScreen() {
    $("#errorsDiv").text("Creating account...");
    $.ajax({
        url : "../sendMail.php",
        type: "POST",
        async: false,
        data: {
            zID: $("#zID").val(),
            password: $("#zPass").val(),
            emailSuffix: $("#emailSuffix").val()
        },
        success : function (data) {
            if(data.split("|")[0] != 'E') {
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
                    <button class='loginButton btnRed floatRight' onclick="setLoginScreen()">Log In</button>`
                );
            } else {
                $("#errorsDiv").html(data);
            }
        }
    });
}

function setLoginScreen() {
    $("#login").html(
       `<center><b><span style="font-size: 20px;">Login into Untangle</span></b></center>
        <br />
        <table>
            <tr>
                <td>Student ID: </td>
                <td><input id="loginUsername" type="text" name="username" /></td>
            </tr>
            <tr>
                <td>Password: </td>
                <td><input id="loginPassword" type="password" name="password" /></td>
            </tr>
            <!--<tr>
                <td>Keep me logged in </td>
                <td><input id="keepMeLoggedIn" type="checkbox" style="width: 20px" name="keepLogged" checked="yes" /></td>
            </tr>-->
            <tr>
                <td colspan="2"><span id="loginErrors"></span></td>
            </tr>
        </table>
        <br />
        <button name="create" class="loginButton btnBlue floatLeft" onclick="createAccountScreen()">Create Account</button>
        <button name="login" class="loginButton btnRed floatRight" onclick="loginUser('')">Log In</button>`
    );
}

function loginUser(session) {
    //alert("attempting login");
    $.ajax({
        url : "../login.php",
        type: "POST",
        data: {
            username: $("#loginUsername").val(),
            password: $("#loginPassword").val(),
            keepMeLoggedIn: $("#keepMeLoggedIn").val(),
            userBrowser: navigator.browser,
            sessionID: session
        },
        success : function (data) {
            //alert(1);
            data = data.split("|");
            if(data[0] != 'E') {
                //alert(2);
                var username = data[1];
                var sessionID = data[2];
                $("#login").html(
                    `<center><b><span style='font-size: 20px;'>Welcome Back ` + username + `</span></b></center>`
                );
                setUserOnline(data[1]);
                if(sessionID != "none") {
                    var expiryDate = new Date();
                    var cookieLifeDays = 14;
                    expiryDate.setTime(expiryDate.getTime() + (cookieLifeDays*24*60*60*1000));
                    document.cookie = "session=" + sessionID + ";expires=" + expiryDate.toUTCString();
                }
            } else {
                //alert(4);
                $("#loginErrors").html(data[1]);
            }
        }
    });
}

function logoutUser() {
    $.ajax({
        url : "../logout.php",
        type: "POST",
        data: {
            userBrowser: navigator.browser,
            sessionID: getCookie("session")
        },
        success : function (data) {
            data = data.split("|");
            if(data[0] != 'E') {
                setLoginText();
                showLoginScreen();
                setUserOffline();
            } else {
                alert("Error please contact administrator");
            }
        }
    });
}

function setUserOnline(zID) {
    $("#header").html("You are logged in as " + zID + ". <span class='login' onclick='logoutUser()'>Log out</span>.</div>");
}

function setUserOffline() {
    $("#header").html("You are not logged in. <span class='login' onclick='showLoginScreen()'>Log in</span>.");
    document.cookie = "session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function sendEmail() {
    
}

function searchBarClick() {
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
        url: '../getCourseDetails.php',
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