<?php
class Model_Auth extends Model
{
    private static $sql_search = "SELECT * FROM `user` WHERE `token` = :token";
	private static $sql_update = "UPDATE `user` SET `verified` = ?, `token` = ? WHERE `user`.`id` = ?";



    public function get_data($login, $password)
    {
        include "config/database.php";
        $hash_passwd = hash("whirlpool", $password);
        try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$sql = "SELECT * FROM `user` WHERE username = ? AND password = ?";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($login, $hash_passwd));
			$data = $stmt->fetch();
			if ($data)
			{
				$_SESSION['username'] = $data['username'];
				$_SESSION['password'] = $data['password'];
				$_SESSION['uid'] = $data['id'];
				return Model::SUCCESS;
			}
			else
				return Model::INCORRECT_NICK_PASS;
		}
		catch (PDOException $ex)
		{
			return Model::DB_ERROR;
		}
    }

    public function confirm_account($token)
    {
        require "config/database.php";
        try
		{
			$pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
			$pdo->exec("USE $DB_NAME");
			$stmt = $pdo->prepare(Model_Auth::$sql_search);
			//Route::console_log("token = ".$token);
			$ar = array('token' => $token);
			$stmt->execute($ar);
			$result = $stmt->fetch();
			if ($result)
			{
				$stmt = $pdo->prepare(Model_Auth::$sql_update);
				$token_n = Model_Auth::token();
				$stmt->execute(array(1, $token_n, $result['id']));
				return Model::SUCCESS;
			}
			else
				return Model::SID_NOT_FOUND;
		}
		catch (PDOException $ex)
		{
			Route::console_log($ex->getMessage());
			return Model::DB_ERROR;
		}
	}	
}