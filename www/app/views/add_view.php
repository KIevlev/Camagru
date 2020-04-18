<?php
if ($data ===  Model::DB_ERROR)
{
		echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Sorry, we have some problem with database. Please stand by.
	</p>
SUC;
}
elseif ($data === Model::UNUPLOADED_FILE)
{

		echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	An error occurred while loading the image. Please try again later
	</p>
SUC;
}
elseif ($data === Model::FORBIDDEN_FILETYPE)
{
		echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	You uploaded file with wrong filetype. Please, upload another file
	</p>
SUC;
}
elseif ($data === Model::NON_CONFIRMED_ACC)
{
		echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	You account isn't confirmed. Please, check your email
	</p>
SUC;
}
elseif ($data === Model::LIKE_EXIST)
{
		echo <<<SUC
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Like already exist
	</p>
SUC;
}
else {
	echo <<<MAIN
	<script src="js/camera.js"></script>
	
	<div id="create_post">
		<div id="image_field">
		<form id="upload_form" enctype="multipart/form-data" action="/add/create/" method="post">
		<input type="file" onchange="readURL();" id="file_up" name="image_upload" accept="image/jpeg, image/png, image/gif" >
		<video id="video" width="640" height="480" autoplay></video>
		<canvas id="canvas" width="640" height="480"></canvas>
	</form>
	</div>
		<div id="side_menu">
			<div id="sticker_bar">
				<img src="/images/ricardo.png">
				<img src="/images/doggi.png">
				<img src="/images/benis.png">
				<button id="del_stick" style="display: none" onclick="delete_sticker()">DELETE STICKER</button>
			</div>
		</div>
			<div id="photo_feed">
		
	
MAIN;
//print_r($data);
//echo '</pre>';

	foreach ($data as $d)
	{
		echo <<<article
			<section class="photo1">
				<img height=100px width=100px src="/exchange/photo/{$d['aid']} ">
				<br>
			</section>
article;
	}
echo "</div></div>
<div id ='description' style='text-align: center'>Description<br><input type='text' form='upload_form' maxlength='250'
																					required='required' name='description'></div>
				<input style='display: none' id='submit' type='submit' form='upload_form'>
				<button id='start_v'>START VIDEO</button>
				<button id='bsubmit' style='display: none' onclick='submit();'>SEND IMAGE</button>
			</div>
<canvas id='hide_canv' style='display: none' width='640' height='480'></canvas>";
}
	
