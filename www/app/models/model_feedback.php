<?php
include_once "config/database.php";
class Model_Feedback extends Model
{   
    private static $sql_check_email = 'SELECT `email` FROM `user` WHERE `username`=:username';
    public static $my_email = 'Kika.Ievlev@yandex.ru';

    public function index(){
    if (!isset($_POST['message']))
        return NULL;
    $email = NULL;
    if( isset($_POST['message']) and !empty($_POST['message']) )
    {
        if(!isset($_SESSION['username'])){

            // Sanitise data
            $name = htmlspecialchars($_POST['name']);
            $email = $_POST['email'];
            $message = htmlspecialchars($_POST['message']);
           
            self::send_mail($name, $email, $message);
            exit();
    }
    else{
        $name = $_POST['name'];
        include 'config/database.php';
    try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_check_email);
			$stmt->execute(array('username' => $_SESSION['username']));
            $data = $stmt->fetch();
    $email = $data['email'];
    $message = htmlspecialchars($_POST['message']);
    self::send_mail($name, $email, $message);
    exit();
    }
    catch (PDOException $ex)
		{
			echo "FAILURE";
		}
}
    }
else{

    exit();
}
    }


public function send_mail($name, $email, $message)
{
    $content = "<p>Hey hey,</p>
		<p>You have recieved an email from $name via the website's 'Contact Us' form. Here's the message:</p>
		<p>$message</p>
		<p>
			From: $name
			<br />
			Email: $email
		</p>";

	$try = mail(self::$my_email,"$name has emailed via the website",$content,"Content-Type: text/html;");
	// If there was an error sending the email (PHP can use 'sendmail' on GNU/Linux, the easiest way - but do check your spam folder)
	if(!$try){
        echo '<br><br><p>There was an error when trying to send your email. Please try again.</p>';
        echo <<< T
        <div class="return">
<div><a href="/" class="button_2" target="_blank">Main page</a></div>
</div>
T;
	} else {
        // echo out some response text (to go in <div id="reponse"></div>)
        
        echo '<br><br>
        <div class="OK" id="OK">
        <p>Thank you, ' . $name . '. We will reply to you at <em>' . $email .'</em></p></div>';
        echo <<< T
        <div class="return">
<div><a href="/" class="button_2" target="_blank">Main page</a></div>
</div>
T;
	}
}
}