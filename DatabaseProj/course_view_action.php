<?

include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$sectionid=$_GET["sectionid"];
verify_session ($sessionid);

//get data for selected course
$sql = "select  sec.coursetitle,
                sec.coursenumber,
                sec.sectionid,
                sec.semester,
                sec.instructor,
                sec.capacity,
                c.coursedescription,
                c.credits
       from section sec
       join course c
       on sec.coursenumber = c.coursenumber
       where sectionid = '$sectionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$values = oci_fetch_array ($cursor);

$coursetitle = $values[0];
$coursenumber = $values[1];
$sectionid = $values[2];
$semester = $values[3];
$instructor = $values[4];
$capacity = $values[5];
$coursedescription = $values[6] ;
$credits = $values[7];

echo ("<pre>");
echo ("<B><U>Course Title         :</B></U>   $coursetitle<BR>");
echo ("<B><U>Course Number        :</B></U>   $coursenumber<BR>");
echo ("<B><U>Semester             :</B></U>   $semester<BR>");
echo ("<B><U>Instructor           :</B></U>   $instructor<BR>");
echo ("<B><U>Capacity             :</B></U>   $capacity<BR>");
echo ("<B><U>Course Description   :</B></U>   $coursedescription<BR>");
echo ("<B><U>Credits              :</B></U>   $credits<BR>");
echo ("<B><U>Course Prerequisite  :</B></U>   ");


//obtain and display prereqs
$sql = " select prereqnumber
         from course_prereq
         where coursenumber = '$coursenumber' ";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
while  ( $values = oci_fetch_array ($cursor)) {
  $prereqnumber = $values[0];
  echo ("$prereqnumber; ") ;
}
?>
