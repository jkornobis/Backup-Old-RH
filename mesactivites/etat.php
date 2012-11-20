<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mon Résumé - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuetat;

$nompage = 'etat.php';

/*////////////////////////////////////////////////////////////////////////////////
														Début de la page
////////////////////////////////////////////////////////////////////////////////*/
$tempshpj=$connexion->query('
	SELECT *
	FROM `UserObm`, `UserObmRH`
	WHERE `userobm_id` = "'.$utilisateur.'"
	AND `userobmrh_id` = `userobm_id`
;');

$tempshpj->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $tempshpj->fetch())
{
	$hpj = $ligne->temps_hpj;
	$conges = ($ligne->userobm_congesnormale/24);
	$useradmin = $ligne->userobm_delegation_target;
}

if($formation == true){/*
	$texteformation = '
<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'">
<fieldset class="formation">
<legend><b>Formation:</b> Votre Module Personnel: Accueil et Première Approche</legend>
<h2>Introduction:</h2><b>Bonjour,</b>
<p>
Bienvenue sur l\'espace formation de votre module Personnel.<br/>
Ce cadre apparaîtra sur chaque page pour vous expliquer son principe et les informations qu\'elle vous donne. </</p>
Ces informations complémentaires à OBM vous permettront de gérer l\'ensemble de votre activité (Temps de travail réalisé, congés, Frais de déplacement et divers) mais vous permettra également de consulter vos statistiques personnelles, de contrôler
vos saisies, de donner votre avis ou poser une question (technique ou non) et de suivre l\'avancement du logiciel.
</p><p>
Ce cadre ce désactivera automatiquement à votre première déconnexion du module personnel. Mais son accès est toujours
possible grâce au lien <i>"Cadre de Formation"</i> dans le menu principal. Ce lien affichera  le cadre en rapport avec la page courante que vous consultez et disparaîtra si vous changez de page.
</p><p>
L\'équipe technique vous souhaite une bonne navigation dans votre module personnel.
</p>
<h2>Mon Résumé:</h2>
<p style="text-align:justify;">
Cette Page vous donne un état des lieux rapide sur vos données. Elle vérifie que vous n\'avez pas oubliez de remplir la catégorie dans vos événements (<i>"Contrôle de vos événements"</i>).
Elle vous donne également un état de vos frais sur le mois précédent ainsi que l\'actuel.<br/>
Enfin elle vous indique le statut de vos demandes de congés, affichant tous les congés demandés et réfusés mais simplement les 8 derniers congés validés par soucis de lisibilité de la page.<br/><br/>
Le retour à cette page ce fait en cliquant sur le logo Mission Locale de l\'Artois dans le menu de gauche.<br/>
Ce menu est le menu principal. Il est divisé en plusieurs blocs de liens pour améliorer la navigation. Le bloc <i>"Gestion"</i> étant
celui qui va vous permettre de gérer efficacement votre compte. Le bloc <i>"Informations"</i> étant lui plus consultatif.<br/>
Enfin vient le bloc <i>"Options"</i> qui vous permet d\'imprimer la page courante ou de retourner proprement à OBM.</p><p>
Ce menu principal est associé à un menu supplémentaire qui apparaît dans la Zone bleu en haut de votre page. Zone qui contient
actuellement <i>"Résumé de vos informations concernant OBM"</i>.
Ce second menu vous permettra de sélectionner les différentes options de sélections propres à la page que vous visiterez.</p><p>
Vous pouvez cliquer sur la page que vous souhaitez consulter 
</p>
</fieldset></div>';*/
}
echo '<div id="content">
<h2>État actuel de votre compte - '.$nomsession.': <i style="font-size:20px;">'.$hpj.' heures/semaine </i> </h2>'.$texteformation.'
<div style="float:left;width:500px;">';
/*////////////////////////////////////////////////////////////////////////////////
				Controle des événements non correctement remplis
////////////////////////////////////////////////////////////////////////////////*/

