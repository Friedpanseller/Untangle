
<?php
	ob_start();
    set_time_limit(0);
    // Sends HTML Page to user
    echo   '<html>
            <head>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                <script type="text/javascript" src="query.js"></script>
                <link rel="stylesheet" type="text/css" href="circle.css" />
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        color: white;
                        font-family: Helvetica;
                        background: black url("images/real_cf.png");
                    }
                    h1, h2 {
                        text-align: center;
                    }
                    h1 {
                        font-size: 48px;
                    }
                    #percent, #percent1, #percent2 {
                        color: white;
                    }
                    #percent:hover {
                        color: #c6ff00;
                    }
                    #percent1:hover {
                        color: #e08833;
                    }
                    #percent2:hover {
                        color: aqua;
                    }
                    p {
                        font-size: 24px;
                    }
                </style>
            </head>
            <body>
                <h1 id="update"><br />Updating</h1>
                <h2 id="date">Courses as of</h2>
                <table style="width: 100vw; text-align: center;" align="center" valign="center">
                    <tr>
                        <td width="33%">
                            <div id="graph" class="c100 p0 dark green big center" onclick="updateCourseID()">
                                <span id="percent">0%</span>
                                <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                            </div>
                            <p><em>Update Courses from Timetable</em></p>
                        </td>
                        <td width="34%">
                            <div id="graph1" class="c100 p0 dark orange big center" onclick="updateCourseDetails()">
                                <span id="percent1">0%</span>
                                <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                            </div>
                            <p><em>Update Details from Handbook</em></p>
                        </td>
                        <td width="33%">
                            <div id="graph2" class="c100 p0 dark blue big center" onclick="updateCourseCapacity()">
                                <span id="percent2">0%</span>
                                <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                            </div>
                            <p><em>Update Course Capacity from Classutils</em></p>
                        </td>
                    </tr>
                </table>
                <div id="status"></div>
            </body>
            <script>
                var clicked = false;
                $("#date").text("Courses as of " + Date());
                if(!jQuery.query.get("action")) {
                    window.location.search = jQuery.query.set("action", "none")
                } else if(jQuery.query.get("action") != "none") {
                    clicked = true;
                }
                function updateCourseID() {
                    if(clicked == false) {
                        window.location.search = jQuery.query.set("action", "updateCourseID");
                    }
                }
                function updateCourseDetails() {
                    if(clicked == false) {
                        window.location.search = jQuery.query.set("action", "updateCourseDetails");
                    }
                }
                function updateCourseCapacity() {
                    if(clicked == false) {
                        window.location.search = jQuery.query.set("action", "updateCourseCapacity");
                    }
                }
            </script>
        </html>';
    echo str_repeat("<!-- AgentDisguise -->", 10);
    ob_end_flush();
    ob_flush();
    flush();    

    function readFromUntil($inputString, $fromString, $untilString, $startFromEnd = true) {
        $index = strpos($inputString, $fromString);
        if($index === false) {
            return "";
        }
        if($startFromEnd === true) {
            $index += strlen($fromString);
        }
        $compare = "";
        $returnString = "";
        while($compare != $untilString) {
            $returnString .= $inputString[$index];
            $index++;
            $compare = "";
            for($x = $index; $x < strlen($untilString) + $index; $x++) {
                $compare .= $inputString[$x];
            }
        }
        return $returnString;
    }

    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "disentangle";

    if ($_GET['action'] === "updateCourseID") {

        // START GETTING COURSE IDs //

        // Get all the courses from timetable website
        $html = file_get_contents("http://timetable.unsw.edu.au/2017/subjectSearch.html");
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $finder = new DomXPath($doc);

        // Courses are in a tr classed rowHighlight or rowLowlight
        $nodes = $finder->query("//*[@class='rowHighlight' or @class='rowLowlight']");
        $subjectAreas = [];

        // Break up the items into ones that have class data
        // Append each subject area to an array
        foreach($nodes as $node) {
            $temp = $doc->saveHTML($node);
            $newDoc = new DOMDocument();
            $newDoc->loadHTML($temp);
            $finder = new DomXPath($newDoc);
            $lists = $finder->query("//*[contains(@class, 'data')]");
            $count = 0;
            foreach($lists as $list) {
                array_push($subjectAreas, $newDoc->saveHTML($list));
                break;
            }
        }

        // Break up the subject areas even more
        $subjectAreaArray = [];
        foreach($subjectAreas as $temp) {
            array_push($subjectAreaArray, readFromUntil($temp, "href=\"", "\">"));
        }  

        $amountOfSubjectAreas = count($subjectAreaArray);
        $countSubjectAreas = 0;

        // List of all courses
        $courseList = array();

        // Loop through all the subject areas in subjectAreaArray
        foreach($subjectAreaArray as $subjectArea) {
            // Update loading circle
            echo   "<script>
                        $('#percent').text('" . ceil(($countSubjectAreas * 100) / $amountOfSubjectAreas) . "%');
                        $('#graph').removeAttr('class');
                        $('#graph').addClass('c100 p" . ceil(($countSubjectAreas * 100) / $amountOfSubjectAreas) . " dark green big center');
                    </script>";

            echo str_repeat("<!-- AgentDisguise -->", 10);
            ob_flush();
            flush();

            $countSubjectAreas++;

            // List Courses "http://timetable.unsw.edu.au/2017/COMPKENS.html"
            // UNSW Handbook
            // Repetition of above
            $html = file_get_contents("http://timetable.unsw.edu.au/2017/" . $subjectArea);
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($html);
            $finder = new DomXPath($doc);
            $nodes = $finder->query("//*[@class='rowHighlight' or @class='rowLowlight']");
            $courses = "";
            foreach($nodes as $node) {
                $temp = $doc->saveHTML($node);
                $newDoc = new DOMDocument();
                $newDoc->loadHTML($temp);
                $finder = new DomXPath($newDoc);
                $lists = $finder->query("//*[contains(@class, 'data')]");
                foreach($lists as $list) {
                    $courses .= $newDoc->saveHTML($list);
                }
                $courses .= "#AgentDisguise#";
            }

            $courses = explode("#AgentDisguise#", $courses);
            $course = [];
            foreach($courses as $temp) {
                $temp = explode("</td>", $temp);
                array_push($course, $temp);
            }

            foreach($course as $temp) {
                if($temp[0]) {
                    $courseList[readFromUntil($temp[0], "l\">", "<")] = readFromUntil($temp[1], "l\">", "<");
                }
            }
        }
        //var_dump($courseList);
        //$courseList = array_unique($courseList);
        
        // Create connection
        $link = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if ($link === false) {
            die("Connection failed: " . mysqli_connect_error());
        } 
        $sql = "INSERT IGNORE INTO `courses`(`ID`, `NAME`) VALUES ";
        foreach($courseList as $key => $value) {
            $sql .=  '("' . $key . '", "' . $value . '"),';
        }
        $sql = rtrim($sql,",");

        //echo "Quering " . $sql . "<br /><br /><br />";
        if(mysqli_query($link, $sql)) {
            echo "Records inserted successfully.";
        } else {
            echo "err " . mysqli_error($link);
        }    
        
        echo "<script>clicked = false</script>";
        mysqli_close($link);
    }

    if ($_GET['action'] === "updateCourseDetails") {
        ob_start();
        // START GETTING COURSE DETAILS //
        echo "<script>$('#status').html('Start Update');</script>";
        echo str_repeat("<!-- AgentDisguise -->", 10);
        ob_end_flush();
        ob_flush();
        flush();
        
        $courseList = [];
        
        $link = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if ($link === false) {
            die("Connection failed: " . mysqli_connect_error());
        } 

        $sql = "SELECT `ID` FROM `courses`";
        if($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result)) {
                    echo "<script>$('#status').html('Pushing: " . $row["ID"] . "');</script>";
                    echo str_repeat("<!-- AgentDisguise -->", 10);
                    ob_flush();
                    flush();
                    array_push($courseList, $row["ID"]);
                }
                mysqli_free_result($result);
            } else {
                echo "No Results Found.";
            }
        }
        
        $amountOfCourses = count($courseList);
        $countCourses = 0;

        foreach($courseList as $course) {                
            echo "<script>$('#status').html('Getting Website http://www.handbook.unsw.edu.au/undergraduate/courses/2017/" . $course . ".html');</script>";
            echo str_repeat("<!-- AgentDisguise -->", 10);
            ob_flush();
            flush();
            if(get_http_response_code("http://www.handbook.unsw.edu.au/undergraduate/courses/2017/" . $course . ".html") == "200") {
                $html = file_get_contents("http://www.handbook.unsw.edu.au/undergraduate/courses/2017/" . $course . ".html");
            } else {
                $html = "";
            }
            $faculty = str_replace('"', "'", readFromUntil($html, "<p><strong>Faculty:</strong>&nbsp;", "</p>"));
            $school = str_replace('"', "'", readFromUntil($html, "<p><strong>School:</strong>&nbsp;", "</p>"));
            $courseOutline = str_replace('"', "'", readFromUntil($html, "<p><strong>Course Outline:</strong>&nbsp;", "</p>"));
            $campus = str_replace('"', "'", readFromUntil($html, "<p><strong>Campus:</strong>&nbsp;", "</p>"));
            $career = str_replace('"', "'", readFromUntil($html, "<p><strong>Career:</strong>&nbsp;", "</p>"));
            $UoC = str_replace('"', "'", readFromUntil($html, "<p><strong>Units of Credit:</strong>&nbsp;", "</p>"));
            $EFTSL = str_replace('"', "'", readFromUntil($html, "<p><strong>EFTSL:</strong>&nbsp;", "&nbsp;<a"));
            $ICHPW = str_replace('"', "'", readFromUntil($html, "<p><strong>Indicative Contact Hours per Week:</strong>&nbsp;", "</p>"));
            $prereq = str_replace('"', "'", readFromUntil($html, "<p>Prerequisite: ", "</p>"));
            $excluded = str_replace('"', "'", readFromUntil($html, "<p><strong>Excluded:</strong>&nbsp;", "</p>"));
            $CSSCC = str_replace('"', "'", readFromUntil($html, "<p><strong>CSS Contribution Charge:</strong>&nbsp;", "&nbsp;<a"));
            $genEd= str_replace('"', "'", readFromUntil($html, "<p><strong>Available for General Education:</strong>&nbsp;", " <a "));
            $description = str_replace("<div>", "", str_replace('"', "'", readFromUntil($html, "<h2>Description</h2><!-- Start Course Description -->", "</div>")));
            $sql = 'UPDATE `courses` SET    `Faculty` = "' . $faculty . '", 
                                            `School` = "' . $school . '", 
                                            `CourseOutline` = "' . $courseOutline . '", 
                                            `Campus` = "' . $campus . '", 
                                            `Career` = "' . $career . '", 
                                            `UoC` = "' . $UoC . '", 
                                            `EFTSL` = "' . $EFTSL . '", 
                                            `ICHPW` = "' . $ICHPW . '", 
                                            `Prereq` = "' . $prereq . '", 
                                            `Excluded` = "' . $excluded . '", 
                                            `CSSCC` = "' . $CSSCC . '", 
                                            `GenEd` = "' . $genEd . '", 
                                            `Description` = "' . $description . '"
                      WHERE `ID` = "' . $course . '";';
            echo "<script>$('#status').html('" . $sql . "');</script>";
            echo str_repeat("<!-- AgentDisguise -->", 10);
            ob_flush();
            flush();
            
            if(mysqli_query($link, $sql)) {
                echo   "<script>
                        $('#percent1').text('" . ceil(($countCourses * 100) / $amountOfCourses) . "%');
                        $('#graph1').removeAttr('class');
                        $('#graph1').addClass('c100 p" . ceil(($countCourses * 100) / $amountOfCourses) . " dark orange big center');
                    </script>";

                echo str_repeat("<!-- AgentDisguise -->", 10);
                ob_flush();
                flush();
                
                $countCourses++;
            } else {
                echo "err " . mysqli_error($link);
                break;
            }    
        }
        echo "<br />success";
        echo "<script>clicked = false</script>";
        mysqli_close($link);
    }

    if ($_GET['action'] === "updateCourseCapacity") {
        ob_start();
        // START GETTING COURSE DETAILS //
        echo "<script>$('#status').html('Start Updatting course capacities');</script>";
        echo str_repeat("<!-- AgentDisguise -->", 10);
        ob_end_flush();
        ob_flush();
        flush();
        
        // Get all the courses from timetable website
        $html = file_get_contents("http://timetable.unsw.edu.au/2017/subjectSearch.html");
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $finder = new DomXPath($doc);

        // Courses are in a tr classed rowHighlight or rowLowlight
        $nodes = $finder->query("//*[@class='rowHighlight' or @class='rowLowlight']");
        $subjectAreas = [];

        // Break up the items into ones that have class data
        // Append each subject area to an array
        foreach($nodes as $node) {
            $temp = $doc->saveHTML($node);
            $newDoc = new DOMDocument();
            $newDoc->loadHTML($temp);
            $finder = new DomXPath($newDoc);
            $lists = $finder->query("//*[contains(@class, 'data')]");
            $count = 0;
            foreach($lists as $list) {
                array_push($subjectAreas, $newDoc->saveHTML($list));
                break;
            }
        }

        // Break up the subject areas even more
        $subjectAreaArray = [];
        foreach($subjectAreas as $temp) {
            array_push($subjectAreaArray, readFromUntil($temp, ".html\">", "</a>"));
        }  

        $amountOfSubjectAreas = count($subjectAreaArray);
        $countSubjectAreas = 0;
        
        foreach($subjectAreaArray as $subjectArea) {
            // Update loading circle
            echo   "<script>
                        $('#percent2').text('" . ceil(($countSubjectAreas * 100) / $amountOfSubjectAreas) . "%');
                        $('#graph2').removeAttr('class');
                        $('#graph2').addClass('c100 p" . ceil(($countSubjectAreas * 100) / $amountOfSubjectAreas) . " dark blue big center');
                    </script>";

            echo str_repeat("<!-- AgentDisguise -->", 10);
            ob_flush();
            flush();
            if(get_http_response_code("https://classutil.carey.li/api?f=" . $subjectArea . "&s=s2") == "200") {
                $html = json_decode(file_get_contents("https://classutil.carey.li/api?f=" . $subjectArea . "&s=s2"), true);
                foreach($html as $courseName => $courseDetails) {
                    if($courseName != "updated") {
                        //var_dump($courseName);
                        $lecEnrolled = 0;
                        $lecCapacity = 0;
                        $tutEnrolled = 0;
                        $tutCapacity = 0;
                        foreach($courseDetails as $type => $details) {
                            if($type == "LEC") {
                                foreach($details as $detail) {
                                    $lecEnrolled += explode("/", $detail["enrolled"])[0];
                                    $lecCapacity += explode(" ", explode("/", $detail["enrolled"])[1])[0];
                                }
                               // echo "Lec: " . $lecEnrolled . "/" . $lecCapacity . "<br />";
                            } else {
                                foreach($details as $detail) {
                                    $tutEnrolled += explode("/", $detail["enrolled"])[0];
                                    $tutCapacity += explode(" ", explode("/", $detail["enrolled"])[1])[0];
                                }
                                //echo "Tut: " . $tutEnrolled . "/" . $tutCapacity;
                            }
                        }
                        
                        $link = mysqli_connect($servername, $username, $password, $dbname);
                        // Check connection
                        if ($link === false) {
                            die("Connection failed: " . mysqli_connect_error());
                        } 

                        $sql = "UPDATE `courses` SET    `lectureEnrol` = " . $lecEnrolled . ",
                                                        `lectureCapacity` = " . $lecCapacity . ",
                                                        `tuteEnrol` = " . $tutEnrolled . ",
                                                        `tuteCapacity` = " . $tutCapacity . "
                                WHERE `ID` = '" . $courseName . "'";
                        if(mysqli_query($link, $sql)) {
                            echo "<script>$('#status').html('Records Inserted Successfully " . $courseName . " ')</script>";
                            echo str_repeat("<!-- AgentDisguise -->", 10);
                            ob_flush();
                            flush();
                        } else {
                            echo "<script>$('#status').html('" . mysqli_error($link) . " " . $courseName . " ')</script>" ;
                            echo str_repeat("<!-- AgentDisguise -->", 10);
                            ob_flush();
                            flush();
                        }    
                    }
                }
            }
            $countSubjectAreas++;
        }
        
        echo "<script>clicked = false</script>";
        mysqli_close($link);
    }
?>