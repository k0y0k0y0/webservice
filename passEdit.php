<?php

//共通変数・関数ファイルを読込み
require('function.php');
require('./errorMesages.php');
require('./validations.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　PASSEDIT.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//Login authentication
require('auth.php');

//GET USER DATA FROM DATABASE
$dbFromData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報: '.print_r($dbFromData, true));

if($_POST){
  debug('POST送信があります。');
  debug('POST情報: '.print_r($_POST, true));

  //変数にユーザー情報を代入
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  if($dbFromData['pass'] !== $pass){
    validRequired($pass, 'pass');
    validHalf($pass, 'pass');
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');
    validMatch($pass, $pass_re, 'pass_re');
  }

  if(empty($err_msg)){
    debug('Validation OKです！');
    try{
      //DataBase Connection
      $dbh = dbConnect();
      //Create SQL
      $sql = 'UPDATE users SET password = :pass WHERE user_id = :user_id';
      $data = array(':pass' => $pass, ':user_id' => $dbFromData['user_id']);
      //Query
      $stmt = queryPost($dbh, $sql, $data);
      //クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC02;
        debug('マイページへ遷移します。');
        header("Location:passEdit.php"); //マイページへ
      }else{
        debug('クエリが失敗しました。');
        $err_msg['common'] = MSG07;
      }
    }catch(Exception $e){
      error_log('エラー発生'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'LOGIN';
require('./head.php');
?>
<body>

  <!-- ヘッダー -->
  <?php
  require('./header.php');
  ?>

  <!-- コンテンツ -->
  <div id="contents" class="site-width">

    <!-- サイドバー -->
    <?php
    $userName = $dbFromData['user_name'];
    require('./sideBar.php');
    ?>

    <!-- メイン -->
    <section id="main" class="with-sidebar">
      <div class="form-container">
        <form action="" method="post" class="form">
          <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
            PASSWORD
            <span style="font-size:12px">※英数字６文字以上</span>
            <span class="form_msg">
              <?php
                if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </span>
            <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo$_POST['pass']; ?>">
          </label>
          <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
            PASSWORD:RE
            <span class="form_msg">
              <?php
                if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
              ?>
            </span>
            <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo$_POST['pass_re']; ?>">
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="SAVE">
          </div>
        </form>
      </div>
    </section>

  </div>

  <!-- フッター -->
  <?php
  require('./footer.php');
  ?>