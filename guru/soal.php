<?php
$title='Soal';
require_once 'view/header.php';
 ?>

<div class="konten">

	<div class="soal">

		<div class="nomorsoal">
			<h1>Tulis Soal</h1>
		</div>
		
		<div class="kop-soal">
			<div class="k3 padding">
				<div class="kop-kelas merah">KELAS X</div>
				<?php
				$i = 0;
				if(!empty($dt[1])){
					foreach($dt[1] as $mapel) :
					$i++;
					$mpl[$i] = $mapel;
					if($i>1){
					$x = $i - 1;
					if($mpl[$x]===$mapel){
				?>
				<?php }else{
				?>
				<a href="tulis.php?v=<?=$mapel?>&q=X"><?=$mapel?></a>
				<?php
					} }else{
				?>
				<a href="tulis.php?v=<?=$mapel?>&q=X"><?=$mapel?></a>
				<?php
					}
					endforeach; }else{ ?> <a href=""> Anda tidak mengajar di Kelas X</a> <?php } ?>
			</div>
			<div class="k3 padding">
				<div class="kop-kelas hijau">KELAS XI</div>
				<?php
				$i = 0;
				if(!empty($dt[2])){
					foreach($dt[2] as $mapel) :
					$i++;
					$mpl[$i] = $mapel;
					if($i>1){
					$x = $i - 1;
					if($mpl[$x]===$mapel){
				?>
				<?php }else{
				?>
				<a href="tulis.php?v=<?=$mapel?>&q=XI"><?=$mapel?></a>
				<?php
					} }else{
				?>
				<a href="tulis.php?v=<?=$mapel?>&q=XI"><?=$mapel?></a>
				<?php
					}
					endforeach; }else{ ?> <a href=""> Anda tidak mengajar di Kelas XI</a> <?php } ?>
			</div>
			<div class="k3 padding">
				<div class="kop-kelas biru">KELAS XII</div>
				<?php
				$i = 0;
				if(!empty($dt[3])){
					foreach($dt[3] as $mapel) : 
					$i++;
					$mpl[$i] = $mapel;
					if($i>1){
					$x = $i - 1;
					if($mpl[$x]===$mapel){
				?>
				<?php }else{
				?>
				<a href="tulis.php?v=<?=$mapel?>&q=XII"><?=$mapel?></a>
				<?php
					} }else{
				?>
				<a href="tulis.php?v=<?=$mapel?>&q=XII"><?=$mapel?></a>
				<?php
					}
					endforeach; }else{ ?> <a href=""> Anda tidak mengajar di Kelas XII</a> <?php } ?>
			</div>
		</div>

	</div>

</div>


<?php 
require_once 'view/footer.php';
 ?>