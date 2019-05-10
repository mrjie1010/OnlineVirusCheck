
<?php
session_start();

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

$query = "CREATE TABLE IF NOT EXISTS input( Name VARCHAR(128) NOT NULL, Content VARCHAR(128), username VARCHAR(128) NOT NULL);";
$result = $conn->query($query);
if(!$result) die($conn->error);
$query = "CREATE TABLE IF NOT EXISTS contriFile( Name VARCHAR(128) NOT NULL, Content VARCHAR(128), username VARCHAR(128) NOT NULL);";
$result = $conn->query($query);
if(!$result) die($conn->error);

$unn = $_SESSION['username'];
if (isset($_POST['upload'])) {
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
        $str = file_get_contents($_FILES["file"]["tmp_name"]);

                $query = "INSERT INTO contriFile (Name, Content, username) VALUES ('$Name', '$str', '$unn')";
                $result = $conn->query($query);
                if (! $result){
                    echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";}         
    }}
            
        
    

echo "Welcome, ". $_SESSION['firstname']."<br>";








if (isset($_POST['Logout'])) {
    header('Location: logout.php'); 
    exit();
}

if (isset($_POST['delete'])) {
    $query = "Delete from contriFile where username = '$unn';";
    $result = $conn->query($query);
    if (! $result)
        echo "DELETE failed: $query<br>" . $conn->error . "<br><br>";
}
echo <<<_END
<form action="contributorPage.php" method="post" enctype="multipart/form-data">

<input type="submit" value="Logout" name="Logout">
<h2 style="background-color:orange;"><center>Congratulation! You are login as a Contributor!</h4>
<hr>
<h3>Please enter the Name and upload a txt File:</h3>
Name:    <input type="text" name="Name" ;">
    Select file to upload ->
    <input type="file" name="file"><br><br>
    <input type="submit" value="Upload" name="upload">
<hr>
<h3>Waiting for admin to double check your uploaded file...</h3>
You can delete all content from our DB by clicking here -><input type="submit" value="delete all content" name="delete">
</form>
_END;

$query = "SELECT * FROM contriFile where username = '$unn'";
$result = $conn->query($query);


if (! $result)
    die($conn->error);
$rows = $result->num_rows;

for ($j = 0; $j < $rows; ++ $j) {
    $result->data_seek($j);

    echo "<br>" . "Name: " . $result->fetch_assoc()['Name'] . "<br>";
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

