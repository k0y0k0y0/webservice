<?php

//共通変数・関数ファイルを読込み
require('./function.php');
require('./errorMesages.php');
require('./validations.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　POST.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//Login authentication
require('auth.php');

//GET USER DATA FROM DATABASE
$dbFromData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報: '.print_r($dbFromData, true));


?>
<?php
$siteTitle = 'POST';
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
          <label class="<?php if(!empty($err_msg['title'])) echo 'err'; ?>">
            TITLE
            <span class="form_msg">
              <?php
                if(!empty($err_msg['title'])) echo $err_msg['title'];
              ?>
            </span>
            <input type="text" name="title" value="<?php echo getFormData('title'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['place'])) echo 'err'; ?>">
            PLACE
            <span class="form_msg">
              <?php
                if(!empty($err_msg['place'])) echo $err_msg['place'];
              ?>
            </span>
            <input type="text" name="place" value="<?php echo getFormData('place'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['category'])) echo 'err'; ?>">
            CATEGORY
            <span class="form_msg">
              <?php
                if(!empty($err_msg['category'])) echo $err_msg['category'];
              ?>
            </span>
            <input type="text" name="category" value="<?php echo getFormData('category'); ?>">
          </label>
          <label class="<?php if(!empty($err_msg['post_comment'])) echo 'err'; ?>">
            COMMENT
            <span class="form_msg">
              <?php
                if(!empty($err_msg['post_comment'])) echo $err_msg['post_comment'];
              ?>
            </span>
            <textarea name="post_comment"><?php echo getFormData('post_comment'); ?></textarea>
          </label>
          <div style="overflow:hidden;">
            <div class="imgDrop-container">
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic1'])) echo $err_msg['pic1'];
                ?>
              </div>
              <label class="area-drop <?php if(!empty($err_msg['pic1'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic1" class="input-file">
                <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">
                  ドラッグ＆ドロップ
              </label>
            </div>

            <div class="imgDrop-container">
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic2'])) echo $err_msg['pic2'];
                ?>
              </div>
              <label class="area-drop <?php if(!empty($err_msg['pic2'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic2" class="input-file">
                <img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic2'))) echo 'display:none;' ?>">
                  ドラッグ＆ドロップ
              </label>
            </div>

            <div class="imgDrop-container">
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic3'])) echo $err_msg['pic3'];
                ?>
              </div>
              <label class="area-drop <?php if(!empty($err_msg['pic3'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic3" class="input-file">
                <img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic3'))) echo 'display:none;' ?>">
                  ドラッグ＆ドロップ
              </label>
            </div>

            <div class="imgDrop-container">
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic4'])) echo $err_msg['pic4'];
                ?>
              </div>
              <label class="area-drop <?php if(!empty($err_msg['pic4'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic4" class="input-file">
                <img src="<?php echo getFormData('pic4'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic4'))) echo 'display:none;' ?>">
                  ドラッグ＆ドロップ
              </label>
            </div>

            <div class="imgDrop-container">
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic5'])) echo $err_msg['pic5'];
                ?>
              </div>
              <label class="area-drop <?php if(!empty($err_msg['pic5'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic5" class="input-file">
                <img src="<?php echo getFormData('pic5'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic5'))) echo 'display:none;' ?>">
                  ドラッグ＆ドロップ
              </label>
            </div>

            <div class="imgDrop-container">
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic6'])) echo $err_msg['pic6'];
                ?>
              </div>
              <label class="area-drop <?php if(!empty($err_msg['pic6'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic6" class="input-file">
                <img src="<?php echo getFormData('pic6'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic6'))) echo 'display:none;' ?>">
                  ドラッグ＆ドロップ
              </label>
            </div>
          </div>
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