<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　PROFEDIT.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//Login authentication
require('auth.php');

//GET USER DATA FROM DATABASE
$dbFromData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報: '.print_r($dbFromData, true));

$userName = $dbFromData['user_name'];

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
    require('./sideBar.php');
    ?>

    <!-- メイン -->
    <section id="main" class="with-sidebar">
      <div class="form-container">
        <form action="" method="post" class="form">
          <label class="<?php if(!empty($err_msg['name'])) echo 'err'; ?>">
            NAME
            <span class="form_msg">
              <?php
                if(!empty($err_msg['name'])) echo $err_msg['name'];
              ?>
            </span>
            <input type="text" name="name" value="<?php if(!empty($_POST['name'])) echo$_POST['name']; ?>">
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
          <label class="<?php if(!empty($err_msg['job'])) echo 'err'; ?>">
            JOB
            <span class="form_msg">
              <?php
                if(!empty($err_msg['job'])) echo $err_msg['job'];
              ?>
            </span>
            <input type="text" name="job" value="<?php if(!empty($_POST['job'])) echo$_POST['job']; ?>">
          </label>
          <label class="<?php if(!empty($err_msg['area'])) echo 'err'; ?>">
            AREA
            <span class="form_msg">
              <?php
                if(!empty($err_msg['area'])) echo $err_msg['area'];
              ?>
            </span>
            <input type="text" name="area" value="<?php if(!empty($_POST['area'])) echo$_POST['area']; ?>">
          </label>
          <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
            COMMENT
            <span class="form_msg">
              <?php
                if(!empty($err_msg['comment'])) echo $err_msg['comment'];
              ?>
            </span>
            <textarea name="comment"></textarea>
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