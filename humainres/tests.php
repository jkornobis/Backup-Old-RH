<?php
if(isset($_POST['chmois']) && isset($_POST['channee'])){
	$moisactuel = $_POST['chmois'];
	$anneeactuel = $_POST['channee'];
}else{
	if(isset($_GET['chmois']) && isset($_GET['channee'])){
		$moisactuel = $_GET['chmois'];
		$anneeactuel = $_GET['channee'];
	}else{
		if(isset($_GET['channee'])){
			$anneeactuel = $_GET['channee'];
			$moisactuel = date("m");
		}else{
			$anneeactuel = date("Y");
			$moisactuel = date("m");
		}
		if(isset ($_GET['chmois'])){
			$moisactuel = $_GET['chmois'];
			$anneeactuel = date("Y");

		}else{
			$moisactuel = date("m");
			$anneeactuel = date("Y");
		}
	}
}
if (isset($_GET['chjour']) ){
	$jour1 = $_GET['chjour'];
	$jour2 = $_GET['chjour']+1;
}else{
	$jour1 = '01';
	$jour2 = '31';
}

$moisactuelmot = array( 'mois', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

$utilisateurchoix = $utilisateur = $_GET['chuti'];
$eventid = $_GET['eventid'];

if ( isset($_GET['nomsession']) ){
	$nomsession =  $_GET['nomsession'];
	$toto = 'ceci est un acces administrateur !!!!!!!!!!!!!!!!!!!!!';
}
?>
