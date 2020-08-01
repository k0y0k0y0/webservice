<section id="sidebar">
  <div class="prof-img">
    <img src="./img/profile/noimage.jpg">
  </div>
  <h3><?php echo $userName;?></h3>
  <nav id="sidebar-nav">
    <ul>
      <li>
        <?php
        if(basename($_SERVER['PHP_SELF']) === 'post.php'){
        ?>
          POST
        <?php
        }else{
        ?>
          <a href="./post.php">POST</a>
        <?php
        }
        ?>
      </li>
      <li>
        <?php
        if(basename($_SERVER['PHP_SELF']) === 'profEdit.php'){
        ?>
          PROFILE EDIT
        <?php
        }else{
        ?>
          <a href="./profEdit.php">PROFILE EDIT</a>
        <?php
        }
        ?>
      </li>
      <li>
        <?php
        if(basename($_SERVER['PHP_SELF']) === 'passEdit.php'){
        ?>
          PASSWORD EDIT
        <?php
        }else{
        ?>
          <a href="./passEdit.php">PASSWORD EDIT</a>
        <?php
        }
        ?>
      </li>
      <li>
        <?php
        if(basename($_SERVER['PHP_SELF']) === 'post.php'){
        ?>
          DIRECT MESSAGE
        <?php
        }else{
        ?>
          <a href="./post.php">DIRECT MESSAGE</a>
        <?php
        }
        ?>
      </li>
      <li>
        <?php
        if(basename($_SERVER['PHP_SELF']) === 'withdrow.php'){
        ?>
          WITHDROW
        <?php
        }else{
        ?>
          <a href="./withdrow.php">WITHDROW</a>
        <?php
        }
        ?>
      </li>
    </ul>
  </nav>
</section>