
<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);


$query = "CREATE TABLE IF NOT EXISTS user(lastname VARCHAR(32) NOT NULL, email VARCHAR(32) NOT NULL, username VARCHAR(32) NOT NULL UNIQUE, password VARCHAR(32) NOT NULL)";
$result = $conn->query($query);
if(!$result) die($conn->error);

$ln = 'admin';
$email = 'admin@email.com';
$un = 'admin';
$password = 'admin';
$salt1 = "*&g!";
$salt2 = "hb%$";
$token = hash('ripemd128', "$salt1$password$salt2");

$query2 = "INSERT INTO user (lastname, email,username, password) VALUES ('$ln', '$email','$un','$token')";
$result = $conn->query($query2);


echo <<<_END
<form action="authentication.php" method="post" enctype="multipart/form-data" >
<div align="center">
<h2 style="background-color:DodgerBlue;"><center>Welcome to File Uploading Website!</h4>
<hr>
<pre>
Username:  <input type="text" name="un">
Password:  <input type="text" name="pw">
<input type="submit" name="login" value = "Login" action="authentication.php">
</pre>
<br>
If you don't have a account, click ->
<input type="button" name="signup" value="Sign Up" onclick="location.href='Signup.php'">
<hr>
If you have account and want to be a Contributor, login by here
<br>
<pre>
Username:  <input type="text" name="us">
Email:     <input type="text" name="eml">
Password:  <input type="text" name="pswd">
<input type="submit" name="contrilog" value = "Login" action="authentication.php">
</pre>
</br>
 <hr>
</div>
</form> 
_END;


?>
