<?
// this code is deprecated


include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);


// Obtain input from admin_functions.php
$q_userid = $_GET["userid"];

// Check to make sure you aren't deleting your own account
$sql = "select sessionid, userid from usersession where userid='$q_userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$allowdeletion = true;
if ( $values = oci_fetch_array ($cursor) ) {
  oci_free_statement ($cursor);
  if ( $values[0] == $sessionid) {
    echo ('You cannot delete your own account');
    echo ("<BR><BR><A HREF=\"admin_functions.php?sessionid=$sessionid\">Go Back");
    $allowdeletion = false;
  } else{
    $sql = "delete from usersession where userid='$q_userid'";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
  }
}

if ($allowdeletion) {

// Retrieve the tuple to be deleted and display it.
$sql = "select userid, fname, lname from users where userid = '$q_userid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ // error unlikely
  display_oracle_error_message ($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
  //Record already deleted by a separate session.  Go back.
  //Header("Location:admin_functions.php?sessionid=$sessionid");
}
oci_free_statement ($cursor);

$userid = $values[0];
$fname = $values[1];
$lname = $values[2];

// Display the tuple to be deleted
echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\">
  UserID (Read-only): <input type=\"text\" readonly value = \"$userid\" name=\"userid\"> <br /> 
  First Name: <input type=\"text\" disabled value = \"$fname\" name=\"fname\">  <br />
  Last Name: <input type=\"text\" disabled value = \"$lname\" name=\"lname\">  <br />
  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");
}
?>

