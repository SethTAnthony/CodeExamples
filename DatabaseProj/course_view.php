<?
include "utility_functions.php";
$sessionid =$_GET["sessionid"];
verify_session ($sessionid);

// Generate the query section
echo ("
  <form method=\"post\" action=\"course_view.php?sessionid=$sessionid\">
  Section ID: <input type=\"text\" size=\"10\" maxlength=\"10\" name=\"q_sectionid\">
  Course #: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_coursenumber\">
  Course Title: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_coursetitle\">
  <BR />
");

echo ("
  </select>
  <input type=\"submit\" value=\"Search\">
  </form>
  <form method=\"post\" action=\"courses.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
");

// Interpret the query requirements
$q_sectionid = $_POST["q_sectionid"];
$q_coursenumber = $_POST["q_coursenumber"];
$q_coursetitle = $_POST["q_coursetitle"];

$whereClause = " 1 = 1";

if (isset ($q_sectionid) and trim ($q_sectionid) != "") {
  $whereClause .= " and sectionid = '$q_sectionid'";
}

if (isset ($q_coursenumber) and $q_coursenumber != "") {
  $whereClause .= " and sec.coursenumber like '%$q_coursenumber%'";
}

if (isset ($q_coursetitle) and $q_coursetitle != "") {
  $whereClause .= " and sec.coursetitle like '$q_coursetitle'";
}


// Form the query statement and run it.
$sql = "select sec.sectionid, sec.coursenumber, sec.coursetitle , c.credits , sec.semester ,
               sec.instructor , sec.seatsopen ,sec.capacity , sec.Time
        from section sec join course c on sec.coursenumber = c.coursenumber
        where $whereClause order by coursetitle";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message ($cursor);
  die ("Client Query Failed.");
}

// Display the query results
echo "<table border=1>";
echo "<tr> <th>SectionId</th> <th>Course #</th> <th>Course Title</th> <th> # of Credits </th>  <th>Semester</th>  <th>Instructor</th> <th> Seats Open </th> <th> Capacity </th> <th> Time </th> <th> Add course </th> </tr>" ;

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $sectionid = $values[0];
  $coursenumber = $values[1];
  $coursetitle = $values[2];
  $credits = $values[3];
  $semester = $values[4];
  $instructor = $values[5];
  $seatsopen = $values[6];
  $capacity = $values[7];
  $time = $values[8];

  echo (
    "<tr><td><A HREF=\"course_view_action.php?sessionid=$sessionid&sectionid=$sectionid\">$sectionid</A></td> <td>$coursenumber</td> <td>$coursetitle</td> <td>$credits</td> <td>$semester </td> <td> $instructor </td><td>$seatsopen</td> <td> $capacity </td> <td> $time </td> " .
    "<td> <A HREF=\"course_add_action.php?sessionid=$sessionid&sectionid=$sectionid\">ADD</A> </td></tr>
  ");
}

oci_free_statement ($cursor);

echo "</table>";


?>
