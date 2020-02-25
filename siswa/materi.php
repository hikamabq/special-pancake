<?php
$title='Materi';
require_once 'view/header.php';
$idu = $_SESSION['user']['id'];
$hasil = ambil('siswa',"WHERE id_user='$idu'");
while($row=mysqli_fetch_assoc($hasil)){
	$ids = $row['id'];
	$idk = $row['id_kelas'];
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
$a = 0;
$hasil = ambil('detail',"WHERE id_kelas='$idk' ORDER BY id_mapel");
while($row=mysqli_fetch_assoc($hasil)){
	$a++;
	$did[$a] = $row['id'];
	$didm[$did[$a]] = $row['id_mapel'];
	$didg[$did[$a]] = $row['id_guru'];
}
$b = 0;
$hasil = ambil('mapel',"");
while($row=mysqli_fetch_assoc($hasil)){
	$b++;
	$idm = $row['id'];
	$mapel[$idm] = $row['nama'];
}
if(!isset($_GET['q']) AND $a>0){
	header("refresh:0; url=materi.php?q=$did[1]");
}elseif(isset($_GET['q'])){
	$o = 0;
	$q = $_GET['q'];
	foreach($did as $idk) :
		if($q===$idk){
			$o++;
		}
	endforeach;
	if($a===0){
		header("refresh:0; url=materi.php");
	}elseif($o===0){
		if(isset($_GET['m'])){
			$m = $_GET['m'];
			header("refresh:0; url=materi.php?q=$did[1]&m=$m");
		}else{
			header("refresh:0; url=materi.php?q=$did[1]");
		}
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
		if(isset($_GET['q'])){
			$i = 0;
			foreach($did as $idk) :
				$i++;
		?>
		<a href="materi.php?q=<?=$idk?>"><i class="fa fa-tag"></i> <?=$mapel[$didm[$idk]]?></a>
		<?php endforeach; } ?>
	</div>
	<div class="mainus">
		<?php
		if(isset($_GET['q'])){
			$q = $_GET['q'];
		?>
		<div class="judul">		
			<h2><?=$mapel[$didm[$q]]?></h2>
			<?php
				$i = 0;
				$hasil = ambil('materi',"WHERE id_detail='$q'");
				while($row=mysqli_fetch_assoc($hasil)){
					$i++;
					$mid[$i] = $row['id'];
					$ket = $row['keterangan'];
					if(isset($_GET['m'])){
						$m = $_GET['m'];
						if($mid[$i]===$m){
							$sty = $bg;
						}else{
							$sty = 'putih';
						}
					}
			?>
			<a href="materi.php?q=<?=$q?>&m=<?=$mid[$i]?>" class="tmbl <?=$sty?>"><?=$ket?></a>
			<?php } ?>
			<form action="soal.php" method="post" class="full">
				<input type="hidden" name="q" value="<?=$q?>">
			<?php
				$i = 0;
				$o = 0;
				$hasil = ambil('paket', "WHERE id_detail='$q' AND kategori='Latihan' AND status='1'");
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
				<input type="submit" name="soal" value="Latihan" onclick="return confirm('Apakah anda yakin? Anda tidak akan dapat mengakses halaman lain sampai anda selesai mengerjakan atau Logout')" class="tmbl putih">
			<?php } ?>
			<?php
				$i = 0;
				$o = 0;
				$hasil = ambil('paket', "WHERE id_detail='$q' AND kategori='Ujian' AND status='1'");
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
				<input type="submit" name="soal" value="Ujian" onclick="return confirm('Apakah anda yakin? Anda tidak akan dapat mengakses halaman lain sampai anda selesai mengerjakan atau Logout')" class="tmbl putih">
			<?php } ?>
			</form>
		</div>
		<?php
			if(isset($_GET['m'])){
				$m = $_GET['m'];
				$i = 0;
				$o = 0;
				$hasil = ambil('materi',"WHERE id='$m'");
				while($row=mysqli_fetch_assoc($hasil)){
					$i++;
					$ket = $row['keterangan'];
					$link = $row['link'];
					foreach($did as $num):
						if($num===$row['id_detail']){
							$o++;
						}
					endforeach;
				}
				if($o===0){
					alert("Tidak bisa mengakses","materi.php?q=$q");
				}
		?>
		<div class="mainisi">
			<center style="padding: 10px;">Materi - <?=$ket?></center>
			<div class="pdf">
				<object data="../assets/data/materi/<?=$link?>.pdf" type="application/pdf" width="100%" height="100%">
				  <!-- <p>Alternative text - include a link <a href="matematika.pdf">to the PDF!</a></p> -->
				</object>
			</div>
		</div>
		<?php
			}else{
				$i = 0;
				$hasil = ambil('materi',"WHERE id_detail='$q'");
				while($row=mysqli_fetch_assoc($hasil)){
					$i++;
				}
				if($i===0){
					echo '<div class="mainisi"><center style="padding: 10px;">Data Kosong!</center></div>';
				}else{
					header("refresh:0; url=materi.php?q=$q&m=$mid[1]");
				}
			}
		}else{ ?>
		<div class="judul">		
			<h2>Data Kosong</h2>
		</div>
		<div class="mainisi">
			<center style="padding: 10px;">Data Kosong</center>
		</div>
	</div>
	<?php } ?>
</div>

<?php
require_once 'view/footer.php';
 ?>