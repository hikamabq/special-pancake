<?php
$title='Data User';
require_once 'view/header.php';
if(!isset($_GET['w']) OR empty($_GET['w'])){
	$w = 'siswa';
	header("refresh:0; url=data_user.php?w=siswa");
}
if(isset($_GET['w'])){
	$w = $_GET['w'];
	if($w==='siswa'){

	}elseif($w==='guru'){

	}else{
		$w = 'siswa';
	}
	if(isset($_GET['q'])){
		$idu = $_GET['q'];
		$q['id'] = $idu;
		$hasil = ambil('user', "WHERE id='$idu'");
		while($row=mysqli_fetch_assoc($hasil)){
			$stat = $row['status'];
			$q['nama'] = $row['nama'];
		}
		if($stat==='Siswa'){
			$hasil = ambil('siswa', "WHERE id_user='$idu'");
			while($row=mysqli_fetch_assoc($hasil)){
				$ids = $row['id'];
				$ket = $row['keterangan'];
				$q['nis'] = $row['NIS'];
				$q['id_kelas'] = $row['id_kelas'];
				$q['alamat'] = $row['alamat'];
			}
			echo '<script type="text/javascript">';
			echo 'alert("'.$ket.'");';
			echo '</script>';
		}elseif($stat==='Guru'){
			$hasil = ambil('guru', "WHERE id_user='$idu'");
			while($row=mysqli_fetch_assoc($hasil)){
				$idg = $row['id'];
				$ket = $row['keterangan'];
				$q['kode'] = $row['kode'];
				$q['nip'] = $row['NIP'];
				$q['alamat'] = $row['alamat'];
				$q['telp'] = $row['telp'];
			}
			echo '<script type="text/javascript">';
			echo 'alert("'.$ket.'");';
			echo '</script>';
		}
	}
}
if(isset($_GET['d'])){
	$idu = $_GET['d'];
	$hasil = ambil('user', "WHERE id='$idu'");
	while($row=mysqli_fetch_assoc($hasil)){
		$stat = $row['status'];
	}
	if($stat==='Siswa'){
		$hasil = ambil('siswa', "WHERE id_user='$idu'");
		while($row=mysqli_fetch_assoc($hasil)){
			$ids = $row['id'];
		}
		if(edit('siswa', "keterangan='' WHERE id='$ids'")){
			alert('Berhasil', "data_user.php");
		}
	}elseif($stat==='Guru'){
		$hasil = ambil('guru', "WHERE id_user='$idu'");
		while($row=mysqli_fetch_assoc($hasil)){
			$idg = $row['id'];
		}
		if(edit('guru', "keterangan='' WHERE id='$idg'")){
			alert('Berhasil', "data_user.php?w=guru");
		}
	}
}
if(isset($_POST['ss'])){
	$data[0] = $_POST['id'];
	$data[1] = $_POST['nama'];
	$data[2] = $_POST['nis'];
	$data[3] = $_POST['kelas'];
	$data[4] = $_POST['alamat'];
	$prm1 = "nama='$data[1]' WHERE id='$data[0]'";
	$prm2 = "NIS='$data[2]', id_kelas='$data[3]', alamat='$data[4]' WHERE id_user='$data[0]'";
	if(edit('user', $prm1) AND edit('siswa', $prm2)){
		header("refresh:0; url=data_user.php?w=siswa&d=$data[0]");
	}
}
if(isset($_POST['sg'])){
	$data[0] = $_POST['id'];
	$data[1] = $_POST['nama'];
	$data[2] = $_POST['kode'];
	$data[3] = $_POST['nip'];
	$data[4] = $_POST['alamat'];
	$data[5] = $_POST['telp'];
	$prm1 = "nama='$data[1]' WHERE id='$data[0]'";
	$prm2 = "kode='$data[2]', NIP='$data[3]', alamat='$data[4]', telp='$data[5]' WHERE id_user='$data[0]'";
	if(edit('user', $prm1) AND edit('guru', $prm2)){
		header("refresh:0; url=data_user.php?w=guru&d=$data[0]");
	}
}
 ?>

