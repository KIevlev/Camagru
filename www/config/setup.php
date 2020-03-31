<?php
require "database.php";

// CREATE DATABASE
    try {
        $pdo = new PDO($DB_DNS_L, $DB_USER, $DB_PASSWORD, $DB_OPTS);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8 COLLATE utf8_general_ci");
        
        echo "Database created successfully\n";
        } catch (PDOException $e) {
        echo "ERROR CREATING DB: \n".$e->getMessage()."\nAborting process\n";
        exit(-1);
    }

     // CREATE TABLE USER
    try {
        // Connect to DATABASE previously created
        $pdo = new PDO($DB_DNS, $DB_USER, $DB_PASSWORD, $DB_OPTS);
        $sql = "CREATE TABLE `user` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(25) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `send_email` TINYINT(1)UNSIGNED NOT NULL DEFAULT 1,
            `token` VARCHAR(32) NOT NULL,
            `verified` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `picturesource` LONGTEXT
            )";
        $pdo->exec($sql);
        echo "Table user created successfully\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }

    // CREATE TABLE IMAGE
    try {
        // Connect to DATABASE previously created
        $dbo = new PDO($DB_DNS, $DB_USER,$DB_PASSWORD, $DB_OPTS);
        $sql = "CREATE TABLE `image` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `userid` INT UNSIGNED NOT NULL,
            `source` LONGTEXT NOT NULL,
            `description` VARCHAR(250) DEFAULT NULL,
            `creationdate` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            FOREIGN KEY (userid) REFERENCES user(id) ON DELETE CASCADE
            )";
        $dbo->exec($sql);
        echo "Table gallery created successfully\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }

    // CREATE TABLE LIKE
    try {
        // Connect to DATABASE previously created
        $dbo = new PDO($DB_DNS, $DB_USER, $DB_PASSWORD, $DB_OPTS);
        $sql = "CREATE TABLE `like` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `userid` INT(11) UNSIGNED NOT NULL,
            `imageid` INT(11) UNSIGNED NOT NULL,
            FOREIGN KEY (userid) REFERENCES `user`(id) ON DELETE CASCADE,
            FOREIGN KEY (imageid) REFERENCES `image`(id) ON DELETE CASCADE
            )";
        $dbo->exec($sql);
        echo "Table like created successfully\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }

    // CREATE TABLE COMMENT
    try {
        // Connect to DATABASE previously created
        $dbo = new PDO($DB_DNS, $DB_USER, $DB_PASSWORD, $DB_OPTS);
        $sql = "CREATE TABLE `comment` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `userid` INT UNSIGNED NOT NULL,
            `imageid` INT UNSIGNED NOT NULL,
            `text` VARCHAR(2000) NOT NULL,
            FOREIGN KEY (userid) REFERENCES user(id) ON DELETE CASCADE,
            FOREIGN KEY (imageid) REFERENCES image(id) ON DELETE CASCADE
            )";
        $dbo->exec($sql);
        echo "Table comment created successfully\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }
?>

