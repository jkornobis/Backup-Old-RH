<?php
require_once('config.php');

if($rh_formation != "fait"){
$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
mysql_select_db("$PARAM_nom_bd",$db);  
mysql_set_charset("utf8", $db);

$req = mysql_query('
	UPDATE `UserObmRH`, `UserObm`
	SET `rh_formation` = "fait"
	WHERE `userobm_id` = `userobmrh_id`
	AND `userobm_id` = "'.$utilisateur.'"
;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}

session_destroy();
unset($_SESSION);
header("location:/obm.php");
?>
