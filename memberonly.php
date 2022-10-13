<?php
session_start();
if( !isset($_SESSION['e']) && !$_SESSION['e']) {
	$host = $_SERVER['HTTP_HOST'];
	$url  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
	header("Location: //$host$url/index.php");
	exit();
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<title>メンバー専用ページ</title>
</head>
<body>
	<div class="container">
		<div class="mx-auto" style="width:400px;">
			<form action="" method="post">
			<h1>
				へいらっしゃい！
			</h1>
			<?php
			if (isset($_SESSION['chenge']) && $_SESSION['chenge']) {
				echo '<h4 class="passChenged">';
				echo 'パスワードの変更を完了しました';
				echo '</h4>';
			} ?>
				<a href="./logout.php">ログアウトする</a>
				<a href="./new_password.php">パスワードを変更する</a>
			</form>
		</div>
	</div>
</body>
</html>