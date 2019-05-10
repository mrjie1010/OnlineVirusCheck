
<?php
session_start();

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

$query = "CREATE TABLE IF NOT EXISTS adminFile( Name VARCHAR(128) NOT NULL, Content VARCHAR(128));";
$result = $conn->query($query);
if(!$result) die($conn->error);

$unn = $_SESSION['username'];
if (isset($_POST['submit'])) {
    if ($_FILES["file"]["type"] !== "text/plain") {
        echo "***********************"."<br>";
        echo "* You must select a txt file. *" . "<br>";
        echo "***********************"."<br>";
    } else if (get_post($conn, "Name") == "") {
        echo "********************"."<br>";
        echo "* Please enter a valid name. *". "<br>";
        echo "***********************"."<br>";
    } else {
        $Name =sanitizeString(get_post($conn, "Name"));
        $str = substr(file_get_contents($_FILES["file"]["tmp_name"]),0 ,20);
        
        $query = "INSERT INTO adminFile (Name, Content) VALUES ('$Name', '$str')";
        $result = $conn->query($query);
        if (! $result)
            echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
            else 
            $message = "Upload the malware file successfully!";
            echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
echo "Welcome, ". $_SESSION['firstname']."<br>";

if (isset($_POST['check'])) {
   
    
    $query = "SELECT * FROM contriFile";
    $result = $conn->query($query);
    if (! $result)
        die($conn->error);
    $rows = $result->num_rows;

        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            echo "<br>" ."Username: ". $result->fetch_assoc()['username'] ."<br>". "Name: " . $result->fetch_assoc()['Name'] . "<br>";
            $result->data_seek($j);
            echo "Content: <br>" . $result->fetch_assoc()['Content'] . "<br>";
            
            $Name =$result->fetch_assoc()['Name'];
            $str = $result->fetch_assoc()['Content'];
            $query0 = "SELECT Content FROM adminFile";
            $result4 = $conn->query($query0);
            if(!$result4) die($conn->error);
            $rows2 = $result4->num_rows;
            $index = 1;
            $tof = FALSE;
            while($index <= $rows2 AND !$tof)
            {
                $result4->data_seek($index - 1);
                $row2 = $result4->fetch_array(MYSQLI_NUM);
                $tof = strpos($str, $row2[0]);
                $index++;
            }
            
            
            if($tof === FALSE)
            {
                $message = "Good News! File Name: ". "$Name ". " is not a virus file!";
                echo "<script type='text/javascript'>alert('$message');</script>";
                    
            }
            else
            {
                $message = "Attention! File Name: ". "$Name ". "It is a Malware File! It has been deleted!";
                echo "<script type='text/javascript'>alert('$message');</script>";
                $query = "Delete from contriFile where Name = '$Name';";
                $result = $conn->query($query);
                if (! $result)
                    echo "DELETE failed: $query<br>" . $conn->error . "<br><br>";
            }
            
            
            
        }
        
     
    
  
        
       
        
        
}


if (isset($_POST['Logout'])) {
    header('Location: logout.php'); 
    exit();
}



echo <<<_END

<form action="adminPage.php" method="post" enctype="multipart/form-data">

<input type="submit" value="Logout" name="Logout">
<h2 style="background-color:red;"><center>Administrator's Page</h4>
<hr>
<h3>Please enter the Name and upload a txt Malware File:</h3>
Name:    <input type="text" name="Name" ;">
    Select malware file to upload ->
    <input type="file" name="file"><br><br>
    <input type="submit" value="submit" name="submit">
    
<hr>
<h3>Below Files from contributor need your double check, Click by here to check all files </h3> 

<input type="submit" value="Check" name="check">

<hr>

</form>
_END;

$query3 = "SELECT * FROM contriFile";
$result = $conn->query($query3);


if (! $result)
    die($conn->error);
$rows3 = $result->num_rows;

for ($j = 0; $j < $rows3; ++ $j) {
    $result->data_seek($j);

    echo "<br>" ."Username: ". $result->fetch_assoc()['username'] ."<br>". "Name: " . $result->fetch_assoc()['Name'] . "<br>";
    $result->data_seek($j);
    echo "Content: <br>" . $result->fetch_assoc()['Content'] . "<br>";


}

$result->close();
$conn->close();

function mysql_fatal_error($msg, $conn)
{
    $msg2 = mysqli_error($conn);
    echo <<< _END
We are sorry, but it was not possible to complete
the requested task. The error message we got was:

	<p>$msg: $msg2</p>
	
Please click the back button on your browser
and try again. If you are still having problems,
please <a href="mailto:admin@server.com">email
our administrator</a>. Thank you.
_END;
}

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}
function sanitizeMySQL($connection, $var) {
    $var = $connection->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}



?>

