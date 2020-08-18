<?php
$siteTitle = 'LOGIN';
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

  </div>

  <!-- フッター -->
  <?php
  require('./footer.php');
  ?>