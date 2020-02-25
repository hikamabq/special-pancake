<?php
$msg='';
require_once "../sys/cent.php";
$link=conn();
session_start();

if(!isset($_SESSION['user']['sesi'])){
	$_SESSION['login']['error'] = 'Please Login First';
	header("location:../index.php");
}else{
	$sta = $_SESSION['user']['status'];
	if($sta != 'Guru'){
		$_SESSION['login']['error'] = 'Please Login First';
		header("location:../index.php");
	}
}

if (!isset($_SESSION["gbr"])) {
	$_SESSION["gbr"]=rand(1,count($_SESSION['rdm']));
}
if($title==='Soal' OR $title==='Tulis Soal'){
	$un = $_SESSION['user']['un'];
	$hasil = ambil('user', "WHERE username='$un'");
	while($row=mysqli_fetch_assoc($hasil)){
		$iduser = $row['id'];
	}
	$hasil = ambil('guru', "WHERE id_user='$iduser'");
		while($row=mysqli_fetch_assoc($hasil)){
		$idguru = $row['id'];
	}
	$i = 0;
	$a = 0;
	$b = 0;
	$c = 0;
	$hasil = ambil('detail', "WHERE id_guru='$idguru' ORDER BY id_mapel ASC");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
		$iddetail = $row['id'];
		$idkelas = $row['id_kelas'];
		$idmapel = $row['id_mapel'];
		$result = ambil('kelas', "WHERE id='$idkelas'");
		while($br=mysqli_fetch_assoc($result)){
			if($br['tingkat']==='X'){
				$x = 1;
				$hsl = ambil('mapel', "WHERE id='$idmapel'");
				while($rsl=mysqli_fetch_assoc($hsl)){
					$a++;
					$dt[$x][$a] = $rsl['nama'];
				}
			}elseif($br['tingkat']==='XI'){
				$x = 2;
				$hsl = ambil('mapel', "WHERE id='$idmapel'");
				while($rsl=mysqli_fetch_assoc($hsl)){
					$b++;
					$dt[$x][$b] = $rsl['nama'];
				}
			}elseif($br['tingkat']==='XII'){
				$x = 3;
				$hsl = ambil('mapel', "WHERE id='$idmapel'");
				while($rsl=mysqli_fetch_assoc($hsl)){
					$c++;
					$dt[$x][$c] = $rsl['nama'];
				}
			}
		}
	}
}
$idu = $_SESSION['user']['id'];
$hasil = ambil('guru', "WHERE id_user='$idu'");
while($row=mysqli_fetch_assoc($hasil)){
	$mynip = $row['NIP'];
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
	<?php if($title==='REMOVE!!'){ ?>
	<script src="../tinymce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinymce.init({
			selector:"textarea",
		});
	</script>
	<?php } ?>
</head>
<body>
	
<div class="navuser">
	<div class="k2">
		
		<?php
			$badge = 'kuning';
		?>
		<div class="icu kiri <?=$badge?>">
			<h1><i class="fa fa-user"></i></h1>
		</div>
		<?=$_SESSION['user']['name'];?> <br>
		<?=$mynip?> <br>
		<?php if($title==='Home'){ ?> <a href="ref.php" style="color:white;"><?=$_SESSION['user']['status'];?></a> <?php }else{ ?> <?=$_SESSION['user']['status'];?> <?php } ?>
	</div>
	<div class="k2">
		<div class="menuus">
			<a href="index.php"><i class="fa fa-home"></i> Home</a>
			<a href="kelas.php">&#9776; Kelas</a>
			<a href="soal.php"><i class="fa fa-pencil"></i> Soal</a>
			<a href="forum.php"><i class="fa fa-group"></i> Forum</a>
			<a href="../logout.php"><i class="fa fa-key"></i> Logout</a>
		</div>
	</div>
</div>