
<?php

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
    

   
    if (isset($_POST['submit'])) {
        $ln = sanitizeString(get_post($conn, "ln"));
        $em = sanitizeString(get_post($conn, "email"));
        $un = sanitizeString(get_post($conn, "un"));
        $psw = sanitizeString(get_post($conn, "pwd"));
        $salt1 = "*&g!";
        $salt2 = "hb%$";
        
        $token = hash('ripemd128', "$salt1$psw$salt2");
        $query = "INSERT INTO user (lastname, email,username, password) VALUES ('$ln', '$em','$un','$token')";
        $result = $conn->query($query);
        if (! $result)
            echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
            else echo "Your account has been created successfully!";
    }
    
    
echo <<<_END
<form action="Signup.php" method="post" enctype="multipart/form-data">
<pre>

<h2 style="background-color:DodgerBlue;"><center>Input the information for Signing up</h4>

Last Name:  <input type="text" name="ln">
Email:      <input type="text" name="email">
Username:   <input type="text" name="un">
Password:   <input type="text" name="pwd">
<input type="submit" value="Submit" name="submit">
<hr>
<input type="button" name="goback" value="Go Back to Main Page" onclick="location.href='mainPage.php'">
</form>
_END;




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

