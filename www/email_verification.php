<?php
    session_start();
	include "templates/header.php";
?>
	<title>Camagru - You are Verified!</title>
	<style>
		.center {
			display: flex;
			justify-content: center;
			align-items: center;
		}
	</style>
</head>
<body>
<?php
require '../config/database.php';

if (isset($_GET['email']) && !empty($_GET['email']) and isset($_GET['token']) && !empty($_GET['token'])) {
	// Verify data
	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$email = ($_GET['email']); // Set email variable
	$token = ($_GET['token']); // Set token variable

$search = $dbh->prepare("SELECT `email`, `token`, `verified` FROM `user` WHERE (`email`=? AND `token`=? AND `verified`=0)");
$search->execute([$email, $token]);
if ($search->rowCount() == 1) {
	// We have a match, activate the account
	$stmt = $dbh->prepare("UPDATE `user` SET `verified`=2 WHERE (`email`=? AND `token`=? AND `verified`=0)");
	$stmt->execute([$email, $token]);
	
		echo "<h2 >You are now verified and can login to start your kborroq cmamgru journey</h2>" ;

}
}
else {
	// No match -> invalid url or account has already been activated.

		echo "<h2>The url is either invalid or you have already activated your account.</h2>";
}
require "templates/footer.php";

?>
</body>
</html>