<?php
class Model_Settings extends Model
{
	private static $sql_update_send_email = "UPDATE `user` SET send_email = ? WHERE id = ?";
	private static $sql_search_nickname = "SELECT `username` FROM `user` WHERE `username`=:nickname";
	private static $sql_update_nickname = "UPDATE `user` SET `username`=:nickname WHERE `id`=:uid";
	private static $sql_search_email = "SELECT `email` FROM `user` WHERE `email` = :email";
	private static $sql_update_email = "UPDATE `user` SET `email`=:email WHERE `id`=:uid";
	private static $sql_update_password = "UPDATE `user` SET `password`=:password WHERE `id`=:uid";

	public function sending_mail()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		require "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			Route::console_log($_SESSION['uid']);
			$stmt = $pdo->prepare(Model_Settings::$sql_update_send_email);
			if ($_POST['send_email'] === "Enable")
			{
				$stmt->execute(array(1, $_SESSION['uid']));
				$_SESSION['send_email'] = 1;
			}
			else
			{
				$stmt->execute(array(0, $_SESSION['uid']));
				$_SESSION['send_email'] = 0;
			}
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
	}

	public function change_nickname()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
			require "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(Model_Settings::$sql_search_nickname);
			$arr = array('nickname' => $_POST['new_nick']);
			$stmt->execute(array('nickname' => $_POST['new_nick']));
			$data = $stmt->fetch();
			if ($data)
				return Model::USER_EXIST;
			$stmt = $pdo->prepare(Model_Settings::$sql_update_nickname);
			$stmt->execute(array(
				'nickname' => $_POST['new_nick'],
				'uid' => $_SESSION['uid']
			));
			$_SESSION['username'] = $_POST['new_nick'];
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
	}

	public function change_email()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		include "config/database.php";
		if (!filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL))
			return Model::BAD_EMAIL;
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(Model_Settings::$sql_search_email);
			$stmt->execute(array('email' => $_POST['new_email']));
			$data = $stmt->fetch();
			if ($data)
				return Model::EMAIL_EXIST;
			$stmt = $pdo->prepare(Model_Settings::$sql_update_email);
			$stmt->execute(array(
				'email' => $_POST['new_email'],
				'uid' => $_SESSION['uid']
			));
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}

	}

	public function change_password()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		if ($_SESSION['password'] !== hash('whirlpool', $_POST['old_password']))
			return Model::WRONG_PASSWORD;
		if ($_POST['confirm_password'] !== $_POST['new_password'])
			return Model::PASS_NOT_MATCH;
		if ($_POST['new_password'] === $_POST['old_password'])
			return Model::SAME_PASS;
		if ($_POST['new_password'] === strtolower($_POST['new_password']) or strlen($_POST['new_password']) < 6)
			return Model::WEAK_PASSWORD;
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(Model_Settings::$sql_update_password);
			$stmt->execute(array(
				'password' => hash('whirlpool', $_POST['new_password']),
				'uid' => $_SESSION['uid']
			));
			$_SESSION['password'] = $_POST['new_password'];
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
	}

	public function change_icon()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		if (!is_uploaded_file($_FILES['image_upload']['tmp_name']))
			return Model::UNUPLOADED_FILE;
		if (($result = $this->_insert_to_ftp()) === Model::SUCCESS)
			return Model::SUCCESS;
	}

	private function _insert_to_ftp()
	{
		include "config/database.php";
		$id = $_SESSION['uid'];
		$type = exif_imagetype($_FILES['image_upload']['tmp_name']);
		switch ($type)
		{
			case IMAGETYPE_JPEG:
				$src_img = imagecreatefromjpeg($_FILES['image_upload']['tmp_name']);
				break;
			case IMAGETYPE_GIF:
				$src_img = imagecreatefromgif($_FILES['image_upload']['tmp_name']);
				break;
			case IMAGETYPE_PNG:
				$src_img = imagecreatefrompng($_FILES['image_upload']['tmp_name']);
				break;
			default:
				return Model::FORBIDDEN_FILETYPE;
		}
//		$ftp = ftp_connect($ftp_host);
//		Route::console_log($ftp);
		/*ftp_login($ftp, $ftp_user, $ftp_pass);
		$lst = ftp_nlist($ftp, "/icons/$id.jpg");
		*/
		if (file_exists("./icons/$id.jpg"))
			unlink("./icons/$id.jpg");
		$img = imagescale($src_img, 128, 128);
		if (imagejpeg($img, "./icons/$id.jpg"))
			return Model::SUCCESS;
		//else
			return Model::DB_ERROR;
	}
}