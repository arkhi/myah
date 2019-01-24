<?php
header("Content-type:model/vrml");
?>
<?php

include("mysql.php");
include("admin/lib-general.php");

$scale_model=0.05;
$scale_texture=2;

# connnexion à la base de données
$connexion=mysql_connect($mon_hote, $mon_pseudo, $mon_mdp);
# selection de la base de données
mysql_select_db($ma_base) or die("impossible d'ouvrir la base de données.");
$select_IP = mysql_query("SELECT * FROM myah ORDER BY pseudo") or die("Impossible d'effectuer la requête\n <!-- ".mysql_error()." -->\n");
// print_r($tab_points);

?>
#VRML V2.0 utf8

WorldInfo {
	title "myah : make yourself a home"
	info [
		"auteur : Fabien Basmaison"
		"e-mail : fbasmaison@free.fr"
		"éditeur : scripté avec jEdit"
		"version : 0.0.1"
		"date : 03.02.2005"
	]
}
NavigationInfo {
	type ["WALK" "ANY"]
	avatarSize [<?php print(0.25*$scale_model); ?>, <?php print(1.6*$scale_model); ?>, <?php print(0.25*$scale_model); ?>]
}

Viewpoint {
	position		<?php print((-150*$scale_model)." ".(1.6*$scale_model)." ".(5000*$scale_model)); ?>
	orientation		0 1 0 -0.5
	description		"vue 1"
	jump			TRUE
}
Viewpoint {
	position		<?php print((-100*$scale_model)." ".(1.6*$scale_model)." ".(1000*$scale_model)); ?>
	orientation		-0.58 1 -0.1 <?php echo deg2rad(-35); ?>
	description		"vue 2"
	jump			TRUE
}
Viewpoint {
	position		<?php print((2000*$scale_model)." ".(1000*$scale_model)." ".(1000*$scale_model)); ?>
	orientation		-0.30 1 0.3 <?php echo deg2rad(80); ?>
	description		"vue 3"
	jump			TRUE
}

Background {skyColor 0 0 0}
# point 1 : 180 100 80
# point 2 : 200 100 60
# point 3 : 50 150 190
# point 4 : 100 250 10

DirectionalLight {
   ambientIntensity 0.75
   intensity 1
   color 1 1 1
   direction 0 -1 1
}

Fog {
	color            1 1 1
	fogType          "EXPONENTIAL "
	visibilityRange  <?php print(15000*$scale_model); ?>
}

Shape {
	appearance Appearance {
		material  Material {
			shininess 0
			diffuseColor .5 .5 .5
			ambientIntensity 0.1
			emissiveColor .2 .2 .2
		}
	}
	geometry IndexedFaceSet {
		solid FALSE
		coord Coordinate {
			point [
			10000 0 10000,
			10000 0 -10000,
			-10000 0 -10000,
			-10000 0 10000
			]
		}
		coordIndex [
			0 1 2 3
		]
		color Color {
			color [
				1 1 1,
				1 1 1,
				1 1 1,
				1 1 1
			]
		}
	}
}
<?php
while($col = mysql_fetch_object($select_IP)) {
	$t_points = explode(".", $col->adr_IP);
	$plan_xy = $t_points[0].".".$t_points[1];
	$x = $t_points[0];
	$y = $t_points[1];
	$z = $t_points[2];
	$w = $t_points[3];
	$poids = $col->poids;
# définition de la position de la boîte
	$pos_x = $x*$w/50*$poids/10;
	$pos_y = $y*$poids*$scale_model/1.5/2;
	$pos_z = $z*$w/50*$poids/10;

		print("
Transform {
	scale ".$scale_model." ".$scale_model." ".$scale_model."
	translation ".$pos_x." ".$pos_y." ".$pos_z."
	children [
		Shape {
			appearance Appearance {
				material  Material {
					diffuseColor 1 1 1
				}
				texture ImageTexture {
					url \"png_creation.php?name=".$col->pseudo."\"
					repeatS TRUE
					repeatT TRUE
				}
				textureTransform TextureTransform {
					scale ".($scale_texture*ceil($poids/10)/2)." ".($scale_texture*floor(1+$poids/10)/2)."
				}
			}
			geometry Box {
				size ".($x*$poids/1.5)." ".($y*$poids/1.5)." ".($z*$poids/1.5)."
			}
		}
	]
}\n");
	$eloignement = 6*$poids-$poids;
	echo align_viewpoint($pos_x + $eloignement, ($pos_y) + $eloignement, $pos_z + $eloignement, $pos_x, $pos_y, $pos_z, $col->pseudo);
}
?>
