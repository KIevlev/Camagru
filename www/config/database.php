<?php
$DB_HOST ='mysql';
$DB_NAME ='db_cam';
$DB_USER ='root';
$DB_PASSWORD ='secret';
$DB_CHARSET ='utf8';
$DB_DNS_L = "mysql:host=$DB_HOST;charset=$DB_CHARSET";
$DB_DNS = "mysql:host=$DB_HOST;charset=$DB_CHARSET;dbname=".$DB_NAME;
$DB_OPTS = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$email_host = 'localhost:8080';
?>
