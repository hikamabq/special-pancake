<?php
$title='Forum';
require_once 'view/header.php';
$id = $_SESSION['user']['id'];

$forum = mysqli_query($link,"SELECT * FROM forum");

if(isset($_GET['w']) AND isset($_GET['id'])){
  $where = $_GET['w'];
  $forum = mysqli_query($link,"SELECT * FROM forum,forum_akses,forum_chat,user WHERE forum_akses.id_forum = forum.id AND forum_chat.id_akses = forum_akses.id AND forum_akses.id_user = user.id AND forum_akses.id_forum = $where ORDER BY forum_chat.id ASC");
  if($where == 1){$title = 'Informasi Sekolah';}elseif($where == 2){$title = 'Informasi Kesiswaan';}
}else{
	header("refresh:0; url=forum.php?w=1&id=$id");
}

if(isset($_POST['kirim'])){
  $pesan = $_POST['pesan'];
  $id = $_GET['id'];
  $where = $_GET['w'];

  $from = mysqli_query($link,"SELECT * FROM forum_akses WHERE id_user = $id AND id_forum = $where");
  while($to = mysqli_fetch_assoc($from)){
    $getid = $to['id'];
  }
  mysqli_query($link,"INSERT INTO forum_chat(id_akses,chat) VALUES($getid,'$pesan')");
  header('Location: forum.php?w='.$where.'&id='.$id);
  }

?>

<div class="main">
  <?php require_once 'view/menu.php'; ?>
	<div class="isi">
      <div class="kop">
        <a href="?w=1&id=<?=$id?>" class="tmbl putih">Informasi Sekolah</a>
        <a href="?w=2&id=<?=$id?>" class="tmbl putih">Informasi Kesiswaan</a>
      </div>
      <?php if(isset($_GET['w'])){ ?>
      <div class="mainisi">
        <center><?=$title?></center>
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
require_once 'view/footer.php';
 ?>
