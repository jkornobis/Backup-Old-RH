<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Avis sur OBM - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menubugtracker;

$nompage = 'bugtrackerindex.php';
/*/////////////////////////////////////////////////////////
							Traitement Formulaire Popularité
/////////////////////////////////////////////////////////*/

if(isset($_GET['messageid']) ){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	$req2 = mysql_query('
		UPDATE `mla`.`BugTrackerUser` 
		SET `bugtrackeruser_dateupdate` = "'.date("Y-m-d H:i:s").'" ,
		`bugtrackeruser_popularite` = `bugtrackeruser_popularite` + "1",
		`bugtrackeruser_plususer` = CONCAT(`bugtrackeruser_plususer`,"'.$nomsession.',")
		WHERE `BugTrackerUser`.`bugtrackeruser_idmessage` = "'.$_GET['messageid'].'"
		LIMIT 1 ;
		') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

}


/*/////////////////////////////////////////////////////////
								Traitement Tableaux Avis
/////////////////////////////////////////////////////////*/
$resultatsavis = $connexion->query('
	SELECT *
	FROM `BugTrackerUser`
	WHERE `bugtrackeruser_category` = "Technique - Evolution" OR `bugtrackeruser_category` = "Humain - Evolution" OR `bugtrackeruser_partage` = "1"
	ORDER BY `bugtrackeruser_status` ASC, `bugtrackeruser_dateupdate` DESC
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
	$useravis =	$ligne->bugtrackeruser_userlogin;
	$titreavis = ucfirst($ligne->bugtrackeruser_titre);
	$messageavis = ucfirst($ligne->bugtrackeruser_message);
	$avisstatut = $ligne->bugtrackeruser_status;
	$idavis = $ligne->bugtrackeruser_idmessage;
	$adminuser = $ligne->bugtrackeruser_admin;

	switch($avisstatut){
			case "0":
				$dateavis= '<b title="Non traité" style="color:black;">'.$dateavisdebut.'</b>';
				$avistotal = $avistotal + 1;
				$stylestatus = "color:black;";
				$statut = '0';
			break;
			case "5":
				$avisfini = $avisfini + "0.5";
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="En cours de traitement" style="color:#D30;">'.$dateavisup.'</b>';
				$stylestatus = "color:#D30;";
				$statut = '5';
			break;
			case "10":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Mise à Jour" style="color:#060;">'.$dateavisup.'</b>';
				$stylestatus = "color:#060;";
				$statut = '10';
			break;
			case "15":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Refusé" style="color:#A00;">'.$dateavisup.'</b>';
				$stylestatus = "color:#A00;";
				$statut = '15';
			break;
	}
	$plususer = explode(",", $ligne->bugtrackeruser_plususer);
	if(isset($ligne->bugtrackeruser_plususer)){
		for($i = 0;$i < 300; $i++){
			if($nomsession == $plususer[$i]){
				$plussoi = "1";
			}
		}
	}else{
		$plussoi = "1";
	}

	if($plussoi == "1" || $statut == '10' || $statut == '15'){
		$boutonplus = NULL;
		$popularite = '<img src="../img/admin.png" alt="users" title="Utilisateurs appuyant la demande: '.$ligne->bugtrackeruser_plususer.'" style="width:25px;"/> <b title="'.$ligne->bugtrackeruser_plususer.'" style="font-size:18px;">'.$ligne->bugtrackeruser_popularite.'</b>';
	}else{
		$boutonplus = '<a href="bugtrackerindex.php?messageid='.$ligne->bugtrackeruser_idmessage.'" title="Appuyer l\'avis"><img src="../img/plusbouton.png" alt="plus 1" style="height:35px;" onclick="return(confirm(\'Etes-vous sûr de vouloir Appuyer cette avis ?\'));"/></a>';
		$popularite = '<img src="../img/admin.png" alt="users" title="Utilisateurs appuyant la demande: '.$ligne->bugtrackeruser_plususer.'" style="width:25px;"/> <b title="'.$ligne->bugtrackeruser_plususer.'" style="font-size:18px;">'.$ligne->bugtrackeruser_popularite.'</b>';
	}
	
	if($ligne->bugtrackeruser_reponse != ""){
		$reponseavis = $ligne->bugtrackeruser_reponse;
		$tableauavis[$statut] .= '
		<tr style="'.$stylestatus.'">
			<td style="text-align:center;" title="N° Ticket">'.$idavis.'</td>
			<td title="Date de la demande">'.$dateavis.'</td>
			<td title="Catégorie">'.$categoryavis.'</td>
			<td title="Interêt">'.$popularite.'</td>
			<td title="Auteur">'.$useravis.'</td>
			<td title="'.$titreavis.': '.$messageavis.'" style="width:400px;text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;"><b style="font-size:15px;"><u>'.$titreavis.'</u></b><br/> '.$messageavis.'</td>
			<td style="text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;width:400px;" title="'.$adminuser.': '.$reponseavis.'"><b>'.$adminuser.':</b><br/> '.$reponseavis.'</td>
			<td title="Soutenir">'.$boutonplus.'</td>
		</tr>';	
	}else{
		$tableauavis[$statut] .= '
		<tr style="'.$stylestatus.'">
			<td style="text-align:center;" title="N° Ticket">'.$idavis.'</td>
			<td title="Date de la demande">'.$dateavis.'</td>
			<td title="Catégorie">'.$categoryavis.'</td>
			<td title="Interêt">'.$popularite.'</td>
			<td title="Auteur">'.$useravis.'</td>
			<td title="'.$titreavis.': '.$messageavis.'" style="text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;"><b style="font-size:15px;"><u>'.$titreavis.'</u></b><br/> '.$messageavis.'</td>
			<td style="height:auto;" title="Réponse"></td>
			<td title="Soutenir">'.$boutonplus.'</td>
		</tr>';
	}
	$plussoi = NULL;
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
	$texteformation = '<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'"><fieldset class="formation"><legend style="padding-left:20px;padding-right:20px;"><b>Formation:</b> <i>"Avis sur OBM"</i></legend>
<p style="font-size:15px;font-weight:normal;">
Bienvenue sur la page <i>"Avis sur OBM"</i> de votre module Personnel.</p>
<p> Cette page vous permet de visualiser les avis et demandes de l\'ensemble des utilisateurs. Cela permet de regrouper les demandes mais également participer à l\'évolution du logiciel. Les demandes faîtes pour les autres utilisateurs sont dotées d\'un bouton soutenir qui vous permet d\'appuyer la demande qui vous intéresse.</p>
<p>Vous pouvez émettre un avis, renseigner un bug ou demander une évolution grâce au lien ci-dessous: <i>"Émettre un avis"</i>.</p>
</fieldset></div>';
}

echo '<div id="content"><br/>';
echo $texteformation;
echo '<b>Progression Globale:</b>
<div style="height:20px;width:400px;border:1px solid #999;border-radius:15px 0px 15px 0px;box-shadow: 2px 2px 5px #CCC;">
<div style="'.$styleprogression.'">
'.$progression.'%
</div></div>
<h2 style="margin-top:15px;margin-bottom:5px;"><a href="bugtracker.php">Émettre un avis</a><br/>
<br/>Tableau des demandes en cours:</h2>
<div style="width:1216px;height:350px;overflow:auto;">
<table style="font-size:13px;width:1200px;">
<tr>
<th>N° Ticket</th><th>Date</th><th>Catégorie</th><th style="width:120px;">Interêt</th><th>Auteur</th><th style="width:400px;">Message</th><th>Réponse</th><th>Soutenir</th>
</tr>
';
echo $tableauavis[0].$tableauavis[5];
echo '</table></div>';

echo '<h2 style="margin:5px;">Tableau des demandes Validées:</h2>
<div style="width:1216px;height:350px;overflow:auto;">
<table style="font-size:13px;width:1200px;">
<tr>
<th>N° Ticket</th><th>Date</th><th>Catégorie</th><th style="width:120px;">Interêt</th><th>Auteur</th><th style="width:400px;">Message</th><th>Réponse</th><th>Soutenir</th>
</tr>';
echo $tableauavis[10];
echo '</table></div>';

echo '<h2 style="margin:5px;">Tableau des demandes Refusées:</h2>
<div style="width:1216px;height:350px;overflow:auto;">
<table style="font-size:13px;width:1200px;">
<tr>
<th>N° Ticket</th><th>Date</th><th>Catégorie</th><th style="width:120px;">Interêt</th><th>Auteur</th><th style="width:400px;">Message</th><th>Réponse</th><th>Soutenir</th>
</tr>';
echo $tableauavis[15];
echo '</table></div>';

echo '</div></body></html>';
?>
