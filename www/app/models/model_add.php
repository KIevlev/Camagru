<?php
class Model_Add extends Model
{
	private static $sql_add_post = "INSERT INTO `image` (`id`, `userid`, `source`, `description`) VALUES (NULL, :uid, :source,:description)";
	private static $sql_add_like = "INSERT INTO `like` (`id`, `userid`, `imageid`) VALUES (NULL, :uid, :aid)";
	private static $sql_search_like = "SELECT * FROM `like` WHERE `imageid` = :aid AND `userid` = :uid";
	private static $sql_del_like = "DELETE FROM `like` WHERE `imageid` = :aid AND `userid` = :uid";

	public function create_article()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		if (!isset($_POST['description']))
			return Model::INCOMPLETE_DATA;
		if (!is_uploaded_file($_FILES['image_upload']['tmp_name']))
			return Model::UNUPLOADED_FILE;
		$date = date("d-M-Y H:m:s");
		try
		{
			$id = $this->_insert_to_table($date);
		}
		catch (PDOException $ex)
		{
			Route::console_log("1 ".$ex);
			return Model::DB_ERROR;
		}
		if (($result = $this->_insert_to_ftp($id, $date)) === Model::SUCCESS)
			return array(Model::SUCCESS, $id);
		else
			return $result;
	}

	public function create_article_base()
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		if (!isset($_POST['description']) or !isset($_POST['base_img']))
			return Model::INCOMPLETE_DATA;
		$data = explode(',',$_POST['base_img']);
		$img = imagecreatefromstring(base64_decode($data[1]));
		if ($img === FALSE)
			return Model::UNUPLOADED_FILE;
			$date = date("d-M-Y H:m:s");
		try
		{
			$id = $this->_insert_to_table($date);
		}
		catch (PDOException $ex)
		{
			Route::console_log("2 ".$ex);
			return Model::DB_ERROR;
		}
		if (($result = $this->_insert_to_ftp_base($id, $img, $date)) === Model::SUCCESS)
			return array(Model::SUCCESS, $id);
		else
			return $result;
	}

	private function _insert_to_table($date)
	{
		$arr = array(
			'uid' => $_SESSION['uid'],
			'description' => mb_strimwidth($_POST['description'], 0, 250),
			'source' => $_SESSION['uid']."_".$date
		);
		try
		{
			include "config/database.php";
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(Model_Add::$sql_add_post);
			$stmt->execute($arr);
			$id = $pdo->lastInsertId();
			return $id;
		}
		catch (PDOException $ex)
		{
			Route::console_log("3 ".$ex);
			throw $ex;
		}
	}

	private function _insert_to_ftp($id, $date)
	{
		include "config/database.php";
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
		$img = imagescale($src_img, 640, 480);
		if (isset($_POST['sticker0']))
			$img = $this->_add_stickers($img);
			$string = "./photos/$id"."_$date".".jpg";
		if (imagejpeg($img, $string))
			return Model::SUCCESS;
		else
			return Model::DB_ERROR;
	}

	private function _insert_to_ftp_base($id, $img, $date)
	{
		include "config/database.php";
		$img = imagescale($img, 640, 480);
		if (isset($_POST['sticker0']))
			$img = $this->_add_stickers($img);
		$string = "./photos/$id_$date.jpg";
			if (imagejpeg($img, $string))
			return Model::SUCCESS;
		else
			return Model::DB_ERROR;
	}

	private function _add_stickers($img)
	{
		for ($i = 0; ; $i++)
		{
			if (!isset($_POST['sticker'.$i]))
				break ;
			$param = explode(';', $_POST['sticker'.$i]);
			$sticker = imagecreatefrompng('images/'.$param[0]);
			imagecopy($img, $sticker, $param[1] - 64, $param[2] - 64, 0, 0, 128, 128);
		}
		return $img;
	}

	public function add_like($aid)
	{
		if (($result = $this->_auth()) !== Model::SUCCESS)
			return $result;
		include "config/database.php";
		try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(self::$sql_search_like);
			$stmt->execute(array(
				'aid' => $aid,
				'uid' => $_SESSION['uid']
			));
			$data = $stmt->fetch();
			if ($data !== false)
			{
				return $this->del_like($aid, $pdo);
			}
			$stmt = $pdo->prepare(self::$sql_add_like);
			$stmt->execute(array(
				'aid' => $aid,
				'uid' => $_SESSION['uid']
			));
			return Model::SUCCESS;
		}
		catch (PDOException $ex)
		{
			Route::console_log("4 ".$ex);
			return Model::DB_ERROR;
		}
	}

	private function del_like($aid, $pdo)
	{
		try
		{
			$stmt = $pdo->prepare(Model_Add::$sql_del_like);
			$stmt->execute(array('uid' => $_SESSION['uid'], 'aid' => $aid));
			if ($stmt->rowCount())
				return Model::SUCCESS;
			else
				return Model::DB_ERROR;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
	}
}