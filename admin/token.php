<?php
$title = 'Token';
$tbl = 'token';
require_once 'view/header.php';
if(isset($_POST['submit'])){
	$data[0] = $_POST['token'];
	$data[1] = $_POST['jml'];
	$data[2] = $_POST['status'];
	$data[3] = $_POST['ket'];
	$value = "'$data[0]', '$data[1]', '$data[2]', '$data[3]'";
	$field = 'token, jumlah, status, ket';
	if(tambah($tbl, $field, $value)){
		unset($_SESSION['admin']['token']);
		$_SESSION['admin']['token'] = uniqid('');
		header("refresh:0; url=token.php");
	}
}

if(isset($_GET['q'])){
	$q = $_GET['q'];
	$id = "WHERE id='$q'";
	if(hapus($tbl, $id)){
		header("refresh:0; url=token.php");
	}
}
 ?>

 <div class="main">
	<?php require_once 'view/menu.php'; ?>
	<div class="isi">

		<div class="kopgen">
			GENERATE TOKEN
		</div>

		<div class="full form-token">
			<h4 class="kiri" style="padding: 10px;">Token</h4>
			<form action="token.php" method="post" class="kanan">
				<input type="text" name="token" placeholder="Token" value="<?=$token?>" readonly>
				<input type="number" min="1" name="jml" placeholder="Jumlah token" autofocus required>
				<select name="status">
					<option value="Guru">Guru</option>
					<option value="Siswa">Siswa</option>
				</select>
				<input type="text" name="ket" placeholder="Ket" value="" placeholder="Kelas">
				<input type="submit" name="submit" value="Generate" class="hijau">
			</form>
		</div>

		<div class="table">
			<table>
				<thead class="hijau">
					<tr>
						<td width="30">No</td>
						<td>Status</td>
						<td align="center">Token</td>
						<td align="center">Jumlah</td>
						<td align="center" width="200">Exp</td>
						<td align="center" width="200">Ket</td>
						<td align="center" width="200">Hapus</td>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 0;
						$hasil = ambil('token','');
						while($row=mysqli_fetch_assoc($hasil)){
							$i++;
							$date=date_create($row['exp']);
							$td = date('z'); //TODAY
							$exp = date_format($date,"z") + 7;
							$id = $row['id'];
							if($exp <= $td){
								alert("Token ".$row['token']." (".$row['status'].") sudah kadaluarsa", "token.php?q=$id");
							}
					?>
					<tr>
						<td><?=$i?></td>
						<td><?=$row['status']?></td>
						<td align="center"><?=$row['token']?></td>
						<td align="center"><?=$row['jumlah']?></td>
						<td align="center"><?=$exp-$td?> Hari lagi</td>
						<td align="center"><?=$row['ket']?></td>
						<td align="center">
							<a href="token.php?q=<?=$row['id']?>" class="tmbl merah">&#10005;</a>
						</td>
					</tr>
					<?php
						} if($i===0){
					?>
					<tr>
						<td align="center" colspan="7">Data Kosong!</td>
					</tr>
						<?php } ?>
				</tbody>
			</table>
		</div>

	</div>
</div>






<?php
require_once 'view/footer.php';
 ?>
