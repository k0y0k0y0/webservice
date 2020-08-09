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

//=====================
// Data Base
//=====================
//DataBaseConnection
function dbConnect(){
  //DBへの接続準備
  $dsn = 'mysql:dbname=phototo;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  // PDOオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}

function queryPost($dbh, $sql, $data){
  //Create Query
  $stmt = $dbh->prepare($sql);
  //Do SQL
  //ダメだったらMSG作成
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました。(＞＜)');
    debug('失敗したsql: '.print_r($stmt, true));
    $err_msg['common'] = MSG07;
    return 0;
  }
  debug('クエリ成功。');
  return $stmt;
}

function getUser($u_id){
  debug('ユーザー情報を取得します。');
  try{
    //DataBase Connection
    $dbh = dbConnect();
    //Create SQL
    $sql = 'SELECT * FROM users WHERE user_id = :u_id AND delete_flg = 0';
    $data = array('u_id' => $u_id);
    //Query
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt) return $stmt->fetch(PDO::FETCH_ASSOC);
    else return false;
  }catch(Exception $e){
    error_log('エラー発生: '.$e->getMessage());
  }
}

//フォーム入力保持
function getFormData($str, $flg = false){
  if($flg)$method = $_GET;
  else $method = $_POST;
  global $dbFromData;
  //ユーザーデータがあるかないか
  if(!empty($dbFromData)){
    //フォームにエラーがあるかないか
    if(!empty($err_msg[$str])){
      //POSTにデータがあるかないか
      if(isset($method[$str])) return sanitize($method[$str]);
      else return sanitize($dbFromData[$str]);
    }else{
      if(isset($method[$str]) && $method[$str] !== $dbFromData[$str]) return sanitize($method[$str]);
      else return sanitize($dbFromData[$str]);
    }
  }else{
    if(isset($method[$str])) return sanitize($method[$str]);
  }
}


//サニタイズ
function sanitize($str){
  return htmlspecialchars($str,ENT_QUOTES);
}


//画像処理
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  debug('FILE情報: '.print_r($file, true));

  // $file['error'] の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている。
  if(isset($file['error']) && is_int($file['error'])){
    //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
    try{
      switch($file['error']){
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイルが選択されていません。');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます。');
        default:
          throw new RuntimeException('その他のエラーが発生しました。');
      }

      // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
      // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
          throw new RuntimeException('画像形式が未対応です');
      }

      // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
      // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
      // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
      // image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
          throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      // 保存したファイルパスのパーミッション（権限）を変更する
      chmod($path, 0644);

      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス：'.$path);
      return $path;

    } catch (RuntimeException $e) {
      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }
  }
}
?>