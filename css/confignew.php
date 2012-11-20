<?php
session_start();
if(isset($_SESSION['uti'])){
	$utilisateur = $_SESSION['uti'];
}else{
	$_SESSION['uti'] = $utilisateur = $_GET['uti'];
} 

$PARAM_hote='localhost'; // le chemin vers le serveur
$PARAM_nom_bd='mla'; // le nom de votre base de données
$PARAM_utilisateur='mlaobmmysql'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe='H864kE5XB2'; // mot de passe de l'utilisateur pour se connecter

$moisactuel = date("m");
$moisprochain = date("m") +1;
$anneeactuel = date("Y");

try{
	$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}catch(Exception $e){
	echo 'Une erreur est survenue lors de la connexion<br/><b>Veuillez contacter l\'administrateur du système</b>';
	die();
}

$creasession=$connexion->query('
	SELECT *
	FROM `UserObm`, `UserObmRH`,  `Domain`
	WHERE `userobm_id` = "'.$utilisateur.'"
	AND `userobmrh_id`= `userobm_id`
	AND  `userobm_domain_id` = `domain_id`
	
;') or die('Erreur SQL !<br/>'.$sql.'<br/>'.mysql_error());
$creasession->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $creasession->fetch())
{
	$_SESSION['user_nom'] = $ligne->userobm_firstname.' '.$ligne->userobm_lastname;
	$_SESSION['user_id'] = $ligne->userobm_id;
	$_SESSION['user_domain_id'] = $ligne->userobm_domain_id;
	$_SESSION['domain_description'] = $ligne->domain_description;
	$_SESSION['user_admin'] = $ligne->userobm_delegation_target;
	$_SESSION['user_archive'] = $ligne->userobm_archive;
	$_SESSION['user_droits'] = $ligne->userobm_perms; 
	$_SESSION['user_delegation'] = $ligne->userobm_delegation;
	
	$_SESSION['hpj'] = $ligne->temps_hpj;
	$_SESSION['userobm_statut'] = $ligne->userobm_statut;
	$_SESSION['rh_congepaye'] = $ligne->rh_congepaye;
	$_SESSION['rh_congesexcep'] = $ligne->rh_congesexcep;
	$_SESSION['rh_rtt'] = $ligne->rh_rtt;
	$_SESSION['rh_maladie'] = $ligne->rh_maladie;	
}

?>