$controle=$connexion->query('
	SELECT *
	FROM  `Event` 
	LEFT OUTER JOIN  `EventCategory1`
	ON  `event_category1_id` =  `eventcategory1_id` 
	WHERE  `event_usercreate` =  "'.$utilisateur.'"
	AND `event_date` >= "'.$anneeactuel.'-01-01"
	AND `event_date` < "'.$anneeactuel.'-12-33"
	ORDER BY  `event_date` 
');
$controle->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
while( $ligne = $controle->fetch() ) // on récupère la liste des membres
{
	$datebrute = $ligne->event_date;
	$repeat = $ligne->event_repeatkind;
	$eventid = $ligne->event_id;
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
	if($datebrute > date($annee.'-04-01') && $datebrute < date($annee.'-11-01')){$h = $h+2;}else{$h++;}
	if($jour < 10){$jour = '0'.$jour;}
	$heure  = $ligne->event_duration/3600;
	list($heurefin, $mfin) = explode ('.', $heure);
	if ($h <10){$h = '0'.$h;}
	switch ($mfin){
		case NULL: $mfin = '00'; break;
		case '0': $mfin = '00'; break;
		case '25': $mfin = '15'; break;
		case '5': $mfin = '30'; break;
		case '75': $mfin = '45'; break;
	}
	if ($m <10){$m = $m.'0';}
	$heurefin = ($h+$heurefin);
	$mfin = ($m+$mfin);
	if ($mfin < 10){$mfin = $mfin.'0';}
	if ($mfin == '60'){$heurefin++; $mfin ='00';}
	if($heurefin < 10){$heurefin = '0'.$heurefin;}
	if ($mois <10){$mois = '0'.$mois;}
	$date = '<b style="color:blue;">'.$jour.'/'.$mois.'/'.$annee.'</b> | <b>'.$h.':'.$m.'-'.$heurefin.':'.$mfin.'</b>';

	if ($ligne->eventcategory1_label == ''){
		$infos = $infos.'<p><b style="color:red;font-size:15px;">Le:</b> '.$date.':<br/><b style="color:red;font-size:17px;">Titre: '.$ligne->event_title.'</b><br/><br/><b> Il manque la catégorie à cette événement </b> : <a  style="font-size:15px;font-weight:bold;" href="controle.php?suppr='.$eventid.'&uti='.$utilisateur.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></p><hr>';
	}
}
/*		AFFICHAGE		*/
if (isset($infos)){
echo '<fieldset><legend>Contrôle des évémenements: <b style="color:red">Anomalies détectés:</b></legend>';
echo $infos;
echo '</fieldset>';
}else{
echo '<fieldset><legend>Contrôle des événements: <b style="color:green">Aucunes Anomalies</b></legend>';
echo '<p><a href="monrealiseannuel.php">Vérifier mon réalisé annuel</a></p>';
echo '</fieldset>';
}
/*////////////////////////////////////////////////////////////////////////////////
								Controle des Frais
////////////////////////////////////////////////////////////////////////////////*/

/////////////////				MOIS PRECEDENT					///////////////////////
$controlefraisdep = $connexion->query('
	SELECT *
	FROM `EventCategory1`, `FraisEvent`
	WHERE `fraisevent_catcode` = `eventcategory1_code`
	AND `fraisevent_userobmid` = "'.$_SESSION['uti'].'"
	AND `fraisevent_date` >= "'.$anneeactuel.'-'.($moisactuel-1).'-01"
	AND `fraisevent_date` <= "'.$anneeactuel.'-'.($moisactuel-1).'-31"
	ORDER BY `fraisevent_date`
	;');

$controlefraisdep->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $controlefraisdep->fetch()) {
	if ($ligne->fraisevent_note == "1"){$note= 'Déplacement';}else{$note= 'Annexe';}
	$datebrute = $ligne->fraisevent_date;
	$date = explode("-", $datebrute);
	$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
	$totalprix = $totalprix + $ligne->fraisevent_prix;
	$fraisdep .= '<span>'.$fraisevent_date.' | '.$note.' | '.$ligne->eventcategory1_label.' | '.$ligne->fraisevent_lieu.' | '.round($ligne->fraisevent_prix,'2').' €</span><br/>';

}
/*		AFFICHAGE		*/
if(isset($fraisdep)){
	echo '<br/><fieldset><legend>Contrôle des frais de déplacements: '.($moisactuel-1).'/'.$anneeactuel.'</legend>'.$fraisdep.'<br/>
	 <b>Total:</b> <i style="font-size:20px;">'.round($totalprix,'2').'€</i></p></fieldset>';
}else{
	echo '<br/><fieldset><legend>Contrôle des frais: '.($moisactuel-1).'/'.$anneeactuel.'</legend><p>Aucun frais pour l\'instant</p></fieldset>';
}
$totalprix = NULL; 
/////////////////					MOIS ACTUEL					///////////////////////
$controlefraisdep2 = $connexion->query('
	SELECT *
	FROM `EventCategory1`, `FraisEvent`
	WHERE `fraisevent_catcode` = `eventcategory1_code`
	AND `fraisevent_userobmid` = "'.$utilisateur.'"
	AND `fraisevent_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
	AND `fraisevent_date` <= "'.$anneeactuel.'-'.$moisactuel.'-33"
	ORDER BY `fraisevent_date`
	;');
$controlefraisdep2->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $controlefraisdep2->fetch()) {
	if ($ligne->fraisevent_note == "1"){$note= 'Déplacement';}else{$note= 'Annexe';}
	$datebrute = $ligne->fraisevent_date;
	$date = explode("-", $datebrute);
	$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
	$totalprix2 = $totalprix2 + $ligne->fraisevent_prix;
	$fraisdep2 .= '<span>'.$fraisevent_date.' | '.$note.' | '.$ligne->eventcategory1_label.' | '.$ligne->fraisevent_lieu.' | '.round($ligne->fraisevent_prix,'2').' €</span><br/>';

}
/*		AFFICHAGE		*/
if(isset($fraisdep2)){
	echo '<br/><fieldset><legend>Contrôle des frais: '.($moisactuel).'/'.$anneeactuel.'</legend>'.$fraisdep2.'<br/>
	 <b>Total:</b> <i style="font-size:20px;">'.round($totalprix2,'2').'€ '.$fraisstatut2.'</i></fieldset>';
}else{
	echo '<br/><fieldset><legend>Contrôle des frais: '.($moisactuel).'/'.$anneeactuel.'</legend><p>Aucun frais pour l\'instant</p></fieldset>';
}
/*////////////////////////////////////////////////////////////////////////////////
								Fin div Gauche 
////////////////////////////////////////////////////////////////////////////////*/
echo '</div><div>';
/*////////////////////////////////////////////////////////////////////////////////
								Controle des Congés Traités - Début div droite
////////////////////////////////////////////////////////////////////////////////*/

$a= 0;
$congesvalidation = $connexion->query('
	SELECT *
	FROM `Event`, `EventCategory1`
	WHERE `event_category1_id` = `eventcategory1_id`
	AND `event_usercreate` = "'.$utilisateur.'"
	AND `event_date` >= "'.$anneeactuel.'-01-00"
	AND `event_date` <= "'.$anneeactuel.'-12-31"
	ORDER BY `event_date` DESC
;');
While($donnees = $congesvalidation->fetch())
{
	$styleth ='';
	$tempsbrute = $donnees['event_duration'] / 3600;
// Mise en Forme française de la date //
	$datebrute= $donnees['event_date'];
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
	
	$h = $h + 2;
	if($h >= 24){
		if ($jour < 31){$jour = $jour + 1;}
		if ($jour == 31 && $mois < 12){$jour = '01'; $mois = $mois +1;}else{$jour = '01'; $mois = '01'; $annee = $annee +1;}
	}
	switch ($mois){
		case '01': $moismot = 'Janvier'; break;
		case '02': $moismot = 'Février'; break;
		case '03': $moismot = 'Mars'; break;
		case '04': $moismot = 'Avril'; break;
		case '05': $moismot = 'Mai'; break;
		case '06': $moismot = 'Juin'; break;
		case '07': $moismot = 'Juillet'; break;
		case '08': $moismot = 'Aout'; break;
		case '09': $moismot = 'Septembre'; break;
		case '10': $moismot = 'Octobre'; break;
		case '11': $moismot = 'Novembre'; break;
		case '12': $moismot = 'Décembre'; break;
	}
	if ($jour < 10){$jour = '0'.$jour;}
	$date = $jour." ".$moismot." ".$annee;
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$userid = $donnees['event_usercreate'];
	$usernom = $donnees['userobm_lastname']." ".$donnees['userobm_firstname'];
	$titre = $donnees['event_title'];
	$cat = $donnees['eventcategory1_label'];
	$catid = $donnees['event_category1_id'];
	$catcode = $donnees['eventcategory1_code'];
	$idevent = $donnees['event_id'];
	$oknon = $donnees['event_description'];
	$congesnormale = $donnees['userobm_congesnormale']/24;
	$congesparental = $donnees['userobm_congesparental'];
	$congesrrtnt = $donnees['userobm_congesrrtnt'];
	$congesrc = $donnees['userobm_congesrc'];
	$congesmaladie = $donnees['userobm_congesmaladie'];
	$tempshpj = $donnees['temps_hpj']/5;

	if(($catcode ==  901 || $catcode ==  902 || $catcode ==  903 || $catcode ==  906 || $catcode ==  909 || $catcode ==  910 || $catcode ==  911) && ($a < 10)){
		if ($oknon == 'ok'){
			if ($a < 8){
				$congest = $congest .'<span><b>'.$date.' | '.$cat.'</b>: <i style="font-size:15px;color:green;">Accepté</i> <img src="../img/valider.png" alt="Accepté" title="Accepté"  width="25px"/></span><br/>';
				$a++;
			}
		}else{
			if($oknon == 'non' ){
				$congesr = $congesr .'<span><b>'.$date.' | '.$cat.'</b>: <i style="font-size:15px;color:red;">Refusé</i> <img src="../img/supprimer.png" width="25px" alt="Refusé" title="Refusé"/></span><br/>';
			}else{
				$congesnt = $congesnt .'<span><b>'.$date.' | '.$cat.'</b> : <i style="font-size:15px;color:#333;">Non Traité</i> <img src="../img/attente.gif" alt="Non Traité" title="Non Traité"  width="25px"/></span><br/>';
			}
		}
	}
}
/*		AFFICHAGE		*/
if (isset($congesnt) || isset($congest) || isset($congesnr)){
	echo '<fieldset><legend>Contrôle des congés:</legend><b>Il vous reste</b> <i style="font-size:18px;color:green;">'.$conges.' <span style="color:#A00;">(en travaux, 0 est normal)</span> jours de congés a prendre.</i>';
	echo '<p>Votre Responsable des congés: <b>'.$useradmin.'</b><br/><span style="color:#A00;font-weight:bold;font-size:12px;">(si vide contacter l\'administrateur)</span></p>';
	echo '<p><b style="font-size:17px;">Mes Congés Non traités:</b><br/>'.$congesnt.'</p><hr>';
	echo '<p><b style="font-size:17px;color:green;">Mes 8 Derniers Congés Acceptés:</b><br/>'.$congest.'</p><hr>';
	echo '<p><b style="font-size:17px;color:red;">Mes Congés Refusés: </b><br/>'.$congesr.'</p>';
	echo '<p><a href="mesconges.php">Vérifier Mes Congés</a></p></fieldset>';

}else{
	echo '<br/><fieldset><legend>Contrôle des congés: <b style="color:red">Aucun Congés existants:</b></legend>';
	echo '<a href="mesconges.php">Vérifier Mes Congés</a></fieldset>';
}
echo '</div></div><br/></body></html>';
?>
