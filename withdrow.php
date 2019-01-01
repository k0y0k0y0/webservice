<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　WITHDROW.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//Login authentication
require('auth.php');

//GET USER DATA FROM DATABASE
$dbFromData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報: '.print_r($dbFromData, true));

$userName = $dbFromData['user_name'];

//post送信されていたら以下の処理を行う
if(!empty($_POST)){
  debug('POST送信があります。');
  try{
    //DataBase Connection
    $dbh = dbConnect();
    //Create SQL
    $sql1 = 'UPDATE users SET delete_flg = 1 WHERE user_id = :u_id';
    $data = array('u_id'=>$_SESSION['user_id']);
    //Query
    $stmt1 = queryPost($dbh, $sql1, $data);
    if($stmt1){
      session_destroy();
      debug('セッションの中身: '.print_r($_SESSION, true));
      debug('トップページへ遷移します。');
      header('Location:index.php');
    }else{
      debug('クエリが失敗しました。');
      $err_msg['common'] = MSG07;
    }
  }catch(Exception $e){
  debug('クエリが失敗しました。'.$e->getMessage());
  $err_msg['common'] = MSG07;
  }
}

?>
<?php
$siteTitle = 'WITHDROW';
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
    require('./sideBar.php');
    ?>

    <!-- メイン -->
    <section id="main" class="with-sidebar">
      <div class="form-container">
        <form action="" method="post" class="form">
          本当に退会しますか？
          <label>
            <input type="checkbox" name="withdrow">
            <span class="checkbox_msg">退会する</span>
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="WITHDROW" name="withdrow">
          </div>
        </form>
      </div>
    </section>

  </div>

  <!-- フッター -->
  <?php
  require('./footer.php');
  ?>