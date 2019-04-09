<?
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
verify_session ($sessionid);
echo ("Course Management");
echo ("<BR><BR>");
echo ("<A HREF = \"course_view.php?sessionid=$sessionid\">Browse Courses</A>");

// get userid
$sql = "select u.userid
	from users u join usersession us on u.userid = us.userid
	where sessionid = '$sessionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($values = oci_fetch_array ($cursor)) {
  $userid = $values[0];
}

//get a list of sections that the user is enrolled in
$sql = " select sec.sectionid,
                sec.coursenumber,
                sec.coursetitle,
                sec.semester,
                sec.instructor,
                c.credits,
                en.grade
	from section sec Join course c on sec.coursetitle = c.coursetitle and sec.coursenumber = c.coursenumber
                Join enrolledin en on sec.sectionid = en.sectionid
	where en.userid = '$userid' ";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message ($cursor);
  die ("Client Query Failed.");
}

echo "<table border=1>";
echo "<tr> <th>Section ID</th> <th>Course #</th> <th>Course Title</th>  <th>Semester</th> <th> Instructor</th> <th> # of Credits</th> <th> Grade</th><th>Drop</th></tr>";


// Fetch the result from the cursor one by one and display in table
while ($values = oci_fetch_array ($cursor)){
  $sectionid = $values[0];
  $coursenumber = $values[1];
  $coursetitle = $values[2];
  $semester = $values[3];
  $instructor = $values[4];
  $credits = $values[5];
  $grade = $values[6];
  $lettergrade = "" ;

  //assign letter grades to numeric grades
  if ( $grade == 4 ) { $lettergrade = "A" ; }
  else if ( $grade == 3 ) { $lettergrade = "B"; }
  else if ( $grade == 2 ) { $lettergrade = "C"; }
  else if ( $grade == 1 ) { $lettergrade = "D"; }
  else if ( $grade == 0 ) { $lettergrade = "F"; }
  echo ("<tr> <td>$sectionid</td> <td>$coursenumber</td> <td>$coursetitle </td> <td>$semester</td> <td>$instructor</td> <td>$credits</td> <td> $lettergrade </td>".
	"<td> <A HREF=\"course_drop_action.php?sessionid=$sessionid&sectionid=$sectionid\">Drop</A> </td></tr> ");
}

echo ("<BR><BR>");
echo ("<A HREF = \"welcomepage.php?sessionid=$sessionid\">Go Back</A>");
?>
