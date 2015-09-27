<html>
<head>
<?php



$captcha = $_POST['g-recaptcha-response'];

if(!$captcha){
  echo "<h2>Please check the the captcha form.</h2>";
  exit;
}
$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcGuAwTAAAAAHOA5pfY-8J6IlmSwGhGmr-hHTKQ&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
if($response['success'] == false){
  echo "<h2>Please check the the captcha form.</h2>";
} else {
	$servername = "localhost";
	$dbusername = "toor";
	$dbpassword = "19711Login";
	$dbname = "HydroMail";
	
	// Create connection
	$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	$username = cs($_POST['user'], $conn);
	$password = cs($_POST['pass'], $conn);
//	echo $username;
//	echo $password;
	$password = hash('sha256', $password);

	
	$sql = "SELECT id FROM Users WHERE username='".$username."'";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) < 1) {
		$sql = "INSERT INTO Users (username, password)
		VALUES ('".$username."', '".$password."')";

		if (mysqli_query($conn, $sql)) {
			echo '<h2>Your Account Has Been Created, ' . $username . '</h2>';
		} else {
		    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}    
	} else {
	    echo "Username, " . $username . ", Has Been Taken";
	}
}

function cs($string, $conn) {
                $string = stripslashes($string);
               	$string = mysqli_real_escape_string($conn, $string);
                $string = str_replace('"', "", $string);
                $string = str_replace("'", "", $string);
                return $string;
        }

?>
</head>
</html>
