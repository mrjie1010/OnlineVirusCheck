
<?php // authenticate.php

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);


if (isset($_POST['login'])) {
    $un =  mysql_entities_fix_string($conn,get_post($conn, 'un'));
    $pw =  mysql_entities_fix_string($conn,get_post($conn, 'pw'));
    
    $query = "SELECT * FROM user WHERE username='$un'";
    $result = $conn->query($query);
    if (!$result){
        die($conn->error);}     
        else if($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            
            //two salts
            $salt1 = "*&g!";
            $salt2 = "hb%$";
            
            $token = hash('ripemd128', "$salt1$pw$salt2");
            //echo $token;
            //echo "token:". $token. '<br>';
            //echo "row[3]:". $row[3]. '<br>';
            if('admin' == $row[2])
            {
                session_start();
                $_SESSION['username'] = $un;
                $_SESSION['password'] = $pw;
                $_SESSION['firstname'] = $row[0];
                $_SESSION['lastname'] = $row[1];
                // echo "Hi! $row[0] $row[1], you are user now logged in as '$row[2]'";
                header('Location: adminPage.php');
            }
            else if($token == $row[3])
            {
                session_start();
                $_SESSION['username'] = $un;
                $_SESSION['password'] = $pw;
                $_SESSION['firstname'] = $row[0];
                $_SESSION['lastname'] = $row[1];
                // echo "Hi! $row[0] $row[1], you are user now logged in as '$row[2]'";
                header('Location: userPage.php');
            }
            else
                
                echo "Invalid username/password combination";
                die("<p><a href=mainPage.php> Click here to try again</a></p>");
                
        }
        
        else
            
            echo "Invalid username/password combination";
            die("<p><a href=mainPage.php> Click here to try again</a></p>");
            
}




if (isset($_POST['contrilog'])) {
    $un =  mysql_entities_fix_string($conn,get_post($conn, 'us'));
    $em =  mysql_entities_fix_string($conn,get_post($conn, 'eml'));
    $pw =  mysql_entities_fix_string($conn,get_post($conn, 'pswd'));

    $query = "SELECT * FROM user WHERE username='$un'";
    $result = $conn->query($query);
    if (!$result){
        die($conn->error);}
        else if($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            
            //two salts
            $salt1 = "*&g!";
            $salt2 = "hb%$";
            
            $token = hash('ripemd128', "$salt1$pw$salt2");
            //echo $token;
            //echo "token:". $token. '<br>';
            //echo "row[3]:". $row[3]. '<br>';
            if($token == $row[3] AND $em == $row[1])
            {
                session_start();
                $_SESSION['username'] = $un;
                $_SESSION['password'] = $pw;
                $_SESSION['firstname'] = $row[0];
                $_SESSION['lastname'] = $row[1];
                // echo "Hi! $row[0] $row[1], you are user now logged in as '$row[2]'";
                header('Location: contributorPage.php');
            }
            else
                
                echo "Invalid username/password combination";
                die("<p><a href=mainPage.php> Click here to try again</a></p>");
                
        }
        
        else
            
            echo "Invalid username/password combination";
            die("<p><a href=mainPage.php> Click here to try again</a></p>");
            
}

$conn->close();
            
            



//sanitazing from MySQL
function mysql_fix_string($connection, $string)
{
    if(get_magic_quotes_gpc())
        $string = stripslashes($string);
        return $connection->real_escape_string($string);
}

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

//sanitazing from HTML
function mysql_entities_fix_string($connection, $string)
{
    return htmlentities(mysql_fix_string($connection, $string));
}



?>
