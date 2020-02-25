<?php
$title='Kelas';
require_once 'view/header.php';
$iddetail = '';
$bg = '';
$v = '';
$w = '';
$id = $_SESSION['user']['id'];
$hasil = ambil('guru', "WHERE id_user='$id'");
while($row=mysqli_fetch_assoc($hasil)){
	$idg = $row['id'];
}
$i = 0;
$hasil = ambil('detail', "WHERE id_guru='$idg'");
while($row=mysqli_fetch_assoc($hasil)){
	$i++;
	$idku[$i] = $row['id'];
}
if(isset($_GET['q']) AND !empty($_GET['q'])){
	if(isset($_GET['v']) AND !empty($_GET['v'])){
		unset($_GET);
		alert('Akses gagal', "kelas.php");
	}
	if(isset($_GET['w']) AND !empty($_GET['w'])){
		unset($_GET);
		alert('Akses gagal', "kelas.php");
	}
	if(isset($_GET['del']) AND !empty($_GET['del'])){
		unset($_GET);
		alert('Akses gagal', "kelas.php");
	}
}
if(isset($_GET['v']) AND !empty($_GET['v'])){
	$_GET['q'] = $_GET['v'];
	$v = $_GET['v'];
}
if(isset($_GET['w']) AND !empty($_GET['w'])){
	$_GET['q'] = $_GET['w'];
	$w = $_GET['w'];
}
if(isset($_GET['q']) AND !empty($_GET['q'])){
	$q = $_GET['q'];
	$a = 0;
	foreach($idku as $num) :
		if($q===$num){
			$a++;
		}
	endforeach;
	if($a===0){
		alert('Tidak ada data', "kelas.php");
	}else{
		$i = 0;
		$hasil = ambil('detail', "WHERE id='$q'");
		while($row=mysqli_fetch_assoc($hasil)){
			$i++;
			$idk = $row['id_kelas'];
			$idm = $row['id_mapel'];
			$idd = $row['id'];
		}
		if($i===0){
			header("refresh:0; url=kelas.php");
		}
		$hasil = ambil('kelas', "WHERE id='$idk'");
		while($row=mysqli_fetch_assoc($hasil)){
			$kelas['tingkat'] = $row['tingkat'];
			$kelas['kelas'] = $row['kelas'];
			$kelas['jurusan'] = $row['jurusan'];
			if($kelas['tingkat']==='X'){
				$bg = 'merah';
			}elseif($kelas['tingkat']==='XI'){
				$bg = 'hijau';
			}elseif($kelas['tingkat']==='XII'){
				$bg = 'biru';
			}
		}
		$hasil = ambil('mapel', "WHERE id='$idm'");
		while($row=mysqli_fetch_assoc($hasil)){
			$mapel['kode'] = $row['kode'];
			$mapel['nama'] = $row['nama'];
		}
	}
}else{
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
	$hasil = ambil('detail', "WHERE id_guru='$idguru'");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
		$iddetail = $row['id'];
		if($i===1){
			header("refresh:0; url=kelas.php?q=$iddetail");
		}
	}
}
if(isset($_POST['Submit'])){
	$data[0] = $_POST['mapel'];
	$data[1] = $_POST['kelas'];
	$data[2] = $_POST['guru'];
	$i = 0;
	$err = 'Kelas sudah memiliki guru';
	$hasil = ambil('detail', "WHERE id_mapel='$data[0]' AND id_kelas='$data[1]'");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
		if($data[2]===$row['id_guru']){
			$err = 'Kelas sudah ada';
		}
	}

	$value = "'$data[0]', '$data[1]', $data[2]";
	$field = "id_mapel, id_kelas, id_guru";

	$mapel = mysqli_query($link,"SELECT * FROM mapel WHERE id = $data[0]");
	while($pel = mysqli_fetch_assoc($mapel)){
		$ma = $pel['nama'];
	}

	$kelas = mysqli_query($link,"SELECT * FROM kelas WHERE id = $data[1]");
	while($las = mysqli_fetch_assoc($kelas)){
			$ke = $las['tingkat'].' '.$las['jurusan'].' '.$las['kelas'];
	}

	mysqli_query($link,"INSERT INTO forum(nama,sub) VALUES('$ke','$ma - $ke')");

	$forum = mysqli_query($link,"SELECT * FROM forum WHERE nama = '$ke'");
	while($rum = mysqli_fetch_assoc($forum)){
		$asd = $rum['id'];
	}

	$siswa = mysqli_query($link,"SELECT * FROM siswa WHERE id_kelas = $data[1]");
	while($sis = mysqli_fetch_array($siswa)){
		$siswa_woi = $sis[1];
		mysqli_query($link,"INSERT INTO forum_akses(id_forum,id_user) VALUES($asd,$siswa_woi)");
	}

	mysqli_query($link,"INSERT INTO forum_akses(id_forum,id_user) VALUES($asd,$id)");

	if($i===0 AND tambah('detail', $field, $value)){
		alert('Kelas baru berhasil ditambahkan', "");
	}else{
		alert($err, "");
	}
}
if(isset($_GET['del'])){
	$id = $_GET['del'];
	$a = 0;
	foreach($idku as $num) :
		if($id===$num){
			$a++;
		}
	endforeach;
	if($a===0){
		alert('Akses gagal', "kelas.php");
	}else{
		function dps($id){
			$i = 0;
			$hasil = ambil('paket', "WHERE id_detail='$id' ORDER BY kode_soal ASC");
			while($row=mysqli_fetch_assoc($hasil)){
				$ks[$i] = $row['kode_soal'];
				$o = 0;
				$result = ambil('paket', "WHERE kode_soal='$ks[$i]'");
				while($br=mysqli_fetch_assoc($result)){
					$o++;
					$kd = $row['id'];
					hapus('hasil', "WHERE id_paket='$kd'");
				}
				if($o===1){
					hapus('soal', "WHERE kode_soal='$ks[$i]'");
				}
				hapus('paket', "WHERE id_detail='$id'");
			}			
		}
		$w = mysqli_query($link,"SELECT * FROM detail WHERE id = $id");
		while($e = mysqli_fetch_array($w)){
			$id_kel = $e[3];
		}
		$r = mysqli_query($link,"SELECT * FROM kelas WHERE id = $id_kel");
		while($t = mysqli_fetch_array($r)){
			$kel = $t[1].' '.$t[3].' '.$t[2];
		}
		$y = mysqli_query($link,"SELECT * FROM forum WHERE nama = '$kel'");
		while($u = mysqli_fetch_array($y)){
			$id_f = $u[0];
		}
		$i = mysqli_query($link,"SELECT * FROM forum_akses WHERE id_forum = $id_f");
		while($o = mysqli_fetch_array($i)){
			$id_a = $o[0];
			//mysqli_query($link,"DELETE FROM forum_chat WHERE id_akses = $id_a");
		}
		mysqli_query($link,"DELETE FROM forum WHERE id = $id_f");
		mysqli_query($link,"DELETE FROM forum_akses WHERE id_forum = $id_f");
			
		if(hapus('detail', "WHERE id='$id'") AND hapus('materi', "WHERE id_detail='$id'") AND dps($id)){
			alert('Kelas berhasil dihapus', "");
		}
	}
}
if(isset($_POST['sm'])){
	$hasil = ambil('materi',"");
	$i = 0;
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
	}
	$data[0] = $q;
	$data[1] = $_POST['keterangan'];
	$data[2] = $i + 1;
	$field = 'id_detail, keterangan, link';
	$value = "'$data[0]', '$data[1]', '$data[2]'";
	$targetdir = '../assets/data/materi/';
	$targetfile = $targetdir.$data[2].'.pdf';
	if(move_uploaded_file($_FILES['materi']['tmp_name'], $targetfile)) {
		if(tambah('materi', $field, $value)){
			alert('Materi berhasil diupload', "");
		}else{
			alert('Terjadi Kesalahan', "");
		}
	}else{
		alert('Materi gagal diupload', "");
	}
}
 ?>

