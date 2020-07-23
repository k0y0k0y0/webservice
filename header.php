<header>
  <div class="site-width">
    <nav id='top-nav-left'>
      <ul>
        <?php
          if(empty($_SESSION['user_id'])){
          ?>
            <li><a href="./signup.php">SIGNUP</a></li>
            <li><a href="./login.php">LOGIN</a></li>
          <?
          }else{
          ?>
            <li><a href="./mypage.php">MYPAGE</a></li>

            <li><a href="./login.php">LOGIN</a></li>
          <?php
          }
          ?>
      </ul>
    </nav>
    <h1><a href="./index.php">PHOTOTO</a></h1>
    <nav id='top-nav-right'>
      <ul>
        <?php
        if(empty($_SESSION['user_id'])){
        ?>
          <li><a href="./signup.php">SIGNUP</a></li>
          <li><a href="./login.php">LOGIN</a></li>
        <?
        }else{
        ?>
          <li><a href="./mypage.php">MYPAGE</a></li>
          <li><a href="./login.php">LOGIN</a></li>
        <?php
        }
        ?>
      </ul>
    </nav>
  </div>
</header>