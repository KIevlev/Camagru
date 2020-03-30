<?php
if ($data === Model::SID_NOT_FOUND)
{
	echo <<<SUC
    <br><br><br><br><br><br>
    <p style="text-align: center; font-size: larger">
        Sorry, but you confirmed link is incorrect. Please, try again
    </p>
    <a  style="text-align: center;" href="/main">
        <p>return to main page</p>
    </a>
SUC;
}
elseif ($data === Model::DB_ERROR)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Sorry, we have some problem with database. Please stand by.
	</p>
SUC;
}
elseif ($data === Model::ALREADY_C)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Account has been already verified.
	</p>
SUC;
}
else
{
		echo <<<SIGNIN
		<div class="wrapper">
		<div class="content">
	<form class="box" method="POST">
	<h1>Login</h1>
	<input type="TEXT" name="username" placeholder="Username" required/>
	<input type="PASSWORD" name="password" placeholder="Password" required/>
	<input type="SUBMIT" name="submit" value="Login" />
	<a class="add" href='/forgotten'> forgot your password?</a>
	<a class="add" href='/signup'>sign in now!</a>
	</form>
	</div>
SIGNIN;
		if ($data === Model::INCORRECT_NICK_PASS)
			echo "<p style='color: darkred; font-style: italic'>Incorrect login or password</p>";
}