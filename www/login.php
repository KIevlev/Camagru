<?php
session_start();
require_once 'templates/header.php';

$output = NULL;

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

 if (empty($username) || empty($password))
 {
 $output = "Please enter all fields";
 }
   else{
        $mysqli = new mysqli('localhost:3036','root','secret','test');

        $username = $mysqli->real_escape_string($username);
        $username = $mysqli->real_escape_string($password);

        $query = $mysqli->query("SELECT id FROM accounts WHERE username = '$username' AND password = md5('$password')");
        
        if ($query->num_rows == 0) {
            $output = "Invalid username/password";
        }
        else{
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user'] = $username;

            $output = "WELCOME" . $_SESSION['user'] . " - <a href='logout.php'>Logout</a>";
        }
  } 
}
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Login page</title>
  <link rel="stylesheet" type="text/css" href="./style/style.css">
 </head>
 <body>
<div class="wrapper">

	<div class="content">
<form class="box" method="POST">
   <?php if(!isset($_SESSION['logedin']))
{
    echo "<p>Welcome, guest </p>";

?>
<h1>Login</h1>
<input type="TEXT" name="username" placeholder="Username" required/>
<input type="PASSWORD" name="password" placeholder="Password" required/>
<input type="SUBMIT" name="submit" value="Login" />
<a class="add" href='forgot.php'> forgot your password?</a>
<a class="add" href='regist.php'>sign in now!</a>

</form>
    
</div><!-- .content -->

<?php require_once 'templates/footer.php';?>
 </body>
</html>


<?php
}
else{
echo "$output";
}

?>