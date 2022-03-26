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

$category = $_GET['category'];
$category_blog = BlogLogic::getCategoryBlog($category);

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


      <div class="boad serch">
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
            <input type="serch" name='title'>
            <input type='submit' value='検索' class="btn_serch">
          </form>

          <div class="category">

            <a href="serch_category.php?category=1" class="a">
              <h2 class="Make glass
              <?php if ($category==1){
                echo 'Make_bg';
              }?>
              ">Make</h2>
            </a>

            <a href="serch_category.php?category=2" class="a">
              <h2 class="Skin glass
              <?php if ($category==2){
                echo 'Skin_bg';
              }?>
              ">Skin</h2>
            </a>

            <a href="serch_category.php?category=3" class="a">
              <h2 class="Hair glass
              <?php if ($category==3){
                echo 'Hair_bg';
              }?>
              ">Hair</h2>
            </a>

            <a href="serch_category.php?category=4" class="a">
              <h2 class="Other glass
              <?php if ($category==4){
                echo 'Other_bg';
              }?>
              ">Other</h2>
            </a>

          </div>

          <div id="<?php echo h(BlogLogic::setCategoryName($category)) ?>">
            <div class= "h">
              <h2 class="<?php echo h(BlogLogic::setCategoryName($category)) ?>_bg"><?php echo h(BlogLogic::setCategoryName($category)) ?></h2>
            </div>
            
            <div class="cards">
              <?php foreach($category_blog as $blog): ?>
                <a href="detail_serch_category.php?id=<?php echo $blog['id'] ?>" class="blog_detail">
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

  <script> </script>