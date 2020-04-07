<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" type="text/css" href="/style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<script type="text/javascript" src="https://vk.com/js/api/share.js?93" charset="windows-1251"></script>
</head>
<body>
	<header>
		<ul class="logo">
				<li><i class='fa fa-camera-retro' style='font-size:36px'></i></li>
				<li><a href="/">Kborroq's Camagru</a></li>
			</ul>
		<nav>
			<ul class="nav_links">
				<li><a href="/about">About</a></li>
				<li><a href="/feedback">Feedback</a></li>
                <?php
        if (!isset($_SESSION['username']) and !isset($_SESSION['password']))
            {
                echo "<li><a href='/auth'><div class='navi'>Sign In</div></a></li>";
                echo "<li><a href='/signup'><div class='navi'>Sign Up</div></a></li>";
            }
        else {
			echo "<li><a href='/add'><div class='navi'>Add</div></a></li>";
            echo "<li><a href='/main/profile/{$_SESSION['uid']}'><div id='user' class='navi'>{$_SESSION['username']}</div></a></li>";
            echo "<li><a href='/settings'><div class='navi'>Settings</div></a></li>";
            echo "<li><a href='/auth/signout'><div class='navi'>Sign Out</div></a></li>";
        }
?>
			</ul>
		</nav>
	</header>