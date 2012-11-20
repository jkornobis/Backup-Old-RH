<?php
	require_once('config.php');
	$titlepage = "Mise à jour des modèles - Module Administrateur";
	require_once('menus.php');
	echo $doctype.$menuprincipale;
	require_once ('tests.php');

// Si tout va bien, on peut continuer
$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
mysql_select_db("$PARAM_nom_bd",$db);
/*//////////////////////////////////////////////////////////////////////////////////////////////
													ATTENTION VIDAGE DE TABLE
//////////////////////////////////////////////////////////////////////////////////////////////*/
$vidange = mysql_query("TRUNCATE TABLE `EventTemplate`;") or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
/*//////////////////////////////////////////////////////////////////////////////////////////////
													ATTENTION VIDAGE DE TABLE
//////////////////////////////////////////////////////////////////////////////////////////////*/

$resultats=$connexion->query("
SELECT *
FROM `UserObm`, `UserObmRH`
WHERE `userobm_id` = `userobmrh_id`
;");
echo '<div id="content"><br/><br/><h2>Mise A jour des modeles</h2>';
$resultats->setFetchMode(PDO::FETCH_OBJ);
while($ligne = $resultats->fetch() )
{
/*
$insertion2 = mysql_query('
INSERT INTO `mla`.`UserObmRH` (
`userobmrh_id` ,
`tempsrh_hpj` ,
`userobm_statut` ,
`rh_congepaye` ,
`rh_congesexcep` ,
`rh_rtt` ,
`rh_maladie` ,
`rh_enfantmalade` ,
`rh_rc` ,
`rh_congesanciennete` ,
`rh_compteepargnetemps` ,
`userobm_cv` ,
`compteur_memoire`
)
VALUES (
"'.$ligne->userobm_id.'", "'.$ligne->temps_hpj.'", "oui", "0", "0", "0", "0", "0", "0", "0", "0", "0", ""
);
') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());*/
//echo $ligne->userobm_id.' - '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.': tache accomplit :D !<br/>' ;

	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);

	////						Requete insertion événement           ////
	$insertion = mysql_query("
	INSERT INTO `EventTemplate` (`eventtemplate_id`, `eventtemplate_domain_id`, `eventtemplate_timeupdate`, `eventtemplate_timecreate`, `eventtemplate_userupdate`, `eventtemplate_usercreate`, `eventtemplate_owner`, `eventtemplate_name`, `eventtemplate_title`, `eventtemplate_location`, `eventtemplate_category1_id`, `eventtemplate_priority`, `eventtemplate_privacy`, `eventtemplate_date`, `eventtemplate_duration`, `eventtemplate_allday`, `eventtemplate_repeatkind`, `eventtemplate_repeatfrequence`, `eventtemplate_repeatdays`, `eventtemplate_endrepeat`, `eventtemplate_allow_documents`, `eventtemplate_alert`, `eventtemplate_description`, `eventtemplate_properties`, `eventtemplate_tag_id`, `eventtemplate_user_ids`, `eventtemplate_contact_ids`, `eventtemplate_resource_ids`, `eventtemplate_document_ids`, `eventtemplate_group_ids`) VALUES
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '101 - Accueil, Information et Orientation', 'default', NULL, 10, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '102 - Informations collectives', 'default', NULL, 15, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '103 - Bureau Information Jeunesse', 'default', NULL, 16, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '201 - CIVIS', 'default', NULL, 11, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '202 - CIVIS – Module Immersion entreprises', 'default', NULL, 65, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '203 - PPAE', 'default', NULL, 14, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '204 - Jeunes hors programme', 'default', NULL, 12, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '205 - Accompagnement TH', 'default', NULL, 17, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '206 - Accompagnement social', 'default', NULL, 13, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '207 - Justice', 'default', NULL, 18, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '208 - RSA', 'default', NULL, 19, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '209 - CLAP', 'default', NULL, 20, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '210 - Permis pour l''emploi', 'default', NULL, 21, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '211 - Accompagnement formation', 'default', NULL, 22, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '212 - Permanences Emploi', 'default', NULL, 23, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '213 - Dossier aide financière (FAJ – FSL)', 'default', NULL, 66, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '214 - Réunions externe avec partenaire', 'default', NULL, 71, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '301 - Atelier Emploi', 'default', NULL, 24, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '302 - Opérations de recrutement', 'default', NULL, 25, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '303 - CAE Passerelle', 'default', NULL, 26, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '304 - Visites d''entreprises', 'default', NULL, 27, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '305 - Journées Job d''Eté', 'default', NULL, 28, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '306 - Forum Emploi', 'default', NULL, 29, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '307 - CIVIS EMPLOI', 'default', NULL, 30, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '308 - Référent Entreprise d''Insertion', 'default', NULL, 31, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '309 - Référent Apprentissage', 'default', NULL, 32, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '310 - Espace Infos métiers', 'default', NULL, 33, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '311 - Référent Parrainage', 'default', NULL, 34, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '312 - C.L.A.P EMPLOI', 'default', NULL, 35, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '313 - Tremploi', 'default', NULL, 64, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '401 - Expertise + Observation du territoire', 'default', NULL, 36, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '501 - SLA Formation', 'default', NULL, 37, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '502 - PARTAJ', 'default', NULL, 38, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '503 - Insertion des Jeunes par le Sport', 'default', NULL, 39, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '504 - Projet Santé pour les Jeunes', 'default', NULL, 40, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '505 - Travailleurs Handicapés', 'default', NULL, 41, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '506 - Logement', 'default', NULL, 42, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '507 - Mobilité - Permis de conduire', 'default', NULL, 43, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '508 - Plan de communication', 'default', NULL, 44, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '509 - CIVIS - FIPJ', 'default', NULL, 45, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '510 - Espace Info formation ', 'default', NULL, 46, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '511 - Animation PPAE', 'default', NULL, 47, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '512 - Partenariat local', 'default', NULL, 48, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '601 - Gestion administrative indifférenciée', 'default', NULL, 49, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '602 - Gestion administrative RSA', 'default', NULL, 70, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '603 - Gestion administrative CIVIS', 'default', NULL, 50, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '604 - Gestion administrative PPAE', 'default', NULL, 51, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '605 - Gestion administrative Emploi', 'default', NULL, 63, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '606 - Réunions internes', 'réunion', NULL, 52, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '607 - Formation', 'default', NULL, 53, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '608 - SAG', 'default', NULL, 54, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '609 - CE', 'default', NULL, 55, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '610 - DP/DS', 'default', NULL, 56, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '611 - Entretien des Locaux', 'default', NULL, 72, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '612 - Prud''homme', 'default', NULL, 68, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '613 - Chequier Syndical', 'default', NULL, 69, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', NULL, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '901 - Congés payés', 'Congé', NULL, 57, 2, 0, '2011-01-03 07:00:00', 32400, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '902 - Congés exceptionnels', 'Congé', NULL, 58, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '903 - Congés enfant malade', 'Congé', NULL, 67, 2, 0, '2011-01-03 07:00:00', 12600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '904 - RTT', 'Congé', NULL, 59, 2, 0, '2011-01-03 07:00:00', 12600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '905 - RC', 'default', NULL, 60, 2, 0, '2011-01-03 07:00:00', 3600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', '6', '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '906 - Maladie', 'Congé', NULL, 61, 2, 0, '2011-01-03 07:00:00', 12600, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '909 - Congé Ancienneté', 'Congé', NULL, 73, 2, 0, '2011-09-20 06:00:00', 36000, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '910 - Congé Compte Épargne Temps', 'Congé', NULL, 76, 2, 0, '2011-09-20 06:00:00', 36000, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL),
(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '911 - Accident du travail', 'Congé', NULL, 77, 2, 0, '2011-09-20 06:00:00', 36000, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL);




") or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
echo $ligne->userobm_id.' - '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.': tache accomplit :D !<br/>' ;
}
echo '</div></body></html>'

/*////////////////////////////////////////////////////////////////////////////////////////////
								Ligne type pour Rajouter un modèle d'événement
//////////////////////////////////////////////////////////////////////////////////////////////

(NULL, 2, '2011-09-22 12:53:26', '2011-09-22 12:53:26', NULL, '".$ligne->userobm_id."', '".$ligne->userobm_id."', '[INTITULE DE L\'EVENEMENT ICI]', 'Congé', NULL, '[NUMERO DE CATEGORIE  ICI]', 2, 0, '2011-09-20 06:00:00', 36000, 0, 'none', 1, '0000000', NULL, 0, -1, NULL, '<extended_desc/>', 1, '".$ligne->userobm_id."', NULL, NULL, NULL, NULL);

////////////////////////////////////////////////////////////////////////////////////////////
																	FIN:	Ligne type
//////////////////////////////////////////////////////////////////////////////////////////*/

?>
