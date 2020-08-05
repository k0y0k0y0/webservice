<?php

//共通変数・関数ファイルを読込み
require('./function.php');
require('./errorMesages.php');
require('./validations.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　LOGIN.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//Login authentication
require('./auth.php');

//post送信されていたら以下の処理を行う
if(!empty($_POST)){
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false; //ショートハンド（略記法）という書き方

  //Validation Check
  //1.未入力
  validRequired($email, 'email');
  validRequired($pass, 'pass');

  //Next Step or Not
  if(empty($err_msg)){
    //2.email
    validEmail($email, 'email');
    validMaxLen($email, 'email');

    //3.password
    validHalf($pass, 'pass');
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');

    if(empty($err_msg)){
      debug('バリデーションOKです。');

      try{
        //DBConnection
        $dbh = dbConnect();
        //Create SQL
        $sql = 'SELECT password, user_id FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        //Query
        $stmt = queryPost($dbh, $sql, $data);
        //Get result of query
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        debug('クエリ結果の中身: '.print_r($result, true));
        if(!empty($result) && password_verify($pass, array_shift($result))){
          debug('パスワードが一致しました。');
          //ログイン有効期限（デフォルトを１時間とする）
          $seeLimit = 60*60;
          //最終ログイン日時を現在日時に
          $_SESSION['login_date'] = time();

          //ログイン保持にチェックがある場合
          if($pass_save){
            debug('ログイン保持します。');
            //30days
            $_SESSION['login_limit'] = $seeLimit * 24 * 30;
          }else{
            debug('ログイン保持しません。');
            $_SESSION['login_limit'] = $seeLimit;
          }
          //ユーザーIDを格納
          $_SESSION['user_id'] = $result['user_id'];
          debug('セッション変数の中身: '.print_r($_SESSION, true));
          debug('マイページに遷移します。');
          header('Location:mypage.php');
        }else{
          debug('パスワードが違います。');
          $err_msg['common'] = MSG09;
        }
      }catch(Exception $e){
        error_log('エラー発生: '.$e->getMessage());
        $err_msg['common'] = MSG07;
      }
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

    <!-- メイン -->
    <section id="main">
      <div class="form-container">
        <form action="" method="post" class="form">
          <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            EMAIL
            <span class="form_msg">
              <?php
                if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </span>
            <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo$_POST['email']; ?>">
          </label>
          <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
            PASSWORD
            <span class="form_msg">
              <?php
                if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </span>
            <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo$_POST['pass']; ?>">
          </label>
          <label>
            <input type="checkbox" name="pass_save">
            <span class="checkbox_msg">次回ログインを省略する</span>
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="LOGIN">
          </div>
          パスワードを忘れた方は<a href="./passRemindSend.php">コチラ</a>
        </form>
      </div>
    </section>
  </div>


  <!-- フッター -->
  <?php
  require('./footer.php');
  ?>