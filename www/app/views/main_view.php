<script>
var h = document.getElementsByTagName('head').item(0);
var s = document.createElement("script");
s.type = "text/javascript"; 
s.appendChild(document.createTextNode("src='/js/scroll.js'>"));
h.appendChild(s);
</script>
<?php
if ($data === Model::DB_ERROR)
	echo <<<DB_SUC
<br><br><br><br><br><br>
<p style="text-align: center; font-size: larger">
Sorry, we have some problem with database. Please stand by.
</p>
DB_SUC;
elseif ($data === Model::EMPTY_PROFILE)
	echo <<<DB_SUC
<br /><br /><br /><br><br><br>
<p style="text-align: center; font-size: larger">
You don't have any posts. Go create one!
</p>
DB_SUC;
else
{
	if ($_SERVER['type'] === 'profile')
		$uid = $data[0]['uid'];
	foreach ($data as $d)
	{
		echo <<<article
	<article class="post">
	<div class="wrapper">
	<div class="content">
	<div class="box_main">
	<a name="{$d['aid']}"></a>
			<section class="user-profile">
				<img class="user-pic"  src="/exchange/icon/{$d['uid']}">
				<a href="/main/profile/{$d['uid']}"><p>{$d['username']}</p></a>
			</section>
			<section class="photo">
				<img src="/exchange/photo/{$d['aid']}">
			</section>
			<p><span style="font-weight: bold">{$d['username']}: </span>{$d['description']}</p>
			<section class="like-comment_button">
				<form action="/add/like/{$d['aid']}" method="post">
			<div class="likes">
			<input  class="fa fa-thumbs-o-up" style="font-size:36px id="like" name="like" value="&#xf087" type="submit">
			<span><i style="font-weight: bold">{$d['likes']}</i>
			</div></form>
			<br>
				<div class="comment-div"><a class="comment-button" href="/article/index/{$d['aid']}">Open comments</a></div>
				</section>
		</div>
		</div>
		</div>
		</article>
		
article;
	}
	echo "<div class='pag'>";
	if ($_SERVER['type'] === 'feed')
		$type = 'index/';
	else
		$type = 'profile/' . $uid;
	if (isset($_GET['page']))
	{
		if (!isset($_SERVER['first']))
		{
			$prev_page = $_GET['page'] - 1;
			echo "<div class='navipage'></div>";
			echo "<div class='navipage1'><a href='/main/$type?page=$prev_page'><button><i class='fa fa-caret-square-o-left' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
		} else
		{
			echo "<div class='navipage'></div>";
			echo "<div class='navipage1'><a href='#'><button><i class='fa fa-caret-square-o-up' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
		}
		if (!isset($_SERVER['last']))
		{
			$next_page = $_GET['page'] + 1;
			echo "<div class='navipage1'><a href='/main/$type?page=$next_page'><button><i class='fa fa-caret-square-o-right' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
		} else
		{
			echo "<div class='navipage1'><a href='#'><button><i class='fa fa-caret-square-o-up' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
		}
	} else
	{
		if (!isset($_SERVER['last']))
		{
			echo "<div class='navipage'></div>";
			echo "<div class='navipage1'><a href='#'><button><i class='fa fa-caret-square-o-up' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
			echo "<div class='navipage1'><a href='/main/$type?page=2'><button><i class='fa fa-caret-square-o-right' style='font-size:24px'></i>︎</button></a></div>";
			echo "<div class='navipage'></div>";
		} else
		{
			echo "<div class='navipage'></div>";
			echo "<div class='navipage1'><a href='#'><button><i class='fa fa-caret-square-o-up' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
			echo "<div class='navipage1'><a href='#'><button><i class='fa fa-caret-square-o-up' style='font-size:24px'></i></button></a></div>";
			echo "<div class='navipage'></div>";
		}
	}
	echo "</div>";
}

?>