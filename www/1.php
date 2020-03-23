<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body>
		<header>
		<ul class="logo">
				<li><i class='fa fa-camera-retro' style='font-size:36px'></i></li>
				<li><p href="#">Kborroq's Camagru</a></li>
			</ul>
		<nav>
			<ul class="nav_links">
				<li><a href="#">About</a></li>
				<li><a href="#">Contact me</a></li>
			</ul>
		</nav>
	</header>

<div class="wrapper">

	<div class="content">
<form class="box" method="POST">
   <?php 
    echo "<p>Welcome, guest </p>";

?>
    <h1>Login</h1>
    <input type="TEXT" name="username" placeholder="Username" required/>
    <input type="PASSWORD" name="password" placeholder="Password" required/>
    <input type="SUBMIT" name="submit" value="Login" />
    <a class="add" href='#'> forgot your password?</a>
    <a class="add" href='regist.php'>sign in now!</a>
    
</form>
		
	</div><!-- .content -->

	<div class="footer">
		
<div class="container">	
				<div class="row">
					<div class="col-4">
						<p class="Footer__name"> © Made by Ievlev Kirill 2020</p>
					</div>
					<div class="col-4">
						<p class="footer__social">My profiles</p>
						<p class="footer__social-icons">
							<a href="#" class="fa fa-facebook"></a>
							<a href="#" class="fa fa-linkedin"></a>
							<a href="#" class="fa fa-skype"></a>
						</p>
					</div>
					<div class="col-4">
						<div><a href="https://github.com/KIevlev/Roger" class="button" target="_blank">Hithub link</a></div>
					</div>
				</div>	
			</div>

	</div>

</div><!-- .wrapper -->



</body></html>