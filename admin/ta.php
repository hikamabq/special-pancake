<?php
$title='Tahun Ajaran';
require_once 'view/header.php';


if(isset($_POST['TA'])){
	echo '<script type="text/javascript">';
	echo 'alert("Apakah anda yakin?");';
	echo '</script>';
	//1. Tahun Ajaran akan berganti menjadi tahun berikutnya.
	$date = date('Y');
	$pre = $date - 1;
	if(edit('ta', "ta='$date' WHERE ta='$pre'")){
		
	}
	//2. Data - data sementara berupa Nilai, Soal, Materi akan hilang.
	$hasil = ambil('hasil', '');
	while($row=mysqli_fetch_assoc($hasil)){
		$id = $row['id'];
		hapus('hasil', "WHERE id='$id'");
	}
	$hasil = ambil('materi', '');
	while($row=mysqli_fetch_assoc($hasil)){
		$id = $row['id'];
		hapus('materi', "WHERE id='$id'");
	}
	$hasil = ambil('soal', '');
	while($row=mysqli_fetch_assoc($hasil)){
		$id = $row['id'];
		hapus('soal', "WHERE id='$id'");
	}
	//3. Penghapusan mata pelajaran dan forum tahun sebelumnya.
	
	//4. Seluruh Kelas X dan Kelas XI, akan diubah tingkatannya menjadi tingkat diatasnya.
	//5. Seluruh Kelas XII akan dihapuskan datanya.
	//6. Konfirmasi akun diperlukan untuk masuk memulai Tahun Ajaran baru.
	$i = 0;
	$hasil = ambil('kelas', "ORDER BY id ASC");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
		$idk = $row['id'];
		$tingkat = $row['tingkat'];
		$jurusan = $row['jurusan'];
		$kelas = $row['kelas'];
		$chk[$idk]['tingkat'] = $tingkat;
		$chk[$idk]['jurusan'] = $jurusan;
		$chk[$idk]['kelas'] = $kelas;
	}
	$hasil = ambil('siswa', "ORDER BY id ASC");
	while($row=mysqli_fetch_assoc($hasil)){
		$id = $row['id'];
		$idu = $row['id_user'];
		$idk = $row['id_kelas'];
		$tk = $chk[$idk]['tingkat'];
		$jr = $chk[$idk]['jurusan'];
		$kl = $chk[$idk]['kelas'];
		if($chk[$idk]['tingkat']==='XI' or $chk[$idk]['tingkat']==='X'){
			if($tk==='X'){
				$tk = 'XI';
			}elseif($tk==='XI'){
				$tk = 'XII';
			}
			$result = ambil('kelas', "WHERE kelas='$kl' AND jurusan='$jr' AND tingkat='$tk'");
			while($br=mysqli_fetch_assoc($result)){
				$idkelas = $br['id'];
			}
			if(edit('siswa', "sesi='Non', id_kelas='$idkelas' WHERE id='$id'")){
				
			}else{
			
			}
		}elseif($chk[$idk]['tingkat']==='XII'){
			$result = ambil('kelas', "WHERE kelas='$kl' AND jurusan='$jr' AND tingkat='$tk'");
			while($br=mysqli_fetch_assoc($result)){
				$idkelas = $br['id'];
			}
			if(hapus('siswa', "WHERE id_kelas='$idkelas'")){
				if(hapus('user', "WHERE id='$idu'")){
				
				}
			}
		}
	}
	
}
 ?>


<div class="main">
	<?php require_once 'view/menu.php'; ?>
	<div class="isi">
		<?php
			$hasil = ambil('ta', "");
			while($row=mysqli_fetch_assoc($hasil)){
				$ta = $row['ta'];
			}
			$te = $ta + 1;
		?>
		<p>Tahun Ajaran sekarang adalah <?=$ta?>/<?=$te?></p>
		<div class="ta error full">
			<p style="font-weight: bold; margin-bottom: 20px;">Jika anda mengganti Tahun Ajaran maka : </p>
			<p>
				1. Tahun Ajaran akan berganti menjadi tahun berikutnya. <br>
				2. Data - data sementara berupa Nilai, Soal, Materi akan hilang. <br>
				3. Penghapusan mata pelajaran dan forum tahun sebelumnya. <br>
				4. Seluruh Kelas X dan Kelas XI, akan diubah tingkatannya menjadi tingkat diatasnya. <br>
				5. Seluruh Kelas XII akan dihapuskan datanya. <br>
				6. Konfirmasi akun diperlukan untuk masuk memulai Tahun Ajaran baru. <br>
			</p><br>
			<form action="ta.php" method="post" class="full">
				Jika anda yakin untuk mengganti tahun ajaran klik tombol : <input type="submit" name="TA" value="Ganti Tahun Ajaran" class="merah">
			</form>
		</div>
	</div>

</div>
 
<?php 
require_once 'view/footer.php';
 ?>