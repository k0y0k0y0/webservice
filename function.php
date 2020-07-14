<?php
//=====================
//　LOG
//=====================
//get errors or not
ini_set('log_errors', 'On');
//Specify log output file
ini_set('error_log', 'log.php');

//=====================
// DEBUG
//=====================
//debug flg
$debug_flg = true;
//debug log function
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)) error_log('debug:'.$str);
}

//=====================
// SESSION
//=====================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

//=====================
// 画面表示処理用ログ吐き出し
//=====================
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('Session ID: '.session_ID());
  debug('Session Variable: '.print_r($_SESSION, true));
  debug('Current Time Stamp: '.time());
  if(!empty($_SESSION['login_date'] && !empty($_SESSION['login_limit'])))
    debug('Login Limit Time Stamp: '.($_SESSION['login_date'] + $_SESSION['login_limit']));
}

?>