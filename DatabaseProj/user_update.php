<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

if (!isset($_POST["update_fail"])) { //if this is the first attempt at updating
  // Fetch the record to be updated.
  $q_userid = $_GET["userid"];

  // the sql string
  $sql = "select userid, fname, lname, usertype, age, address from users where userid = '$q_userid'";

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    display_oracle_error_message ($cursor);
    die ("Query Failed.");
  }

  $values = oci_fetch_array ($cursor);
  oci_free_statement ($cursor);

  $userid = $values[0];
  $fname = $values[1];
  $lname = $values[2];
  $usertype = $values[3];
  $age = $values [4];
  $address = $values [5];
  $studenttype;
  if ($usertype != "admin") {
    $sql = "select studenttype
	    from studentuser
	    where userid = '$userid'";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
    $values = oci_fetch_array ($cursor);
    $studenttype = $values[0];
  }
}
else { // from user_update_action.php
  // Obtain values of the record to be updated directly.
  $userid = $_POST["userid"];
  $fname = $_POST["fname"];
  $lname = $_POST["lname"];
  $usertype = $_POST["usertype"];
  $age = $_POST["age"];
  $address = $_POST["address"];
  if ($usertype != "admin") $studenttype = $_POST["studenttype"];
}

//table formatting logic to initialize a checked box
$isStudent = "";
$isStuadmin = "";
$isAdmin = "";
switch ($usertype) {
  case "student":
    $isStudent = "checked";
    break;
  case "stuadmin":
    $isStuadmin = "checked";
    break;
  case "admin":
    $isAdmin = "checked";
    break;
}
if($usertype != "admin") {
  $isUndergrad = "";
  $isGrad = "";
  switch ($studenttype) {
    case "undergraduate":
      $isUndergrad = "checked";
      break;
    case "graduate":
      $isGrad = "checked";
      break;
   }
}
// Display the record to be updated.
echo("
  <form method=\"post\" action=\"user_update_action.php?sessionid=$sessionid\">
  User Id (Read-only): <input type=\"text\" readonly value = \"$userid\" size=\"10\" maxlength=\"10\" name=\"userid\"> <br /> 
  Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"30\" name=\"fname\">  <br />
  Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"30\" name=\"lname\">  <br />
  Age: <input type=\"text\" value = \"$age\" size=\"3\" maxlength=\"3\" name=\"age\">  <br />
  Address: <input type=\"text\" value = \"$address\" size=\"30\" maxlength=\"50\" name=\"address\">  <br />
  <div>
  User Type (Required): <input type=\"radio\" value=\"student\" name=\"usertype\" $isStudent required/>Student</label>
  <input type=\"radio\" value=\"stuadmin\" name=\"usertype\" $isStuadmin/>Student Admin</label>
  <input type=\"radio\" value=\"admin\" name=\"usertype\" $isAdmin/>Admin</label>
  </div> 
  Student Level: <input type=\"radio\" value=\"undergraduate\" name=\"studenttype\" $isUndergrad/>Undergraduate</label>
  <input type=\"radio\" value=\"graduate\" name=\"studenttype\" $isGrad/>Graduate</label>
");


echo("
  <BR>
  </select>  <input type=\"submit\" value=\"Update\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"admin_functions.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");
?>

