<?php
//================================
// ログイン認証・自動ログアウト
//================================
//ログインしている場合
if(!empty($_SESSION['login_date'])){
  debug('ログイン済みユーザーです。');

  //ログイン有効期限を過ぎていた場合
  if($_SESSION['login_date'] + $_SESSION['login_limit'] < time()){
    debug('ログイン有効期限オーバーです。');

    session_destroy();
    header('Location:login.php');
  }else{
    debug('ログイン有効期限内です。');
    $_SESSION['login_date'] = time();

    //現在ページがlogin.phpの場合はmypage.phpへ遷移
    if(basename($_SESSION['PHP_SELF']) === 'login.php'){
      debug('マイページへ遷移します。');
      header('Location:mypage.php');
    }
  }
}else{
  debug('未ログインユーザーです。');
  //現在ページがlogin.phpでない場合はlogin.phpへ遷移
  if(basename($_SESSION['PHP_SELF']) !== 'login.php') header('Location:login.php');
}
?>