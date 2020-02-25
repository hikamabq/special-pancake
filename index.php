<?php
$msg='';
$err='';
$log='';
require_once "sys/cent.php";
$link=conn();
session_start();

if(isset($_POST['Login'])){
	$data[1] = $_POST['Username'];
	$data[2] = $_POST['Password'];
	if(login($data)){
		$rows = sta($data);
		while($row=mysqli_fetch_assoc($rows)){
			$id	= $row['id'];
			$name = $row['nama'];
			$sta = $row['status'];
		}
		$_SESSION['user']['un'] = $data[1];
		$_SESSION['user']['name'] = $name;
		$_SESSION['user']['id'] = $id;
		$_SESSION['user']['pass'] = $data[2];
		$_SESSION['user']['status'] = $sta;
		$_SESSION['user']['sesi'] = '1';
		unset($_SESSION['login']['error']);
		$msg = 'Welcome '.$name.'!';
		if($sta==='Siswa'){
			header("refresh:0; url=siswa/index.php");
		}elseif($sta==='Guru'){
			header("refresh:0; url=guru/index.php");
		}elseif($sta==='Admin'){
			header("refresh:0; url=admin/index.php");
		}
		alert('Selamat Datang!', "");
	}else{
		alert('Username dan/atau Password salah!', "");
	}
}

if(isset($_GET['is']) AND !empty($_GET['is'])){
	$is = $_GET['is'];
	if($is==='Guru' OR $is==='Siswa'){
		$is = $is;
	}else{
		header("refresh:0; url=index.php");
	}
}else{
	$is = 'Siswa';
	$to = 'Guru';
	header("refresh:0; url=index.php?is=Siswa");
}

if($is==='Siswa'){
	$to = 'Guru';
}elseif($is==='Guru'){
	$to = 'Siswa';
}


