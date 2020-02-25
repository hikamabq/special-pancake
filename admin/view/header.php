<?php
$msg='';
require_once "../sys/cent.php";
$link=conn();
session_start();

if(!isset($_SESSION['user']['sesi'])){
	$_SESSION['login']['error'] = 'Please Login First!';
	header("location:../index.php");
}else{
	$sta = $_SESSION['user']['status'];
	if($sta != 'Admin'){
		$_SESSION['login']['error'] = 'Please Login First!';
		header("location:../index.php");
	}
}

if(!isset($_SESSION['admin']['token'])){
	$_SESSION['admin']['token'] = uniqid('');
}else{
	$token = $_SESSION['admin']['token'];
}

if (!isset($_SESSION["gbr"])) {
	$_SESSION["gbr"]=rand(1,count($_SESSION['rdm']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SEKO-NOL | <?=$title;?></title>
	<link rel="icon" type="img/png" href="../assets/sekokop.png">
	<link rel="stylesheet" href="../theme.css">
	<link rel="stylesheet" href="../fonts/font-awesome.css">
</head>
<body>
	
<div class="header-admin">
		 <div class="logodash kiri">
		 	<img src="../assets/sekologo.png" alt="">
		 </div>
		<a href="../logout.php" class="kanan" style="color: #fff;"><i class="fa fa-key"></i> Logout</a>
</div>
<div class="kop">Dashboard / <?= $title; ?></div>