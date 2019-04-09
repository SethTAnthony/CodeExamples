<?
// this code is deprecated


include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);


// suppress php auto warning.
ini_set ("display_errors", 0);  

// obtain input from user_delete.php
$userid = $_POST["userid"];

//test for student type
$sql = "select usertype from users where userid='$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($values = oci_fetch_array ($cursor)) {
  oci_free_statement ($cursor);
  //delete from studentuser table before deleting from users table
  if($values[0] == 'student' or $values[0] == 'stuadmin') {
    $sql = "delete from studentuser where userid='$userid'";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
    if ($result == false){ 
      // Error occured.  Display error-handling interface.
      echo "<B>Deletion Failed.</B> <BR />";

      display_oracle_error_message ($cursor);

      die ("<i>

	    <form method=\"post\" action=\"admin_functions.php?sessionid=$sessionid\">
	    Read the error message, and then try again:
	    <input type=\"submit\" value=\"Go Back\">
	    </form>

  	    </i>
      ");
    }
  }
}
// Finally delete from users table
$sql = "delete from users where userid = '$userid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ 
  // Error occured.  Display error-handling interface.
  echo "<B>Deletion Failed.</B> <BR />";

  display_oracle_error_message ($cursor);

  die ("<i>

  <form method=\"post\" action=\"admin_functions.php?sessionid=$sessionid\">
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record deleted.  Go back automatically.
Header ("Location:admin_functions.php?sessionid=$sessionid");
?>

