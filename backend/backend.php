<?php
$servername = "localhost";
$username = "toor";
$password = "19711Login";

// Create connection
$conn = mysqli_connect($servername, $username, $password, "HydroMail");

$cmd = cs($_POST['action'], $conn);
$username = cs($_POST['username'], $conn);
$password = cs($_POST['password'], $conn);
$to = cs($_POST['to'], $conn);
$subject = cs($_POST['subject'], $conn);
$body = cs($_POST['body'], $conn);
$option = cs($_POST['option'], $conn);


// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




if ($cmd == "send") {
        $sql = "SELECT id FROM Users WHERE username='".$username."' AND password='".$password."'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
		$v = strpos($to, '.');
		if ($v !== false) {
			
			$to = str_replace("[at]", "@", $to);
			$message = wordwrap($body, 70, "\r\n");
			$headers = 'From: '. $username .'@HydroMail' . "\r\n" .
			    'Reply-To: ' . $username .'@omnihydro.com' . "\r\n" .
			    'Client-IP: ' . $_SERVER['REMOTE_ADDR'] . "\r\n" .
			    'X-Mailer: Planet Express';
			mail($to, $subject, $message, $headers);
			echo 'Sent';

		} else {
			$sql = "INSERT INTO Mail (Sender, Recepient, Subject, Body)
			VALUES ('".$username."', '".$to."', '".$subject."', '".$body."')";
			
			if (mysqli_query($conn, $sql)) {
			    echo "Sent";
			} else {	
			    echo "Failed";
			    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
         } else {
                 echo 'false';
         }
	
} elseif ($cmd == "login") {

	$sql = "SELECT id FROM Users WHERE username='".$username."' AND password='".$password."'";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		echo 'true';	
	} else {
		echo 'false';
	}
		
} elseif ($cmd == "getR") {
         $sql = "SELECT id FROM Users WHERE username='".$username."' AND password='".$password."'";
         $result = mysqli_query($conn, $sql);
         if (mysqli_num_rows($result) > 0) {
		$sql = "SELECT id, Sender, Recepient, Subject, Body FROM Mail WHERE Recepient='".$username."' ORDER BY id DESC";
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) {
		    // output data of each row
		    echo "{";
		    while($row = mysqli_fetch_assoc($result)) {
		        echo "'{\"" . $row['Sender'] ."\", \"" . $row['Subject'] . "\", \"" . $row['Body'] . "\", \"" . $row['id'] . "\"}',";
		    }
		    echo "}";
		} else {
		    echo "Empty";
		}
         } else {
                 echo 'false';
         }

} elseif ($cmd == "delete") {
	$sql = "SELECT id FROM Users WHERE username='".$username."' AND password='".$password."'";

	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {	
	       

		$sql = "DELETE FROM Mail WHERE id='".$option."'";

		if (mysqli_query($conn, $sql)) {
		    echo "DS";
		} else {
		    echo "DF";
		}


	} else {
	        echo 'DI';
	}

}


function cs($string, $conn) {
                $string = stripslashes($string);
                $string = mysqli_real_escape_string($conn, $string);
		$string = str_replace('"', "", $string);
		$string = str_replace("'", "", $string);
                return $string;
        }


mysqli_close($conn);
?>
