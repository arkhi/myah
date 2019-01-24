<?php
######################################################################
#                           fonctions                                #
######################################################################
function testVar($var,$defaut)
	{
	if(isset($var))
		{
		$var=$var;
		}
	else
		{
		$var=$defaut;
		}
	return $var;
	}
function testGetVar($var,$defaut)
	{
	if(isset($_GET[$var]))
		{
		$var=$_GET[$var];
		}
	else
		{
		$var=$defaut;
		}
	return $var;
	}
function testPostVar($var,$defaut)
	{
	if(isset($_POST[$var]))
		{
		$var=$_POST[$var];
		}
	else
		{
		$var=$defaut;
		}
	return $var;
	}

function date_validation($date, $format) {
	if($date != "0000-00-00 00:00:00") {
		return date($format, strtotime($date));
	}
	else return "";
}

function align_viewpoint($vx,$vy,$vz,$cx,$cy,$cz,$pseudo) {
# différences de coordonnées
	$delta_x = $vx - $cx;
	$delta_y = $vy - $cy;
	$delta_z = $vz - $cz;

# projection de la ligne reliant le point de vue et la cible en plan
	$plane_line = sqrt(pow($delta_x,2) + pow($delta_z,2));
# angle alignant la vue sur la cible en plan
	$ay = acos(abs($delta_z) / $plane_line);
	//$ay = $vx > $cx && $vz > $cz ? $ay : $ay;
	$ay = $vx > $cx && $vz < $cz ? -$ay+deg2rad(180) : $ay;
	$ay = $vx < $cx && $vz < $cz ? $ay+deg2rad(180) : $ay;
	$ay = $vx < $cx && $vz > $cz ? -$ay : $ay;

# distance la plus courte entre les deux points 
	$short_dist = sqrt(pow($plane_line,2) + pow($delta_y,2));
# angle alignant la vue sur la cible autour de x
	$ax = acos($plane_line / $short_dist);
	$ax = $vy > $cy && $vz < $cz ? -$ax+deg2rad(180) : $ax;
	$ax = $vy < $cy && $vz < $cz ? $ax+deg2rad(180) : $ax;
	//$ax = $vy < $cy && $vz > $cz ? $ax : $ax;
	$ax = $vy > $cy && $vz > $cz ? -$ax : $ax;

# On écrit tout ça
	$Viepoint = "
Transform {
	rotation 0 1 0 ".$ay."
	translation ".$vx." ".$vy." ".$vz."
	children [
		Transform {
			rotation 1 0 0 ".$ax."
			children [
				Viewpoint
					{
					 description \"".$pseudo."\"
					 position 0 0 0
					 orientation 0 0 1 0
					}
			]
		}
	]
}";
	return $Viepoint;
}
?>
