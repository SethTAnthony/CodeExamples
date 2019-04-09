<?
include "utility_functions.php";

$sessionid = $_GET["sessionid"];
verify_session ($sessionid);

// Get the client id and password and verify them
$oldpassword = $_POST["oldpassword"];
$newpassword = $_POST["newpassword"];

$sql = "select u.pword,us.userid ".
       "from usersession us ".
       "left join users u ".
       "on u.userid = us.userid ".
       "where us.sessionid = '$sessionid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message ($cursor);
  die ("Client Query Failed.");
}

if ($values = oci_fetch_array ($cursor))
  oci_free_statement ($cursor);

// Make sure correct password is entered
if ($oldpassword == $values[0]) {
      echo ('Password Updated'); 
	$sqlupdate = "update users set pword='$newpassword' where userid='$values[1]'";
	execute_sql_in_oracle ($sqlupdate); 
} else {
     echo ('Incorrect Password Entered.');  
}
echo ("<BR><BR>");
echo ("<A href=\"personal_info.php?sessionid=$sessionid\">Go Back</A>");
?>

