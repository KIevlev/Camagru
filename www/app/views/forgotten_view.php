<?php
if ("array" === gettype($data) and $data[0] === Model::WRONG_PASSWORD)
{
	echo <<<SIGNIN
	<div class="wrapper">
		<div class="content">
	<form class="box" action="/forgotten/recovery/{$data[1]}" method="POST">
	<h1>Please, enter new password</h1>
	<input type="PASSWORD" placeholder="New Password" name="new_password" required/>
	<input type="PASSWORD" placeholder="Confirm Password" name="confirm_password" required/>
	<input type="SUBMIT" name="submit" value="Sign in" />
	</form>
	</div>
	</div>
SIGNIN;
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
elseif ($data === Model::SID_NOT_FOUND)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	your link is invalid :(
	</p>
SUC;
}
elseif ($data === Model::SAME_PASS)
{
	echo <<<SIGNIN
	<div class="wrapper">
		<div class="content">
	<form class="box" action="/forgotten/recovery/{$data[1]}" method="POST">
	<h1>Please, enter new password</h1>
	<input type="PASSWORD" placeholder="New Password" name="new_password" required/>
	<input type="PASSWORD" placeholder="Confirm Password" name="confirm_password" required/>
	<input type="SUBMIT" name="submit" value="Sign in" />
	<p style="text-align: center; font-size: larger">
	new password should be different from the previous one 
	</p>
	</form>
	</div>
	</div>
SIGNIN;
}
elseif ($data === Model::SID_NOT_FOUND)
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
elseif ($data === Model::WEAK_PASSWORD)
{
	echo <<<SIGNIN
	<div class="wrapper">
		<div class="content">
	<p>Enter new password</p>
	<form class="box" action="/forgotten/recovery/{$data[1]}" method="post">
		<input  type="password" placeholder="New Password" name="new_password" required="required">
		<input  type="password" placeholder="Confirm Password" name="confirm_password" required="required">
		<input type="SUBMIT" name="submit" value="Sign in" />
		<p style='color: darkred; font-style: italic'>Your password is weak. Please, input minimum
																	7 characters with upper case symbol</p>
	</form>
	</div>
	</div>
SIGNIN;
}
elseif ($data === Model::PASS_NOT_MATCH)
{
	echo <<<SIGNIN
	<div class="wrapper">
		<div class="content">
	<p>Enter new password</p>
	<form class="box" action="/forgotten/recovery/{$data[1]}" method="post">
		<input  type="password" placeholder="New Password" name="new_password" required="required">
		<input  type="password" placeholder="Confirm Password" name="confirm_password" required="required">
		<input type="SUBMIT" name="submit" value="Sign in" />
		<p style='color: darkred; font-style: italic'>passwords don't match</p>
	</form>
	</div>
	</div>
SIGNIN;
}
elseif ($data === Model::SUCCESS)
{
	echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
		We sent a link to your E-Mail. Please, check it
	</p>
	<a  style="text-align: center;" href="/main">
		<p>return to main page</p>
	</a>
SUC;
}
elseif ($data === Model::BAD_EMAIL)
{
	echo <<<SIGNIN
<div class="wrapper">
<div class="content">
	
	<form class="box" action="/forgotten/check_email" method="post">
	<h1>Please, enter your E-Mail</h1>
		<input  type="email" placeholder="E-Mail" name="email" required="required">
		<input type="submit" name="submit" value="Send link">
		<p style='color: darkred; font-style: italic'>There is no such email in database</p>
</form>
	</div>
	</div>
SIGNIN;
}
else
echo <<<SIGNIN
<div class="wrapper">
<div class="content">
	
	<form class="box" action="/forgotten/check_email" method="post">
	<h1>Please, enter your E-Mail</h1>
		<input  type="email" placeholder="E-Mail" name="email" required="required">
		<input type="submit" name="submit" value="Send link">
</form>
	</div>
	</div>
SIGNIN;
