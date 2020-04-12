<?php
define('PER_PAGE', 5);
class Model_Main extends Model
{
	private static $sql_get_image = "SELECT `image`.`id` as aid, `user`.`id` as uid, `user`.`username`, `image`.`description` 
                FROM `image`, `user` 
                WHERE `user`.`id` = `image`.`userid` 
                ORDER BY `image`.`creationdate` DESC LIMIT ?, ?";
	private static $sql_get_profile = "SELECT `image`.`id` as aid, `user`.`id` as uid, `user`.`username`, `image`.`description` 
                FROM `image`, `user` 
                WHERE `user`.`id` = `image`.`userid` AND `image`.`userid` = :uid
                ORDER BY `image`.`creationdate` DESC LIMIT 5 OFFSET :page";
	private static $sql_get_likes = "SELECT COUNT(*) as likes FROM `like` WHERE `imageid` = :aid";
	private static $sql_num_page = "SELECT COUNT(*) as num FROM `image` WHERE `userid`=?";
	private static $sql_get_image_num = "SELECT COUNT(*) as num FROM `image`"; 

    public function get_feed()
    {
        include 'config/database.php';
        try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt= $pdo->prepare(Model_Main::$sql_get_image);
			// FETCH CONTENTS
			if (!isset($_POST['page']))
				$_POST['page'] = 1;
			$page = $_POST['page'];
			$start = ($page-1) * PER_PAGE;
				$end = $start + PER_PAGE;
				$stmt->execute(array($start, $end));
			$data = $stmt->fetchAll();
			if (count($data) < PER_PAGE)
				$_SERVER['last'] = true;
			$stmt = $pdo->prepare(Model_Main::$sql_get_likes);
				for ($i = 0; $i < count($data); $i++)
				{
					$stmt->execute(array('aid' => $data[$i]['aid']));
					$likes = $stmt->fetch();
					$data[$i]['likes'] = $likes['likes'];
				}
				$_SERVER['type'] = 'feed';
				return $data;
			}
			catch (PDOException $ex)
			{
				Route::console_log("1");
				return Model::DB_ERROR;
			}
    }

    public function get_profile($id)
    {
        include 'config/database.php';
        try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			
			$stmt = $pdo->prepare(Model_Main::$sql_get_profile);
			if (!isset($_GET['page']) or $_GET['page'] < 2)
			{
				$stmt->execute(array('uid' => $id, 'page' => 0));
				$_SERVER['first'] = true;
			}
			else
				$stmt->execute(array('uid' => $id, 'page' => ($_GET['page'] - 1) * 5));
			$data = $stmt->fetchAll();
			$num = count($data);
			if ($num < 5)
				$_SERVER['last'] = true;
			if ($num === 0)
				return Model::EMPTY_PROFILE;
			$stmt = $pdo->prepare(self::$sql_num_page);
			$stmt->execute(array($id));
			$num = $stmt->fetch();
			if ($num['num'] == 5)
				$_SERVER['last'] = true;
			$stmt = $pdo->prepare(Model_Main::$sql_get_likes);
			for ($i = 0; $i < count($data); $i++)
			{
				$stmt->execute(array('aid' => $data[$i]['aid']));
				$likes = $stmt->fetch();
				$data[$i]['likes'] = $likes['likes'];;
				Route::console_log($data);
			}
			$_SERVER['type'] = 'profile';
			return $data;
		}
		catch (PDOException $ex)
		{
			Route::console_log("profile");
			return Model::DB_ERROR;
		}
    }
}