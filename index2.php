<?php

include("admin/lib-general.php");
include("mysql.php");
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
$connexion=mysql_connect($mon_hote, $mon_pseudo, $mon_mdp);

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
	INDEX(id_user))") or die("Impossible de créer la table 'myah'\n <!-- ".mysql_error()." -->\n");

$select_IP = mysql_query("SELECT * FROM myah") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");
while($col = mysql_fetch_object($select_IP)) {
	if($col->adr_IP != $adresse_ip) {
		$present = 0;
	}
	else {
		$present = 1;
		$poids = $col->poids;
		$date_visit = $col->date_visit;
		break;
	}
}

$time_elapsed = date("i")-date_validation($date_visit, "i");

# Vérification de l'existence d'un pseudo fourni dans le formulaire de la page précédente
if($pseudo != "aucun pseudo fourni" && $pseudo != "") {
	if($present == 1) {
			mysql_unbuffered_query("UPDATE myah SET poids=poids+1, date_visit='".date("Y-m-d H:i:s")."' WHERE adr_IP='".$adresse_ip."'")
				or die("update visites foiré...\n <!-- ".mysql_error()." -->\n");
			$poids=$poids+1;
		}
	else {
		mysql_unbuffered_query("INSERT INTO myah (adr_IP, pseudo, poids, date_crea) VALUES ('".$adresse_ip."','".$pseudo."','".$poids."','".date("Y-m-d H:i:s")."')")
			or die("insert myah loupé...\n <!-- ".mysql_error()." -->\n");
	}
}
# sinon, on redirige vers la page d'entrée
else {
	header("location:index.php");
}
/*
# ajout manuel d'une entrée dans la BDD
mysql_unbuffered_query("INSERT INTO myah (adr_IP, pseudo, poids, date, heure) VALUES ('12.56.91.250','test numéro 2','1','".date("Y-m-d")."','".date("H:i:s")."')")
		or die("insert myah loupé...");
*/
# deconnexion
mysql_close($connexion) or die("probleme durant la deconnexion.");

print("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>
		<title>make yourself a home</title>
		
		<meta http-equiv="Content-language" content="fr" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

		<meta name="author" content="Fabien Basmaison" />
		<meta name="DC.Contributor" content="Bleu" />
		<meta name="DC.Date.created" scheme="W3CDTF" content="2005-01-30" />
		<meta name="DC.Date.modified" scheme="W3CDTF" content="2005-02-11" />

		<meta name="revisit-after" content="3 days" />
		<meta name="robots" content="index,follow" />

		<meta name="description" content="un site interactif où chaque visiteur est un point de l'espace associé." />
		<meta name="keywords" content="architecture,VRML,virtual reality modeling language,X3D,eXtended 3D, an.archi,BAM!,architecte,projet,virtuel,jeu,video,objet, infographie,3d,urbain,urba,cao,pao,dao" />
		<meta name="generator" content="scripté avec jEdit" />

		<meta http-equiv="Page-Enter" content="RevealTrans(Duration=1,Transition=12)" />

		<link rel="start" title="accueil" href="./" />
		<link rel="mailto:" title="mail" href="fbasmaion[ad]free.fr?subject=myah%20yourself%20a%20home" />
		<link rel="stylesheet" type="text/css" href="css/defaut/defaut.css" title="défaut" />
	</head>

	<body>
		<div id="contenant">
			<h1>
				<strong>m</strong>ake <strong>y</strong>ourself <strong>a</strong> <strong>h</strong>ome
			</h1>
			<ul id="menu">
				<li>
					<a href="./">page d'entrée</a>
				</li>
				<li>
					<a href="FAQ.htm"><abbr title="Frequently asked questions / questions fréquemment posées">FAQ</abbr></a>
				</li>
				<li>
					<a href="liens.htm">liens</a>
				</li>
			</ul>
			<div id="navigation">
				<object type="model/vrml" data="vrml_boxes.php"	width="400" height="330">
					<param name="viewpoint_transition_mode" value="1" />
					<param name="standby" value="chargement en cours..." />
					<param name="classid" value="CLSID:86A88967-7A20-11d2-8EDA-00600818EDB1" />
					<param name="codebase" value="http://www.parallelgraphics.com/bin/cortvrml.cab#Version=4,2,0,93" />
				</object>
			</div>
			<div id="info">
				<dl>
					<dt>
						pseudo&nbsp;:
					</dt>
					<dd>
						<?php echo $pseudo; ?>
					</dd>
					<dt>
						adresse&nbsp;:
					</dt>
					<dd>
						<?php echo $adresse_ip; ?>
					</dd>
					<dt>
						nombre de visites&nbsp;:
					</dt>
					<dd>
						<?php echo $poids; ?>
					</dd>
				</dl>
				<p>
					<a href="vrml_boxes.php" 
						title="ouvre une popup en plein écran si votre navigateur le permet" onclick="window.open(this.href,'myah','resizable=yes,fullscreen=yes'); return false;">voir en plein écran (popup)</a>
				</p>
			</div>
			<address>
				contact : <a href="mailto:fbasmaison[ad]free.fr?subject=make%20yourself%20a%20home" title="contact">Fabien Basmaison</a>
			</address>
		</div>
	</body>
</html>
