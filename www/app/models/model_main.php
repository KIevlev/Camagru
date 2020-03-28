<?php
class Model_Main extends Model
{
	private static $sql_get_image = "SELECT image.id as aid, user.id as uid, user.username , image.description 
                FROM image, user 
                WHERE user.id = image.userid 
                ORDER BY image.creationdate DESC LIMIT 5 OFFSET ?";
	private static $sql_get_profile = "SELECT image.id as aid, user.id as uid, user.username , image.description 
                FROM image, user 
                WHERE user.id = image.userid AND image.userid = :uid
                ORDER BY image.creationdate DESC LIMIT 5 OFFSET :page";
	private static $sql_get_likes = "SELECT COUNT(*) FROM `like` WHERE `imageid` = :uid";
	private static $sql_num_page = "SELECT COUNT(*) as num FROM image WHERE userid=?";

    public function get_feed()
    {
        include 'config/database.php';
        try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt= $pdo->prepare(Model_Main::$sql_get_image);
			if (!isset($_GET['page']) or $_GET['page'] < 2)
			{
				$stmt->execute(array(0));
				$_SERVER['first'] = true;
			}
			else
				$stmt->execute(array(($_GET['page'] - 1) * 5));
			$data = $stmt->fetchAll();
			if (count($data) < 5)
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
				$data[$i]['likes'] = $likes['likes'];
			}
			$_SERVER['type'] = 'profile';
			return $data;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
    }
}