<?php
require_once('config.php');
$titlepage = "Statistiques - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menufrais;
require_once('tests.php');

echo '<div id="content"><h2>Statistiques Globales Projets/Axes: <a href="statsfrais.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'  ">'.$flprecedent.'</a><span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="statsfrais.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.' ">'.$flsuivant.'</a></h2>';

echo '<table style="width:800px;"><tr><th>Projets:</th><th>Total des frais</th></tr>';

for($codeprojet = 100; $codeprojet < 900; $codeprojet++ ){

	$statsfrais = $connexion->query('
		SELECT *
		FROM `FraisEvent`, `EventCategory1`
		WHERE `fraisevent_catcode` = `eventcategory1_code`
		AND `fraisevent_catcode` = "'.$codeprojet.'"
		AND `fraisevent_date` > "'.$anneeactuel.'-'.$moisactuel.'-00"
		AND `fraisevent_date` < "'.$anneeactuel.'-'.$moisactuel.'-33"
		ORDER BY `fraisevent_date`
	;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

	$statsfrais->setFetchMode(PDO::FETCH_OBJ);
	while( $ligne = $statsfrais->fetch() ) {
		$label[$codeprojet] = $ligne->eventcategory1_label;
		$totalfrais[$codeprojet] = $totalfrais[$codeprojet] + $ligne->fraisevent_prix;
		$totalmoisfrais = $totalmoisfrais + $ligne->fraisevent_prix;
	}
	if ($totalfrais[$codeprojet] > 0){
		echo '<tr><td>'.$label[$codeprojet].'</td><td><b style="font-size:15px;">'.round($totalfrais[$codeprojet], 2).'</b> €</td></tr>';
	}
}
echo '<tr><th>TOTAL Du Mois:</th><th>'.round($totalmoisfrais, 2).' €</th></tr>';


echo '</table></div></body></html>';
?>
