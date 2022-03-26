<?php

require_once '../dbconnect.php';

Class BlogLogic
{

  // カテゴリ名を表示
  
  public function setCategoryName($category){
    if ($category === '1'){
      return 'Make';
    } elseif ($category === '2') {
      return 'Skin';
    } elseif ($category === '3') {
      return 'Hair';
    } else {
      return 'Other';
    }
  }


  //ブログデータを保存
  function fileSave($filename, $save_path, $title, $content, $category, $login_user)
  {
    $result = False;

    $sql = "INSERT INTO posts (file_name, file_path, title, content, category, member_id) VALUE (?, ?, ?, ?, ?, ?)";

    try{
      $stmt = connect()->prepare($sql);
      $stmt->bindValue(1, $filename);
      $stmt->bindValue(2, $save_path);
      $stmt->bindValue(3, $title);
      $stmt->bindValue(4, $content);
      $stmt->bindValue(5, $category);
      $stmt->bindValue(6, $login_user);
      $result = $stmt->execute();
      return $result;
    }catch(\Exception $e){
      echo $e->getMessage();
      return $result;
    }
  }

  //ブログの更新
  function fileUpdate()
  {
    $blogs = $_POST;
    $id = $_SESSION['blog_detail']['id'];
    $result = False;

    $sql = "UPDATE posts SET title = :title, content = :content, category = :category WHERE id = :id";

    try{
      $stmt = connect()->prepare($sql);
      $stmt->bindValue(':title', $blogs['title'],PDO::PARAM_STR);
      $stmt->bindValue(':content', $blogs['content'],PDO::PARAM_STR);
      $stmt->bindValue(':category', $blogs['category'],PDO::PARAM_INT);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $result = $stmt->execute();
      return $result;
    }catch(\Exception $e){
      echo $e->getMessage();
      return $result;
    }
  }
  
  //ブログを削除
  function fileDelete($id){

    if(empty($id)){
      exit('IDが不正です。');
    }
    

    //SQLの準備
    $stmt = connect()->prepare("DELETE FROM posts Where id = :id");
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    
    //SQL実行
    $result = $stmt->execute();
    $msg = '削除完了';
    $_SESSION['suc']=$msg;

    return $result;

  }


  //ブログデータを取得
  function getAllBlog()
  {
    $sql = "SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY id DESC";

    $BlogData = connect()->query($sql);

    return $BlogData;
  }

  //ユーザを取得
  function getAllUser()
  {
    $sql = "SELECT * FROM members ORDER BY id DESC";

    $UserData = connect()->query($sql);

    return $UserData;
  }

  function getById($id){

    //IDが空の場合
    if(empty($id)){
      exit('IDが不正です。');
    }
    
    $stmt = connect()->prepare("SELECT * FROM members JOIN posts ON  members.id = posts.member_id Where posts.id = :id");
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$result){
      exit('ブログがありません。');
    }
    
    return $result;
    
  }

  //タイトル検索
  function getSerchBlog($serch_title){
    $sql = "SELECT * FROM members JOIN posts ON  members.id = posts.member_id Where title like :title ORDER BY posts.id DESC";
    
    $stmt = connect()->prepare($sql);
    $stmt->bindValue(":title", "%".$serch_title."%", PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

  //カテゴリ別表示
  function getCategoryBlog($category){
    $sql = "SELECT * FROM members JOIN posts ON  members.id = posts.member_id Where category = :category ORDER BY posts.id DESC";
    
    $stmt = connect()->prepare($sql);
    $stmt->bindValue(":category", $category, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

  //カテゴリ別特定個数表示
  function getCategoryMain($category){
    // $sql = "SELECT id FROM posts WHERE title LIKE :title";
    $sql = "SELECT * FROM members JOIN posts ON  members.id = posts.member_id Where category = :category ORDER BY RAND() limit 3";
    
    $stmt = connect()->prepare($sql);
    $stmt->bindValue(":category", $category, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }


  //マイページブログデータを取得
  function getMyBlog($login_user_id)
  {
    $sql = "SELECT * FROM members JOIN posts ON  members.id = posts.member_id Where posts.member_id = $login_user_id ORDER BY posts.id DESC";

    $BlogData = connect()->query($sql);

    return $BlogData;
  }

  //ログインユーザーの投稿ブログ数を取得
  function cntBlog($login_user_id)
  {
    
    $sql = "SELECT COUNT(*) FROM posts WHERE member_id = $login_user_id";
    $res = connect()->query($sql);
    $count = $res->fetchColumn();

    return $count;
  }

  //ログインユーザーのカテゴリ別投稿ブログ数を取得
  function cntCategoryBlog($login_user_id, $category)
  {
    
    $sql = "SELECT COUNT(*) FROM posts WHERE member_id = $login_user_id AND category = $category";
    $res = connect()->query($sql);
    $count = $res->fetchColumn();

    return $count;
  }



}//Blog
