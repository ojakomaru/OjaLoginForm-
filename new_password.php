<?php
session_start();
if( !isset($_SESSION['e']) && !$_SESSION['e']) {
	$host = $_SERVER['HTTP_HOST'];
	$url  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
	header("Location: //$host$url/index.php");
	exit();
}
$err_msg = [];
$check = false;
$e_check = false;
//最初の認証
if ($_POST['change']) {
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
  //認証チェック
	$userfile = '../../userinfo.txt';
  $users    = array();
  unset($_SESSION);
  if (file_exists($userfile)) {
    $users = file_get_contents( $userfile );
    $user  = explode("\n",$users);
    foreach ( $user as $key => $arr) :
      $userId = str_getcsv($arr);
      if ($userId[0] === $_POST['e']) {
        $e_check = true;
        if (password_verify($_POST['p'] , $userId[1])) {
          //問題なければパスワード生成ページへ
          if (!$err_msg) {
            $_SESSION['e'] = $_POST['e'];
            $_SESSION['p'] = $_POST['p'];
            $check = true;
          }
				} else {
          $err_msg[] = 'パスワードが間違っています';
        }
			}
    endforeach;
    if (!$e_check) {
      $err_msg[] = 'メールアドレスに誤りがございます';
    }
  } else {
		$err_msg[] = 'ユーザーファイルリストが見つかりません';
	}
} else {
  $_POST['e'] = '';
}

//新規パスワード生成・メール送信
if ($_POST['register']) {
  //入力項目チェック
  if (!$_POST['p2']) {
    $err_msg[] = "パスワードを入力してください。";
  } else if ( mb_strlen($_POST['p2']) > 100 ) {
    $err_msg[] = "パスワードは１００文字以内でご記入ください。";
  }

  if (!$_POST['p3']) {
    $err_msg[] = "確認用パスワードを入力してください。";
  } else if ( mb_strlen($_POST['p3']) > 100 ) {
    $err_msg[] = "確認用パスワードは１００文字以内でご記入ください。";
  }
  
  if ($_POST['p2'] !== $_POST['p3']) {
    $err_msg[] = "確認用パスワードが一致しません。";
  }

  $userfile = '../../userinfo.txt';
  $users    = array();
	$chenge = false;
  if (file_exists($userfile)) {
    $users    = file_get_contents( $userfile );
    $user    = explode("\n",$users);
    foreach ( $user as $key => $arr) {
      $userId = str_getcsv($arr);
      if (!$err_msg ){
					//入力チェック後パスワードを保存して送る
				$pass = $_POST['p2'];
				$message = "パスワードが変更されました\r\n".$pass."\r\n";
				mail($_SESSION['e'],'パスワードを変更しました',$message);
				$ph = password_hash($pass , PASSWORD_DEFAULT);
				$line = '"'.$_SESSION['e'].'","'.$ph.'"';
				$user[$key] = $line;
				$userinfo = implode("\n",$user);
				$ret = file_put_contents($userfile,$userinfo);
				$chenge = true;
        $_SESSION['chenge'] = $chenge;
        $host = $_SERVER['HTTP_HOST'];
        $url  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
        header("Location: //$host$url/memberonly.php");
        exit();
			}
    }
		if (!$chenge) {
      $check = true;
			$err_msg[] = 'パスワード変更に失敗しました';
		}
  } else {
		$err_msg[] = 'ユーザーファイルリストが見つかりません';
	}
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
	<title>パスワード変更フォーム</title>
</head>
<body>
	<div class="container">
		<div class="mx-auto" style="width:400px;">
    <h1>パスワード変更画面</h1>
    <?php
    if ($err_msg) {
      echo '<div class="alert alert-danger" role="alert">';
      echo implode('<br>',$err_msg );
      echo '</div>';
    } ?>
    <?php
    if ($check === false) { ?>
        <form action="./new_password.php" method="post">
          Eメール   <input type="email" name="e" value="<?php echo htmlspecialchars($_POST['e']); ?>" class="form-control"><br>
          現在のパスワード<input type="password" name="p" value="" class="form-control"><br>
          <div class="button text-center">
          <input class="btn btn-primary btn-lg"type="submit" value="パスワードを変更する" name="change">
          </div>
        </form>
  <?php } else { ?>
        <form action="./new_password.php" method="post">
          新しいパスワード<input type="password" name="p2" value="" class="form-control"><br>
          新しいパスワード（確認用）<input type="password" name="p3" value="" class="form-control"><br>
          <div class="button text-center">
            <input class="btn btn-primary btn-lg"type="submit" value="新しいパスワードに変更する" name="register">
          </div>
        </form>
  <?php } ?>
		</div>
	</div>
</body>
</html>