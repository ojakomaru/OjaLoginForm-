<?php
$err_msg = [];
if ($_POST) {
	//入力項目チェック
  if (!$_POST['e']) {
    $err_msg[] = "メールアドレスを入力してください。";
  } else if ( mb_strlen($_POST['e']) > 200 ) {
    $err_msg[] = "メールアドレスは２００文字以内でご記入ください。";
  } else if (!filter_var($_POST['e'], FILTER_VALIDATE_EMAIL)) {
    $err_msg[] = "メールアドレスが不正です。";
  }

	//認証チェック
	$userfile = '../../userinfo.txt';
  $users    = array();
	$complate = false;
  if (file_exists($userfile)) {
    $users    = file_get_contents( $userfile );
    $user    = explode("\n",$users);
    foreach ( $user as $key => $arr) {
      $userId = str_getcsv($arr);
      if ($userId[0] === $_POST['e']) {
					//メールが一致していたらパスワードを生成して送る
				$pass = bin2hex(random_bytes(5));
				$message = "パスワードが変更されました\r\n"
										.$pass."\r\n";
				mail($_POST['e'],'パスワード変更しました',$message);
				$ph = password_hash($pass , PASSWORD_DEFAULT);
				$line = '"'.$_POST['e'].'","'.$ph.'"';
				$user[$key] = $line;
				$userinfo = implode("\n",$user);
				$ret = file_put_contents($userfile,$userinfo);
				$complate = true;
				break;
			}
    }
		if (!$complate) {
			$err_msg[] = 'ユーザー名が間違っています';
		}
  } else {
		$err_msg[] = 'ユーザーファイルリストが見つかりません';
	}
	
} else {
	if (isset($_SESSION['e']) && $_SESSION['e']) {
		$host = $_SERVER['HTTP_HOST'];
		$url  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		header("Location: //$host$url/memberonly.php");
		exit();
	}
	$_POST = array();
	$_POST['e'] = '';
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
	<title>再発行用フォーム</title>
</head>
<body>
	<div class="container">
		<div class="mx-auto" style="width:400px;">
		<?php
    if ($err_msg) {
      echo '<div class="alert alert-danger" role="alert">';
      echo implode('<br>',$err_msg );
      echo '</div>';
    } ?>
		<h1>パスワード再発行画面</h1>
		<?php
		if ($complate) { ?>
			<h2>パスワードを再発行しました。<br>メールをご確認下さい</h2>
			<a href="./index.php">ログイン画面へ</a>
		<?php
		} else { ?>
			<form action="./forgetpassword.php" method="post">
			Eメール   <input type="email" name="e" value="<?php echo htmlspecialchars($_POST['e']); ?>" class="form-control"><br>
			<div class="button text-center">
				<input class="btn btn-primary btn-lg"type="submit" value="再発行する" name="forget_password">
			</div>
			</form>
		<?php } ?>
		</div>
	</div>
</body>
</html>