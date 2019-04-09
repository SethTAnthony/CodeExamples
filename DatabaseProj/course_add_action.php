<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

// Suppress PHP auto warnings.
ini_set ( "display_errors", 0);


//get the userid
$sectionid = $_GET["sectionid"];
$errMsg = "";
$sql = "select u.userid
	from users u join usersession us on u.userid = us.userid
	where us.sessionid = '$sessionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($values = oci_fetch_array ($cursor) ) {
  $userid = $values[0];
}
//this flag keeps track of errors
$insertable = true;

//check if already enrolled in course
$sql = "select * from
	enrolledin
	where userid='$userid' and sectionid=$sectionid";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if($values = oci_fetch_array ($cursor) ) {
$errMsg = "You are already enrolled in or have already passed this class.";
  $insertable = false;
}

//find associated coursenumber for given sectionid
$sql = "select coursenumber 
	from section
        where sectionid = $sectionid" ;
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if($values = oci_fetch_array ($cursor)) {
  $coursenumber = $values[0];
}

//make sure the class hasn't already been passed
$sql2 = "select * from enrolledin en
         join section sec
         on en.sectionid = sec.sectionid
         where en.userid = '$userid' and sec.coursenumber = '$coursenumber' and en.grade > 1 " ;
$result_array = execute_sql_in_oracle ($sql2);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if($values = oci_fetch_array ($cursor) ) {
  $insertable = false ;
  $errMsg = "You are already enrolled in or have already passed this class.";
}

//ensure that student isn't enrolling in class past deadline
$sql = "select semester from section
	where sectionid = $sectionid";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if($values = oci_fetch_array ($cursor) ) {
   $date = $values[0];
   $year = $date[3];
   if ($year < 9) {
     $insertable = false ;
     $errMsg = "You are passed the enrollment deadline for this course";
   }
}

//ensure that the student has already taken the prerequisites before enrollment
$sql = "select prereqnumber from course_prereq
        where coursenumber = '$coursenumber' ";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
 while ( $values = oci_fetch_array ($cursor) and $insertable == true ) {
$prereqnumber = $values[0] ;
$sql2 = "select coursenumber from enrolledin en
         join section sec on en.sectionid = sec.sectionid
         where en.userid = '$userid'  and sec.coursenumber = '$prereqnumber' " ;
$result_array2 = execute_sql_in_oracle ($sql2);
$result2 = $result_array2["flag"];
$cursor2 = $result_array2["cursor"];
if ($values2 = oci_fetch_array ($cursor2) ) {
}
else { $insertable = false ;
      $errMsg = " Missing Prerequisite. " ;
}
 }





//proceed with enrollment if no error is found
if($insertable == true) {
  $sql = "insert into enrolledin values ($sectionid,'$userid',4)";
  $result_array = execute_sql_in_oracle ($sql);
  if ($result = $result_array["flag"]){
    $cursor = $result_array["cursor"];
    echo ("Course added.");
  } else
    $errMsg = "This class has no more open seats.";
} else {
  echo ($errMsg);
}

echo ("<BR><BR>");
echo ("<A HREF = \"course_view.php?sessionid=$sessionid\">Go Back</A>");
?>
