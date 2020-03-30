<?php
class Model_Signup extends Model
{
    private static $sql_check = "SELECT * FROM `user` WHERE `email` = :email OR `username` = :username";
    private static $sql_write_db = "INSERT INTO `user` (`id`, `username`, `password`, `email`, `token`, `verified`)
                    VALUES (NULL, :username, :password, :email, :token, 0)";
    private static $sql_add_to_change = "INSERT INTO `change_table` (`id`, `id_user`, `reason`, `sid`)
                    VALUES (NULL, :id, :reason, :sid)";
    private static $sql_get_id = "SELECT `id` FROM `user` WHERE `email` = :email AND `username` = :username
                                    AND password = :password";

	public static function token()
	{
		return bin2hex(random_bytes(16));
	}

    public function create_account($username, $passwd, $email)
    {
        require "config/database.php";
        if ($passwd === strtolower($passwd) or strlen($passwd) < 6)
        	return Model::WEAK_PASSWORD;
        try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$token = Model_Signup::token();
			$arr = array('username' => $username, 'password' => hash("whirlpool", $passwd), 'email' => $email, 'token' => $token);
			$arr2 = array('username' => $username, 'email' => $email);
			if ($this->_check($pdo, $arr2))
			return self::USER_EXIST;
			switch ($this->_check($pdo, $arr2))
			{
				case Model::DB_ERROR:
					return Model::DB_ERROR;
				break;
				case Model::USER_EXIST:
					return Model::USER_EXIST;
				break;
				case Model::SUCCESS:
				break;
			}
		if ($this->_write_to_db($pdo, Model_Signup::$sql_write_db, $arr) === Model::SUCCESS)
			{
				$this->_send_mail($email, $token);
				return Model::SUCCESS;
			}
			else
				return Model::DB_ERROR;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
    }

    private function _check($pdo, $arr2)
    {
        //unset($arr['password']);
        try
		{
			$stmt = $pdo->prepare(self::$sql_check);
			$stmt->execute($arr2);
			$result = $stmt->fetchAll();
			foreach ($result as $r)
			{
				if ($r['email'] == $arr2['email'])
				{
					Route::console_log($r['email']. " arr2 email - ". $arr2['email']);
					return Model::EMAIL_EXIST;
				}
				elseif ($r['username'] == $arr2['username'])
				{
					Route::console_log($r['username']. " arr2 us- ". $arr2['username']);
					return Model::USER_EXIST;
				}
			}
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			Model::DB_ERROR;
		}
    }

    private function _add_to_change($arr, $pdo) {
    	try
		{
			$stmt = $pdo->prepare(self::$sql_get_id);
			$stmt->execute($arr);
			$id = $stmt->fetch();
			$stmt = $pdo->prepare(self::$sql_add_to_change);
			$sid = hash("whirlpool", $arr['email'].time());
			$arr2 = array('id' => $id['id'], 'reason' => Model::REASON_CREATE, 'sid' => $sid);
			$stmt->execute($arr2);
			$this->_send_mail($arr['email'], $sid);
			return Model::SUCCESS;
		}
        catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
    }

    private function _write_to_db($pdo, $sql, $arr)
    {
    	try
		{
			$stmt = $pdo->prepare($sql);
			$stmt->execute($arr);
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
    }

    private function _send_mail($email, $sid)
    {
		$subject = "Welcome to Camagru!";
$msg = '<html>
<head>
  <title>Welcome to Camagru</title>
</head>
<body>
  <p>Thank you for registering on our site!</p>
  <p> To confirm your entry, follow this<p>
  <p><a href="http://localhost:8080/auth/confirm/">LINK</a></p>
</body>
</html>';
$msg = wordwrap($msg,70, "\r\n");
// send email
mail($email,$subject,$msg);
$headers  = 'MIME-Version: 1.0' . "\r\n";
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
