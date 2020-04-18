<?php
/*if ($data === Model::ARTICLE_NOT_FOUND)
	echo <<<NOT_FOUND
		<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Page not found. Please, check you link.
	</p>
NOT_FOUND;
else*/ if ($data === Model::DB_ERROR)
	echo <<<DB
		<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Sorry, we have some problem with database. Please stand by.
	</p>
DB;
elseif ($data === Model::INCOMPLETE_DATA)
	echo <<<ID
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	Your message is empty. Please write anything.
	</p>
ID;
elseif ($data === Model::NON_CONFIRMED_ACC)
	echo <<<NCA
	<br><br><br><br><br><br>
	<p style="text-align: center; font-size: larger">
	You account isn't confirmed. Please, check your email
	</p>
NCA;
else {
	echo <<<ARTICLE
	<main>
	<article class="post">
	<div class="wrapper">
	<div class="content">
	<div class="box_main">
		<section class="user-profile">
			<img class="user-pic" width="50" height="50" src="/exchange/icon/{$data[0]['uid']}">
			<a href="/main/profile/{$data[0]['uid']}"><p>{$data[0]['username']}</p></a>
			
ARTICLE;
	if (isset($_SESSION['uid']) and $_SESSION['uid'] === $data[0]['uid']) {
		echo "<h1> </h1>";
		echo "<form action='/article/delete/{$data[0]['aid']}' method='post'>";
		echo "<input style='font-style: italic; color: red' type='submit' name='del' value='Delete Post'>";
		echo "</form><br>";
	}
	echo <<<ARTICLE
	</section>
		<section class="photo">
			<img heigh="60vh" width="400vw" src="/exchange/photo/{$data[0]['aid']}">
		</section>
		<br>
		<p><span style="font-weight: bold">{$data[0]['description']}</p>
		<section class="like-comment_button">
				<form action="/add/like/{$data[0]['aid']}" method="post">
			<div class="likes">
			<input  class="fa fa-thumbs-o-up" style="font-size:36px id="like" name="like" value="&#xf087" type="submit">
			<span><i style="font-weight: bold">{$data[0]['likes']}</i>
			</div></form>
			<br>
ARTICLE;

	foreach ($data[1] as $data1) {
		$content = htmlentities($data1['text']);
		echo <<<COMMENT
	<div class="com">
	<p><span style="font-weight: bold"><a href="/main/profile/{$data1['uid']}">{$data1['nickname']}:</a></span>
	{$content}
	</p>
COMMENT;
		if ($data1['uid'] === $_SESSION['uid'])
			echo <<<DEL_INPUT
			<h1> </h1>
	<form action="/article/del_comment/{$data1['cid']};{$data[0]['aid']}" method="post">
	<input style="color: red" type="submit" value="Delete comment" name="Delete">
</form>

DEL_INPUT;
		echo "</div><hr>";
	}
	echo <<<ADD_COMMENT
	<br>
<form id="comment_field" action="/article/add/{$data[0]['aid']}" method="post">
	<input style="width: 50%" name="comment" type="text" required="required" maxlength="250">
	<input type="submit" value="Add comment" name="butt">
</form>
<br>
<div class='public'>
ADD_COMMENT;

?>

<script type="text/javascript">
document.write(VK.Share.button({
  //url: encodeURIComponent(document.location.href),
  title: 'Amazing photo',
  description: 'Check it out',
  image: document.location.protocol + document.location.host + '/photos/<?php echo $data[0]['aid']; ?>' + '.jpg',
  noparse: false,
}, {type: "round",text: 'Опубликовать'}));
myHeaders.append('og:image', document.location.protocol + document.location.host + '/photos/<?php echo $data[0]['aid']; ?>' + '.jpg');
</script>
<?php
	echo "</div></section></div></div></div></article></main>";
}
