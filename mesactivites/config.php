<?php
session_start();
if(isset($_SESSION['uti'])){
	$utilisateur = $_SESSION['uti'];
}else{
	$_SESSION['uti'] = $utilisateur = $_GET['uti'];
} 

$PARAM_hote=''; // le chemin vers le serveur
$PARAM_nom_bd=''; // le nom de votre base de données
$PARAM_utilisateur=''; // nom d'utilisateur pour se connecter
$PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter

$moisactuel = date("m");
$moisprochain = date("m") +1;
$anneeactuel = date("Y");

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
}
try
{
	// On se connecte à MySQL
	$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}
$resultats=$connexion->query('
	SELECT *
	FROM `UserObm`, `UserObmRH`
	WHERE `userobm_id` = "'.$utilisateur.'"
	AND `userobmrh_id`= `userobm_id`
;');
$resultats->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultats->fetch() ) {
	$nomsession = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;
	$cvsession = $ligne->userobm_cv;
	$rhformation = $ligne->rh_formation;
	$userperms = $ligne->userobm_perms;
}
if($userperms == "administratif"){
	$_SESSION['login'] = $nomsession;
	$accesadmin = '<b>Accès Admin:</b><ul><li><a href="../humainres/etat.php?nomsession='.$nomsession.'">Module Admin.</a></li></ul>';
}
?>
