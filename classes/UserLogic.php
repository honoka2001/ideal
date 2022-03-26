<?php

require_once '../dbconnect.php';

class UserLogic
{
 
  //ユーザを登録する
  public static function createUser($userData)
  {

      $stmt = connect()->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?');
      echo $ret = $stmt->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT),
        $_SESSION['join']['image']));
   }
    
  //ログイン処理
  public static function login($email, $password)
  {
    $result = false;
    $user = self::getUserByEmail($email);
    
    if (!$user) {
      $_SESSION['msg']['email'] = '登録されているメールアドレスがありません。';
        return $result;
    }

    //パスワードの照会
    if (password_verify($password, $user['password'])){
      //ログイン成功
      session_regenerate_id(true); // セッションハイジャック対策(ログイン成功後にセッションを再発行)

      $_SESSION['login_user'] = $user;
      $result = true;
      return $result;
    }

    $_SESSION['msg']['pass'] = 'パスワードが一致しません。';
    return $result;
  }

  // emailからユーザを取得
  public static function getUserByEmail($email)
  {
    $sql = 'SELECT * FROM members WHERE email = ?';

    $arr = [];
    $arr[] = $email;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $user = $stmt->fetch();
      return $user;
    } catch(\Exception $e) {
      return false;
    }
  }

  //ログインチェック
  public static function checkLogin()
  {
    $result = false;

    //セッションにログインユーザが入っていなかったらfalse
    if (isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0){
      return $result = true;
    }

    return $result;
  }

// ログアウト
  public static function logout()
  {
    $_SESSION = array();
    session_destroy();
  }

  
// ユーザIDからユーザ情報を取得
public static function getById($id)
{
  
  //IDが空の場合
  if(empty($id)){
    exit('IDが不正です。');
  }
  
  $stmt = connect()->prepare("SELECT * FROM members Where id = :id");
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if(!$result){
    exit('ユーザが存在しません。');
  }
  
  return $result;
}


  //プロフィールの更新
  function profUpdate($name, $intro, $picture, $login_user_id)
  {

    $result = False;

    $sql = "UPDATE members SET name = :name, introduction = :introduction, picture = :picture WHERE id = :id";

    try{
      $stmt = connect()->prepare($sql);
      $stmt->bindValue(':name', $name,PDO::PARAM_STR);
      $stmt->bindValue(':introduction', $intro ,PDO::PARAM_STR);
      $stmt->bindValue(':picture', $picture,PDO::PARAM_STR);
      $stmt->bindValue(':id', $login_user_id, PDO::PARAM_INT);
      $result = $stmt->execute();
      return $result;
    }catch(\Exception $e){
      echo $e->getMessage();
      return $result;
    }
  }


 
}//class UserLogic