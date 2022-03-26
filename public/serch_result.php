<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../classes/BlogLogic.php';
require_once '../functions.php';

// ログインしているか判定し、していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if (!$result) {
  $_SESSION['login_err'] = 'ユーザを登録してログインしてください！';
  header('Location: signup_form.php');
  return;
}

$login_user = $_SESSION['login_user'];

if((! isset($_POST['title'])) && (! isset($_GET['serch_title']))) {
  header('Location: serch.php');
}

if(isset($_GET['serch_title'])) {
  $serch_title = $_GET['serch_title'];
}
if(isset($_POST['title'])) {
  $serch_title = $_POST['title'];
}

$serches = BlogLogic::getSerchBlog($serch_title);

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

  <title>検索</title>
</head>

<body>

  <div class="container">
    <div class="inner">


      <div class="boad serch serch_result">
        <!-- ヘッダー -->
        <header>
          <img src="images/logo_small.png" alt="ideal" class="logo">
        </header>

        <!-- ログアウト -->
        <form action="logout.php" method="POST">
          <div class="input_box">
            <input type="submit" name="logout" value="ログアウト" onclick="return confirm('ログアウトしますか？')">
          </div>
        </form>


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
                        
            <a class="nav-link active glass" href="serch.php">
              <i class="fas fa-search"></i>
              SERCH
            </a>

            <a class="nav-link" href="new_post.php">
              <i class="fas fa-feather"></i>
              WRITE
            </a>
            
            <a class="nav-link" href="mypage.php">
              <i class="fas fa-user-circle"></i>
              MYPAGE
            </a>

          </nav>
          
        </div>
        
        <div class="right">
          <div class="return">
            <a href="serch.php">
              <i class="fas fa-arrow-left"></i>
            </a>
          </div>
                    
          <!-- 検索ボックス -->
          <form action="serch_result.php" method="post" class="serch_box">
            <i class="fas fa-search"></i>
            <input type="serch" name='title' value="<?php echo $serch_title ?>">
            <input type='submit' value='検索' class="btn_serch">
          </form>

          <div id="result">
            <div class= "h">
              <!-- <h2 class="result_bg">Result</h2> -->
              <p class="result">”<span> <?php echo $serch_title ?> </span>” の検索結果</p>
            </div>
            
            <div class="cards">
              <?php if(!$serches): ?>
                <p class="noneresult">検索したタイトルの記事が見つかりませんでした。</p>
              <?php endif; ?>
              
              <?php foreach($serches as $blog): ?>
                <a href="detail_serch_title.php?id=<?php echo $blog['id']."&serch=".$serch_title ?>" class="blog_detail">
                <div class="card">
                  <img src="<?php echo h("{$blog['file_path']}"); ?>" alt="サムネ">
                  <div class="tags">
                    <div class="tag category<?php echo h("{$blog['category']}") ?>"><?php echo h(BlogLogic::setCategoryName("{$blog['category']}")) ?></div>
                  </div>
                  <h3><?php echo h("{$blog['title']}") ?></h3>
                  <div class="posted_person">
                    <img src="member_picture/<?php echo h("{$blog['picture']}") ?>" alt="プロフィール画像">
                    <p><?php echo h("{$blog['name']}") ?></p>
                  </div>
                  <p class="date"><?php echo h("{$blog['modified']}") ?></p>
                </div>
                </a>
              <?php endforeach; ?>
            
            </div>
          
          </div>

        </div>

      </div>

    </div>
   </div>
  </div>

  <script> </script>
</body>