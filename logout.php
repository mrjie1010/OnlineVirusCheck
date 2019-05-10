
<?php
session_start();

$_SESSION = array();
session_destroy();


echo <<<_END
<br>
<center>You have been logged out.
<br><br>
<input type="button" name="goback" value="Go Back to Main Page" onclick="location.href='mainPage.php'">
_END;



?>
