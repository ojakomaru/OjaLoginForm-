<?php
session_start();
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

  if (!$_POST['p']) {
    $err_msg[] = "パスワードを入力してください。";
  } else if ( mb_strlen($_POST['p']) > 100 ) {
    $err_msg[] = "パスワードは１００文字以内でご記入ください。";
  }
	//認証チェック
	$userfile = '../../userinfo.txt';
  $users    = array();
  if (file_exists($userfile)) {
    $users    = file_get_contents( $userfile );
    $user    = explode("\n",$users);
    foreach ( $user as $key => $arr) {
      $userId = str_getcsv($arr);
      if ($userId[0] === $_POST['e']) {
				if (password_verify($_POST['p'] , $userId[1])) {
					//問題なければメンバー専用ページへ
					$_SESSION['e'] = $_POST['e'];
					if (!$err_msg) {
						$host = $_SERVER['HTTP_HOST'];
						$url  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
						header("Location: //$host$url/memberonly.php");
						exit();
					}
				}
			}
    }
		$err_msg[] = 'メールアドレスまたはパスワードが間違っています';
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
	<title>ログインフォーム</title>
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
		<h1>ユーザーログイン画面</h1>
		<a href="./register.php">初めての方はこちら</a><br>
		<a href="./forgetpassword.php">パスワードをお忘れたの方はこちら</a>
			<form action="" method="post">
				Eメール   <input type="email" name="e" value="<?php echo htmlspecialchars($_POST['e']); ?>" class="form-control"><br>
				パスワード<input type="password" name="p" value="" class="form-control"><br>
				<div class="button text-center">
					<input class="btn btn-primary btn-lg"type="submit" value="ログインする" name="login">
				</div>
			</form>
		</div>
	</div>
</body>
</html>