<?
include "utility_functions.php";
$sessionid = $_GET["sessionid"];
verify_session ($sessionid);
echo ("	<FORM name=\"changepassword\" method=\"POST\" action=\"change_password_action.php?sessionid=$sessionid\">
	Old Password: <INPUT type=\"text\" name=\"oldpassword\" size=\"12\" maxlength=\"14\"> <br />
	New Password: <input type=\"text\" name=\"newpassword\" size=\"12\" maxlength=\"14\" >
	<INPUT type=\"submit\" name=\"submit\" value=\"Change\"> </FORM> ");
?>
