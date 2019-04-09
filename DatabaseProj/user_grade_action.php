<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$sectionid = $_GET["sectionid"];
$userid = $_GET["userid"];

verify_session ($sessionid);

// Suppress PHP auto warning.
ini_set ( "display_errors", 0);

// Obtain information for the record to be updated.
$grade = $_POST["grade"];

//make sure entered grade is valid
if ($grade >= 0 and $grade <= 4) {
  //update the grade
  $sql = "update enrolledin
          set grade = $grade
          where sectionid = $sectionid and userid = '$userid'" ;
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    // Error handling interface.
    echo "<B>Update Failed.</B> <BR />";

    display_oracle_error_message ($cursor);
    die ("<i>

    <form method=\"post\" action=\"user_grade.php?sessionid=$sessionid&userid=$userid\">

    <input type=\"hidden\" value = \"1\" name=\"update_fail\">
    <input type=\"hidden\" value = \"$grade\" name=\"grade\">

    Read the error message, and then try again:
    <input type=\"submit\" value=\"Go Back\">
    </form>

    </i>
    ");
  } else {
    // calculate GPA and update status
    $statusString = "good";
    $GPA = get_GPA($userid);

    if ($GPA < 2) {
      $statusString = "probation";
    }

    $sql = "update studentuser set status = '$statusString' where userid = '$userid'";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    echo ("Grade Updated") ;
    echo ("<br>") ;
    echo ("<A HREF = \"user_grade.php?sessionid=$sessionid&userid=$userid\">Go Back</A>");
  }
} else {
  echo (" Grade must be between 0 and 4 ") ;
  echo ("<br>") ;
  echo ("<A HREF = \"user_grade.php?sessionid=$sessionid&userid=$userid\">Go Back</A>");
}
?>


