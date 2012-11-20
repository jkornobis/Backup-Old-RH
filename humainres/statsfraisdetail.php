<?php
require_once('config.php');
$titlepage = "Statistiques - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menufrais;
require_once('tests.php');

echo '<div id="content"><h2>Statistiques Globales Projets/Axes: <a href="statsfraisdetail.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'  ">'.$flprecedent.'</a><span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="statsfraisdetail.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.' ">'.$flsuivant.'</a></h2>';

echo '<table style="width:800px;border:none;"><tr><th>Projets:</th><th style="width:200px;">Total des frais</th></tr>';

for($codeprojet = 100; $codeprojet < 900; $codeprojet++ ){
	
	$statsfrais = $connexion->query('
		SELECT *
		FROM `FraisEvent`, `EventCategory1`, `UserObm`
		WHERE `fraisevent_catcode` = `eventcategory1_code`
		AND `fraisevent_userobmid` = `userobm_id`
		AND `fraisevent_catcode` = "'.$codeprojet.'"
		AND `fraisevent_date` > "'.$anneeactuel.'-'.$moisactuel.'-00"
		AND `fraisevent_date` < "'.$anneeactuel.'-'.$moisactuel.'-33"
	;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

	$statsfrais->setFetchMode(PDO::FETCH_OBJ);
	while( $ligne = $statsfrais->fetch() ) {
		$iduser = $ligne->userobm_id;
		$nom[$iduser] = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;
		$label[$codeprojet] = $ligne->eventcategory1_label;

		$totalfrais[$codeprojet] = $totalfrais[$codeprojet] + $ligne->fraisevent_prix;
		$totalfraisuser[$iduser][$codeprojet] = $totalfraisuser[$iduser][$codeprojet] + $ligne->fraisevent_prix;

		$totalmoisfrais = $totalmoisfrais + $ligne->fraisevent_prix;
	
	}
}


for($codeprojet = 100; $codeprojet < 900; $codeprojet++ ){
	if ($totalfrais[$codeprojet] > 0){
		echo '<tr style="background-color:#FFF;border:none;height:20px;"></tr>';
		echo '<tr><th style="text-align:left;padding-left:5px;">'.$label[$codeprojet].'</th><th>'.round($totalfrais[$codeprojet], 2).' €</th></tr>';
		$resultats=$connexion->query("
		SELECT *
		FROM `UserObm`
		ORDER BY `userobm_lastname`
		;");

		$resultats->setFetchMode(PDO::FETCH_OBJ);
		while($champs = $resultats->fetch() )
		{
			$i= $champs->userobm_id;
			if(isset($totalfraisuser[$i][$codeprojet])){
				echo '<tr><td style="text-align:left;padding-left:5px;">'.$nom[$i].'</td><td><b>'.$totalfraisuser[$i][$codeprojet].'</b> €</td></tr>';
			}
		}
		echo '<tr><th style="text-align:left;padding-left:5px;"> TOTAL : </th><th><b style="font-size:15px;">'.round($totalfrais[$codeprojet], 2).'</b> €</th></tr>';
		
	}
}
	
echo '<tr style="background-color:#FFF;border:none;height:20px;"></tr>';
echo '<tr><th>TOTAL Du Mois:</th><th>'.round($totalmoisfrais, 2).' €</th></tr>';


echo '</table></div></body></html>';
?>
