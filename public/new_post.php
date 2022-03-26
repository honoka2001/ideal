<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../functions.php';

// ログインしているか判定し、していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if (!$result) {
  $_SESSION['login_err'] = 'ユーザを登録してログインしてください！';
  header('Location: signup_form.php');
  return;
}

$login_user = $_SESSION['login_user'];

if(isset($_SESSION['err_msgs'])) { 
  $err_msgs = $_SESSION['err_msgs'];
}
else {
  $err_msgs = NULL;
}
$_SESSION['err_msgs'] = array();

//値の保持
if(isset($_SESSION['keep'])) { 
  $keep = $_SESSION['keep'];
}
else {
  $keep = NULL;
}
$_SESSION['keep'] = array();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/reset.css">
  <link rel="stylesheet" type="text/css" href="css/common.css?v=2">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/73ee5ba027.js" crossorigin="anonymous"></script>

  <title>新規投稿</title>
</head>

<body>

  <div class="container">
    <div class="inner">


      <div class="boad new_post">
        <!-- ヘッダー -->
        <header>
          <img src="images/logo_small.png" alt="ideal" class="logo">
        </header>

        
        
        <div class="left glass">
          
          
          <!-- ログインユーザー情報 -->
          <div class="person">
           <img src="member_picture/<?php echo h($login_user['picture']) ?>" alt="アイコン" class="my_icon">
            <p class="my_name">
              <?php echo h($login_user['name']) ?>
            </p>
          </div>
          
          <!-- ナビゲーション -->
          <nav>
            <a class="nav-link" href="home.php">
              <i class="fas fa-home"></i>
              HOME
            </a>
            
            <a class="nav-link" href="serch.php">
              <i class="fas fa-search"></i>
              SERCH
            </a>
            
            <a class="nav-link active glass" href="./new_post.php">
            <i class="fas fa-feather"></i>
            WRITE
          </a>
          
          <a class="nav-link" href="mypage.php">
              <i class="fas fa-user-circle"></i>
              MYPAGE
            </a>
        </nav>
        
      </div>

      <div class="cards">

        <form action="./blog_create.php" enctype="multipart/form-data" method="POST">
          <h2 class= "article glass">新規投稿</h2>

          <div class="msg">
            <?php
          if($err_msgs != NULL) {
            foreach($err_msgs as $err_msg){
              echo $err_msg;
              echo '<br>';
            }
          }
          ?>
          </div>

          <h3><i class="far fa-image"></i>Photo</h3>
          <div class="img">
            <div class="file-up">
              <!-- <p>写真を選択</p> -->
              <i class="fas fa-camera"></i>
              <!-- <i class="fas fa-plus"></i> -->
              <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
              <input name="photo" id="photo" type="file" accept="image/*" />
            </div>
            <div class="preview">
              <img id="preview">
            </div>
          </div>

            <script>
          document.getElementById('photo').addEventListener('change', function (e) {
          // 1枚だけ表示する
          var file = e.target.files[0];

          // ファイルのブラウザ上でのURLを取得する
          var blobUrl = window.URL.createObjectURL(file);

          // img要素に表示
          var img = document.getElementById('preview');
          img.src = blobUrl;
          });
          </script>

          <h3><i class="far fa-check-circle"></i>Category</h3>
            <div class="category">

            <input type="radio" name="category" value="1" id="1" 
            <?php 
             if(isset($keep['category'])){
              if($keep['category'] == 1) echo "checked";
            }
             ?>>
              <label for="1"><h2 class="Make_bg glass">Make</h2></label>

            <input type="radio" name="category" value="2" id="2" 
            <?php 
             if(isset($keep['category'])){
              if($keep['category'] == 2) echo "checked";
            }
             ?>>
              <label for="2"><h2 class="Skin_bg glass">Skin</h2></label>

            <input type="radio" name="category" value="3" id="3" 
            <?php 
             if(isset($keep['category'])){
              if($keep['category'] == 3) echo "checked";
            }
             ?>>
              <label for="3"><h2 class="Hair_bg glass">Hair</h2></label>

            <input type="radio" name="category" value="4" id="4"  checked="checked"
            <?php 
             if(isset($keep['category'])){
              if($keep['category'] == 4) echo "checked";
            }
             ?>>
              <label for="4"><h2 class="Other_bg glass">Other</h2></label>
          </div>

          <h3><i class="fas fa-heading"></i>Title</h3>
          <input autocomplete="off" type="text" name="title" placeholder="タイトルは30文字以内で入力してください。" value="<?php 
              if(isset($keep['title'])){
                echo h($keep['title']);
              }?>">
          <h3><i class="fas fa-paragraph"></i>Content</h3>
          <textarea name="content" id="content" cols="30" rows="10" placeholder="本文を入力してください。"><?php 
              if(isset($keep['content'])){
                echo str_replace("&amp;#13;&amp;#10;","&NewLine;",h($keep['content']));
              }?></textarea>
          <br>

          <label>
            <div class="btn_post">
              <i class="fab fa-telegram-plane"></i>
              <input type="submit" value="投稿">
            </div>
          </label>
          
        </form>
      </div>
        
      </div>
      
    </div>
  </div>
</div>

<script> </script>
</body>