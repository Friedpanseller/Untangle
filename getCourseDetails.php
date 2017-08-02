<?php
    // Stores the SQL Login Details
    require 'sqlDetails.php';

    $sql = new sqlDetails;

    // Create connection
    $link = mysqli_connect($sql->server, $sql->username, $sql->password, $sql->database);
    // Check connection
    if ($link === false) {
        die("Connection failed: " . mysqli_connect_error());
    } 
    
    // Query
    $sql = "SELECT * FROM courses WHERE ID = '" . $_POST["courseName"] . "'";

    // Execute Query 
    if($result = mysqli_query($link, $sql)) {
        // If there are results
        if (mysqli_num_rows($result) > 0) {
            // Fetch results into an array
            $row = mysqli_fetch_array($result);
            
            // Calculate capacity by highest percentage of lecture or tutorial capacity
            $enrollment = max($row["lectureEnrol"], $row["tuteEnrol"]);
            if($enrollment == $row["lectureEnrol"]) {
                $capacity = $row["lectureCapacity"];
            } else {
                $capacity = $row["tuteCapacity"];
            }
            if($capacity <= 0) {
                $enrollmentPercent = 0;
            } else {
                $enrollmentPercent = ceil($enrollment / $capacity * 100);
            }
            
            // Echo webpage to user
            echo    
                   '<span class="h2" id="ID">' . $row["ID"] . '</span>
                    <span class="h3" id="Name">' . $row["Name"] . '</span>
                    <br /><br />
                    <hr style="hrRed" />
                    <div id="leftDiv">
                        <table id="course">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <span class="h3 bold"><center>Details</center></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"><br /></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Faculty: </b><span id="Faculty">' . $row["Faculty"] . '</span></td>
                                <td colspan="2"><b>Prerequisite: </b><span id="Prereq">' . $row["Prereq"] . '</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>School: </b><span id="School">' . $row["School"] . '</span></td>
                                <td colspan="2"><b>Excluded: </b><span id="Excluded">' . $row["Excluded"] . '</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Course Outline: </b><span id="CourseOutline">' . $row["CourseOutline"] . '</span></td>
                                <td colspan="2"><b>CSS Contribution Charge: </b><span id="CSSCC">' . $row["CSSCC"] . '</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Campus: </b><span id="Campus">' . $row["Campus"] . '</span></td>
                                <td colspan="2"><b></b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Career: </b><span id="Career">' . $row["Career"] . '</span></td>
                                <td colspan="2"><b>Available for General Education: </b><span id="GenEd">' . $row["GenEd"] . '</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Units of Credit: </b><span id="UoC">' . $row["UoC"] . '</span></td>
                                <td colspan="2"><b></b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>EFTSL: </b><span id="EFTSL">' . $row["EFTSL"] . '</span></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Indicative Contact Hours per Week: </b><span id="ICHPW">' . $row["ICHPW"] . '</span></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="4"><br /></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: left">
                                    <span class="description" id="description">
                                        ' . $row["Description"] . '
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table id="review" cellspacing="0" cellpadding="0">
                            <tr><td><br /><br /><br /></td></tr>
                            <tr>
                                <td colspan="4">
                                    <span class="h3 bold">Reviews</span>
                                </td>
                            </tr>
                            <tr><td colspan="4"><br /></td></tr>
                            <tr>
                                <td>
                                    <span class="bold">Comment</span>
                                </td>
                                <td><span class="bold">Enjoyability</span></td>
                                <td><span class="bold">Difficulty</span></td>
                                <td><span class="bold">Skippability</span></td>
                            </tr>
                            <tr><td colspan="4"><br /></td></tr>
                            <tr>
                                <td style="text-align: left">
                                    "This is an amazing course!" - John Citizen
                                </td>
                                <td>100%</td><td>100%</td><td>100%</td>
                            </tr>
                            <tr><td colspan="4"><hr /></td></tr>
                            <tr>
                                <td style="text-align: left">
                                    "Doesn\'t teach me anything about security" - Terry Hills
                                </td>
                                <td>20%</td><td>100%</td><td>20%</td>
                            </tr>
                            <tr><td colspan="4"><hr /></td></tr>
                            <tr>
                                <td style="text-align: left">
                                    "This Russian Language learning game is quite impressive, already after a few games I had a rather vast comprehension of the language and Its underlying culture. 

    A few russian fellas said my grasp was "fluent". 

    Protip: DA DA, DAVAI DAVAI RUSH B♥♥♥♥♥♥♥♥♥♥♥!
    One of the most frequent phrases used by Russian CS:GO Pros, translated; Rush B. Don\'t stop - until a molotov comes and then all should stop in the tunnel for a pleasant barbeque experience." - vortOtun
                                </td>
                                <td>100%</td><td>100%</td><td>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div id="rightDiv">
                        <table id="rating">
                            <tr>
                                <td>
                                    <span class="h3 bold">Capacity</span>
                                </td>
                            </tr>
                            <tr><td><br /></td></tr>
                            <tr>
                                <td>
                                    <div class="c100 p' . $enrollmentPercent . ' big orange center">
                                        <span>' . $enrollment . '/' . $capacity . '</span>
                                        <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                                    </div>
                                </td>
                            </tr>
                            <tr><td><br /><hr style="width: 75%" /><br /></td></tr>
                            <tr>
                                <td>
                                    <span class="h3 bold">Links</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="http://www.handbook.unsw.edu.au/undergraduate/courses/2017/' . $row["ID"] . '.html" target="_blank">UNSW Handbook</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="http://pathways.csesoc.unsw.edu.au/#' . $row["ID"] . '" target="_blank">CSE Pathways</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">cats</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">Timetable</a>
                                </td>
                            </tr>
                            <tr><td><br /><hr style="width: 75%" /><br /></td></tr>
                            <tr>
                                <td>
                                    <span class="h3 bold">Ratings</span>
                                </td>
                            </tr>
                            <tr><td><br /></td></tr>
                            <tr>
                                <td>
                                    <div class="c100 p100 green center" onclick="rateCourse(\'' . $row["ID"] . '\', \'Enjoyability\', \'green\')">
                                        <span>N/A%</span>
                                        <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                                    </div>

                                    <p><em>Enjoyability</em></p>
                                </td>
                            </tr>
                            <tr><td><br /><br /><br /></td></tr>
                            <tr>
                                <td>
                                    <div class="c100 p70 red center" onclick="rateCourse(\'' . $row["ID"] . '\', \'Difficulty\', \'red\')">
                                        <span>N/A%</span>
                                        <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                                    </div>

                                    <p><em>Difficulty</em></p>
                                </td>
                            </tr>
                            <tr><td><br /><br /><br /></td></tr>
                            <tr>
                                <td>
                                    <div class="c100 p33 blue center" onclick="rateCourse(\'' . $row["ID"] . '\', \'Skippability\', \'blue\')">
                                        <span>N/A%</span>
                                        <div class="slice"><div class="bar"></div><div class="fill"></div></div>
                                    </div>

                                    <p><em>Lecture Skippability</em></p>
                                </td>
                            </tr>
                        </table>
                    </div>';
            mysqli_free_result($result);
        } else {
            echo "No Results Found.";
        }
    }
    mysqli_close($link);
?>