<div class="bungkus">

	<div class="sideus">
		<div class="atasside">
		<?php
			$hasil = ambil('ta', "");
			while($row=mysqli_fetch_assoc($hasil)){
				$ta = $row['ta'];
			}
			$te = $ta + 1;
		?>
			<h2>TA. <?=$ta?> / <?=$te?></h2>
		</div>
		<?php
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
			$hasil = ambil('detail', "WHERE id_guru='$idguru' ORDER BY id_mapel DESC, id_kelas ASC");
			while($row=mysqli_fetch_assoc($hasil)){
				$i++;
				$iddetail = $row['id'];
				if($i===1){ $def = $row['id']; }
				$idkelas = $row['id_kelas'];
				$idmapel = $row['id_mapel'];
				$result = ambil('mapel', "WHERE id='$idmapel'");
				while($br=mysqli_fetch_assoc($result)){
					$course = $br['nama'];
				}
				$result = ambil('kelas', "WHERE id='$idkelas'");
				while($br=mysqli_fetch_assoc($result)){
					if(!empty($q) AND $q===$iddetail){
						$clr = 'color:white';
						if($br['tingkat']==='X'){
							$sty = 'merah';
						}elseif($br['tingkat']==='XI'){
							$sty = 'hijau';
						}elseif($br['tingkat']==='XII'){
							$sty = 'biru';
						}
						}else{ $sty = 'putih'; $clr = ''; }
		?>
		<a href="kelas.php?q=<?=$iddetail?>" class="<?=$sty?>" style="<?=$clr?>">&#9745; <?=$course?> - <?=$br['tingkat'].' '.$br['jurusan'].' '.$br['kelas']?></a>
		<?php } }
			if(!isset($_GET['q']) AND $i>0){
				header("refresh:0; url=kelas.php?q=$def");
			}
		?>
		<a> <form action="kelas.php" method="post" class="full">
		<label for="">Mata Pelajaran & Kelas</label>
		<input type="hidden" name="guru" value="<?=$idguru?>">
		<select name="mapel" class="k2" autofocus>
		<?php
			$hasil = ambil('mapel', "ORDER BY id ASC");
			while($row=mysqli_fetch_assoc($hasil)){
		?>
			<option value="<?=$row['id']?>"><?=$row['nama']?></option>
		<?php
			}
		?>
		</select>
		<select name="kelas" class="f50">
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
		<label for=""></label>
		<input type="submit" name="Submit" value="Tambah" class="<?=$bg?> full">
		</form>
		</a>
	</div>
	<div class="mainus">
		<?php if(!empty($q)){ ?>
		<div class="judul">
			<h2><?=$kelas['tingkat'].' '.$kelas['jurusan'].' '.$kelas['kelas'].' - '.$mapel['nama']?></h2>
			<a href="kelas.php?q=<?=$q?>" class="tmbl <?=$bg?>">Data Kelas</a>
			<a href="kelas.php?v=<?=$q?>" class="tmbl <?=$bg?>">Materi</a>
			<a href="kelas.php?w=<?=$q?>" class="tmbl <?=$bg?>">Soal</a>
			<a href="kelas.php?del=<?=$idd?>" class="tmbl putih">Hapus Kelas</a>
		</div>
		<?php } ?>
		<?php if(isset($_GET['v']) AND !isset($_GET['w'])){ ?>
		<div class="judul">
			<form action="kelas.php?v=<?=$q?>" method="post" enctype="multipart/form-data">
				<input type="file" name="materi" accept=".pdf" required>
				<input type="text" name="keterangan" placeholder="Keterangan" required>
				<input type="submit" name="sm" value="Tambah Materi" class="tmbl <?=$bg?>">
			</form>
			<?php
			if(isset($_GET['m']) AND !empty($_GET['m'])){
				$m = $_GET['m'];
				echo '<a href="kelas.php?v='.$q.'&d='.$m.'" class="tmbl putih">Hapus Materi</a>';
			}
			?>
		</div>
		<?php
			$i = 0;
			$hasil = ambil('materi', "WHERE id_detail='$q'");
			while($row=mysqli_fetch_assoc($hasil)){
				$i++;
			}
			if($i>0){
			?>
		<div class="judul">
		<?php
			$i = 0;
			$hasil = ambil('materi', "WHERE id_detail='$q'");
			while($row=mysqli_fetch_assoc($hasil)){
				$i++;
				$mpl[$i] = $row['id'];
				$ket = $row['keterangan'];
				$sty = 'putih';
				if(isset($_GET['m']) AND !empty($_GET['m'])){
					$m = $_GET['m'];
					if($mpl[$i]===$m){
						$sty = $bg;
					}else{
						$sty = 'putih';
					}
				}
		?>
			<a href="kelas.php?v=<?=$q?>&m=<?=$mpl[$i]?>" class="tmbl <?=$sty?>"><?=$ket?></a>
		<?php } if($i>0 AND !isset($_GET['m'])){ header("refresh:0; url=kelas.php?v=$q&m=$mpl[1]"); } ?>
		</div>
		<?php } } ?>
		<?php if(isset($_GET['w']) AND !isset($_GET['v'])){
				$a = 0;
				$hasil = ambil('paket', "WHERE id_detail='$q' ORDER BY kode_soal ASC");
				while($row=mysqli_fetch_assoc($hasil)){
					$a++;
					$m[$a] = $row['id'];
				}
				if(!isset($_GET['m']) AND !isset($_GET['s'])){
					if($a>0){
						header("refresh:0; url=kelas.php?w=$q&m=$m[1]");
					}
				}
			if($a>0){
		?>
		<div class="judul">
			<?php
				$a = 0;
				$hasil = ambil('paket', "WHERE id_detail='$q' ORDER BY kode_soal ASC");
				while($row=mysqli_fetch_assoc($hasil)){
					$a++;
					$m[$a] = $row['id'];
					if(isset($_GET['m']) AND !empty($_GET['m'])){
						$ks = $_GET['m'];
						if($ks===$m[$a]){
							$sty = $bg;
						}else{
							$sty = 'putih';
						}
					}
			?>
			<a href="kelas.php?w=<?=$q?>&m=<?=$m[$a]?>" class="tmbl <?=$sty?>"><?=$row['keterangan']?></a>
			<?php
				}
				if(isset($_GET['s']) AND !empty($_GET['s'])){
					$s = $_GET['s'];
					$hasil = ambil('paket', "WHERE id='$s'");
					while($row=mysqli_fetch_assoc($hasil)){
						$ss = $row['id_detail'];
						$sta = $row['status'];
					}
					$a = 0;
					foreach($idku as $num) :
						if($ss===$num){
							$a++;
						}
					endforeach;
					if($a===0){
						alert('Akses gagal', "kelas.php");
					}else{
						if($sta==='0'){
							$prm = "status='1' WHERE id='$s'";
							if(edit('paket', $prm)){
								alert('Soal dibagikan', "kelas.php?w=$q&m=$s");
							}
						}elseif($sta==='1'){
							$prm = "status='0' WHERE id='$s'";
							if(edit('paket', $prm)){
								alert('Soal ditarik', "kelas.php?w=$q&m=$s");
							}
						}
					}
				}
			if(isset($_GET['m']) AND !empty($_GET['m'])){
				$dm = $_GET['m'];
				$hasil = ambil('paket', "WHERE id='$dm'");
				while($row=mysqli_fetch_assoc($hasil)){
					$ss = $row['id_detail'];
					$sta = $row['status'];
					if($sta==='0'){
						$nb = 'Bagikan';
					}elseif($sta==='1'){
						$nb = 'Tarik';
					}
				}
				$a = 0;
				foreach($idku as $num) :
					if($ss===$num){
						$a++;
					}
				endforeach;
				if($a===0){
					alert('Akses gagal', "kelas.php");
				}else{
			?>
			<a href="kelas.php?w=<?=$q?>&s=<?=$dm?>" class="tmbl <?=$bg?>"><?=$nb?></a>
			<?php
				}
			}
			?>
		</div>
			<?php } ?>
		<?php } ?>
		<?php if(isset($_GET['q']) AND empty($v) AND empty($w)){ ?>
		<div class="mainisi">
			<center>Data Kelas</center>

					<div class="table">
						<table>
							<thead class="<?=$bg?>">
								<tr>
									<td width="10"></td>
									<td width="30">No</td>
									<td align="center" width="100">NIS</td>
									<td align="left">Nama Siswa</td>
								</tr>
							</thead>
							<tbody>
							<?php
								$i = 0;
								$hasil = ambil('siswa', "WHERE id_kelas='$idk' ORDER BY NIS ASC");
								while($row=mysqli_fetch_assoc($hasil)){
									$i++;
									$usr = $row['id_user'];
									$nis = $row['NIS'];
									$result = ambil('user', "WHERE id='$usr'");
									while($br=mysqli_fetch_assoc($result)){
										$nama = $br['nama'];
									}
							?>
								<tr>
									<form class="" method="post">
										<td align="center">
											<input type="checkbox" name="" value="">
										</td>
										<td align="center"><?=$i?></td>
										<td align="center"><?=$nis?></td>
										<td align="left"><?=$nama?></td>
									</form>
								</tr>
								<?php } if($i===0){ echo '<tr><td colspan="10" align="center">Tidak Ada Siswa!</td></tr>'; } ?>
							</tbody>
						</table>
					</div>

		</div> <?php } ?>
		<?php if(isset($_GET['v']) AND empty($w)){
			if(isset($_GET['d']) AND !empty($_GET['d'])){
				$d = $_GET['d'];
				$hasil = ambil('materi', "WHERE id='$d'");
				while($row=mysqli_fetch_assoc($hasil)){
					$dm = $row['id_detail'];
				}
				$a = 0;
				foreach($idku as $num) :
					if($dm===$num){
						$a++;
					}
				endforeach;
				if($a===0){
					alert('Akses gagal'.$num, "kelas.php");
				}else{
					if(hapus('materi', "WHERE id='$d'")){
						alert("Materi dihapus", "kelas.php?v=$q");
					}
				}
			}
			if(isset($_GET['m']) AND !empty($_GET['m'])){
				$m = $_GET['m'];
				$i = 0;
				$hasil = ambil('materi', "WHERE id='$m'");
				while($row=mysqli_fetch_assoc($hasil)){
					$i++;
					$link = $row['link'];
					$ket = $row['keterangan'];
				}
				if($i===0){
					header("refresh:0; url=kelas.php?v=$v");
				}else{
			?>
		<div class="mainisi">
			<center style="padding: 10px;">Materi - <?=$ket?></center>
			<div class="pdf">
				<object data="../assets/data/materi/<?=$link?>.pdf" type="application/pdf" width="100%" height="100%">
				  <!-- <p>Alternative text - include a link <a href="matematika.pdf">to the PDF!</a></p> -->
				</object>
			</div>
		</div> 
			<?php } } } ?>
		<?php if(isset($_GET['w']) AND empty($v)){ 
				if(isset($_GET['m']) AND !empty($_GET['m'])){
					$m = $_GET['m'];
					$a = 0;
					$hasil = ambil('paket', "WHERE id='$m' ORDER BY kode_soal ASC");
					while($row=mysqli_fetch_assoc($hasil)){
						$a++;
						$p1 = $row['kode_soal'];
						$p2 = $row['keterangan'];
						$p3 = $row['kategori'];
					}
					if($a===0){ header("refresh:0; url=kelas.php?w=$q"); }
					$b = 0;
					$hasil = ambil('soal', "WHERE kode_soal='$p1' ORDER BY id ASC");
					while($row=mysqli_fetch_assoc($hasil)){
						$b++;
					}
			if($a>0){
				?>
		<div class="mainisi">
			<?php if($b===0){ echo '<center>Silahkan isi soal dahulu!</center>'; }else{ ?>
			<center><?='Paket : '.$p1.' | Keterangan : '.$p2.' | Kategori : '.$p3?></center>
		</div> 
		<?php } } } } ?>
	</div>

</div>

<?php
require_once 'view/header.php';
 ?>
