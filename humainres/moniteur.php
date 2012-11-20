<?php
require_once('config.php');
$titlepage = "Moniteur - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuadmin;
/*///////////////////////////////////////////////////////////////////
					Traitement Formulaire
///////////////////////////////////////////////////////////////////*/


/*///////////////////////////////////////////////////////////////////
						Affichage infos 
///////////////////////////////////////////////////////////////////*/
if ($ligne->userobm_delegation_target == $_SESSION['login'] || $_SESSION['login'] == "KORNOBIS Jérémie" || $_SESSION['login'] == "FIERRARD Virginie"|| $_SESSION['login'] == "PECOURT Antoine" || $_SESSION['login'] == "BOUTON Michael" ){
echo '
<div id="contentadmin">
	<h2>Page d\'Administration</h2>
	<fieldset style="width:1190px;"><legend>Informations sur le serveur:</legend><br/>
		<b>Charge du serveur (HTTP):</b><br/> Cela correspond au taux de connexion au logiciel OBM (exemple type: le rechargemment d\'une page).<br/><br/>
		<img src="http://192.168.1.190:3000/plugins/rrdPlugin?action=arbreq&which=graph&arbfile=IP_HTTPBytes&arbiface=eth0&arbip=&start=now-4h&end=now&counter=&title=" alt="Protocole Actif" width="590px" height="250px"/>
		<img src="http://192.168.1.190:3000/plugins/rrdPlugin?action=arbreq&which=graph&arbfile=IP_HTTPBytes&arbiface=eth0&arbip=&start=now-24h&end=now&counter=&title=" alt="Protocole Actif" width="590px" height="250px"/>
		<img src="http://192.168.1.190:3000/plugins/rrdPlugin?action=arbreq&which=graph&arbfile=IP_HTTPBytes&arbiface=eth0&arbip=&start=now-48h&end=now&counter=&title=" alt="Protocole Actif" width="590px" height="250px"/>
		<img src="http://192.168.1.190:3000/plugins/rrdPlugin?action=arbreq&which=graph&arbfile=IP_HTTPBytes&arbiface=eth0&arbip=&start=now-72h&end=now&counter=&title=" alt="Protocole Actif" width="590px" height="250px"/>
	</fieldset>
</div>
</body></html>
';
}
?>
