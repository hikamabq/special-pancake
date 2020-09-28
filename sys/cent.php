<?php

function conn(){
	$link=mysqli_connect("localhost","mysql","secret","seko_nol") or die ("Connection Failed!");
	return $link;
}

function run($ok){
	global $link;
	if(mysqli_query($link, $ok))return true;
	else return false;
}

function login($data){
	global $link;
	$ok = "SELECT * FROM user WHERE username='$data[1]' AND password='$data[2]'";
	$result = mysqli_query($link, $ok);
	if(mysqli_num_rows($result)===1)return true;
	else return false;
}

function sta($data){
	global $link;
	$ok = "SELECT * FROM user WHERE username='$data[1]' AND password='$data[2]'";
	$result = mysqli_query($link, $ok);
	return $result;
}

function ambil($tbl, $prm){
	global $link;
	$ok = "SELECT * FROM $tbl $prm";
	$result = mysqli_query($link, $ok);
	return $result;
}

function tambah($tbl, $field, $value){
	global $link;
	$ok = "INSERT INTO $tbl ($field) VALUES ($value)";
	return run($ok);
}

function edit($tbl, $prm){
	global $link;
	$ok = "UPDATE $tbl SET $prm";
	return run($ok);
}

function hapus($tbl, $data){
	global $link;
	$ok = "DELETE FROM $tbl $data";
	return run($ok);
}

function alert($txt, $url){
	echo '<script type="text/javascript">';
	echo 'alert("'.$txt.'");';
	echo 'window.location="'.$url.'";';
	echo '</script>';
	return true;
}
?>