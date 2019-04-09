<?
include "utility_functions.php";
$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

$sectionid = $_GET["sectionid"];

//obtain userid
$sql = "select u.userid
	from users u join usersession us on u.userid = us.userid
	where us.sessionid = '$sessionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ( $values = oci_fetch_array ($cursor) ) {
  $userid = $values[0];
}

// Delete course from enrolledin
$sql = "delete from enrolledin where userid = '$userid' and sectionid = '$sectionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ // error unlikely
  display_oracle_error_message ($cursor);
  die("Client Query Failed.");
}

oci_free_statement ($cursor);

echo ("Course Dropped.");
echo ("<BR><BR>");
echo ("<A HREF = \"courses.php?sessionid=$sessionid\">Go Back</A>");
?>
