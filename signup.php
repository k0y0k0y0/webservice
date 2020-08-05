<?php

//共通変数・関数ファイルを読込み
require('./function.php');
require('./errorMesages.php');
require('./validations.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　SIGNUP.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//post送信されていたら以下の処理を行う
if(!empty($_POST)){
  debug('POST送信があります。');
  //POSTの中身を変数に代入
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  //Validation Check
  //1.未入力
  validRequired($user_name, 'user_name');
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  //Next Step or Not
  if(empty($err_msg)){
    //2.name
    validMaxLen($user_name, 'user_name');

    //Next Step or Not
    if(empty($err_msg)){
      //3.email
      validEmail($email, 'email');
      validMaxLen($email, 'email');
      validEmailDup($email);

      //Next Step or Not
      if(empty($err_msg)){
        //4.password
        validHalf($pass, 'pass');
        validMaxLen($pass, 'pass');
        validMinLen($pass, 'pass');

        //Next Step or Not
        if(empty($err_msg)){
          //5.password re
          validMatch($pass, $pass_re, 'pass_re');

          if(empty($err_msg)){
            try{
              //Data Base Connection
              $dbh = dbConnect();
              //Create SQL
              $sql = 'INSERT INTO users (user_name, email, password, login_time, create_date)
                      VALUES (:name, :email, :pass, :login_time, :create_date)';
              $data = array(':name' => $user_name,
                            ':email' => $email,
                            ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                            ':login_time' => date('Y-m-d H:i:s'),
                            ':create_date' => date('Y-m-d H:i:s'));
              //Query
              $stmt = queryPost($dbh, $sql, $data);
              //クエリ成功の場合
              if($stmt){
                //ログイン有効期限(default=1[h])
                $seeLimit = 60*60;
                //最終ログインを現在日時に
                $_SESSION['login_date'] = time();
                $_SESSION['login_limit'] = $seeLimit;
                //ユーザーIDを格納
                $_SESSION['user_id'] = $dbh->lastInsertId();

                debug('セッション変数の中身: '.print_r($_SESSION, true));

                //Go to mypage
                header('Location:mypage.php');
              }
            }catch(Exception $e){
              error_log('エラー発生: '.$e->getMessage());
              $err_msg['common'] = MSG07;
            }
          }
        }
      }
    }
  }
}
?>
<?php
$siteTitle = 'SIGNUP';
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
          <label class="<?php if(!empty($err_msg['user_name'])) echo 'err'; ?>">
            NAME
            <span class="form_msg">
              <?php
                if(!empty($err_msg['user_name'])) echo $err_msg['user_name'];
              ?>
            </span>
            <input type="text" name="user_name" value="<?php if(!empty($_POST['user_name'])) echo$_POST['user_name']; ?>">
          </label>
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
            <input type="submit" class="btn btn-mid" value="SIGNUP">
          </div>
        </form>
      </div>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('./footer.php');
  ?>