<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Avis sur OBM - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menubugtracker;
/*/////////////////////////////////////////////////////////
								Traitement Tableaux Avis
/////////////////////////////////////////////////////////*/
$resultatsavis = $connexion->query('
	SELECT *
	FROM `BugTrackerUser`
	ORDER BY `bugtrackeruser_reponse` ASC, `bugtrackeruser_dateupdate` DESC
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

	$categoryavis	= $ligne->bugtrackeruser_category;
	$useravis =	$ligne->bugtrackeruser_userlogin;
	$titreavis = ucfirst($ligne->bugtrackeruser_titre);
	$messageavis = ucfirst($ligne->bugtrackeruser_message);
	$adminuser = $ligne->bugtrackeruser_admin;
	$idreponse = $ligne->bugtrackeruser_idmessage;
	$avisstatut = $ligne->bugtrackeruser_status;
	$partageavis = $ligne->bugtrackeruser_partage;

	switch($ligne->bugtrackeruser_status){
			case "0":
				$dateavis= '<b title="Non traité" style="color:black;">'.$dateavisdebut.'</b>';
				$avistotal = $avistotal + 1;
				$stylestatus = "color:black;";
			break;
			case "5":
				$avisfini = $avisfini + "0.5";
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="En cours de traitement" style="color:#D30;">'.$dateavisup.'</b>';
				$stylestatus = "color:#D30;";
			break;
			case "10":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Mise à Jour" style="color:#060;">'.$dateavisup.'</b>';
				$stylestatus = "color:#060;";
			break;
			case "15":
				$avisfini = $avisfini + 1;
				$avistotal = $avistotal + 1;
				$dateavis= '<b title="Refusé" style="color:#A00;">'.$dateavisup.'</b>';
				$stylestatus = "color:#A00;";
			break;
	}
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
	if($partageavis == "1" && ($categoryavis != 'Humain - Evolution' || $categoryavis != 'Technique - Evolution')){
		$partageavis = "<b style=\"font-size:18px;color:#060;\"><u>Oui</u></b>";
	}else{
		if($categoryavis == 'Humain - Evolution' || $categoryavis == 'Technique - Evolution'){
			$partageavis = "Auto";
		}else{
			$partageavis = "Non";
		}
	}	

	if($ligne->bugtrackeruser_reponse != ""){
			$reponseavis = $ligne->bugtrackeruser_reponse;
			$tableauavis[$avisstatut] .= '
			<tr style="'.$stylestatus.'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#FFF\';">
				<td style="text-align:center;" title="N° Ticket">'.$idreponse.'</td>
				<td title="Date de la demande">'.$dateavis.'</td>
				<td title="Catégorie">'.$categoryavis.'</td>
				<td title="Interêt" style="color:black;">'.$popularite.'</td>
				<td title="Auteur">'.$useravis.'</td>
				<td title="'.$titreavis.': '.$messageavis.'" style="width:400px;text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;"><b style="font-size:15px;"><u>'.$titreavis.'</u></b><br/> '.$messageavis.'</td>
				<td style="text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;width:400px;" title="'.$adminuser.': '.$reponseavis.'"><b>'.$adminuser.':</b><br/> '.$reponseavis.'</td>
				<td title="Partage">'.$partageavis.'</td>
				<td><a href="bugtrackerdetail.php?idmessage='.$idreponse.'">Modifier</a></td>
			</tr>';	
		}else{
			$tableauavis[$avisstatut] .= '
			<tr style="'.$stylestatus.'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#FFF\';">
				<td style="text-align:center;" title="N° Ticket">'.$idreponse.'</td>
				<td title="Date de la demande">'.$dateavis.'</td>
				<td title="Catégorie">'.$categoryavis.'</td>
				<td title="Interêt" style="color:black;">'.$popularite.'</td>
				<td title="Auteur">'.$useravis.'</td>
				<td title="'.$titreavis.': '.$messageavis.'" style="width:400px;text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;"><b style="font-size:15px;"><u>'.$titreavis.'</u></b><br/> '.$messageavis.'</td>
				<td style="text-align:justify;padding-left:5px;text-shadow:2px 2px 2px #CCC;width:400px;" title="'.$adminuser.': '.$reponseavis.'"><b>'.$adminuser.'</b><br/> '.$reponseavis.'</td>
				<td title="Partage" >'.$partageavis.'</td>
				<td><a href="bugtrackerdetail.php?idmessage='.$idreponse.'">Répondre</a></td>
			</tr>';
	}
	$plussoi = NULL;
}
/*/////////// Fin Boucle While ///////////////////*/
if($avistotal >= "1"){
	$progression = round($avisfini *100 / $avistotal);

	if($progression == "100"){
		$styleprogression = 'height:20px;width:'.$progression.'%;background:#00BF06;text-align:center;font-weight:bold;color:#FFF;text-shadow: 2px 2px 2px #666;line-height:18px;border-radius:15px 0px 15px 0px;';
	}else{
		if($progression >= "50"){
			$styleprogression = 'height:20px;width:'.$progression.'%;background:#55C459;text-align:center;font-weight:bold;color:#FFF;text-shadow: 2px 2px 2px #666;line-height:18px;border-radius:15px 0px 15px 0px;';
		}else{
			if($progression >= "25"){
				$styleprogression = 'height:20px;width:'.$progression.'%;background:#B2C455;text-align:center;font-weight:bold;color:#FFF;text-shadow: 2px 2px 2px #666;line-height:18px;border-radius:15px 0px 15px 0px;';
			}else{		
				if($progression >= "15"){
					$styleprogression = 'height:20px;width:'.$progression.'%;background:#C46B55;text-align:center;font-weight:bold;color:#FFF;text-shadow: 2px 2px 2px #666;line-height:18px;border-radius:15px 0px 15px 0px;';
				}else{		
					$styleprogression = 'height:20px;width:'.$progression.'%;background:#D14826;text-align:center;color:white;font-weight:bold;color:#000;text-shadow: 2px 2px 2px #666;line-height:18px;border-radius:15px 0px 15px 0px;';
				}
			}
		}
	}
}
/*/////////////////////////////////////////////////////////
										Début affichage
/////////////////////////////////////////////////////////*/
echo '<div id="content">';
echo '<h2>Chasseur d\'erreurs:  '.$_SESSION['login'].' <br/></h2>';
for($i == 0;$i <= $plususertaille; $i++){
		echo $plususer[$i];
}

