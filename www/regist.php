<?php
session_start();
require 'templates/header.php';
require "functions/verifyLoginDetails.php";

$error = "";

if(isset($_POST['sign_in']))
{
$u_name = $_POST['Username'];
if ($_POST['pass1'] != $_POST['pass2']) 
    {
		$error = "Пароли не совпадают";
	}
else
{
	$pass1 = $_POST['pass1'];
}
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>sign in now!</title>
  <link rel="stylesheet" type="text/css" href="./style/style.css">
 </head>
<body>
<div class="wrapper">

	<div class="content">
<form class="box" action="regist.php" method="POST">
	<h1>Registration page</h1>
	<?php if ($error != "") echo "$error"; ?>
	<input type="text" name="Username" placeholder="Username" required>
	<input type="email" name="Email" placeholder="example@mail.com" required> 
	<input type="password" name="pass1" placeholder="Password" required>
	<input type="password" name="pass2" placeholder="Password once more" required>
	<input type="submit" name="sign_in" value="sign in">
	<span> 
	<?php
        if ($_SESSION['signup_success'] == TRUE) {
        	echo "Signup success! Please check your email to verify your account.";
            $_SESSION['error'] = NULL;
            $_SESSION['signup_success'] = NULL;
        } else if ($_SESSION['error'] !== NULL) {
            echo " Signup failed. " . $_SESSION['error'];
        	$_SESSION['error'] = NULL;
        }
    ?>
	</span>
	</form>
    
	</div>

<?php require_once 'templates/footer.php'; ?>
</body>
</html>
