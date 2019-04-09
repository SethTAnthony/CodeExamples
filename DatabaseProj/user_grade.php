<?
include "utility_functions.php";


$sessionid =$_GET["sessionid"];
$userid =$_GET["userid"];
verify_session ($sessionid);

//pull all data about the selected users courses from the database
$sql = " select      sec.sectionid,
            sec.coursenumber,
            sec.coursetitle,
            sec.semester,
            sec.instructor,
            en.grade
          from section sec
          Join enrolledin en on sec.sectionid = en.sectionid
	  where en.userid = '$userid'
	  order by sec.semester desc";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message ($cursor);
  die ("Client Query Failed.");
}

echo "<table border=1>";
echo "<tr> <th>Section ID</th> <th>Course #</th> <th>Course Title</th>  <th>Semester</th> <th> Instructor <th> Grade </th> </tr>" ;

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $sectionid = $values[0];
  $coursenumber = $values[1];
  $coursetitle = $values[2];
  $semester = $values[3];
  $instructor = $values[4];
  $grade = $values[5];


  echo ("<tr> <td>$sectionid</td> <td>$coursenumber</td> <td>$coursetitle </td> <td>$semester</td> <td>$instructor </td>
  <td>  <form method=\"post\" action=\"user_grade_action.php?sessionid=$sessionid&userid=$userid&sectionid=$sectionid\">
  Grade : <input type=\"text\" value = \"$grade\" size=\"2\" maxlength=\"1\" name=\"grade\"
  </td>  ");

  echo (" <td>
  </select>  <input type=\"submit\" value=\"Update\">
  </form>
  </td> </tr>  ");
};

oci_free_statement ($cursor) ;

echo "</table>" ;

echo ("<br> <A HREF = \"admin_functions.php?sessionid=$sessionid\">Go Back</A>");
?>

