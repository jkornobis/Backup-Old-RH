<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale;
require_once ('tests.php');

echo '<br/>';
$resultats=$connexion->query('
				SELECT *
				FROM `UserObmGroup`, `UserObm`, `UGroup`
				WHERE  `userobmgroup_group_id` = `group_id`
				AND `userobmgroup_userobm_id` =  `userobm_id`
				AND `group_id` != "1"
				AND `group_id` != "13"
				ORDER BY  `UGroup`.`group_desc` ASC
	');
$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
	while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
	{
		echo '<p style="border: 1px solid #CCC">'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.' | 
		'.$ligne->group_name.' | '.$ligne->group_id.'</p>';
	}

?>
