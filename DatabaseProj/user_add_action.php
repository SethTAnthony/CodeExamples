<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

// Suppress PHP auto warnings.
ini_set ( "display_errors", 0);

// Get the values of the record to be inserted.
$sql = "select count(*) from users";

//calculate the current sequence id
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$values = oci_fetch_array ($cursor);
$numUsers = $values[0];
$numUsers = $numUsers + 1;

//format user name and id
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$fname = strtolower($fname);
$fname = ucfirst($fname);
$lname = strtolower($lname);
$lname = ucfirst($lname);

$age = $_POST["age"];
$address = $_POST["address"];
$usertype = $_POST["usertype"];
$studenttype = $_POST["studenttype"];

//final formatted user id
$userid = strtolower($fname[0]) . strtolower($lname[0]) . $numUsers;

//make sure user isn't already created
$insertable = false;
$sql = "select userid from users where userid='$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($values = oci_fetch_array ($cursor)) {
  echo ("That User ID is already taken!");
  $insertable = false;
} else if ($userid != NULL and $fname != NULL and $lname != NULL and $usertype != NULL) {
  // Form the insertion sql string and run it.
  $sql = "insert into users values ('$userid', '$fname', '$lname', '$userid', '$usertype' ,'$age', '$address')";
  $insertable = true;
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];
  if($usertype != 'admin') {
    $sql = "insert into studentuser values ('$userid', 4, 'good', '$studenttype')";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
  }
  if ($result == false) $insertable = false;
}

if (!$insertable) {
  // Error handling interface.
  echo "<B> Insertion Failed.</B> <BR />";
  die ("<i>
  <form method=\"post\" action=\"user_add.php?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$userid\" name=\"eid\">
  <input type=\"hidden\" value = \"$fname\" name=\"fname\">
  <input type=\"hidden\" value = \"$lname\" name=\"lname\">
  <input type=\"hidden\" value = \"$usertype\" name=\"usertype\">

  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record inserted.  Go back.
Header ("Location:admin_functions.php?sessionid=$sessionid");

?>

