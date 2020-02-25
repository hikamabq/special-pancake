<?php
$title='Data Sekolah';
require_once 'view/header.php';
if(!isset($_GET['w']) OR empty($_GET['w'])){
	$w = 'kelas';
	header("refresh:0; url=data_sekolah.php?w=kelas");
}
if(isset($_GET['w'])){
	$w = $_GET['w'];
	if($w==='kelas'){
		
	}elseif($w==='mapel'){
		
	}else{
		$w = 'kelas';
	}
	if(isset($_GET['q'])){
		$q = $_GET['q'];
		if(hapus($w, "WHERE id='$q'")){
			alert('Berhasil menghapus data', "data_sekolah.php?w=$w");
		}
	}
	if(isset($_GET['d'])){
		$d = $_GET['d'];
		if(hapus($w, "WHERE id='$d'")){
			alert('Berhasil menghapus data', "data_sekolah.php?w=$w");
		}
	}
}
if(isset($_POST['sk'])){
	$data[0] = $_POST['tingkat'];
	$data[1] = strtoupper($_POST['jurusan']);
	$data[2] = $_POST['kelas'];
	$i = 0;
	$hasil = ambil('kelas', "WHERE tingkat='$data[0]' AND jurusan='$data[1]' AND kelas='$data[2]'");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
	}
	$field = 'tingkat, jurusan, kelas';
	$value = "'$data[0]', '$data[1]', '$data[2]'";
	if($i===0 AND tambah('kelas', $field, $value)){
		alert('Berhasil menambah data', '');
	}else{
		alert('Gagal menambah data', '');
	}
}
if(isset($_POST['sm'])){
	$data[0] = strtoupper($_POST['kode']);
	$data[1] = ucwords($_POST['nama']);
	$i = 0;
	$hasil = ambil('mapel', "WHERE kode='$data[0]' OR nama='$data[1]'");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
	}
	$field = 'kode, nama';
	$value = "'$data[0]', '$data[1]'";
	if($i===0 AND tambah('mapel', $field, $value)){
		alert('Berhasil menambah data', 'data_sekolah.php?w=mapel');
	}else{
		alert('Gagal menambah data', 'data_sekolah.php?w=mapel');
	}
}
 ?>

<div class="main">
	<?php require_once 'view/menu.php'; ?>
	<div class="isi">

		<div class="kopgen">
			<h2 class="kiri">Data Sekolah - <?=ucfirst($w)?></h2>
			<?php 
			if($w==='kelas'){
				$W = 'Kelas';
				$v1 = 'mapel';
				$V1 = 'Mata Pelajaran';
				$kn = 'Kelas';
				$prm = 'ORDER BY jurusan, tingkat, kelas ASC';
			}elseif($w==='mapel'){
				$W = 'Mata Pelajaran';
				$v1 = 'kelas';
				$V1 = 'Kelas';
				$kn = 'Kode';
				$prm = '';
			}
			?>
			<a href="data_sekolah.php?w=<?=$v1?>" class="tmbl biru kanan"><?=$V1?></a>
		</div>

		<div class="full form-token">
			<h4 class="kiri" style="padding: 10px;">Tambah <?=$W?></h4>
		<?php if($w==='kelas'){ ?>
			<form action="data_sekolah.php" method="post" class="kanan">
				<select name="tingkat" autofocus>
					<option value="X">X</option>
					<option value="XI">XI</option>
					<option value="XII">XII</option>
				</select>
				<input type="text" maxlength="5" name="jurusan" placeholder="Jurusan" required>
				<select name="kelas">
					<option value="A">A</option>
					<option value="B">B</option>
					<option value="C">C</option>
					<option value="D">D</option>
					<option value="E">E</option>
					<option value="F">F</option>
					<option value="G">G</option>
					<option value="H">H</option>
				</select>
				<input type="submit" name="sk" value="Tambah" class="hijau">
			</form>
		<?php }elseif($w==='mapel'){ ?>
			<form action="data_sekolah.php" method="post" class="kanan">
				<input type="text" maxlength="5" name="kode" placeholder="Kode Mapel" autofocus required>
				<input type="text" value="" name="nama" placeholder="Nama Mapel" required>
				<input type="submit" name="sm" value="Tambah" class="hijau">
			</form>
		<?php } ?>
		</div>

		<div class="table">
			<table>
				<thead class="hijau">
					<tr>
						<td width="30">No</td>
						<td align="center"><?=$kn?></td>
						<?php if($w==='mapel'){ ?><td align="center">Mata Pelajaran</td> <?php } ?>
						<td align="center" width="200">Hapus</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if($w==='kelas' OR $w==='mapel'){
					$i = 0;
					function dtusr($tbl, $prm){
						global $i;
						$hasil = ambil($tbl,$prm);
						while($row=mysqli_fetch_assoc($hasil)){
							$i++;
							$idt = $row['id'];
							if($tbl==='kelas'){
								$kd = $row['tingkat'].' '.$row['jurusan'].' '.$row['kelas'];
								$hl = 'q';
							}elseif($tbl==='mapel'){
								$kd = $row['kode'];
								$nama = $row['nama'];
								$hl = 'd';
							}
					?>
					<tr>
						<td align="center"><?=$i?></td>
						<td align="center"><?=$kd?></td>
						<?php if($tbl==='mapel'){ ?><td align="center"><?=$nama?></td><?php } ?>
						<td align="center">
							<a href="data_sekolah.php?w=<?=$tbl?>&<?=$hl?>=<?=$idt?>" class="tmbl merah">&#10005;</a>
						</td>
					</tr>
					<?php
						if($i===0){
					?>
					<tr>
						<td align="center" colspan="5">Data Kosong!</td>
					</tr>
					<?php
					} } } dtusr($w, $prm); }
					?>
				</tbody>
			</table>
		</div>

	</div>
</div>
 
<?php 
require_once 'view/footer.php';
 ?>