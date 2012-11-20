<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Avis sur OBM - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menubugtracker;

$nompage ='bugtracker.php';
/*/////////////////////////////////////////////////////////
							Traitement Formulaire Avis
/////////////////////////////////////////////////////////*/

if(isset($_POST['avisform'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
		$req2 = mysql_query('
		INSERT INTO `mla`.`BugTrackerUser` (
			`bugtrackeruser_userlogin`,
			`bugtrackeruser_idmessage`,
			`bugtrackeruser_dateupdate`,
			`bugtrackeruser_datebegin`,
			`bugtrackeruser_category`,
			`bugtrackeruser_titre` ,
			`bugtrackeruser_message`,
			`bugtrackeruser_status`,
			`bugtrackeruser_reponse`,
			`bugtrackeruser_plususer`,
			`bugtrackeruser_popularite`
		)
		VALUES (
			"'.$nomsession.'",
			"NULL",
			"'.date("Y-m-d H:i:s").'",
			"'.date("Y-m-d H:i:s").'",
			"'.$_POST['category'].'",
			"'.$_POST['titre'].'",
			"'.$_POST['message'].'",
			"0",
			"",
			"'.$nomsession.',",
			"1"
		);
		') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if(isset($_GET['killavis'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
		$req2 = mysql_query('
			DELETE FROM `BugTrackerUser` WHERE `BugTrackerUser`.`bugtrackeruser_idmessage` = "'.$_GET['killavis'].'" LIMIT 1;
		') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
/*/////////////////////////////////////////////////////////
								Traitement Tableaux Avis
/////////////////////////////////////////////////////////*/
$resultatsavis = $connexion->query('
	SELECT *
	FROM `BugTrackerUser`
	WHERE `bugtrackeruser_userlogin` = "'.$nomsession.'"
	ORDER BY  `bugtrackeruser_dateupdate` DESC, `bugtrackeruser_status` ASC
	;');

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
	
	$annee = $mois = $jour = $h = $m = $s = NULL;

	$categoryavis	= $ligne->bugtrackeruser_category;
	$titreavis = ucfirst($ligne->bugtrackeruser_titre);
	$messageavis = ucfirst($ligne->bugtrackeruser_message);
	$avisid = $ligne->bugtrackeruser_idmessage;
	$adminuser = $ligne->bugtrackeruser_admin;	
	
	switch($ligne->bugtrackeruser_status){
			case "0":
				$dateavis= '<b title="Non traité" style="color:black;">'.$dateavisdebut.'</b>';
				$avistotal = $avistotal + 1;
				$boutonsupp= ' / <span><a href="bugtracker.php?killavis='.$avisid.'">Supprimer l\'avis</a></span>';
			break;
			case "5":
				$avisfini = $avisfini + "0.5";
				$avistotal = $avistotal + 1;
				$dateavis = '<b title="En cours de traitement" style="color:orange;">'.$dateavisup.'</b>';
				$boutonsupp = NULL;
			break;
			case "10":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Mise à Jour" style="color:#0A0;">'.$dateavisup.'</b>';
				$boutonsupp = NULL;
			break;
			case "15":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Refusé" style="color:#A00;">'.$dateavisup.'</b>';
				$boutonsupp = NULL;
			break;
	}

	if($ligne->bugtrackeruser_reponse != ""){
		$reponseavis = $ligne->bugtrackeruser_reponse;
		$tableauavis .= '<fieldset style="height:auto;width:550px;overflow:auto;margin-top:5px;"><legend style="font-size:16px;">'.$dateavis.':<b> '.$categoryavis.'</b></legend><b><u>'.$titreavis.'</u></b><div style="height:auto;max-height:55px;overflow:auto;padding-right:10px;">'.$messageavis.'</div><br/><b><u>'.$adminuser.':</u></b><br/>'.$reponseavis.'</fieldset>';	
	}else{
		$tableauavis .= '<fieldset style="height:auto;width:550px;overflow:auto;margin-top:5px;"><legend style="font-size:16px;">'.$dateavis.':<b> '.$categoryavis.'</b> '.$boutonsupp.'</legend><b><u>'.$titreavis.'</u></b><div style="height:auto;max-height:55px;overflow:auto;padding-right:10px;">'.$messageavis.'</div></fieldset>';
		
	}
}
/*/////////// Fin Boucle While ///////////////////*/
if($avistotal >= "1"){
	$progression = round($avisfini *100 / $avistotal);

	if($progression == "100"){
		$styleprogression = 'height:20px;width:'.$progression.'%;background:#00BF06;text-align:center;font-weight:bold;color:#FFF;border-radius:15px 0px 15px 0px;font-size:18px;text-shadow: 2px 2px 2px #666;line-height:18px;';
	}else{
		if($progression >= "50"){
			$styleprogression = 'height:20px;width:'.$progression.'%;background:#55C459;text-align:center;font-weight:bold;color:#FFF;border-radius:15px 0px 15px 0px;font-size:18px;text-shadow: 2px 2px 2px #666;line-height:18px;';
		}else{
			if($progression >= "25"){
				$styleprogression = 'height:20px;width:'.$progression.'%;background:#B2C455;text-align:center;font-weight:bold;color:#FFF;border-radius:15px 0px 15px 0px;font-size:18px;text-shadow: 2px 2px 2px #666;line-height:18px;';
			}else{		
				if($progression >= "15"){
					$styleprogression = 'height:20px;width:'.$progression.'%;background:#C46B55;text-align:center;font-weight:bold;color:#FFF;border-radius:15px 0px 15px 0px;font-size:18px;text-shadow: 2px 2px 2px #666;line-height:18px;';
				}else{		
					$styleprogression = 'height:20px;width:'.$progression.'%;background:#D14826;text-align:center;color:white;font-weight:bold;color:#000;border-radius:15px 0px 15px 0px;font-size:18px;text-shadow: 2px 2px 2px #666;line-height:18px;';
				}
			}
		}
	}
}
/*/////////////////////////////////////////////////////////
										Début affichage
/////////////////////////////////////////////////////////*/
if($formation == true){
/*
	$texteformation = '<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'"><fieldset class="formation"><legend style="padding-left:20px;padding-right:20px;"><b>Formation:</b> <i>"Émettre un avis sur OBM"</i></legend>
<p style="font-size:15px;font-weight:normal;">
Bienvenue sur la page <i>"Émettre un avis sur OBM"</i> de votre module Personnel.<br/>
Cette page Résume l\'ensemble de vos demandes, qu\'elles soient public ou privé. Cette notion de public-privé est définit par
la catégorie de votre avis (indiquer dans le menu déroulant).</p><p>
En complément de la notion <i>"public et privé"</i>, les avis ont une catégorie <i>"Technique"</i> ou <i>"Humain"</i> qui détermine le domaine de réponse souhaitée.
</p>
</fieldset></div>';
*/
}

echo '<div id="content">';
echo '<h2>Chasseur d\'erreurs:  '.$nomsession.'<br/> <a href="bugtrackerindex.php">Retour</a></h2>'.$texteformation;

echo '<div id="deplacements" style="width:600px;height:auto;">';

echo '<fieldset style="height:auto;text-align:justify;"><legend style="width:130px;padding-left:15px;">Vos Avis: </legend>';
echo '<b>Progression:</b>
<div style="height:20px;width:250px;border:1px solid #999;font-size:18px;border-radius:15px 0px 15px 0px;box-shadow: 2px 2px 5px #999;">
<div style="'.$styleprogression.'">
'.$progression.'%
</div></div><br/>
';
echo $tableauavis;
echo '</fieldset></div><div id="annexes">';
/*/////////////////////////////////////////////////////////
								Affichage Formulaire Avis									
/////////////////////////////////////////////////////////*/
echo '
<fieldset style="height:280px;width:520px;"><legend style="width:200px;padding-left:15px;">Émettre un avis</legend><br/>
	<form method="POST" action="bugtracker.php">
		<input type="hidden" name="avisform" id="avisform" value="'.$nomsession.'"/>
		<input type="hidden" name="login" id="login" value="'.$nomsession.'"/>
		Catégorie:
		<select id="category" name="category" style="height:30px;">
					<optgroup label="Bug - Erreurs: Privé">
						<option value="Technique - Bug">Technique - Bug</option>
						<option value="Humain - Erreurs">Humain - Erreurs</option>
						<option value="Humain - Questions">Humain - Questions</option>
					</optgroup>
					<optgroup label="Évolution: Public">
						<option value="Technique - Evolution">Technique - Evolution</option>
						<option value="Humain - Evolution">Humain - Evolution</option>
					</optgroup>
		</select><br/><br/>
		Titre:
		<input type="text" name="titre" id="titre" size="20"/>
		<br/><br/>
		Message:<br/><textarea cols="50" rows="3" name="message" id="message"></textarea>
		<br/><br/>
		<button type="submit" style="height:25px;" onclick="return(confirm(\'Etes-vous sûr de vouloir poster cette avis ?\'));">Valider l\'avis</button>
	</form>
</fieldset>
';


echo '</div></div></body></html>';
?>
