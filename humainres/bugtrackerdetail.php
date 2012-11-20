<?php
require_once('config.php');
require_once('tests.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menubugtracker;

/*/////////////////////////////////////////////////////////
							Traitement Formulaire Popularité
/////////////////////////////////////////////////////////*/
if(isset($_POST['idreponse'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	
	$req = mysql_query('
			UPDATE `BugTrackerUser`
			SET `bugtrackeruser_dateupdate` = NOW( ) ,
				`bugtrackeruser_status` = "'.$_POST['etat'].'",
				`bugtrackeruser_admin` = "'.$_POST['formlogin'].'",
				`bugtrackeruser_reponse` = "'.$_POST['reponse'].'",
				`bugtrackeruser_partage` = "'.$_POST['partage'].'"
			WHERE `bugtrackeruser_idmessage` = "'.$_POST['idreponse'].'"
	;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}

/*/////////////////////////////////////////////////////////
								Traitement Tableaux Avis
/////////////////////////////////////////////////////////*/
if(isset($_POST['idreponse'])){
	$resultatsavis = $connexion->query('
		SELECT *
		FROM `BugTrackerUser`
		WHERE `bugtrackeruser_idmessage` = "'.$_POST['idreponse'].'"
	;');
	$messetat= '<h2 style="background:lightblue;width:1200px;">La Réponse est validée.</h2>';
}else{
	$resultatsavis = $connexion->query('
		SELECT *
		FROM `BugTrackerUser`
		WHERE `bugtrackeruser_idmessage` = "'.$_GET['idmessage'].'"
	;');
}
$avisfini = $avistotal = NULL;

$resultatsavis->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultatsavis->fetch() ) {

	$dateavisdebut = $ligne->bugtrackeruser_datebegin;
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($dateavisdebut ,"%d-%d-%d %d:%d:%d");
	if($jour < 10){$jour = '0'.$jour;}
	if($mois < 10){$mois = '0'.$mois;}
	if($h < 10){$h = '0'.$h;}
	if($m < 10){$m = '0'.$m;}
	$dateavisdebut = $jour.'/'.$mois.'/'.$annee.' '.$h.':'.$m;

	$annee = $mois = $jour = $h = $m = $s = NULL;

	$dateavisup = $ligne->bugtrackeruser_dateupdate;
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($dateavisup ,"%d-%d-%d %d:%d:%d");
	if($jour < 10){$jour = '0'.$jour;}
	if($mois < 10){$mois = '0'.$mois;}
	if($h < 10){$h = '0'.$h;}
	if($m < 10){$m = '0'.$m;}
	$dateavisup = $jour.'/'.$mois.'/'.$annee.' '.$h.':'.$m;

	$categoryavis	= $ligne->bugtrackeruser_category;
	$useravis =	$ligne->bugtrackeruser_userlogin;
	$titreavis = ucfirst($ligne->bugtrackeruser_titre);
	$messageavis = ucfirst($ligne->bugtrackeruser_message);
	$adminuser = $ligne->bugtrackeruser_admin;
	$idreponse = $ligne->bugtrackeruser_idmessage;
	$avisstatut = $ligne->bugtrackeruser_status;	

	switch($ligne->bugtrackeruser_status){
			case "0":
				$dateavis= '<b title="Non traité" style="color:black;">'.$dateavisdebut.'</b>';
				$avistotal = $avistotal + 1;
				$stylestatus = "color:black;";
				$valuesel = "0";
				$valuetext = "Non traité";
			break;
			case "5":
				$avisfini = $avisfini + "0.5";
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="En cours de traitement" style="color:#D30;">'.$dateavisup.'</b>';
				$stylestatus = "color:#D30;";
				$valuesel = "5";
				$valuetext = "En cours d'analyse";
			break;
			case "10":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Mise à Jour" style="color:#060;">'.$dateavisup.'</b>';
				$stylestatus = "color:#060;";
				$valuesel = "10";
				$valuetext = "Traité";
			break;
			case "15":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Refusé" style="color:#A00;">'.$dateavisup.'</b>';
				$stylestatus = "color:#A00;";
				$valuesel = "15";
				$valuetext = "Refusé";
			break;
	}
	$formreponse = '
		<form method="POST" action="bugtrackerdetail.php" style="text-align:left;">

			<input type="hidden" name="formlogin" id="formlogin" value="'.$_SESSION['login'].'"/>
			<input type="hidden" name="idreponse" id="idreponse" value="'.$idreponse.'"/>
			État:
			<select id="etat" name="etat" style="height:30px;">
				<option value="'.$valuesel.'">'.$valuetext.'</option>
				<option value="0">____________________</option>
				<option value="0">Non Traité</option>
				<option value="5">En cours d\'analyse</option>
				<option value="10">Traité</option>
				<option value="15">Refusé</option>
			</select><br/><br/>Partager à l\'ensemble des utilisateurs: 
			<select id="partage" name="partage" style="height:30px;">
				<option value="0">Non</option>
				<option value="1">Oui</option>
			</select> 
			<br/><i>(Bugs ou questions récurrent(e)s)</i><br/><br/>
			Message:<br/><textarea cols="60" rows="4" name="reponse" id="reponse"></textarea>
			<br/>valider: <br/>
			<button type="submit" style="text-align:left;margin:0px;padding:0px;position:relative;left:0px;" onclick="return(confirm(\'Etes-vous sûr de Répondre ainsi à cette avis ?\'));">Valider la réponse</button>
		</form>
	';

	$plususer = explode(",", $ligne->bugtrackeruser_plususer);
	for($i = 0;$i < 300; $i++){
		if($nomsession == $plususer[$i]){
			$plussoi = "1";
		}
	}
	
	if($plussoi == "1"){
		$popularite = '<img src="../img/admin.png" alt="users" title="Utilisateurs appuyant la demande: '.$ligne->bugtrackeruser_plususer.'" style="width:15px;"/> <b title="'.$ligne->bugtrackeruser_plususer.'" style="font-size:18px;">'.$ligne->bugtrackeruser_popularite.'</b>';
	}else{
		$popularite = '<img src="../img/admin.png" alt="users" title="Utilisateurs appuyant la demande: '.$ligne->bugtrackeruser_plususer.'" style="width:25px;"/> <b title="'.$ligne->bugtrackeruser_plususer.'" style="font-size:18px;">'.$ligne->bugtrackeruser_popularite.'</b>';
	}	

	if($ligne->bugtrackeruser_reponse != ""){
			$reponseavis = $ligne->bugtrackeruser_reponse;
			$tableauavis[$avisstatut] .= '
			<p style="'.$stylestatus.'">
				<span style="text-align:center;" title="N° Ticket"><b>N° Ticket:</b> '.$idreponse.'</span><br/><br/>
				<span title="Date de la demande"><b>Date de la demande:</b> <br/>'.$dateavis.'</span><br/><br/>
				<span title="Catégorie"><b>Catégorie:</b> '.$categoryavis.'</span><br/><br/>
				<span title="Interêt"><b>Interêt:</b> '.$popularite.'</span><br/><br/>
				<span title="Auteur"><b>Auteur:</b> '.$useravis.'</span><br/><br/>
				<span title="Titre"><u><b>'.$titreavis.': </b></u><br/>
				<span title="Message">'.$messageavis.'</span><br/><br/>
				<span title="Auteur de la Réponse"><b>'.$adminuser.': </b></span>
				<span title="Réponse"><br/>'.$reponseavis.'</span><br/><br/>
			</p>';	
		}else{
			$tableauavis[$avisstatut] .= '
			<p style="'.$stylestatus.'">
				<span style="text-align:center;" title="N° Ticket"><b>N°:</b> '.$idreponse.'</span><br/><br/>
				<span title="Date de la demande"><b>Date de la demande:</b> <br/>'.$dateavis.'</span><br/><br/>
				<span title="Catégorie"><b>Catégorie:</b> '.$categoryavis.'</span><br/><br/>
				<span title="Interêt"><b>Interêt:</b> '.$popularite.'</span><br/><br/>
				<span title="Auteur"><b>Auteur:</b><br/> '.$useravis.'</span><br/><br/>
				<span title="Titre"><b>Titre:</b><br/> '.$titreavis.'</span><br/><br/>
				<span title="Message"><b>Message:</b><br/> '.$messageavis.'</span><br/><br/>
			</p>';
	}
	$plussoi = NULL;
}
/*/////////// Fin Boucle While ///////////////////*/
/*/////////////////////////////////////////////////////////
										Début affichage
/////////////////////////////////////////////////////////*/
echo '<div id="content">';
echo '<h2>Chasseur d\'erreurs:  '.$_SESSION['login'].' <br/></h2>
<fieldset style="border:none;background:white;"><a href="bugtrackeradmin" style="padding-left:10px;padding-right:15px;">Retour</a></fieldset><br/>'.$messetat;
for($i == 0;$i <= $plususertaille; $i++){
		echo $plususer[$i];
}

echo '<div id="donnees" style="float:left;">
<fieldset style="font-size:17px;width:500px;"><legend>Répondre à une demande</legend>';
echo $tableauavis[0].$tableauavis[5].$tableauavis[10].$tableauavis[15];
echo'</fieldset>';

echo '</div><div style="float:left;"><fieldset><legend>Rédaction de la réponse</legend><p>'.$formreponse.'</p></fieldset></div>';

echo '</div></body></html>';
?>
