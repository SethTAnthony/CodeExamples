<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

// Suppress PHP auto warning.
ini_set( "display_errors", 0);

// Obtain information for the record to be updated.
$userid = $_POST["userid"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$usertype = $_POST["usertype"];
$age = $_POST["age"];
$address = $_POST["address"];
$studenttype = "";
if ($usertype != "admin") $studenttype = $_POST["studenttype"];

// Form the sql string and execute it.
$sql = "select * from users where userid = '$userid' for update";
$result_array = execute_sql_in_oracle ($sql);
$sql = "update users set fname = '$fname', lname = '$lname', usertype = '$usertype', age = '$age', address = '$address' where userid='$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if($result != false and $usertype != "admin") {
  $sql = "update studentuser set studenttype = '$studenttype' where userid='$userid'";
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];
}


if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  die("<i>

  <form method=\"post\" action=\"user_update.php?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$userid\" name=\"userid\">
  <input type=\"hidden\" value = \"$fname\" name=\"fname\">
  <input type=\"hidden\" value = \"$lname\" name=\"lname\">
  <input type=\"hidden\" value = \"$usertype\" name=\"usertype\">
  <input type=\"hidden\" value = \"$age\" name=\"age\">
  <input type=\"hidden\" value = \"$address\" name=\"address\">
  <input type=\"hidden\" value = \"$studenttype\" name=\"studenttype\">

   Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record updated.  Go back.
Header("Location:admin_functions.php?sessionid=$sessionid");
?>

