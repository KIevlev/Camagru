<?php
session_start();
require_once 'templates/header.php';
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>About Camagru</title>
  <link rel="stylesheet" type="text/css" href="./style/style.css">
 </head>
 <body>
     
 <div class="wrapper_1">
 <style>
 .wrapper_1{
background: #c7b39b url(images/var5.jpg); /* Цвет фона и путь к файлу */
color: #fff; /* Цвет текста */
height: 88%;
} 
</style>
 <!--<img src="images/var5.jpg">-->
<div class="content">
<div class="about">
<h1>About this project</h1>
<ul>
<li><a>The application should allow a user to sign up by asking at least a valid email
address, an username and a password with at least a minimum level of complexity.</a></li>
<li><a>At the end of the registration process, an user should confirm his account via a
unique link sent at the email address fullfiled in the registration form.</a></li>
<li><a>The user should then be able to connect to your application, using his username
and his password. He also should be able to tell the application to send a password
reinitialisation mail, if he forget his password.</a></li>
<li><a>This gallery part is public and displays all the images edited by all the users,
ordered by date of creation. It should also allow (only) a connected user to like
them and/or comment them.</a></li>
</ul>
</div>
</div>
</div>
<?php
require_once 'templates/footer.php';
?>