<div class="main">
	<?php require_once 'view/menu.php'; ?>
	<div class="isi">

		<div class="kopgen">
			<h2 class="kiri">Data User - <?=ucfirst($w)?></h2>
			<?php
			if($w==='siswa'){
				$v = 'guru';
				$V = 'Guru';
				$kn = 'NIS';
			}elseif($w==='guru'){
				$v = 'siswa';
				$V = 'Siswa';
				$kn = 'Kode';
			}
			?>
			<a href="data_user.php?w=<?=$v?>" class="tmbl biru kanan"><?=$V?></a>
		</div>
		
		<?php 
		if(isset($_GET['q'])){
			if(isset($_GET['q'])){
				if($stat==='Guru'){
					$sub = 'Edit Data Guru';
		?>
		<div class="full form-token">
			<h4 class="kiri" style="padding: 10px;"><?=$sub?></h4>
			<form action="data_user.php" method="post" class="kanan">
				<input type="hidden" name="id" value="<?=$q['id']?>">
				<input type="text" name="nama" value="<?=$q['nama']?>" placeholder="Nama" autofocus required>
				<input type="text" name="kode" value="<?=$q['kode']?>" placeholder="Kode" required>
				<input type="text" name="nip" value="<?=$q['nip']?>" placeholder="NIP" required>
				<input type="text" name="alamat" value="<?=$q['alamat']?>" placeholder="Alamat" required>
				<input type="tel" name="telp" value="<?=$q['telp']?>" placeholder="Telp" required>
				<input type="submit" name="sg" value="Edit" class="hijau">
			</form>
		</div>
		<?php
				}elseif($stat==='Siswa'){
					$sub = 'Edit Data Siswa';
		?>
		<div class="full form-token">
			<h4 class="kiri" style="padding: 10px;"><?=$sub?></h4>
			<form action="data_user.php" method="post" class="kanan">
				<input type="hidden" name="id" value="<?=$q['id']?>">
				<input type="text" name="nama" value="<?=$q['nama']?>" placeholder="Nama" autofocus required>
				<input type="number" name="nis" value="<?=$q['nis']?>" placeholder="NIS" required>
				<select name="kelas">
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
						if($q['id_kelas']===$row['id']){
							$slc = 'selected';
						}else{
							$slc = '';
						}
				?>
					<option value="<?=$row['id']?>" <?=$slc?>><?=$kl?></option>
					<?php }if($u>0){ echo '</optgroup>'; } ?>
				</select>
				<input type="text" name="alamat" value="<?=$q['alamat']?>" placeholder="Alamat" required>
				<input type="submit" name="ss" value="Edit" class="hijau">
			</form>
		</div>
		<?php } } } ?>

		<div class="table">
			<table>
				<thead class="hijau">
					<tr>
						<td width="30">No</td>
						<td align="center"><?=$kn?></td>
						<td align="center">Nama</td>
						<td align="center">Status</td>
						<td align="center" width="200">Opsi</td>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					$o = 0;
					function dtusr($tbl){
						global $w;
						global $i;
						global $o;
						if(isset($_GET['q'])){
							global $idu;
							$q = $idu;
						}
						$hasil = ambil($tbl,'');
						while($row=mysqli_fetch_assoc($hasil)){
							if(!empty($row['keterangan'])){
								$o++;
							}
						}
						$hasil = ambil($tbl,'');
						while($row=mysqli_fetch_assoc($hasil)){
							$i++;
							if(!empty($row['keterangan'])){
								$idu = $row['id_user'];
								$ket = $row['keterangan'];
							$result = ambil('user', "WHERE id='$idu'");
							while($br=mysqli_fetch_assoc($result)){
								$nama = $br['nama'];
								$status = $br['status'];
							}
							if($status==='Guru'){
								$kd = $row['kode'];
							}elseif($status==='Siswa'){
								$kd = $row['NIS'];
							}
							if(isset($_GET['q']) AND $q===$idu){
								$sty = 'font-weight:bold;';
							}else{
								$sty = '';
							}
					?>
					<tr style="<?=$sty?>">
						<td align="center"><?=$i?></td>
						<td align="center"><?=$kd?></td>
						<td align="center"><?=$nama?></td>
						<td align="center"><?=$status?></td>
						<td align="center">
							<a href="data_user.php?w=<?=$w?>&q=<?=$idu?>" class="tmbl biru">&#9998;</a>
							<a href="data_user.php?w=<?=$w?>&d=<?=$idu?>" class="tmbl merah">&#10005;</a>
						</td>
					</tr>
					<?php
						} }
						if($o===0){
					?>
					<tr>
						<td align="center" colspan="5">Data Kosong!</td>
					</tr>
					<?php } } dtusr($w); ?>
				</tbody>
			</table>
		</div>

	</div>
</div>

<?php
require_once 'view/footer.php';
 ?>
