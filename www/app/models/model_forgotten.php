<?php
class Model_Forgotten extends Model
{
	private static $sql_check_email = 'SELECT * FROM `user` WHERE `email`=:email';
	private static $sql_check_sid = 'SELECT * FROM `user` WHERE `token` = :sid';
	private static $sql_update_password = 'UPDATE `user` SET `password`=:password WHERE `id`=:uid';
	private static $sql_update_pw = "UPDATE `user` SET `password`= ?, `token` = ? WHERE `user`.`id` = ?";

	public function new_password($sid)
	{
		$data = $this->_check_sid($sid);
		$result = gettype($data);
		if ("array" != $result)
			return $data;
		if ($_POST['confirm_password'] != $_POST['new_password'])
			return Model::PASS_NOT_MATCH;
		if ($_POST['new_password'] === strtolower($_POST['new_password']) or strlen($_POST['new_password']) < 6)
			return Model::WEAK_PASSWORD;
			$password = hash('whirlpool', $_POST['new_password']);
		if ($password === $data['passwd'])
			return Model::SAME_PASS;
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$uid = $data['uid'];
			$token = Model::token();
			$stmt = $pdo->prepare(self::$sql_update_pw);
			if ($stmt->execute(array($password, $token, $uid)))
				return Model::SUCCESS;
			else
				return Model::DB_ERROR;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
	}

	public function _check_sid($sid)
	{
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_check_sid);
			$stmt->execute(array('sid' => $sid));
			$data = $stmt->fetch();
			if (!$data)
				return Model::SID_NOT_FOUND;
			$arr = array('uid' => $data['id'], 'passwd' => $data['password'], 'res' => Model::SUCCESS);
			return $arr;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
	}

	public function check_email()
	{
		include 'config/database.php';
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_check_email);
			$stmt->execute(array('email' => $_POST['email']));
			$data = $stmt->fetch();
			if (!$data)
				return Model::BAD_EMAIL;
			$result = $this->_send_mail($data['email'], $data['token']);
			return $result;
		}
		catch (PDOException $ex)
		{
			Model::DB_ERROR;
		}
	}

	private function _send_mail($email, $sid)
	{
		include 'config/database.php';
		$subject = "Password recovery in Camagru";
$msg = '<html>
<head>
  <title>Password recovery in Camagru</title>
</head>
<body>
  <p> You try to recover password. Please, click here: <p>
  <p><a href="http://localhost:8080/forgotten/recovery/'.$sid.'">LINK</a></p>
</body>
</html>';
$msg = wordwrap($msg,70, "\r\n");
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: Kika.Ievlev@yandex.ru'."\r\n".
			'Reply-To: Kika.Ievlev@yandex.ru' . "\r\n" .
			"X-Mailer: PHP/".phpversion();
			if (! empty($_POST)) {
				if (!mail($email, $subject, $msg, $headers))
		{
			return Model::DB_ERROR;
		}
			}
		
    }
}