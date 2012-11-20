<?php
require_once('config.php');
$titlepage = "Compteurs - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menucompteurs;
require_once('tests.php');

echo '
<div id="content">
<h2>Tableau de rapport des congés le <u>'.date('d/m/Y à h:i:s').'</u>(en travaux !):</h2>
<table>
	<tr style="background-color:#3E7DD1;color:white;">
		<th>Nom Prénom</th>
		<th style="width:120px;">H/Semaines</th>
		<th style="width:120px;">Congés</th>
		<th style="width:120px;">Exceptionnels</th>
		<th style="width:120px;">RTT</th>
		<th style="width:120px;">RC</th>
		<th style="width:120px;">maladie</th>
	</tr>
';
$resultats=$connexion->query("
SELECT *
FROM UserObm, UserObmRH
WHERE userobmrh_id = userobm_id
ORDER BY userobm_lastname ASC
");
$resultats->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultats->fetch() ) {
	if( $ligne->userobm_lastname == 'admin' || $ligne->userobm_lastname == 'Admin Lastname' || $ligne->userobm_lastname == 'MLA'
|| $ligne->userobm_lastname == 'Secrétaires'){
	}else{
		echo '<tr>';
		echo '<td style="border:1px solid black;background-color:lightblue;text-align:left;font-weight:bold;padding-left:5px;">'.$ligne->userobm_lastname.'
		'.$ligne->userobm_firstname.'</td>';
		echo '<td style="border:1px solid black;">'.$ligne->temps_hpj.'</td>'; 
		echo '<td style="border:1px solid black;">'.($ligne->userobm_congesnormale/24).'</td>';
		echo '<td style="border:1px solid black;">'.($ligne->userobm_congesexcep/24).'</td>';
		echo '<td style="border:1px solid black;">'.($ligne->userobm_congesrrtnt/24).'</td>';
		echo '<td style="border:1px solid black;">'.$ligne->userobm_congesrc.'</td>';
		echo '<td style="border:1px solid black;">'.$ligne->userobm_congesmaladie.'</td>';
		echo '</tr>';
	}
}
echo '</table></div></body></html>';
$dbd = null;
?>
