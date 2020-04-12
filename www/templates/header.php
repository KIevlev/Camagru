<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" type="text/css" href="/style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<script type="text/javascript" src="https://vk.com/js/api/share.js?93" charset="windows-1251"></script>
    <script>
        function ready(){
        document.getElementsByClassName("toggle").onclick = func();
        function func() {
            elems = document.getElementsByClassName('item');
            [].forEach.call(elems, function( el ) {
                if (el.classList.contains('active'))
                {
                    el.classList.remove('active');
                }
                else{
                    el.classList.add('active');
                }
            });    
    };
};
    </script>
</head>
<body>
	<header>
	<nav>
		<ul class="menu">
				<li class="logo"><a href="/"><i class='fa fa-camera-retro'></i></a><a id="1" href="/">Kborroq's Camagru</a></li>
				
				<li class='item'><a href="/about">About</a></li>
				<li class='item'><a href="/feedback">Feedback</a></li>
                <?php
        if (!isset($_SESSION['username']) and !isset($_SESSION['password']))
            {
                echo "<li class=\"item button3\"><a href='/auth'>Log In</a></li>";
				echo "<li class=\"item button3 secondary\"><a href='/signup'>Sign In</a></li>";
            }
        else {
			echo "<li class='item'><a href='/add'>Add</a></li>";
            echo "<li class='item'><a href='/main/profile/{$_SESSION['uid']}'><div id='user'>{$_SESSION['username']}</div></a></li>";
            echo "<li class='item'><a href='/settings'>Settings</div></a></li>";
            echo "<li class='item' button3 secondary\"><a href='/auth/signout'>Sign Out</a></li>";
        }
?>

			<li class="toggle" onclick="return ready();"><span class="bars"></span></li>
			</ul>
		</nav>
	</header>