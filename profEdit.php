<?php

//共通変数・関数ファイルを読込み
require('./function.php');
require('./errorMesages.php');
require('./validations.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　PROFEDIT.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//Login authentication
require('auth.php');

//GET USER DATA FROM DATABASE
$dbFromData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報: '.print_r($dbFromData, true));

//POST送信があった場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報: '.print_r($_POST, true));

  //変数にユーザー情報を代入
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $job = $_POST['job'];
  $area = $_POST['area'];
  $comment = $_POST['comment'];

  //DBの情報と入力情報が異なる場合のみValidationCheckを行う
  if($dbFromData['user_name'] !== $user_name){
    //名前の最大文字数チェック
    validMaxLen($user_name, 'user_name');
  }
  if($dbFromData['email'] !== $email){
    //emailの最大文字数チェック
    validMaxLen($email, 'email');
    if(empty($err_msg['email'])){
      //emailの重複チェック
      validEmailDup($email);
    }
    //emailの形式チェック
    validEmail($email, 'email');
    //emailの未入力チェック
    validRequired($email, 'email');
  }
  if($dbFromData['job'] !== $job){
    validMaxLen($job, 'job');
  }
  if($dbFromData['area'] !== $area){
    validMaxLen($area, 'area');
  }
  if($dbFromData['comment'] !== $comment){
    validMaxLen($comment, 'comment');
  }

  if(empty($err_msg)){
    debug('Validation OKです！');
    try{
      //DataBase Connection
      $dbh = dbConnect();
      //Create SQL
      $sql = 'UPDATE users SET user_name = :user_name, email = :email, job = :job, area = :area, comment = :comment WHERE user_id = :user_id';
      $data = array(':user_name' => $user_name, ':email' => $email, ':job' => $job, ':area' => $area, ':comment' => $comment, ':user_id' => $dbFromData['user_id']);
      //Query
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC02;
        //ここを変更する必要がある
        //値の保存後にどういう処理を行うのか？
        debug('マイページへ遷移します。');
        header("Location:profEdit.php"); //マイページへ
        //ここまで
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
$siteTitle = 'PROFEDIT';
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
          <label class="<?php if(!empty($err_msg['user_name'])) echo 'err'; ?>">
            NAME
            <span class="form_msg">
              <?php
                if(!empty($err_msg['user_name'])) echo $err_msg['user_name'];
              ?>
            </span>
            <input type="text" name="user_name" value="<?php echo getFormData('user_name'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            EMAIL
            <span class="form_msg">
              <?php
                if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </span>
            <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['job'])) echo 'err'; ?>">
            JOB
            <span class="form_msg">
              <?php
                if(!empty($err_msg['job'])) echo $err_msg['job'];
              ?>
            </span>
            <input type="text" name="job" value="<?php echo getFormData('job'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['area'])) echo 'err'; ?>">
            AREA
            <span class="form_msg">
              <?php
                if(!empty($err_msg['area'])) echo $err_msg['area'];
              ?>
            </span>
            <input type="text" name="area" value="<?php echo getFormData('area'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
            COMMENT
            <span class="form_msg">
              <?php
                if(!empty($err_msg['comment'])) echo $err_msg['comment'];
              ?>
            </span>
            <textarea name="comment"><?php echo getFormData('comment'); ?></textarea>
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