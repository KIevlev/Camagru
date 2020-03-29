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
		return bin2hex(random_bytes(10));
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
			if ($this->_check($pdo, $arr))
				return self::USER_EXIST;
			switch ($this->_check($pdo, $arr))
			{
				case Model::DB_ERROR:
					return Model::DB_ERROR;
				case Model::USER_EXIST:
					return Model::USER_EXIST;
				case Model::SUCCESS:
				break;
			}
			
			
		if ($this->_write_to_db($pdo, Model_Signup::$sql_write_db, $arr) === Model::SUCCESS)
			{
				$this->_send_mail($email, $token);
				return Model::SUCCESS;
			}
			else
				return Model::DB_ERROR2;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR1;
		}
    }

    private function _check($pdo, $arr)
    {
        unset($arr['password']);
        try
		{
			$stmt = $pdo->prepare(self::$sql_check);
			$stmt->execute($arr);
			$result = $stmt->fetchAll();
			foreach ($result as $r)
			{
				if ($r['email'] == $arr['email'] or $r['username'] == $arr['username'])
					return Model::USER_EXIST;
			}
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			Model::DB_ERROR1;
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
			echo "ERROR \n".$ex->getMessage()."\nAborting process\n";
			return Model::DB_ERROR1;
		}
    }

    private function _send_mail($email, $sid)
    {
		$subject = "Welcome to Camagru!";
		$msg = "Thank you for registering on our site. To confirm your entry, follow this link: http://localhost:8080/auth/confirm/".$sid;

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail($email,$subject,$msg);
/*
    	require 'config/database.php';
        
        $main = "Thank you for registering on our site. To confirm your entry, follow this link: http://".
            $email_host."/auth/confirm/".$sid;
        $main = wordwrap($main, 60, "\r\n");
        $headers = 'From: camagru_kborroq@localhost'."\r\n".
                  //  "Reply-To: kostya.marinenkov@gmail.com"."\r\n".
                    "X-Mailer: PHP/".phpversion();
		if (!mail($email, $subject, $main))
		{
			return Model::DB_ERROR1;
		}
    }*/
}
}