<?php
$title='Home';
require_once 'view/header.php';
$un = $_SESSION['user']['un'];
$hasil = ambil('user', "WHERE username='$un'");
while($row=mysqli_fetch_assoc($hasil)){
	$iduser = $row['id'];
}
$hasil = ambil('siswa', "WHERE id_user='$iduser'");
	while($row=mysqli_fetch_assoc($hasil)){
	$idsiswa = $row['id'];
	$sesi = $row['sesi'];
	$kelas = $row['id_kelas'];
	$alamat = $row['alamat'];
}
if(isset($_POST['kirim'])){
	$data[0] = $_POST['id'];
	$data[1] = $_POST['keterangan'];
	if(edit('siswa', "keterangan='$data[1]' WHERE id='$data[0]'")){
		alert('Keluhan terkirim!', "");
	}
}
 ?>

<div class="konten">

	<div class="soal">
 		<div class="nomorsoal"><h1>Data Siswa</h1></div>
 		<div class="k2 padding">
			<div class="table full">
				<table>
					<tbody>
						<tr>
							<td width="200">Nama</td>
							<td>: <?=$_SESSION['user']['name']?> </td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td>: <?=$alamat?></td>
						</tr>
						<tr>
							<td>Total Skor</td>
							<?php
								$i = 0;
								$hasil = ambil('detail', "WHERE id_kelas='$kelas'");
								while($row=mysqli_fetch_assoc($hasil)){
									$i++;
									$idd[$i] = $row['id'];
								}
								if($i>0){									
									$skor = 0;
									$hasil = ambil('hasil', "WHERE id_siswa='$idsiswa'");
									while($row=mysqli_fetch_assoc($hasil)){
										$skor+=intval($row['skor']);
									}
								}else{
									$skor = 0;
								}
								//TBC - RANKINGS
								$TBC = 0;
								if($TBC>0){
									$i = 0;
									$o = 1;
									foreach($idd as $id):
										$hasil = ambil('hasil', "id_detail='$id'");
										while($row=mysqli_fetch_assoc($hasil)){
											$i++;
											$is = $row['id_siswa'];
											$result = ambil('hasil', "WHERE id_siswa='$is'");
											while($br=mysqli_fetch_assoc($result)){
												$idku[$o] = $is;
												$skor[$o] += intval($row['skor']);
											}
											$o++;
										}
									endforeach;
								}
							?>
							<td>: <?=$skor?></td>
						</tr>
						<tr>
							<td>Staus</td>
							<?php if($sesi==='Aktif'){ ?>
							<td>: Terdaftar</td>
							<?php }elseif($sesi==='Non'){ ?>
							<td>: Belum Terdaftar</td>
							<?php } ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="k2 padding">
   			<div class="masukan">
				<h3>Kirim Keluhan</h3>
				<form action="index.php" method="post" class="full">
					<label for="">Tulis disini - Gunakan jika terdapat kesalahan data</label>
					<input type="hidden" name="id" value="<?=$idsiswa?>">
					<textarea name="keterangan" class="full"></textarea>
					<input type="submit" class="hijau" name="kirim" value="Kirim">
					<input type="reset" class="merah" value="Reset">
				</form>
			</div>
		</div>
		
	</div>
		<center><h1>SEKO-NOL v1.0 - 2018</h1></center>
</div>


<?php
require_once 'view/footer.php';
 ?>
