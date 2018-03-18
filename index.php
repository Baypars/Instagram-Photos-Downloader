<!DOCTYPE html>
<html>
<head>
	<title>Insta Photos Downloader</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<style type="text/css">
	body{
		background-color: #f2f2f2;
	}
	.img{
		padding:10px;
		background-color: #f9f9f9;
		transition: 0.5s;
		margin-bottom: 10px;
		border-radius: 3px;

	}
	img{
		min-height: 30vh;
		max-height: 30vh;
		height: 30vh;
		width: 100vw;
	}
	.img:hover{
		background-color: #fff;
		transition: 0.5s;
	}
</style>
<div class="page-header text-center">
	<div class="container">
		<h1 style="color:#333;text-shadow: 2px 2px 1px #aaa;">Instagram Photos Downloader</h1>
		<p style="color:#777;">Download larger instagram profile photos with InstaPPD.<br/>
		(Only last 12 photos)</p>
	</div>
</div>


<div style="width:260px;margin:0 auto;">
	<form action="" method="POST">
		<input type="text" class="form-control" style="width:190px;margin:auto 10px 20px auto; float:left;" name="profileid" placeholder="Profile username .." />
		<button type="submit" class="btn btn-info" style="margin:auto auto 20px auto;float:left;" name="submittt">Send</button>
	</form>
</div>
<div style="clear:both;"></div>
<hr>

<?php
include("simple_html_dom.php");
//returns a big old hunk of JSON from a non-private IG account page.
if (isset($_POST['submittt']) AND !empty($_POST['profileid'])) {
	if (!preg_match('/[^abcdefghijklmnoprstuvyzxwq_.0123456789ABCDEFGHIJKLMNOPRSTUVYZXWQ]/', $_POST['profileid'])) {

		$link = 'https://www.instagram.com/'.$_POST['profileid'].'/';

		function get_http_response_code($istek) {
			$headers = get_headers($istek);
			return substr($headers[0], 9, 3);
		}
		$get_http_response_code = get_http_response_code($link);

		if ( $get_http_response_code == 404 ) {
			echo"<div class=\"container text-center\">
			<div class=\"alert alert-danger\">
			<strong>Warning!</strong> No Such User.
			</div>
			</div>";
		} else {
			$kaynakkod = file_get_contents('https://www.instagram.com/'.$_POST['profileid']);
			if (strpos($kaynakkod, 'is_private":true') != FALSE) {
				# code...
				echo "<div class=\"container text-center\">
				<div class=\"alert alert-danger\">
				<strong>Warning!</strong> This profile is Private.
				</div>
				</div>";
			}
			else{

				$parcala_1 = explode('window._sharedData = ', $kaynakkod);
				$parcala_2 = explode(';</script>', $parcala_1[1]); 
				$instadizi = json_decode($parcala_2[0], TRUE);
				$instafoto = $instadizi['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];

				$diziSayisi = count($instafoto);
				echo '
				<div class="container text-center">
				<div class="alert alert-success">
				<strong>Success!</strong> The Photos Was Successfully Displayed.
				</div>
				</div>
				';

				for ($i=0; $i < $diziSayisi ; $i++) { 

					echo '
					<div class="container col-lg-2 col-md-3 col-sm-4">
					<div class="img">
					<a href="'.$instafoto[$i]['node']['display_url'].'">

					<img src="'.$instafoto[$i]['node']['display_url'].'" height="100" class="img-responsive" />
					</a>
					<br>
					<p align="center">
					<span class="label label-primary">Likes : '.$instafoto[$i]['node']['edge_liked_by']['count'].'</span>'.'
					<br>
					<span class="label label-danger">Comments : '.$instafoto[$i]['node']['edge_media_to_comment']['count'].'</span>
					</p>
					</div>
					</div>

					';
				}
			}
		}
	}
	else{
		echo "
		<div class=\"container text-center\">		
		<div class=\"alert alert-danger\">
		<strong>Warning!</strong> You Have Entered an Invalid Username. Please, Don't Use ".htmlspecialchars("\";#()[]'^-\<>/")." etc.
		</div>
		</div>

		";
	}
}
else{
	echo "
	<div class=\"container text-center\">
	<div class=\"alert alert-info\">
	<strong>Info!</strong> Fill in The Box with Instagram Profile ID, pls..
	</div>
	</div>";
}

?>
</body>
</html>