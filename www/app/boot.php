<?php
//session_start();
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
//echo "Норм<br />";

//echo "req - ".$_GET['request'];
Route::start(); // запускаем маршрутизатор
