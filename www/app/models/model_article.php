<?php
class Model_Article extends Model
{
	private $sql_get_post = "SELECT `image`.`id` as aid, `user`.`id` as uid, `user`.`username`, `image`.`likes`, `image`.`description` 
                FROM `image`, `user`
                WHERE `image`.`id` = :aid AND `user`.`id` = `image`.`userid`
                ORDER BY `image`.`creationdate` DESC";
	private $sql_get_comment = "SELECT `comment`.`id` as cid, `user`.`id` as uid, `user`.`username` as nickname, `comment`.`text`
								FROM `comment`, `user`
								WHERE `imageid` = :aid AND `user`.`id` = `comment`.`userid` ORDER BY `comment_date` ASC";
	private $sql_put_comment = "INSERT INTO `comment` VALUES (NULL, :uid, :aid, NOW(), :content)";
	private $sql_send_email = "SELECT `email`, `send_email` FROM `user` INNER JOIN `image`
								ON `user`.`id` = `image`.`userid` AND `image`.id = :aid";
	private static $sql_get_likes = "SELECT COUNT(*) as likes FROM `like` WHERE `imageid` = :aid";
	private static $sql_del_post = "DELETE FROM `image` WHERE `id` = :aid AND `userid` = :uid";
	private static $sql_del_comment = "DELETE FROM `comment` WHERE `id` = :cid AND `userid` = :uid";


	public function get_data($aid)
	{
		include "config/database.php";
		try
		{
			$result = $this->_auth();
		if ( $result !== Model::SUCCESS)
		return $result;
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare($this->sql_get_post);
			$stmt->execute(array('aid' => $aid));
			$data[] = $stmt->fetch();
			if (!$data[0])
				return Model::ARTICLE_NOT_FOUND;
			$stmt = $pdo->prepare($this->sql_get_comment);
			$stmt->execute(array('aid' => $aid));
			$data[] = $stmt->fetchAll();
			$stmt = $pdo->prepare(Model_Article::$sql_get_likes);
			$stmt->execute(array('aid' => $data[0]['aid']));
			$likes = $stmt->fetch();
			$data[0]['likes'] = $likes['likes'];
			//Route::console_log($data);
			return $data;
		}
		catch (PDOException $ex)
		{
			throw $ex;
			return Model::DB_ERROR;
		}
	}

	public function put_comment($aid)
	{
		$result = $this->_auth();
		if ( $result !== Model::SUCCESS)
			return $result;
		if (!isset($_POST['comment']) or mb_strlen($_POST['comment']) === 0)
			return Model::INCOMPLETE_DATA;
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare($this->sql_put_comment);
			$stmt->execute(array(
				'uid' => $_SESSION['uid'],
				'aid' => $aid,
				'content' => $_POST['comment']
			));
			$stmt = $pdo->prepare($this->sql_send_email);
			$stmt->execute(array('aid' => $aid));
			$data = $stmt->fetch();
			$this->_send_mail($data['email'], $_SESSION['username'], $aid, $data['send_email']);
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			throw $ex;
			return Model::DB_ERROR;
		}
	}

	private function _send_mail($email, $nickname, $aid, $confirmed)
	{
		include 'config/database.php';
		if ($confirmed === 0)
			return;
		$subject = "You have comments in Camagru!";
			$msg = '<html>
			<head>
			  <title>Hey! You have comments in Camagru!</title>
			</head>
			<body>
			  <p>Hello, '.$nickname.'!</p>
			  <p> Somebody left a comment under your post! Check it out!<p>
			  <p><a href="http://localhost:8080/article/index/'.$aid.'">LINK</a></p>
			</body>
			</html>';
			$msg = wordwrap($msg,70, "\r\n");
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

	public function delete_post($aid)
	{
		$result = $this->_auth();
		if ( $result !== Model::SUCCESS)
			return $result;
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_del_post);
			$stmt->execute(array('aid' => $aid, 'uid' => $_SESSION['uid']));
			if (!$stmt->rowCount())
				return Model::ARTICLE_NOT_FOUND;
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			throw $ex;
			return Model::DB_ERROR;
		}
	}

	public function delete_comment($cid)
	{
		$result = $this->_auth();
		if ( $result !== Model::SUCCESS)
			return $result;
		if (!strstr($cid, ';'))
			return Model::ARTICLE_NOT_FOUND;
		$arr = explode(';', $cid);
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_del_comment);
			$stmt->execute(array('cid' => $arr[0], 'uid' => $_SESSION['uid']));
			if (!$stmt->rowCount())
				return Model::ARTICLE_NOT_FOUND;
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			throw $ex;
			return Model::DB_ERROR;
		}
	}
}