<?php
$title='Soal';
require_once 'view/header.php';
$idu = $_SESSION['user']['id'];
$hasil = ambil('siswa',"WHERE id_user='$idu'");
while($row=mysqli_fetch_assoc($hasil)){
	$idk = $row['id_kelas'];
	$ids = $row['id'];
}
$hasil = ambil('kelas',"WHERE id='$idk'");
while($row=mysqli_fetch_assoc($hasil)){
	if($row['tingkat']==='X'){
		$bg = 'merah';
	}elseif($row['tingkat']==='XI'){
		$bg = 'hijau';
	}elseif($row['tingkat']==='XII'){
		$bg = 'biru';
	}
}
if(isset($_SESSION['user']['soal']['paket'])){
	//$_SESSION['user']['soal']['paket'];
	//$_SESSION['user']['soal']['num'];
	//$_SESSION['user']['jawab'][$i]['id'];
	//$_SESSION['user']['jawab'][$i]['key'];
	//print_r($_SESSION['user']['soal']);
	if(!isset($_SESSION['user']['soal']['num']) AND empty($_SESSION['user']['soal']['num'])){
		$_SESSION['user']['soal']['num'] = '1';
	}else{
		$no = $_SESSION['user']['soal']['num'];
	}
	if(isset($_POST['num'])){
		$num = $_POST['num'];
		$_SESSION['user']['soal']['num'] = $num;
		$no = $_SESSION['user']['soal']['num'];
	}
	if(isset($_POST['prev'])){
		$num = intval($no)-1;
		$_SESSION['user']['soal']['num'] = $num;
		$no = $_SESSION['user']['soal']['num'];
	}
	if(isset($_POST['next'])){
		$num = intval($no)+1;
		$_SESSION['user']['soal']['num'] = $num;
		$no = $_SESSION['user']['soal']['num'];
	}
	$paket  = $_SESSION['user']['soal']['paket'];
	$hasil = ambil('paket', "WHERE id='$paket' ORDER BY id ASC");
	while($row=mysqli_fetch_assoc($hasil)){
		$ip = $row['kode_soal'];
	}
	$i = 0;
	$hasil = ambil('soal', "WHERE kode_soal='$ip' ORDER BY id ASC");
	while($row=mysqli_fetch_assoc($hasil)){
		$i++;
		$sid[$i] = $row['id'];
		$ss[$i] = $row['text'];
		$sj[$i]['a'] = $row['a'];
		$sj[$i]['b'] = $row['b'];
		$sj[$i]['c'] = $row['c'];
		$sj[$i]['d'] = $row['d'];
		$sj[$i]['e'] = $row['e'];
		$sj[$i]['benar'] = $row['benar'];
	}
	$i = 0;
	if(isset($_POST['jawab'])){
		foreach($sid as $id):
			$i++;
			if($i===intval($no)){
				$_SESSION['user']['jawab'][$i]['id'] = $id;
				$_SESSION['user']['jawab'][$i]['key'] = $_POST['jawab'];
			}
		endforeach;
		header("refresh:0;");
	}
	if(isset($_POST['selesai'])){
		$i = 0;
		foreach($sid as $id):
			$i++;
		endforeach;
		$o = 0;
		for($x=1;$x<=$i;$x++){
			if(isset($_SESSION['user']['jawab'][$x]['id']) AND !empty($_SESSION['user']['jawab'][$x]['id']) AND isset($_SESSION['user']['jawab'][$x]['key']) AND !empty($_SESSION['user']['jawab'][$x]['key'])){
				if($_SESSION['user']['jawab'][$x]['key']===$sj[$x]['benar']){
					$o++;					
				}
			}
		}
		$mp = 100 / $i;
		$skor = $o * $mp;
		unset($_SESSION['user']['soal']);
		unset($_SESSION['user']['jawab']);
		$hasil = ambil('paket', "WHERE id='$paket'");
		while($row=mysqli_fetch_assoc($hasil)){
			$idd = $row['id_detail'];
		}
		$field = 'id_siswa, id_detail, id_paket, skor';
		$value = "'$ids', '$idd', '$paket', '$skor'";
		if(tambah('hasil', $field, $value)){
			alert('Perolehan Nilai anda : '.$skor.'', 'index.php');
		}
	}
}
 ?>

