<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

//retrieve all data for current user
$sql = "select  u.userid,
		u.fname,
		u.lname,
                u.age,
                u.address,
                u.usertype
        from usersession us
        left join users u
        on us.userid = u.userid
        where sessionid= '$sessionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$values = oci_fetch_array ($cursor);
$userid = $values[0];
$fname = $values[1];
$lname = $values[2];
$age = $values[3];
$address = $values[4];
$usertype = $values[5];

echo ("<pre>");
echo ("<B><U>User ID   :</B></U>   $userid<BR>");
echo ("<B><U>First Name:</B></U>   $fname<BR>");
echo ("<B><U>Last Name :</B></U>   $lname<BR>");
echo ("<B><U>Age       :</B></U>   $age<BR>");
echo ("<B><U>Address   :</B></U>   $address<BR>");
//display additional data if the user is a student
if ($usertype != 'admin') {
  $sql = "select  studenttype,
                  status,
                  GPA
          from studentuser
          where userid = '$userid'" ;
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];
  $values = oci_fetch_array ($cursor);
  $studenttype = $values[0];
  $status = $values[1];
  $GPA = $values [2] ;

  echo ("<B><U>Student Type   :</B></U>   $studenttype<BR>");
  echo ("<B><U>Status   :</B></U>   $status<BR>");
}


echo ("</pre>");
echo ("<pre>");
echo ("<A HREF=\"change_password.php?sessionid=$sessionid\">Change Password</A><BR>");
echo ("<A HREF = \"welcomepage.php?sessionid=$sessionid\">Go Back</A>");
echo ("</pre>");

//show courses if the user is a student
if ($usertype == 'student' || $usertype == 'stuadmin')  {

  $sql = "select      sec.sectionid,
             sec.coursenumber,
             sec.coursetitle,
             sec.semester,
             sec.instructor,
             c.credits,
             en.grade
          from section sec Join course c on sec.coursenumber = c.coursenumber
                           Join enrolledin en on sec.sectionid = en.sectionid
	  where en.userid = '$userid'
	  order by sec.semester desc";
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];
  if ($result == false){
    display_oracle_error_message ($cursor);
    die ("Client Query Failed.");
  }

  echo "<table border=1>";
  echo "<tr> <th>Section ID</th> <th>Course #</th> <th>Course Title</th>  <th>Semester</th> <th> Instructor</th> <th> # of Credits</th> <th> Grade</th></tr>";

  $numbercourses = 0;
  $numbercompleted = 0;


  // Fetch the result from the cursor one by one
  while ($values = oci_fetch_array ($cursor)){
    $sectionid = $values[0];
    $coursenumber = $values[1];
    $coursetitle = $values[2];
    $semester = $values[3];
    $instructor = $values[4];
    $credits = $values[5];
    $grade = $values[6];
    $lettergrade = "" ;
    //show letter grade instead of numeric grade
    if ( $grade == 4 ) { $lettergrade = "A" ; }
    else if ( $grade == 3 ) { $lettergrade = "B"; }
    else if ( $grade == 2 ) { $lettergrade = "C"; }
    else if ( $grade == 1 ) { $lettergrade = "D"; }
    else if ( $grade == 0 ) { $lettergrade = "F"; }
    echo ("<tr> <td>$sectionid</td> <td>$coursenumber</td> <td>$coursetitle </td> <td>$semester</td> <td>$instructor</td> <td>$credits</td> <td> $lettergrade </td> </tr> ");
    //make sure only passed/completed courses are factored into credit total
    if ($grade > 1 and $semester[3] < 9 ) { 
      $numbercompleted += 1;
      $numbercredits += $credits ;
    }
  };

  oci_free_statement ($cursor);

  echo "</table>";
  echo ("<B><U>Total Number of courses completed   :</B></U>   $numbercompleted<BR>");
  echo ("<B><U>Total of credits earned   :</B></U>   $numbercredits<BR>");
  $GPA = get_GPA($userid);
  echo ("<B><U>GPA   :</B></U>   $GPA <BR>");
}
?>