//IPIN
if(isset($_POST['daftar'])){
	$KA = $_POST['KA'];
	$nama = $_POST['nama'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$almt = $_POST['almt'];

	$activation = mysqli_query($link,"SELECT * FROM token WHERE token = '$KA'");
	if(mysqli_num_rows($activation) != 0){
		while($activ = mysqli_fetch_assoc($activation)){
			$status = $activ['status'];
			$jumlah = $activ['jumlah'];
		}

		if($status == $is){

			$cek = mysqli_query($link,"SELECT * FROM user WHERE username = '$user'");
			if(mysqli_num_rows($cek) != 0){
				$err = 'Username sudah digunakan';
			}else{
				if(strlen($pass) < 8){
					$err = 'Password minimal 8 karakter';
				}else{
					mysqli_query($link,"INSERT INTO user(nama,username,password,status) VALUES('$nama','$user','$pass','$is')");

					$procc = mysqli_query($link,"SELECT * FROM user WHERE nama = '$nama' ");
					while ($nomor = mysqli_fetch_assoc( $procc )) {
					$id_user = $nomor['id'];
					}

					if($is == 'Siswa'){
					$nis = $_POST['nis'];
					$kelas = $_POST['kelas'];
					$forum_k = mysqli_query($link,"SELECT * FROM kelas WHERE id = $kelas");

					while($keke = mysqli_fetch_assoc($forum_k)){
						$f_kelas = $keke['tingkat'].' '.$keke['jurusan'].' '.$keke['kelas'];
					}
					$cek_fk = mysqli_query($link,"SELECT * FROM forum WHERE nama = '$f_kelas'");
					if(mysqli_num_rows($cek_fk) != 0){
						while($tf = mysqli_fetch_assoc($cek_fk)){
							$final = $tf['id'];
							mysqli_query($link,"INSERT INTO forum_akses(id_forum,id_user) VALUES($final,'$id_user')");
						}
					}
						
					if(mysqli_query($link,"INSERT INTO siswa(id_user,NIS,id_kelas,alamat,sesi, keterangan) VALUES('$id_user','$nis','$kelas','$almt','Aktif', '')") AND
					mysqli_query($link,"INSERT INTO forum_akses(id_forum,id_user) VALUES(2,'$id_user')")){
						alert('Selamat Bergabung!', "");
					}

					}else{
						$nip = $_POST['nip'];
						$KG  = $_POST['KG'];
						$telp = $_POST['telp'];
						
						if(mysqli_query($link,"INSERT INTO guru(kode,id_user,NIP,alamat,telp, keterangan) VALUES('$KG','$id_user','$nip','$almt','$telp', '')") AND
						mysqli_query($link,"INSERT INTO forum_akses(id_forum,id_user) VALUES(1,$id_user)")){
							alert('Selamat Bergabung!', "");
						}
					}

					$jumlah -= 1;
					mysqli_query($link, "UPDATE token set jumlah = $jumlah");

				}
			}

		}else{$err = 'Kode aktivasi bukan untuk '.$is.'';}

	}else{$err = 'Kode Aktivasi yang anda masukkan salah!';}
}
//END IPIN

if(isset($_SESSION['user']['sesi'])){
	$sta = $_SESSION['user']['status'];
	if($sta==='Siswa'){
		header("refresh:0; url=siswa/index.php");
	}elseif($sta==='Guru'){
		header("refresh:0; url=guru/index.php");
	}elseif($sta==='Admin'){
			header("refresh:0; url=admin/index.php");
	}
}



$dir = "assets/assets";
$d = 0;
$i = 0;
$gbr = NULL;
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
		$d++;
		if($d > 2){
			$i++;
		$gbr[$i][1]= "url('assets/assets/".$file."')";
		$_SESSION['rdm'][$i]= "url('../assets/assets/".$file."')";
		}
    }
    closedir($dh);
  }
}else{
	echo 'gagal';
}
if (!isset($_SESSION["gbr"])) {
	$_SESSION["gbr"]=rand(1,$i);
	$rdm=$_SESSION["gbr"];
}else{
	$rdm=$_SESSION["gbr"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SEKO-NOL | Wekcome</title>
	<link rel="icon" type="img/png" href="../assets/sekokop.png">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="../fonts/font-awesome.css">
	<style>
		body{
			background-image:<?=$gbr[$rdm][1]?>;
			background-attachment:fixed;
			background-position:center;
			background-repeat:no-repeat;
			background-size:cover;
		}
	</style>
</head>
<body>

<div class="login">
	<div class="k2">
	 <a href="index.php" class="logo" style="color:black;">SEKO-NOL | SEKOLAH dari NOL</a>
	</div>
	<div class="k2">
		<form action="index.php" method="post" class="kanan">
			<input type="text" name="Username" placeholder="Username" autofocus required>
			<input type="password" name="Password" placeholder="Password" required>
			<!-- <select name="">
				<option value="">Siswa</option>
				<option value="">Guru</option>
			</select> -->
			<input type="submit" name="Login" value="Login" class="biru">
		</form>
	</div>
</div>

<div class="wrapper">
	<div class="k2">
		<div class="welcome">
			<h1>Selamat datang di Seko-Nol</h1>
			<p>Seko-Nol merupakan website pembelajaran online yang menyediakan materi dan juga soal-soal yang diberikan di Sekolah. Di website ini kita akan sama - sama belajar tentang berbagai macam bidang keahlian. Jika anda sebagai <?=$to?>, anda bisa daftarkan diri di link berikut.</p>
			<br>
			<p><a href="logout.php?is=<?=$to?>" class="tmbl hijau">Daftar <?=$to?></a></p>
		</div>
	</div>
	<div class="k2">
		<?php if($is==='Siswa'){ ?>
		<div class="daftarsiswa kanan">
			<h2>PENDAFTARAN SISWA</h2>
			<?php if($err != ''){ echo '<i class="error">'.$err.'</i>'; } ?>
			<form method="post">
				<label for="">Kode Aktivasi & Nomor Induk Siswa</label>
				<input type="number" name="nis" placeholder="NIS" class="f50" min="10000000" max="99999999" tabindex="1" required>
				<input type="text" name="KA" placeholder="Kode Aktivasi" class="k2" tabindex="2" required>
				<label for="">Nama Lengkap</label>
				<input type="text" name="nama" placeholder="Nama Siswa" class="full" tabindex="3" required>
				<label for="">Username & Password</label>
				<input type="text" name="user" placeholder="Username" class="k2" tabindex="4" required>
				<input type="password" name="pass" placeholder="Password" class="f50" tabindex="5" required>
				<label for="">Kelas & Jurusan</label>
				<select name="kelas" class="full" tabindex="6" required>
					<?php
						$i = 0;
						$o = 0;
						$u = 0;
						$hasil = ambil('kelas','ORDER BY jurusan, tingkat, kelas ASC');
						while($row=mysqli_fetch_assoc($hasil)){
							$i++;
							$kl = $row['tingkat'].' '.$row['jurusan'].' '.$row['kelas'];
							$id = $row['id'];
							if($o===1 AND $jr!=$row['jurusan']){
								$o = 0;
							}
							if($o===0){
								$o++;
								$u++;
								$jr = $row['jurusan'];
								$st = '<optgroup label="'.$jr.'">';
								$nd = '</optgroup>';
								if($u>1){ echo $nd; }
								echo $st;
							}else{
								$st = '';
								$nd = '';
							}
					?>
					<option value="<?=$row['id']?>"><?=$kl?></option>
					<?php }if($u>0){ echo '</optgroup>'; } ?>
				</select>
				<label for="">Alamat</label>
				<input type="text" name="almt" placeholder="Alamat" class="full" tabindex="6" required>
				<input type="hidden" name="Status" value="Siswa">
				<label for=""></label>
				<input type="submit" name="daftar" value="Daftar" class="k2 biru" tabindex="7">
				<input type="reset" value="Reset" class="f50 merah" tabindex="8">
				<label for=""></label>
			</form>
			<h4><a href="logout.php?is=<?=$is?>" style="color:#444;">Dibuat dengan <div style="display:inline-block; color:#eb3838;"><i class="fa fa-heart"></i></div> oleh Fantastic 4</a></h4>
		</div>
		<?php }elseif($is==='Guru'){ ?>
		<div class="daftarsiswa kanan">
			<h2>PENDAFTARAN GURU</h2>
			<?php if($err != ''){ echo '<i class="error">'.$err.'</i>'; } ?>
			<form method="post">
				<label for="">Kode Aktivasi & Kode Guru</label>
				<input type="text" name="KA" placeholder="Kode Aktivasi" class="k2" tabindex="1" required>
				<input type="text" name="KG" placeholder="Kode Guru" class="f50" tabindex="2" required>
				<label for="">Nomor Induk Pegawai & No. Telp</label>
				<input type="text" name="nip" placeholder="NIP" class="k2" tabindex="3" required>
				<input type="tel" name="telp" placeholder="Telp" class="f50" tabindex="4" required>
				<label for="">Nama Lengkap</label>
				<input type="text" name="nama" placeholder="Nama Guru" class="full" tabindex="5" required>
				<label for="">Username & Password</label>
				<input type="text" name="user" placeholder="Username" class="k2" tabindex="6" required>
				<input type="password" name="pass" placeholder="Password" class="f50" tabindex="7" required>
				<label for="">Alamat</label>
				<input type="text" name="almt" placeholder="Alamat" class="full" tabindex="8" required>
				<input type="hidden" name="Status" value="Guru">
				<label for=""></label>
				<input type="submit" name="daftar" value="Daftar" class="k2 biru" tabindex="9">
				<input type="reset" value="Reset" class="f50 merah" tabindex="10">
				<label for=""></label>
			</form>
			<h4><a href="logout.php?is=<?=$is?>" style="color:#444;">Dibuat dengan
				<div style="display:inline-block; color:#eb3838;"> <i class="fa fa-heart"></i></div>
				oleh Fantastic 4</a></h4>
		</div>
		<?php } ?>
	</div>

</div>

</body>
</html>
