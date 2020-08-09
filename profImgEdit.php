<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　PROFIMGEDIT.PHP　');
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

  //画像をアップロードし、パスを格納
  $pic = (!empty($_FILES['pic']['name']))? uploadImg($_FILES['pic'], 'pic'): '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $pic = ( empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $pic;

  if(empty($err_msg)){
    debug('Validation OKです！');
    try{
      //DataBase Connection
      $dbh = dbConnect();
      //Create SQL
      $sql = 'UPDATE users SET pic = :pic WHERE user_id = :user_id';
      $data = array(':pic' => $pic, ':user_id' => $dbFromData['user_id']);
      //Query
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC02;
        //ここを変更する必要がある
        //値の保存後にどういう処理を行うのか？
        debug('マイページへ遷移します。');
        header("Location:profImgEdit.php"); //マイページへ
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
$siteTitle = 'PROFIMGEDIT';
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
        <form action="" >
          <label class="<?php if(!empty($err_msg['pic'])) echo 'err'; ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input type="file" name="pic" class="input-file">
            <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
            ドラック＆ドロップ
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