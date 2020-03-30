<?php
/*
if ($data === Model::USER_EXIST)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	An account with this email or login has already been created  
	</p>
SUC;
}*/
if ($data === Model::DB_ERROR)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Sorry, we have some problem with database. Please stand by.
	</p>
SUC;
}
/*elseif ($data === Model::DB_ERROR1)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	AAAAAAAAAAAAAAAAAAAAAa
	</p>
SUC;
}
elseif ($data === Model::DB_ERROR2)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	BBBBBBBBBBBBBBBBB
	</p>
SUC;
}*/
elseif ($data === Model::SUCCESS)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
		You successfully create account! Now, check you email address and confirm account.
	</p>
	<a  style="text-align: center;" href="/main">
		<p>return to main page</p>
	</a>
SUC;
}
else
{
	echo <<<SIGNIN
				<div class="wrapper">
				<div class="content">
				<form class="box" action="/signup/create" method="POST">
				<h1>Registration page</h1>
				<input type="text" name="username" placeholder="Username" required>
				<input type="email" name="email" placeholder="example@mail.com" required> 
				<input type="password" name="password" placeholder="Password" required>
				<input type="password" name="pass2" placeholder="Password once more" required>
SIGNIN;
			if ($data === Model::INCOMPLETE_DATA)
				echo "<p style='color: darkred; font-style: italic'>Please, enter login, e-mail and password</p>";
			elseif ($data === Model::BAD_EMAIL)
				echo "<p style='color: darkred; font-style: italic'>Please, enter correct e-mail</p>";
			elseif ($data === Model::WEAK_PASSWORD)
				echo "<p style='color: darkred; font-style: italic'>Your password is weak. Please, input minimum
																	7 characters with upper case symbol</p>";
			elseif ($data === Model::PASS_DIFF)
				echo "<p style='color: darkred; font-style: italic'>passwords don't match</p>";
			elseif ($data === Model::USER_EXIST)
				echo "<p style='color: darkred; font-style: italic'> An account with this login has been already created</p>";
			elseif ($data === Model::EMAIL_EXIST)
				echo "<p style='color: darkred; font-style: italic'> An account with this email has been already created</p>";
			echo <<<SIGNIN
				<p><input type="submit" name="submit" value="Sign In"></p>
			</form>
		</div>
SIGNIN;
}