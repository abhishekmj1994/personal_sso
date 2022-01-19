<?php
error_reporting(1);
if(strpos($_SERVER['PHP_SELF'],"pages") !== false) { 
	$vars = trim(file_get_contents('../../../7c6a180b36896a0a8c02787eeafb0e4'));  
}else {
	$vars = trim(file_get_contents('../../7c6a180b36896a0a8c02787eeafb0e4')); 
}
/*$con = mysql_connect("localhost","psodev","$vars");
$dbname="checklist_portal";
$db = mysql_select_db( $dbname, $con );
if($db){ //echo "<script> alert('connected'); </script>"; 
} else { 	echo "disconnect"; }	
//include("include/funct.php");*/
date_default_timezone_set("Asia/Kolkata");
$servername = "localhost";
$username = "psodev"; // For MYSQL the predifined username is root
$password = $vars; // For MYSQL the predifined password is " "(blank)
// Create connection OOPS
$conn = new mysqli($servername, $username, $password,"checklist_portal");
// Check connection
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
//	echo "Connected successfully";
}
?>
