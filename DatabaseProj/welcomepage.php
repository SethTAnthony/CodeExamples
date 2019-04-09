<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

//find the usertype to display a customized menu list
$sql = "select u.usertype
        from usersession us
        left join users u
        on us.userid = u.userid
          where sessionid= '$sessionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$values = oci_fetch_array ($cursor);
$usertype = $values[0];

// Here we can generate the content of the welcome page
echo ("University Homepage: <br />");
echo ("<UL>");

if ($usertype=="student" or $usertype=="stuadmin"){
  echo ("  <LI><A HREF=\"courses.php?sessionid=$sessionid\">Add/Drop/View Courses</A></LI> ");
}

echo (" <LI><A HREF=\"personal_info.php?sessionid=$sessionid\">View/Edit Personal Info</A></LI> ");

if ($usertype=="admin" or $usertype=="stuadmin"){
  echo (" <LI><A HREF=\"admin_functions.php?sessionid=$sessionid\">Admin Functions</A></LI> ");
}

echo (" </UL>");
echo ("<br />");
echo ( "<br />");
echo ("Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.");
?>

