<?php
$err_msg = [];
if ($_POST) {
  //入力項目チェック
  if (!$_POST['e']) {
    $err_msg[] = "メールアドレスを入力してください。";
  } else if ( mb_strlen($_POST['e']) > 200 ) {
    $err_msg[] = "メールアドレスは２００文字以内でご記入ください。";
  } else if (!filter_var($_POST['e'], FILTER_VALIDATE_EMAIL)) {
    $err_msg[] = "メールアドレスは正しくご記入ください。";
  }

  if (!$_POST['p']) {
    $err_msg[] = "パスワードを入力してください。";
  } else if ( mb_strlen($_POST['p']) > 100 ) {
    $err_msg[] = "パスワードは１００文字以内でご記入ください。";
  }

  if (!$_POST['p2']) {
    $err_msg[] = "確認用パスワードを入力してください。";
  } else if ( mb_strlen($_POST['p2']) > 100 ) {
    $err_msg[] = "確認用パスワードは１００文字以内でご記入ください。";
  }
  
  if ($_POST['p'] !== $_POST['p2']) {
    $err_msg[] = "確認用パスワードが一致しません。";
  }
  //新規ユーザー登録処理
  $userfile = '../../userinfo.txt';
  $users    = array();
  if (file_exists($userfile)) {
    $users    = file_get_contents( $userfile );
    $user    = explode("\n",$users);
    foreach ( $user as $key => $arr) {
      $userId = str_getcsv($arr);
      if ($userId[0] === $_POST['e']) {
        $err_msg[] = 'そのメールアドレスはすでに登録されてます';
        break;
      }
    }
  }
  if (!$err_msg) {
    $ph       = password_hash($_POST['p'] , PASSWORD_DEFAULT);
    $line     = '"'.$_POST['e'].'","'.$ph.'"'."\n";
    $ret      = file_put_contents($userfile,$line,FILE_APPEND);
  }
  //問題なければログイン画面へ
  if (!$err_msg) {
    $host = $_SERVER['HTTP_HOST'];
    $url  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
    header("Location: //$host$url/index.php");
    exit();
  }
} else {
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
	<title>新規登録フォーム</title>
</head>
<body>
	<div class="container">
		<div class="mx-auto" style="width:400px;">
    <h1>ユーザー新規登録</h1>
    <?php
    if ($err_msg) {
      echo '<div class="alert alert-danger" role="alert">';
      echo implode('<br>',$err_msg );
      echo '</div>';
    } ?>
			<form action="./register.php" method="post">
				Eメール   <input type="email" name="e" value="<?php echo htmlspecialchars($_POST['e']); ?>" class="form-control"><br>
				パスワード<input type="password" name="p" value="" class="form-control"><br>
				パスワード（確認用）<input type="password" name="p2" value="" class="form-control"><br>
				<div class="button text-center">
					<input class="btn btn-primary btn-lg"type="submit" value="新規登録する" name="register">
				</div>
			</form>
		</div>
	</div>
</body>
</html>