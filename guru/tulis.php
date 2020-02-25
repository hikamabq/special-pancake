<?php
$title='Tulis Soal';
require_once 'view/header.php';
$idu = $_SESSION['user']['id'];
$hasil = ambil('guru', "WHERE id_user='$idu'");
while($row=mysqli_fetch_assoc($hasil)){
	$myid = $row['id'];
}
$i = 0;
$hasil = ambil('detail', "WHERE id_guru='$myid'");
while($row=mysqli_fetch_assoc($hasil)){
	$i++;
	$idku[$i] = $row['id'];
}
if(!empty($dt[1]) OR !empty($dt[2]) OR !empty($dt[3])){
	$i = 0;
	$o = 1;
	$paket = 'SMK-'.$o;
	while($i<1){
		$a = 0;
		$hasil = ambil('paket', "WHERE kode_soal='$paket'");
		while($row=mysqli_fetch_assoc($hasil)){
			$a++;
		}
		if($a===0){
			$i++;
		}else{
			$o++;
			$paket = 'SMK-'.$o;
		}
	}
	if(isset($_GET['v']) AND isset($_GET['q'])){
		$v = $_GET['v'];
		$q = $_GET['q'];
		
	}else{
		$v = '';
		$q = '';
	}
	$i = 0;
	$hasil = ambil('mapel', "WHERE nama='$v'");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
		$idmapel = $row['id'];
	}
	$o = 0;
	$hasil = ambil('kelas', "WHERE tingkat='$q'");
	while($row=mysqli_fetch_assoc($hasil)){
		$o++;
		$kls[$o] = $row['id'];
	}
	if($i===0 OR $o===0){
		alert('Kesalahan data', "soal.php");
	}
	$i = 0;
	$o = 0;
	$a = 0;
	$hasil = ambil('detail', "WHERE id_guru='$idguru'");
	while($row=mysqli_fetch_assoc($hasil)){
		if($idmapel===$row['id_mapel']){
			$i++;
			foreach($kls as $idk) :
			if($idk===$row['id_kelas']){
				$o++;
				$a++;
				$det[$a] = $idk;
				$idd[$a] = $row['id'];
			}
			endforeach;
		}
	}
	if(isset($_GET['dp']) AND !empty($_GET['dp'])){
		$dp = $_GET['dp'];
		$a = 0;
		$i = 0;
		$hasil = ambil('paket', "WHERE kode_soal='$dp'");
		while($row=mysqli_fetch_assoc($hasil)){				
			foreach($idd as $num) :
				if($row['id_detail']===$num AND $row['status']==='0'){
					$a++;
					$kd = $row['id'];
					hapus('hasil', "WHERE id_paket='$kd'");
				}
			endforeach;
		}
		if($a===0){
			alert('Gagal menghapus paket soal atau Soal sedang dikerjakan', "tulis.php?v=$v&q=$q");
		}else{
			if(hapus('paket', "WHERE kode_soal='$dp'") AND hapus('soal', "WHERE kode_soal='$dp'")){
				alert('Paket soal dihapus', "tulis.php?v=$v&q=$q");
			}
		}
	}
	if(isset($_POST['sp'])){
		$data[0] = $_POST['kode_soal'];
		$data[1] = ucwords($_POST['keterangan']);
		$data[2] = $_POST['kategori'];
		$data[3] = $_POST['status'];
		$field = 'kode_soal, keterangan, kategori, status';
		$value = "'$data[0]', '$data[1]', '$data[2]', '$data[3]'";
		$a = 0;
		if(isset($_POST['kelas'])){
			foreach($_POST['kelas'] as $det) :
				$fld = $field.', id_detail';
				$val = $value.", '$det'";
				if(tambah('paket', $fld, $val)){
					$a++;
				}else{
					alert('Gagal membuat paket soal', "");
				}
			endforeach;
		}else{
			alert('Kelas harus dipilih!', "");
		}
		if($a>0){
			alert('Berhasil membuat paket soal', "");
		}
	}
	if(isset($_POST['ss'])){
		$data[0] = $_POST['ks'];
		$data[1] = $_POST['text'];
		$data[2] = $_POST['A'];
		$data[3] = $_POST['B'];
		$data[4] = $_POST['C'];
		$data[5] = $_POST['D'];
		$data[6] = $_POST['E'];
		$data[7] = $_POST['benar'];
		$i = 0;
		$hasil = ambil('paket', "WHERE kode_soal='$data[0]'");
		while($row=mysqli_fetch_assoc($hasil)){
			if($row['status']==='1'){
				$i++;
			}
		}
		$field = 'kode_soal, text, A, B, C, D, E, benar';
		$value = "'$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]'";
		if($i===0){
			if(tambah('soal', $field, $value)){
				alert('Penambahan soal berhasil', "");
			}else{
				alert('Penambahan soal gagal', "");
			}
		}else{
			alert('Soal sedang dikerjakan', "");
		}
	}
	if(isset($_POST['es'])){
		$data[0] = $_POST['id'];
		$data[1] = $_POST['text'];
		$data[2] = $_POST['A'];
		$data[3] = $_POST['B'];
		$data[4] = $_POST['C'];
		$data[5] = $_POST['D'];
		$data[6] = $_POST['E'];
		$data[7] = $_POST['benar'];
		$i = 0;
		$hasil = ambil('soal', "WHERE id='$data[0]'");
		while($row=mysqli_fetch_assoc($hasil)){
			$paket = $row['kode_soal'];
		}
		$hasil = ambil('paket', "WHERE kode_soal='$paket'");
		while($row=mysqli_fetch_assoc($hasil)){
			if($row['status']==='1'){
				$i++;
			}
		}
		$prm = "text='$data[1]', A='$data[2]', B='$data[3]', C='$data[4]', D='$data[5]', E='$data[6]', benar='$data[7]' WHERE id='$data[0]'";
		if($i===0){
			if(edit('soal', $prm)){
				alert('Perubahan soal disimpan', "");
			}else{					
				alert('Perubahan soal gagal', "");
			}
		}else{
			alert('Soal sedang dikerjakan', "");
		}
	}
	if($i>0 AND $o>0){
?>

<div class="konten">

	<div class="soal">
		<div class="nomorsoal">
			<a href="tulis.php?v=<?=$v?>&q=<?=$q?>"><div class="bold">Nomor Soal</div></a>
		</div>
		<form action="tulis.php?v=<?=$v?>&q=<?=$q?>" method="post" enctype="multipart/form-data">
		Buat Paket
			<input type="text" name="kode_soal" value="<?=$paket?>" readonly>
			<?php
				$a = 0;
				$hasil = ambil('kelas', "WHERE tingkat='$q'");
				while($row=mysqli_fetch_assoc($hasil)){
					$a++;
					$idkls[$a] = $row['id'];
				}
				$hasil = ambil('mapel', "WHERE nama='$v'");
				while($row=mysqli_fetch_assoc($hasil)){
					$idm = $row['id'];
				}
				$hasil = ambil('guru', "WHERE id_user='$idu'");
				while($row=mysqli_fetch_assoc($hasil)){
					$idg = $row['id'];
				}
				$a = 0;
				$hasil = ambil('detail', "WHERE id_guru='$idg' AND id_mapel='$idm'");
				while($row=mysqli_fetch_assoc($hasil)){
					foreach($idkls as $cls) :
						if($cls===$row['id_kelas']){
							$a++;
							$did[$a] = $row['id'];
						}
					endforeach;
				}
				foreach($did as $dtl) :
					$hasil = ambil('detail', "WHERE id='$dtl'");
					while($row=mysqli_fetch_assoc($hasil)){
						$ok = $row['id_kelas'];
						$result = ambil('kelas', "WHERE id='$ok'");
						while($br=mysqli_fetch_assoc($result)){
							$kls = $br['tingkat'].' '.$br['jurusan'].' '.$br['kelas'];
						}
					}
			?>
				<input type="checkbox" name="kelas[]" value="<?=$dtl?>"><?=$kls?>
			<?php endforeach; ?>
			<input type="text" name="keterangan" value="" placeholder="Keterangan" required>
			<select name="kategori" value="<?=$paket?>">
				<option value="Latihan">Latihan</option>
				<option value="Ujian">Ujian</option>
			</select>
			<input type="hidden" name="status" value="0">
			<input type="submit" name="sp" value="Buat Paket" class="tmbl hijau">
			<input type="reset" value="Reset" class="tmbl merah">
		</form>
		<?php
			$a = 0;
			$hasil = ambil('paket', "ORDER BY kode_soal ASC");
			while($row=mysqli_fetch_assoc($hasil)){
				$a++;
				$ok = $row['kode_soal'];
				if($a===1){
					$ks[$a] = $ok;
				}elseif($a>1){
					$b = $a - 1;
					if($ok!=$ks[$b]){
						$ks[$a] = $ok;
					}else{
						$a--;
					}
				}
			}
			if($a>0){
			$o = 0;
			foreach($ks as $kd) :
			$hasil = ambil('paket', "WHERE kode_soal='$kd'");
			while($row=mysqli_fetch_assoc($hasil)){
				foreach($did as $dtl) :
					if($dtl===$row['id_detail']){
						$o++;
					}
				endforeach;
			}
			endforeach;
			if($o>0){
				foreach($ks as $kd) :
				$o = 0;
				$hasil = ambil('paket', "WHERE kode_soal='$kd'");
				while($row=mysqli_fetch_assoc($hasil)){
					foreach($did as $dtl) :
						if($dtl===$row['id_detail']){
							$o++;
						}
					endforeach;
				}
				if($o>=1){
					echo '<a href="tulis.php?v='.$v.'&q='.$q.'&p='.$kd.'">'.$kd.'</a> / ';
				}
				endforeach;
				}
			} ?>
		<?php 
		if(isset($_GET['p']) AND !empty($_GET['p'])){
			$p = $_GET['p'];
			$o = 0;
			$hasil = ambil('paket', "WHERE kode_soal='$p'");
			while($row=mysqli_fetch_assoc($hasil)){				
				foreach($idd as $num) :
					if($row['id_detail']===$num){
						$o++;
					}
				endforeach;
			}
			echo '<a href="tulis.php?v='.$v.'&q='.$q.'&dp='.$p.'">Hapus</a>';	
			if(isset($_GET['dn']) AND !empty($_GET['dn'])){
				$dn = $_GET['dn'];
				$a = 0;
				$hasil = ambil('soal', "WHERE id='$dn'");
				while($row=mysqli_fetch_assoc($hasil)){
					$dp = $row['kode_soal'];
				}
				$hasil = ambil('paket', "WHERE kode_soal='$dp'");
				while($row=mysqli_fetch_assoc($hasil)){			
					foreach($idd as $num) :
						if($row['id_detail']===$num AND $row['status']==='0'){
							$a++;
						}
					endforeach;
				}
				if($a===0){
					alert('Soal gagal dihapus atau Soal sedang dikerjakan', "tulis.php?v=$v&q=$q&p=$p&n=$dn");
				}else{
					if(hapus('soal', "WHERE id='$dn'")){
						alert('Soal dihapus', "tulis.php?v=$v&q=$q&p=$p");
					}
				}
			}
			if($o>0){
		?>
		<div class="no">
			<?php
				$a = 0;
				$data[1] = '';
				$data[2] = '';
				$data[3] = '';
				$data[4] = '';
				$data[5] = '';
				$data[6] = '';
				$data[7] = '';
				$hasil = ambil('soal', "WHERE kode_soal='$p'");
				while($row=mysqli_fetch_assoc($hasil)){
					$a++;
					$num = $row['id'];
					$bg = 'hijau';
					if(isset($_GET['n']) AND !empty($_GET['n'])){
						$n = $_GET['n'];
						$b = 0;
						$result = ambil('soal', "WHERE kode_soal='$p' AND id='$n'");
						while($br=mysqli_fetch_assoc($result)){
							$b++;
							$data[1] = $br['text'];
							$data[2] = $br['a'];
							$data[3] = $br['b'];
							$data[4] = $br['c'];
							$data[5] = $br['d'];
							$data[6] = $br['e'];
							$data[7] = $br['benar'];						
						}
						if($b===0){
							header("refresh:0; url=tulis.php?v=$v&q=$q&p=$p");
						}elseif($n===$num){
							$bg = 'biru';
						}
					}
					echo '<a href="?v='.$v.'&q='.$q.'&p='.$p.'&n='.$num.'" class="tmbl '.$bg.' kiri">'.$a.'</a>';
				}
				$a++;
				if(!isset($_GET['n'])){
					echo '<a href="?v='.$v.'&q='.$q.'&p='.$p.'" class="tmbl biru kiri">'.$a.'</a>';
				}else{
					echo '<a href="?v='.$v.'&q='.$q.'&p='.$p.'" class="tmbl hijau kiri">'.$a.'</a>';
					echo '<a href="?v='.$v.'&q='.$q.'&p='.$p.'&dn='.$num.'" class="tmbl biru kiri">Hapus</a>';
				}
			?>
		</div>
		<!-- Tulis Soal -->
		<div class="nomorsoal">
			<?php if(isset($_GET['n']) AND !empty($_GET['n'])){ ?>
			<form action="tulis.php?v=<?=$v?>&q=<?=$q?>&p=<?=$p?>&n=<?=$n?>" method="post" enctype="multipart/form-data">
			<?php }else{ ?>
			<form action="tulis.php?v=<?=$v?>&q=<?=$q?>&p=<?=$p?>" method="post" enctype="multipart/form-data">
			<?php } ?>
					<div class="no">
						<?php if(isset($_GET['n']) AND !empty($_GET['n'])){ ?>
						<input type="hidden" name="id" class="kiri" value="<?=$n?>" required>
						<?php }else{ ?>
						<input type="hidden" name="ks" class="kiri" value="<?=$p?>" required>
						<?php } ?>
						<label for="" class="bold">Tulis Soal</label>
						<textarea name="text" class="full" cols="30" rows="10" placeholder="Tulis Soal..." required autofocus><?=$data[1]?></textarea>
					</div>
					<label for="" class="bold">Jawaban</label>

				<div class="jawab">
					<div class="opsibar">
						<a class="kiri tmbl putih">A</a>
						<input type="text" name="A" class="kiri" value="<?=$data[2]?>" required>
					</div>
					<div class="opsibar">
						<a class="kiri tmbl putih">B</a>
						<input type="text" name="B" class="kiri" value="<?=$data[3]?>" required>
					</div>
					<div class="opsibar">
						<a class="kiri tmbl putih">C</a>
						<input type="text" name="C" class="kiri" value="<?=$data[4]?>" required>
					</div>
					<div class="opsibar">
						<a class="kiri tmbl putih">D</a>
						<input type="text" name="D" class="kiri" value="<?=$data[5]?>" required>
					</div>
					<div class="opsibar">
						<a class="kiri tmbl putih">E</a>
						<input type="text" name="E" class="kiri" value="<?=$data[6]?>" required>
					</div>
				</div>
				<label for="" class="bold">Jawaban Benar</label>
				<select name="benar" class="f20" required>
					<?php
						$benar = $data[7];
						function ckl($i){
							global $benar;
							if($i===$benar){
								echo 'selected';
							}else{
								echo '';
							}
						}
					?>
					<?php $i='A'; ?>
					<option value="A" <?=ckl($i);?>>A</option>
					<?php $i='B'; ?>
					<option value="B" <?=ckl($i);?>>B</option>
					<?php $i='C'; ?>
					<option value="C" <?=ckl($i);?>>C</option>
					<?php $i='D'; ?>
					<option value="D" <?=ckl($i);?>>D</option>
					<?php $i='E'; ?>
					<option value="E" <?=ckl($i);?>>E</option>
				</select>
				<label for=""></label>
				<label for="">
					<?php if(isset($_GET['n']) AND !empty($_GET['n'])){ ?>
					<input type="submit" name="es" value="Edit Soal" class="tmbl hijau">
					<?php }else{ ?>
					<input type="submit" name="ss" value="Submit Soal" class="tmbl hijau">
					<?php } ?>
					<input type="reset" value="Reset" class="tmbl merah">
				</label>
			</form>
		</div>
		<?php
			}else{
				header("refresh:0; url=tulis.php?v=$v&q=$q");
			}
		}else{
			$x = 0;			
			foreach($idd as $num) :
				$hasil = ambil('paket', "WHERE id_detail='$num'");
				while($row=mysqli_fetch_assoc($hasil)){
					$x++;
					$pkt[$x] = $row['kode_soal'];
				}
			endforeach;
			if($x>0){
				header("refresh:0; url=tulis.php?v=$v&q=$q&p=$pkt[1]");
			}
		}
		?>
	</div>

</div>


<?php 
	}else{
		alert('Tidak bisa membuat soal', "soal.php");
	}
}else{
	alert('Tidak bisa mengakses halaman', "soal.php");
}
require_once 'view/footer.php';
 ?>