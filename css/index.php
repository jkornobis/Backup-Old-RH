<?php
$user = array ('1', 'Mission Local de l\'Artois', 'Jérémie Kornobis', '28', 'CDD', '33', '-5');

if($user[6] > 0){
	$user[6] = '+'.$user [6].' H';
}else{
	$user[6] = $user [6].' H';;
}




$tableaurealise = NULL;
for($moiscomp = 0; $moiscomp < count($user); $moiscomp++){
	$tableaurealise .= $user[$moiscomp].' / ';
}


	$donneesrealise = array(array());
	for($mois = 1; $mois <13; $mois++){
		echo '<tr><th>'.$mois.'</th>';
		for($jour = 1; $jour < 32; $jour++){
			echo '<td>'.$donneesrealise[$mois][$jour].'</td>'; 
		}
		echo '</tr>';
	}


echo '
<!DOCTYPE html>
<html>
<head>
<title>Test interface OBM</title>
<link rel="stylesheet" type="text/css" href="domaine'.$user[0].'.css" />
</head>
<body>

<header>
<h2>Bienvenue '.$user[2].' sur l\'espace OBM++ de la '.$user[1].' </h2> 
<p>Solde du mois: '.$user[6].'</p>
</header> 

<nav id="navpage">
<ul>
<li><a href="" ><img src="realise.png" alt="Mon Realisé"/></a></li>
<li><a href="" ><img src="conges.png" alt="Mes Congés"/></a></li>
<li><a href="" ><img src="frais.png" alt="Mes frais"/></a></li>
<li><a href="" ><img src="stats.png" alt="Mes stats"/></a></li>
<li><a href="" ><img src="controle.png" alt="controle"/></a></li>
<li><a href="" ><img src="imprimer.png" alt="Mes stats"/></a></li>
<li><a href="" ><img src="stats.png" alt="Mes stats"/></a></li>
<li><a href="" ><img src="stats.png" alt="Mes stats"/></a></li>
<li><a href="" ><img src="stats.png" alt="Mes stats"/></a></li>
</ul>
</nav>

<section id="realise" onMouseOver="">
<img src="realise.png" alt="Mon Realisé"> Mon Réalisé</img>
<p>
'.$tableaurealise.'<table>';

echo '</table></p>
</section>

<section id="conges">
<img src="conges.png" alt="Mes Congés"> Mes Congés</img>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ultrices ultrices sem, sit amet imperdiet 
felis fringilla quis. Integer risus tortor, consequat eleifend dapibus sit amet, mollis vel urna. Phasellus
 aliquet consequat nulla, eget pretium magna lobortis non. Vivamus non turpis at lectus bibendum lobortis.
 Donec viverra aliquam nisi nec mattis. Mauris imperdiet rutrum justo, ultricies pellentesque urna vehicula a.
 Nullam eu arcu lacus.
</p>
</section>

<section id="frais">
<img src="frais.png" alt="Mes frais"> Mes Frais</img>
<p>
Vestibulum et velit rhoncus urna dignissim tempus non et erat. Pellentesque vitae libero massa, ac lacinia mi.
 Duis vestibulum, dolor ut tempor vestibulum, nisl augue vestibulum augue, nec posuere nisi elit vel leo.
 Cras eu dolor tellus. Ut ornare nisl et turpis auctor ac gravida diam mollis. Suspendisse laoreet,
 neque non dignissim laoreet, erat lacus adipiscing urna, et dictum ante risus ac tellus.
 Praesent elit dolor, rhoncus sed posuere non, dignissim nec est. Aliquam erat volutpat.
 Nullam porttitor nisl sit amet ipsum pharetra mollis. Donec cursus sodales dignissim. 
</p>
</section>

<section id="stats">
<img src="stats.png" alt="Mes stats"> Mes Statistiques</img>
<h1></h1>
<p>
Vestibulum et velit rhoncus urna dignissim tempus non et erat. Pellentesque vitae libero massa, ac lacinia mi.
 Duis vestibulum, dolor ut tempor vestibulum, nisl augue vestibulum augue, nec posuere nisi elit vel leo.
 Cras eu dolor tellus. Ut ornare nisl et turpis auctor ac gravida diam mollis. Suspendisse laoreet,
 neque non dignissim laoreet, erat lacus adipiscing urna, et dictum ante risus ac tellus.
 Praesent elit dolor, rhoncus sed posuere non, dignissim nec est. Aliquam erat volutpat.
 Nullam porttitor nisl sit amet ipsum pharetra mollis. Donec cursus sodales dignissim. 
</p>
</section>



</body>
</html>

';
?>
