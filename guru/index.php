<?php
$title='Home';
require_once 'view/header.php';
$un = $_SESSION['user']['un'];
$hasil = ambil('user', "WHERE username='$un'");
while($row=mysqli_fetch_assoc($hasil)){
	$iduser = $row['id'];
}
$hasil = ambil('guru', "WHERE id_user='$iduser'");
	while($row=mysqli_fetch_assoc($hasil)){
	$idguru = $row['id'];
	$alamat = $row['alamat'];
}
if(isset($_POST['kirim'])){
	$data[0] = $_POST['id'];
	$data[1] = $_POST['keterangan'];
	if(edit('guru', "keterangan='$data[1]' WHERE id='$data[0]'")){
		alert('Keluhan terkirim', "");
	}
}
 ?>

<div class="konten">

	<div class="soal">
  		<div class="nomorsoal"><h1>Data Guru</h1></div>

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
						<?php
							$i = 0;
							$hasil = ambil('detail', "WHERE id_guru='$idguru'");
							while($row=mysqli_fetch_assoc($hasil)){
								$i++;
							}
							if($i===0){
								$str = '-';
							}else{
								$str = '<a href="kelas.php">Lihat</a>';
							}
						?>
						<tr>
							<td>Mapel</td>
							<td>: <?=$str?></td>
						</tr>
						<tr>
							<td>Kelas</td>
							<td>: <?=$str?></td>
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
					<input type="hidden" name="id" value="<?=$idguru?>">
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
