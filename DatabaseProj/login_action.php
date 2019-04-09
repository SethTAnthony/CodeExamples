<?
include "utility_functions.php";

// Get the client id and password and verify them
$userid = $_POST["userid"];
$pword = $_POST["pword"];

$sql = "select userid
        from users
        where userid='$userid'
          and pword ='$pword'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message ($cursor);
  die ("Client Query Failed.");
}

if ($values = oci_fetch_array ($cursor)){
  oci_free_statement ($cursor);

  // found the client
  $userid = $values[0];

  // check to see if the user is already logged in.
  $sql = "select sessionid
	  from usersession
	  where userid='$userid'";
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  // user is logged in, log them out
  if ($values = oci_fetch_array ($cursor)) {
    oci_free_statement ($cursor);
    $oldsession = $values[0];
    $sql = "delete from usersession
	    where sessionid='$oldsession'";
    execute_sql_in_oracle ($sql);
  }


  // create a new session for this client
  $sessionid = md5 (uniqid ( rand ()));

  // store the link between the sessionid and the clientid
  // and when the session started in the session table

  $sql = "insert into usersession (sessionid, userid, sessiondate)
          values ('$sessionid', '$userid', sysdate)";
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];
  if ($result == false){
    display_oracle_error_message ($cursor);
    die ("Failed to create a new session");
  } else {
    // insert OK - we have created a new session
    header ("Location:welcomepage.php?sessionid=$sessionid");
  }
} else {
  // client username not found
  die ('Login failed.  Click <A href="login.html">here</A> to go back to the login page.');
}
?>

