<?
include "utility_functions.php";
$sessionid =$_GET["sessionid"];
verify_session ($sessionid);


// Generate the query section
echo ("
  <form method=\"post\" action=\"admin_functions.php?sessionid=$sessionid\">
  User ID: <input type=\"text\" size=\"10\" maxlength=\"10\" name=\"q_userid\"> 
  Firstname: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_fname\"> 
  Lastname: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_lname\"> 
  Section: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_section\">
  Status: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_status\">
  <BR />
  ");

echo ("
  </select>
  <input type=\"submit\" value=\"Search\">
  </form> 
  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
");

// Interpret the query requirements
$q_userid = $_POST["q_userid"];
$q_fname = $_POST["q_fname"];
$q_lname = $_POST["q_lname"];
$q_section = $_POST["q_section"];
$q_status = $_POST["q_status"];

$whereClause = " 1 = 1";
$joinClause = " ";
if (isset ($q_userid) and trim ($q_userid) != "") {
  $whereClause .= " and u.userid = '%$q_userid'%";
}

if (isset ($q_fname) and $q_fname != "") {
  $whereClause .= " and u.fname like '%$q_fname%'";
}

if (isset ($q_lname) and $q_lname != "") {
  $whereClause .= " and u.lname like '%$q_lname'%";
}

if (isset ($q_section) and $q_section != "") {
  $whereClause .= " and en.sectionid like '$q_section'";
  $joinClause = "join enrolledin en on u.userid=en.userid";
}
if (isset ($q_status) and $q_status != "") {
  $whereClause .= " and su.status like '$q_status'";
  $joinClause = "join studentuser su on u.userid=su.userid";
}

// Form the query statement and run it.
$sql = "select u.userid, u.fname, u.lname , u.usertype
        from users u
	$joinClause
        where $whereClause order by u.lname";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message ($cursor);
  die ("Client Query Failed.");
}

// Display the query results
echo "<table border=1>";
echo "<tr> <th>UserId</th> <th>Firstname</th> <th>Lastname</th>  <th>Update</th>  <th>Reset Password</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $userid = $values[0];
  $fname = $values[1];
  $lname = $values[2];
  $usertype = $values[3] ;

  echo ("<tr>" .
    "<td>$userid</td> <td>$fname</td> <td>$lname</td>".
    " <td> <A HREF=\"user_update.php?sessionid=$sessionid&userid=$userid\">Update</A> </td> " .
    " <td> <A HREF=\"reset_password.php?sessionid=$sessionid&userid=$userid\">Reset</A> </td> " );
    if ($usertype != 'admin') 
	echo ( " <td> <A HREF=\"user_grade.php?sessionid=$sessionid&userid=$userid\">Update Grades</A> </td> " );
    echo (   "</tr>");
}

oci_free_statement ($cursor);

echo "</table>";
echo ("
  <BR>
  <form method=\"post\" action=\"user_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add New User\">
  </form>
")


?>
