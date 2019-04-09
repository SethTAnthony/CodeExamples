<?
include "utility_functions.php";
$sessionid = $_GET["sessionid"];
verify_session ($sessionid);

echo ('Password Reset');

$userid = $_GET["userid"];

//change password in database
$sql = "update users set pword='$userid' where userid='$userid'";
$result_array = execute_sql_in_oracle ($sql);
$results = $result_array["flag"];
$cursor = $result_array["cursor"];

echo ("<BR><BR>");
echo ("<A HREF =\"admin_functions.php?sessionid=$sessionid\">Go Back");

?>
