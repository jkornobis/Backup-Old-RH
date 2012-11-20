<?php
require_once('confignew.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Mon Test - OBM++</title>
<link rel="stylesheet" media="screen" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="../favicon.ico">
</head>
<body>
<nav>
<p>
 Bienvenue 
<?php
echo $_SESSION['user_nom'].' ';
?>
 dans votre espace OBM++ de
<?php
echo $_SESSION['domain_description'].' ';
?> 
nous sommes le: 
<?php
echo date("d/m/Y  H:i");
?>
</p>
</nav>



<section>

<article id="realise">

</article>

<article id="conges">
<p>
Fuerit toto in consulatu sine provincia, cui fuerit, antequam designatus est, decreta provincia. Sortietur an non? Nam et non sortiri absurdum est, et, quod sortitus sis, non habere. Proficiscetur paludatus? Quo? Quo pervenire ante certam diem non licebit. ianuario, Februario, provinciam non habebit; Kalendis ei denique Martiis nascetur repente provincia.
</p>
</article>

<article id="frais">
<p>
Fuerit toto in consulatu sine provincia, cui fuerit, antequam designatus est, decreta provincia. Sortietur an non? Nam et non sortiri absurdum est, et, quod sortitus sis, non habere. Proficiscetur paludatus? Quo? Quo pervenire ante certam diem non licebit. ianuario, Februario, provinciam non habebit; Kalendis ei denique Martiis nascetur repente provincia.
</p>
</article>

<article id="infos">
<h2>Vos informations</h2>
<?php
echo	$_SESSION['user_id'].'<br/>' ;
echo	$_SESSION['user_admin'].'<br/>' ;
echo	$_SESSION['user_archive'].'<br/>' ;
echo	$_SESSION['user_droits'].'<br/>' ;
echo	$_SESSION['user_delegation'].'<br/>' ;
echo	$_SESSION['hpj'].'<br/>' ;
echo	$_SESSION['userobm_statut'].'<br/>' ;
echo	($_SESSION['rh_congepaye']/24).'<br/>' ;
echo	$_SESSION['rh_congesexcep'].'<br/>' ;
echo	$_SESSION['rh_rtt'].'<br/>' ;
echo	($_SESSION['rh_maladie']/24).'<br/>' ;
?>
</article>

</section>
</body>
</html>