<?php
session_start();
session_unset();
session_destroy();
if(isset($_GET['is'])){
	$is = $_GET['is'];
	if($is==='Guru' OR $is==='Siswa'){
		$is = '?is='.$is;
	}else{
		$is = '?is=Siswa';
	}
}else{
	$is = '';
}
header("refresh:0; url=index.php$is");
?>