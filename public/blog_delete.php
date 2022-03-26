<?php
session_start();

require_once ('../dbconnect.php');
require_once '../classes/BlogLogic.php';

$login_user = $_SESSION['login_user']['id'];


$id = $_SESSION['blog_detail']['id'];


BlogLogic::fileDelete($id);
header ('Location: mypage.php');

?>
