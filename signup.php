<?php

//共通変数・関数ファイルを読込み
require('./function.php');
require('./errorMesages.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　SIGNUP.PHP　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//post送信されていたら以下の処理を行う
if(!empty($_POST)){

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
  <div id="contents">

    <!-- メイン -->
    <section id="main">
      <div class="form-container">
        <form action="" method="post" class="form">
          <label class="<?php if(!empty($err_msg['name'])) echo 'err'; ?>">
            NAME
            <input type="text" name="name">
          </label>
          <div class="form_msg">
            <?php
              if(!empty($err_msg['name'])) echo $err_msg['name'];
            ?>
          </div>
          <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            EMAIL
            <input type="text" name="email">
          </label>
          <div class="form_msg">
            <?php
              if(!empty($err_msg['email'])) echo $err_msg['email'];
            ?>
          </div>
          <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
            PASSWORD <span>※英数字６文字以上</span>
            <input type="password" name="pass">
          </label>
          <div class="form_msg">
            <?php
              if(!empty($err_msg['pass'])) echo $err_msg['pass'];
            ?>
          </div>
          <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
            PASSWORD:RE
            <input type="password" name="pass_re">
          </label>
          <div class="form_msg">
            <?php
              if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
            ?>
          </div>
          <div>
            <input type="submit" value="SIGNUP">
          </div>
        </form>
      </div>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('./footer.php');
  ?>