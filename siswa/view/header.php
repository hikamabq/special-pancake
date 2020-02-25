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
	if($sta != 'Siswa'){
		$_SESSION['login']['error'] = 'Please Login First!';
		header("location:../index.php");
	}
}
if(isset($_SESSION['user']['soal']['paket'])){
	$i = 0;
	$paket = $_SESSION['user']['soal']['paket'];
	$hasil = ambil('paket', "WHERE id='$paket'");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
	}
	if($i===0){
		unset($_SESSION['user']['soal']);
		if(isset($_SESSION['user']['jawab']) AND !empty($_SESSION['user']['jawab'])){
			unset($_SESSION['user']['jawab']);
			alert('Terjadi Kesalahan', 'index.php');
		}
	}else{
		if($title!='Soal'){
			alert('Anda masih mengerjakan soal', "soal.php");
		}
	}
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
	<link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="../fonts/font-awesome.css">
	<style>
		.navuser, .navadmin{
			background-image:<?=$_SESSION['rdm'][$_SESSION["gbr"]];?>;
			background-attachment:fixed;
			background-position:center bottom;
			background-repeat:no-repeat;
			background-size:cover;
		}
	</style>
</head>
<body>
	
<div class="navuser">
	<div class="k2">
		<?php
			$us = $_SESSION['user']['un'];
			$hasil = ambil('user', "WHERE username='$us'");
			while($row=mysqli_fetch_assoc($hasil)){
				$iduser = $row['id'];
			}
			$hasil = ambil('siswa', "WHERE id_user='$iduser'");
			while($row=mysqli_fetch_assoc($hasil)){
				$idkelas = $row['id_kelas'];
				$nis = $row['NIS'];
			}
			$hasil = ambil('kelas', "WHERE id='$idkelas'");
			while($row=mysqli_fetch_assoc($hasil)){
				$tingkat = $row['tingkat'];
				$kelas = $row['kelas'];
				$jurusan = $row['jurusan'];
			}
			if($tingkat==='X'){
				$badge = 'merah';
			}elseif($tingkat==='XI'){
				$badge = 'hijau';
			}elseif($tingkat==='XII'){
				$badge = 'biru';
			}
		?>
		<div class="icu kiri <?=$badge?>">
			<h1><i class="fa fa-user"></i></h1>
		</div>
		<?=$_SESSION['user']['name'];?> <br>
		<?=$nis.' | '.$tingkat.' '.$jurusan.' '.$kelas?> <br>
		<?php if($title==='Home'){ ?> <a href="ref.php" style="color:white;"><?=$_SESSION['user']['status'];?></a> <?php }else{ ?> <?=$_SESSION['user']['status'];?> <?php } ?>
	</div>
	<div class="k2">
		<div class="menuus">
			<a href="index.php"><i class="fa fa-home"></i> Home</a>
			<a href="materi.php"><i class="fa fa-book"></i> Materi</a>
			<a href="soal.php"><i class="fa fa-pencil"></i> Soal</a>
			<a href="forum.php"><i class="fa fa-group"></i> Forum</a>
			<a href="../logout.php"><i class="fa fa-key"></i> Logout</a>
		</div>
	</div>
</div>