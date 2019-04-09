<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

// Get values for the record to be added if from user_add_action.php
$userid = $_POST["userid"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];

// display the insertion form.
echo ("
  <form method=\"post\" action=\"user_add_action.php?sessionid=$sessionid\">
  Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"30\" name=\"fname\">  <br />
  Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"30\" name=\"lname\">  <br />
  Age: <input type=\"text\" value = \"$age\" size=\"3\" maxlength=\"3\" name=\"age\">  <br />
  Address: <input type=\"text\" value = \"$address\" size=\"50\" maxlength=\"50\" name=\"address\">  <br />
  <div>
  User Type (Required): <input type=\"radio\" value=\"student\" name=\"usertype\" checked required/>Student</label>
  <input type=\"radio\" value=\"stuadmin\" name=\"usertype\" />Student Admin</label>
  <input type=\"radio\" value=\"admin\" name=\"usertype\" />Admin</label>
  </div>
  <div>
  Student Level (Required): <input type=\"radio\" value=\"undergraduate\" name=\"studenttype\" check required/>Undergraduate</label>
  <input type=\"radio\" value=\"graduate\" name=\"studenttype\" check required/>Graduate</label>
  </div>
");

echo ("
  </select>
  <BR>
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"admin_functions.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");
?>

