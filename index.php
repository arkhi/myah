<?php
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>");

include("mysql.php");
# indexation des referers
$connexion = mysql_connect($mon_hote, $mon_pseudo, $mon_mdp) or die("impossible de se connecter... <!-- ".mysql_error()." -->\n");
if(!empty($_SERVER['HTTP_REFERER']))
	{
	$Precedent=$_SERVER['HTTP_REFERER'];
	if($Precedent!="" && !ereg("makeyourselfahome.free.fr", $Precedent))
		{
# création de la base si elle n'existe pas
	mysql_query("CREATE DATABASE IF NOT EXISTS myah");
# selection de la base de données
	mysql_select_db($ma_base) or die("impossible d'ouvrir la base de donnees.");
# création de la table si elle n'existe pas
	mysql_query("CREATE TABLE IF NOT EXISTS myah_referers(
		id_referer MEDIUMINT (9) NOT NULL AUTO_INCREMENT,
		date DATETIME DEFAULT NULL,
		site_url varchar(150) default NULL,
		adr_IP VARCHAR (15) NOT NULL,
		navig varchar(80) default NULL,
		poids SMALLINT (9) NOT NULL default '1',
		INDEX(id_referer))") or die("<!-- impossible de créer la table 'myah'\n".mysql_error()." -->\n");
# met à jour le nombre de visites depuis tel ou tel referer
	mysql_unbuffered_query("UPDATE myah_referers SET poids=poids+1 WHERE site_url='".$_SERVER['HTTP_REFERER']."'") or die("<!-- update visites loupé...\n".$Precedent."\n".mysql_error()."-->\n");
# ajoute le referer à la BDD
	mysql_unbuffered_query("INSERT INTO myah_referers (date,site_url,adr_IP,navig) VALUES ('".date("Y-m-d H:i:s")."','".$_SERVER['HTTP_REFERER']."','".gethostbyaddr($_SERVER['REMOTE_ADDR'])."','".$_SERVER['HTTP_USER_AGENT']."')") or die("<!-- insert referer loupé...\n".mysql_error()."-->\n");
			}
		}
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
		<meta name="DC.Date.modified" scheme="W3CDTF" content="<?php echo date("Y-m-d", filemtime("index.php")); ?>" />

		<meta name="revisit-after" content="3 days" />
		<meta name="robots" content="index,follow" />

		<meta name="description" content="un site interactif où chaque visiteur est un point de l'espace associé. Page d'accueil" />
		<meta name="keywords" content="architecture,VRML,virtual reality modeling language,X3D,eXtended 3D, an.archi,BAM!,architecte,projet,virtuel,jeu,video,objet, infographie,3d,urbain,urba,cao,pao,dao" />
		<meta name="generator" content="scripté avec jEdit" />

		<meta http-equiv="Page-Enter" content="RevealTrans(Duration=1,Transition=12)" />

		<link rel="start" title="accueil" href="./" />
		<link rel="mailto:" title="mail" href="fbasmaion[ad]free.fr?subject=myah%20yourself%20a%20home" />
		<link rel="stylesheet" type="text/css" href="css/defaut/defaut.css" title="défaut" />
	</head>
	
	<body>
<!-- 
		"version : 0.0.1"
-->
		<div id="contenant">
				<h1>
					<strong>m</strong>ake <strong>y</strong>ourself <strong>a</strong> <strong>h</strong>ome
				</h1>
				<ul id="menu">
					<li>
						<a href="FAQ.htm"><abbr title="Frequently asked questions / questions fréquemment posées">FAQ</abbr></a>
					</li>
					<li>
						<a href="liens.htm">liens</a>
					</li>
					<li>
						<a href="changelog.htm">changelog</a>
					</li>
				</ul>
				<form action="index2.php" method="post">
					<p>
						Veuillez entrer votre pseudonyme : <input type="text" name="pseudo" value="" maxlength="20" /> 
						<input class="submit" type="submit" value="valider" />
					</p>
				</form>
				<p class="warning">
					Ce site mettra en rapport votre adresse <abbr title="Internet Protocol">IP</abbr> et le pseudonyme que vous entrerez dans la boîte de dialogue précédente. Merci de ne pas continuer ou de prendre les dispositions nécessaires si vous estimez que cela ne vous convient pas.
				</p>
				<p>
					La page suivante nécessite un plugin permettant de lire les fichiers <abbr title="Virtual Reality Modeling Language / Langage de modélisation de réalité virtuelle">VRML</abbr>.<br />
					Vous trouverez une liste de ressources sur <a href="liens.htm">la page de liens</a>.
				</p>
				<address>
					contact : <a href="mailto:fbasmaison[ad]free.fr?subject=make%20yourself%20a%20home" title="contact">Fabien Basmaison</a>
				</address>
		</div>
	</body>
</html>
