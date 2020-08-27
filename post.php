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

// 画面表示用データ取得
//================================
//GETデータを格納
$post_id = (!empty($_GET['post_id'])) ? $_GET['post_id']: '';
//DBからpostデータを取得
$dbFromData = (!empty($post_id)) ? getPost($_SESSION['user_id'], $post_id) : '';
//新規登録か編集か？
$edit_flg = (empty($dbFromData))? false: true;
//カテゴリデータの取得
$dbCategoryData = getCategory();
debug('POST_ID'.$post_id);
debug('POSTデータ: '.print_r($dbFromData, true));
debug('カテゴリデータ: '.print_r($dbCategoryData, true));
//GET USER DATA FROM DATABASE
$dbUserData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報: '.print_r($dbUserData, true));


//パラメータ改ざんCheck
//================================
// GETパラメータはあるが、改ざんされている（URLをいじくった）場合、正しい商品データが取れないのでマイページへ遷移させる
if(!empty($post_id) && empty($dbFromData)){
  debug('GETパラメータのPOST_IDが違います。マイページへ遷移します。');
  header("Location:mypage.php"); //マイページへ
}

//post sending
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  //変数にユーザー情報を代入
  $title = $_POST['title'];
  $place = $_POST['place'];
  $category = $_POST['category_id'];
  $comment = $_POST['comment'];
  //画像をアップロードし、パスを格納
  $pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'],'pic1') : '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $pic1 = ( empty($pic1) && !empty($dbFromData['pic1']) ) ? $dbFromData['pic1'] : $pic1;
  $pic2 = ( !empty($_FILES['pic2']['name']) ) ? uploadImg($_FILES['pic2'],'pic2') : '';
  $pic2 = ( empty($pic2) && !empty($dbFromData['pic2']) ) ? $dbFromData['pic2'] : $pic2;
  $pic3 = ( !empty($_FILES['pic3']['name']) ) ? uploadImg($_FILES['pic3'],'pic3') : '';
  $pic3 = ( empty($pic3) && !empty($dbFromData['pic3']) ) ? $dbFromData['pic3'] : $pic3;
  $pic4 = ( !empty($_FILES['pic4']['name']) ) ? uploadImg($_FILES['pic4'],'pic4') : '';
  $pic4 = ( empty($pic4) && !empty($dbFromData['pic4']) ) ? $dbFromData['pic4'] : $pic4;
  $pic5 = ( !empty($_FILES['pic5']['name']) ) ? uploadImg($_FILES['pic5'],'pic5') : '';
  $pic5 = ( empty($pic5) && !empty($dbFromData['pic5']) ) ? $dbFromData['pic5'] : $pic5;
  $pic6 = ( !empty($_FILES['pic6']['name']) ) ? uploadImg($_FILES['pic6'],'pic6') : '';
  $pic6 = ( empty($pic6) && !empty($dbFromData['pic6']) ) ? $dbFromData['pic6'] : $pic6;

  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFromData)){
    validRequired($title, 'title');
    validMaxLen($title, 'title');
    validSelect($category, 'category_id');
    validMaxLen($comment, 'comment', 500);
  }else{
    if($dbFromData['title'] !== $title){
      validRequired($title, 'title');
      validMaxLen($title, 'title');
    }
    if($dbFromData['category_id'] !== $category_id){
      validSelect($category, 'category_id');
    }
    if($dbFromData['comment'] !== $comment){
      validMaxLen($comment, 'comment', 500);
    }
  }

  if(empty($err_msg)){
    debug('Validation OK!');
    try{
      //Database Connection
      $dbh = dbConnect();
      //SQL
      if($edit_flg){
        debug('DB更新です。');
        $sql = 'UPDATE post SET title = :title, place = :place, category_id = :category, comment = :comment, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3, pic4 = :pic4, pic5 = :pic5, pic6 = :pic6 WHERE user_id = :user_id AND post_id = :post_id';
        $data = array(':title' => $title, ':place' =>$place, ':category' => $category, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':pic4' => $pic4, ':pic5' => $pic5, ':pic6' => $pic6, ':user_id' => $_SESSION['user_id'], ':post_id' => $post_id);
      }else{
        debug('DB新規登録です。');
        $sql = 'insert into (post title = :title, place = :place, category_id = :category, comment = :comment, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3, pic4 = :pic4, pic5 = :pic5, pic6 = :pic6 WHERE user_id = :user_id AND post_id)';
        $data = array(':title' => $title, ':place' =>$place, ':category' => $category, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':pic4' => $pic4, ':pic5' => $pic5, ':pic6' => $pic6, ':user_id' => $_SESSION['user_id'], ':post_id' => $post_id);
      }
      debug('SPQ: '.$sql);
      debug('流し込みデータ: '.print_r($data, true));
      //Query
      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('マイページへ遷移します。');
        header("Location:mypage.php"); //マイページへ
      }

    }catch(Exception $e){
      error_log('エラー発生: '.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
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
    $userName = $dbUserData['user_name'];
    require('./sideBar.php');
    ?>

    <!-- メイン -->
    <section id="main" class="with-sidebar">
      <div class="form-container">
        <form action="" method="post" class="form">
          <!-- TITLE -->
          <label class="<?php if(!empty($err_msg['title'])) echo 'err'; ?>">
            TITLE
            <span class="form_msg">
              <?php
                if(!empty($err_msg['title'])) echo $err_msg['title'];
              ?>
            </span>
            <input type="text" name="title" value="<?php echo getFormData('title'); ?>">
          </label>
          <!-- PLACE -->
          <label class="<?php if(!empty($err_msg['place'])) echo 'err'; ?>">
            PLACE
            <span class="form_msg">
              <?php
                if(!empty($err_msg['place'])) echo $err_msg['place'];
              ?>
            </span>
            <input type="text" name="place" value="<?php echo getFormData('place'); ?>">
          </label>
          <!-- CATEGORY -->
          <label class="<?php if(!empty($err_msg['category'])) echo 'err'; ?>">
            CATEGORY
            <span class="form_msg">
              <?php
                if(!empty($err_msg['category'])) echo $err_msg['category'];
              ?>
            </span>
            <select name="category" id="">
              <option value="0" <?php if(getFormData('category_id') == 0) echo 'selected'; ?>>
                選択してください
              </option>
              <?php
                foreach($dbCategoryData as $key => $val){
              ?>
                  <option value="<?php echo $val['id'] ?>" <?php if(getFormData('category_id') == $val['id']) echo 'selected'; ?>>
                    <?php echo $val['name']; ?>
                  </option>
              <?php
                }
              ?>
            </select>
          </label>
          <!-- COMMENT -->
          <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
            COMMENT
            <span class="form_msg">
              <?php
                if(!empty($err_msg['comment'])) echo $err_msg['comment'];
              ?>
            </span>
            <textarea name="comment"><?php echo getFormData('comment'); ?></textarea>
          </label>
          <!-- POST -->
          <div class="post-img" style="overflow:hidden;">
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