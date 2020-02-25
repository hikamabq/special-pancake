<?php
$title='Forum';

require_once 'view/header.php';
$id = $_SESSION['user']['id'];

$hah = mysqli_query($link,"SELECT * FROM kelas");
while($huh = mysqli_fetch_assoc($hah)){
  $dapet = $huh['tingkat'].' '.$huh['jurusan'].' '.$huh['kelas'];
}

$menu = mysqli_query($link,"SELECT * FROM forum,forum_akses,siswa WHERE forum.id = forum_akses.id_forum AND forum_akses.id_user = siswa.id_user AND siswa.id_user = $id");

if(isset($_GET['w']) AND isset($_GET['id'])){
  $content = $_GET['w'];
  $id_user = $_GET['id'];
  $check = mysqli_query($link,"SELECT * FROM forum_akses WHERE id_user = $id_user AND id_forum = $content");
  if(mysqli_num_rows($check) == 0){
	  header('Location: forum.php');
  }
  $title_forum = mysqli_query($link,"SELECT * FROM forum WHERE id = $content");
  while($ts = mysqli_fetch_array($title_forum)){
    $title = $ts[1];
    $sub   = $ts[2];
  }
  $forum = mysqli_query($link,"SELECT * FROM forum,forum_akses,forum_chat,user WHERE forum_akses.id_forum = forum.id AND forum_chat.id_akses = forum_akses.id AND forum_akses.id_user = user.id AND forum_akses.id_forum = $content ORDER BY forum_chat.id ASC");
}else{
	header("refresh:0; url=forum.php?w=2&id=$id");
}

if(isset($_POST['pesan'])){
  $chat   = $_POST['pesan'];
  $id_u   = $_GET['id'];
  $where  = $_GET['w'];
  $from = mysqli_query($link,"SELECT * FROM forum_akses WHERE id_user = $id AND id_forum = $where");
  while($to = mysqli_fetch_assoc($from)){
    $getid = $to['id'];
  }
  mysqli_query($link,"INSERT INTO forum_chat(id_akses,chat) VALUES($getid,'$chat')");
  header('Location: forum.php?w='.$_GET['w'].'&id='.$_GET['id']);
}
 ?>

<div class="bungkus">

	<div class="sideus">
		<div class="atasside">
			<h2>Forum Sekolah</h2>
		</div>
    <?php while($bub = mysqli_fetch_array($menu)){ 
	$pl = explode(" ",$bub[2]);
	?>
	<?php if($pl[0] == 'Admin'){ ?>
	<a href="?w=<?=$bub[0]?>&id=<?=$id?>"><i class="fa fa-comment"></i> <?=$bub[1]?></a>
	<?php }else{ ?>
    <a href="?w=<?=$bub[0]?>&id=<?=$id?>"><i class="fa fa-comment"></i> <?=$pl[0]?></a>
    <?php }} ?>
	</div>
	<div class="mainus">
    <?php if(isset($_GET['w'])){ ?>
		<div class="judul">
			<h2><?=$title?> <a href="" class="kanan tmbl putih"><i class="fa fa-refresh"></i></a></h2>
		</div>
		<div class="mainisi">
			<center><?=$sub?></center>
			<div class="chat">
        <?php while($isi = mysqli_fetch_assoc($forum)){ ?>
          <?php if($isi['id_user'] != $_SESSION['user']['id']){ ?>
    				<div class="postchat">
    					<b><?=$isi['nama']?></b>
    					<p><?=$isi['chat']?></p>
    				</div>
          <?php }else{ ?>
    				<div class="postadmin">
    					<b><?=$isi['nama']?></b>
    					<p><?=$isi['chat']?></p>
    				</div>
          <?php } ?>
        <?php } ?>
			</div>
			<div class="chatketik">
				<form method="post">
            <input type="text" name="pesan" class="f80 kiri" placeholder="Ketik pesan..." autofocus>
					<input type="submit" name="kirim" value="Kirim" class="f20 kiri hijau">
				</form>
			</div>
		</div>
    <?php } ?>
	</div>

</div>

<?php
require_once 'view/header.php';
 ?>
