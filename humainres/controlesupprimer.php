<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menurapport;
require_once('tests.php');

if (isset($_POST['accepter'])){
	
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  

	//$req = mysql_query('INSERT INTO mla.EventSauv (SELECT * FROM mla.Event WHERE `event_id` = "'.$_POST['eventid'].' " )') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());  

	$req2 = mysql_query('DELETE FROM `Event` WHERE  `event_id` = "'.$_POST['eventid'].' " ') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

	echo '<div><fieldset style="width:400px;height:200px;background-color:#EEE;position:relative;top:30px;margin:0 auto;"><legend>Confirmation</legend><h1>Votre événement à bien été supprimer:</h1><br/><a href="controle.php">Retourner au menu personel</a></fieldset></div></body></html>';
}else{
	echo '<h1>ERREUR !!!!<br/>Veuillez cocher la case de validation de suppression de l\'événement</h1></body></html>';
}
?>