echo '';
echo '<b>Progression:</b>
<div style="height:20px;width:600px;border:1px solid #999;font-size:18px;border-radius:15px 0px 15px 0px;box-shadow: 2px 2px 5px #CCC;">
<div style="'.$styleprogression.'">
'.$progression.'%
</div></div>
';
echo '<div id="donnees"><h2 style="margin-top:5px;margin-bottom:0px;">À Traité</h2>
<table style="font-size:13px;">
<tr>
<th style="width:30px;">N°</th><th style="width:100px;">Date</th><th>Catégorie</th><th style="width:80px;">Interêt</th><th>Auteur</th><th style="width:480px;">Message</th><th>Réponse</th><th>Partagé</th><th>Actions</th></tr>
';
echo $tableauavis[0].$tableauavis[5];
echo'</table><br/>';

echo'<h2 style="margin:0;">Traités</h2><table style="font-size:13px;">
<tr>
<th style="width:30px;">N°</th><th style="width:100px;">Date</th><th>Catégorie</th><th style="width:80px;">Interêt</th><th>Auteur</th><th style="width:480px;">Message</th><th>Réponse</th><th>Partagé</th><th>Actions</th></tr>
';
echo $tableauavis[10].$tableauavis[15];
echo'</table>';


echo '</div></div></body></html>';
?>
