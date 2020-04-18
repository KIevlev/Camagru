<?php
include_once "config/database.php";
class Model_Feedback extends Model
{
    private static $sql_check_email = 'SELECT `email` FROM `user` WHERE `username`=:username';
    public static $my_email = 'Kika.Ievlev@yandex.ru';
// This script sends an email to your email address from the contact form. Change the variable below to your own email address:

// Checks email address is a valid one (as far as possible - it basically checks for user errors)

public function index($params = null){
// Use validateEmail function above
if(!isset($_SESSION['username'])){

	// Sanitise data
	$name = htmlspecialchars($_POST['name']);
	$email = $_POST['email'];
	$url = htmlspecialchars($_POST['url']);
	$message = htmlspecialchars($_POST['message']);

	self::send_mail($name, $email, $url=null, $message);

// If the email address does not pass the validation
} else {
    $name = $_SESSION['username'];
    include 'config/database.php';
    try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_check_email);
			$stmt->execute(array('username' => $_POST['username']));
			$data = $stmt->fetch();
    $email = $data;
    $message = htmlspecialchars($_POST['message']);
    $url = htmlspecialchars($_POST['url']);
    self::send_mail($name, $email, $url=null, $message);
    }
    catch (PDOException $ex)
		{
			Model::DB_ERROR;
		}
}
}

public function send_mail($name, $email, $url=null, $message)
{
    $content = "<p>Hey hey,</p>
		<p>You have recieved an email from $name via the website's 'Contact Us' form. Here's the message:</p>
		<p>$message</p>
		<p>
			From: $name
			<br />
			Email: $email
			<br />
			Website: $url
		</p>";


	$try = mail(self::$my_email,"$name has emailed via the website",$content,"Content-Type: text/html;");

	// If there was an error sending the email (PHP can use 'sendmail' on GNU/Linux, the easiest way - but do check your spam folder)
	if(!$try){
		echo '<p>There was an error when trying to send your email. Please try again.</p>';
	} else {
		// echo out some response text (to go in <div id="reponse"></div>)
		echo '<p>Thank you ' . $name . '. We will reply to you at <em>' . $email .'</em></p>';
	}
}
}