<?php
session_start();
$_SESSION = array();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<title>ログアウト後のページ</title>
</head>
<body>
	<div class="container">
		<div class="mx-auto" style="width:400px;">
			<form action="" method="post">
			<h1>
				また来てね〜
			</h1>
				<a href="./index.php">ログインして楽しむ</a>
			</form>
		</div>
	</div>
</body>
</html>