<div class="konten">

	<div class="soal">
		<?php
			if(isset($_POST['mode'])){
				$q = $_POST['q'];
				$paket = $_POST['mode'];
				$i = 0;
				$hasil = ambil('paket', "WHERE id_detail='$q'");
				while($row=mysqli_fetch_assoc($hasil)){
					if($row['kode_soal']===$paket){
						$i++;
						$idp = $row['id'];
					}
				}
				if($i===1){
					$_SESSION['user']['soal']['paket'] = $idp;
					$_SESSION['user']['soal']['num'] = '1';
					alert('Selamat Mengerjakan!', '');
				}else{
					alert('Tidak bisa mengakses', '');
				}
			}elseif(isset($_SESSION['user']['soal']['paket'])){
				$i = 0;
		?>
		<div class="nomorsoal">
			<form action="soal.php" method="post" class="full">
		<?php
				foreach($sid as $id):
				$i++;
				if(isset($_SESSION['user']['soal']['num']) AND !empty($_SESSION['user']['soal']['num'])){
					if($i===intval($no)){
						$sty = $bg;
					}else{
						$sty = 'putih';
					}
				}
		?>
				<input type="submit" name="num" value="<?=$i?>" class="tmbl <?=$sty?>">
		<?php
				endforeach;
				$o = 0;
				for($x=1;$x<=$i;$x++){
					if(isset($_SESSION['user']['jawab'][$x]['id']) AND !empty($_SESSION['user']['jawab'][$x]['id']) AND isset($_SESSION['user']['jawab'][$x]['key']) AND !empty($_SESSION['user']['jawab'][$x]['key'])){
						$o++;
					}
				}
				if($i===$o){
		?>
			<input type="submit" name="selesai" value="Selesai" class="tmbl <?=$bg?>">
		<?php
				}else{
		?>
			<a class="tmbl putih">Selesai</a>
		<?php
				}
		?>
			</form>
		</div>
		<?php
				if(isset($_SESSION['user']['soal']['num']) AND !empty($_SESSION['user']['soal']['num'])){
					if(isset($_SESSION['user']['jawab'][$no]['id']) AND !empty($_SESSION['user']['jawab'][$no]['id']) AND isset($_SESSION['user']['jawab'][$no]['key']) AND !empty($_SESSION['user']['jawab'][$no]['key'])){
						$key  = $_SESSION['user']['jawab'][$no]['key'];
						function chk($jwb){
							global $key;
							global $bg;
							if($jwb===$key){
								$sty = $bg;
							}else{
								$sty = 'putih';
							}
							return $sty;
						}
		?>
		<div class="judulsoal padding" style="font-size: 1.3em;">
			<?=$no?>. <?=$ss[$no]?>
		</div>
		<div class="jawaban">
			<form action="soal.php" method="post" class="full">
				<label for=""></label>
				<input type="submit" name="jawab" value="A" class="tmbl <?=chk('A')?>"> <?=$sj[$i]['a']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="B" class="tmbl <?=chk('B')?>"> <?=$sj[$i]['b']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="C" class="tmbl <?=chk('C')?>"> <?=$sj[$i]['c']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="D" class="tmbl <?=chk('D')?>"> <?=$sj[$i]['d']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="E" class="tmbl <?=chk('E')?>"> <?=$sj[$i]['e']?>.
			</form>
		</div>
		<?php
					}else{
		?>
		<div class="judulsoal padding" style="font-size: 1.3em;">
			<?=$no?>. <?=$ss[$no]?>
		</div>
		<div class="jawaban">
			<form action="soal.php" method="post" class="full">
				<label for=""></label>
				<input type="submit" name="jawab" value="A" class="tmbl putih"> <?=$sj[$i]['a']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="B" class="tmbl putih"> <?=$sj[$i]['b']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="C" class="tmbl putih"> <?=$sj[$i]['c']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="D" class="tmbl putih"> <?=$sj[$i]['d']?>.
				<label for=""></label>
				<input type="submit" name="jawab" value="E" class="tmbl putih"> <?=$sj[$i]['e']?>.
			</form>
		</div>
		<?php
					}
		?>
		<div class="nomorsoal">
			<label for=""></label>
			<form action="soal.php" method="post" class="full">
		<?php
					if(intval($no)>1){
		?>
				<input type="submit" name="prev" value="Sebelumnya" class="tmbl <?=$bg?> kiri">
		<?php
					}
					$i = 0;
					foreach($sid as $id):
						$i++;
					endforeach;
					if(intval($no)<$i){
		?>
				<input type="submit" name="next" value="Selanjutnya" class="tmbl <?=$bg?> kanan">
		<?php
					}
		?>
			</form>
		</div>
		<?php
				}
			}elseif(isset($_POST['soal'])){
				$q = $_POST['q'];
				$soal = $_POST['soal'];
				$i = 0;
				$o = 0;
				$hasil = ambil('paket', "WHERE id_detail='$q' AND kategori='$soal' AND status='1'");
		?>
		<div class="nomorsoal">
			<form action="soal.php" method="post" class="full">
				<input type="hidden" name="q" value="<?=$q?>">
			<?php
				while($row=mysqli_fetch_assoc($hasil)){
					$paket = $row['kode_soal'];
					$result = ambil('soal', "WHERE kode_soal='$paket'");
					while($br=mysqli_fetch_assoc($result)){
						$i++;
					}
					$sid = $row['id'];
					$result = ambil('hasil', "WHERE id_paket='$sid' AND id_detail='$q' AND id_siswa='$ids'");
					while($br=mysqli_fetch_assoc($result)){
						$o++;
					}
				}
				if($i>0 AND $o===0){
			?>
				<input type="submit" name="mode" value="<?=$paket?>" onclick="return confirm('Perhatian! Anda tidak diizinkan untuk mengakses halaman lain sampai anda selesai mengerjakan atau Logout')" class="tmbl <?=$bg?>">
			<?php
				}
			?>
			</form>
		</div>
		<?php
			}else{
		?>
		<div class="judulsoal padding" style="font-size: 1.3em;">
			<label for=""></label>
			Anda dapat mulai mengerjakan soal - soal Ujian maupun Latihan melalui halaman materi yang telah disediakan
		</div>
		<?php } ?>
	</div>

</div>


<?php
require_once 'view/footer.php';
 ?>