<?php

if($_SESSION['droits'] == "Gestionnaire" || $_SESSION['droits'] == "administratif" || $nomsession == "admin admin"){
	$_SESSION['login'] = $nomsession;
	$accesadmin = '<b>Accès Admin:</b><ul><li><a href="../humainres/etat.php?nomsession='.$nomsession.'">Module Admin.</a></li></ul>';
}
if($_GET['deminfos'] == 1){
	$formation = true;
}else{
	if($rhformation != "fait" || $formation == true){
		$formation = true;
	}else{
		$formation = false;
	}
}

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

switch ($moisactuel){
	case '01': $moisactuelmot = 'Janvier'; break;
	case '02': $moisactuelmot = 'Février'; break;
	case '03': $moisactuelmot = 'Mars'; break;
	case '04': $moisactuelmot = 'Avril'; break;
	case '05': $moisactuelmot = 'Mai'; break;
	case '06': $moisactuelmot = 'Juin'; break;
	case '07': $moisactuelmot = 'Juillet'; break;
	case '08': $moisactuelmot = 'Aout'; break;
	case '09': $moisactuelmot = 'Septembre'; break;
	case '10': $moisactuelmot = 'Octobre'; break;
	case '11': $moisactuelmot = 'Novembre'; break;
	case '12': $moisactuelmot = 'Décembre'; break;
	case '13': $moisactuelmot = 'Année Entière'; break;
}
if (isset($_GET['chjour']) ){
	$jour1 = $_GET['chjour'];
	$jour2 = $_GET['chjour']+1;
}else{
	$jour1 = '01';
	$jour2 = '31';
}
?> 
