<?php print("<?xml version=\"1.0\" encoding=\"utf-8\"?>"); ?>
<?php

include("lib-general.php");
include("../mysql.php");
######################################################################
#                           variables                                #
######################################################################
# différentes variables
$pseudo = testPostVar("pseudo","aucun pseudo fourni");
$adresse_ip = $_SERVER['REMOTE_ADDR'];
$poids = 1;

######################################################################
#                           base de données                          #
######################################################################
# connnexion à la base de données
$connexion = mysql_connect($mon_hote, $mon_pseudo, $mon_mdp) or die("impossible de se connecter... <!-- ".mysql_error()." -->\n");

# création de la base si elle n'existe pas
mysql_query("CREATE DATABASE IF NOT EXISTS myah");
# selection de la base de donnees
mysql_select_db($ma_base) or die("impossible d'ouvrir la base de donnees.");
# création de la table si elle n'existe pas
mysql_query("CREATE TABLE IF NOT EXISTS myah (
	id_user MEDIUMINT (9) NOT NULL AUTO_INCREMENT,
	adr_IP VARCHAR (15) NOT NULL,
	pseudo VARCHAR (20) NOT NULL,
	poids SMALLINT (9) NOT NULL default '1',
	date_crea DATETIME DEFAULT NULL,
	date_visit DATETIME DEFAULT NULL,
	INDEX(id_user))") or die("impossible de créer la table 'myah'\n <!-- ".mysql_error()." -->\n");

$select_IP = mysql_query("SELECT * FROM myah") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>
		<title>myah / administration</title>
		
		<meta http-equiv="Content-language" content="fr" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex,nofollow" />
		<link rel="stylesheet" type="text/css" href="../css/defaut/defaut.css" title="défaut" />
	</head>

	<body>
		<div id="contenant">
			<h1>
				<a href="../">
					<strong>m</strong>ake <strong>y</strong>ourself <strong>a</strong> <strong>h</strong>ome
				</a>
			</h1>
			<div id="console">
				<table>
					<caption>
						les dix dernières visites&nbsp;:
					</caption>
					<tr>
						<th>date de dernière visite</th>
						<th>pseudo</th>
						<th>poids</th>
						<th>numéro d'inscription</th>
						<th>adresse IP</th>
					</tr>
				
<?php
	$select_IP = mysql_query("SELECT * FROM myah WHERE date_visit != '0000-00-00 00:00:00' ORDER BY date_visit DESC LIMIT 10") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");
	while($col = mysql_fetch_object($select_IP)) {
		print("
					<tr>
						<td>".date_validation($col->date_visit, "d.m.Y - H:i")."</td>
						<td>".$col->pseudo."</td>
						<td>".$col->poids."</td>
						<td>".$col->id_user."</td>
						<td>".$col->adr_IP."</td>
					</tr>");
	}
?>
				</table>
				<table>
					<caption>
						les derniers referers&nbsp;:
					</caption>
					<tr>
						<th>date</th>
						<th><abbr title="Uniform resource locator">URL</abbr></th>
						<th>navigateur</th>
					</tr>
				
<?php
	$select_referer = mysql_query("SELECT * FROM myah_referers ORDER BY date DESC LIMIT 10") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");
	while($col = mysql_fetch_object($select_referer)) {
		print("
					<tr>
						<td>".date_validation($col->date, "d.m.Y - H:i")."</td>
						<td><a href=\"".$col->site_url."\">".$col->site_url."</a></td>
						<td>".$col->navig."</td>
					</tr>");
	}
?>
				</table>
				<table>
					<caption>
						les dix derniers inscrits&nbsp;:
					</caption>
					<tr>
						<th>numéro d'inscription</th>
						<th>date de première visite</th>
						<th>date de dernière visite</th>
						<th>pseudo</th>
						<th>poids</th>
						<th>adresse IP</th>
					</tr>
				
<?php
	$select_IP = mysql_query("SELECT * FROM myah ORDER BY id_user DESC LIMIT 10") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");
	while($col = mysql_fetch_object($select_IP)) {
		print("
					<tr>
						<td>".$col->id_user."</td>
						<td>".date_validation($col->date_crea, "d.m.Y - H:i")."</td>
						<td>".date_validation($col->date_visit, "d.m.Y - H:i")."</td>
						<td>".$col->pseudo."</td>
						<td>".$col->poids."</td>
						<td>".$col->adr_IP."</td>
					</tr>");
	}
?>
				</table>
				<table>	
					<caption>
						les dix plus présents&nbsp;:
					</caption>
					<tr>
						<th>poids</th>
						<th>pseudo</th>
						<th>numéro d'inscription</th>
					</tr>
<?php
	$select_IP = mysql_query("SELECT * FROM myah ORDER BY poids DESC, date_visit DESC LIMIT 10") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");
	while($col = mysql_fetch_object($select_IP)) {
		print("
					<tr>
						<td>".$col->poids."</td>
						<td>".$col->pseudo."</td>
						<td>".$col->id_user."</td>
					</tr>");
	}
?>
				</table>
			</div>
			<div>
				<object type="model/vrml" data="../vrml_boxes.php"	viewpoint_transition_mode="1" vrml_dashboard="false" standby="chargement en cours..." width="400" height="250">
					<param name="classid" value="CLSID:86A88967-7A20-11d2-8EDA-00600818EDB1" />
					<param name="codebase" value="http://www.parallelgraphics.com/bin/cortvrml.cab#Version=4,2,0,93" />
				</object>
			</div>
		</div>
	</body>
</html>
<?php
# deconnexion
mysql_close($connexion) or die("probleme durant la deconnexion.");
?>
