<?php
header("Content-Type: image/png");

$final_dim = 256;							# dimension finale de l'image
$text = utf8_decode($_GET['name']);			# Le texte à dessiner
$font_size = 75;							# taille de la police
$margin = 20;								# marge

# donne le nom de la police à utiliser
/* désactiver pour question de sécurité chez pas certains hébergeurs
$fonts = glob("./fonts/*.ttf");				# cherche tous les fichiers .ttf dans l'arborescence indiquée
*/
if ($handle = opendir('fonts')) {
    while ($file = readdir($handle)) {
        if ($file != "." && $file != ".." && ereg("ttf$", $file)) {
            //echo "$file\n";
			$fonts[] = $file;
        }
    }
    closedir($handle);
}
$font = "./fonts/".$fonts[rand(0,count($fonts)-1)];	# définit la police aléatoirement
//echo $font;
# tableau contenant les coordonnées du rectangle englobant l'image
$taile_texte = imagettfbbox($font_size,0,$font,$text);

# Création d'une image carrée selon la taille du texte à écrire
$taille_image = $taile_texte[4]+($margin*2);
$img=imagecreate($taille_image, $taille_image);

# Création de quelques couleurs (la première couleur allouée est la couleur de fond)
$fond = imagecolorallocate($img, rand(50, 255), rand(50, 255), rand(50, 255));
$noir = imagecolorallocate($img, 0, 0, 0);
$vert = imagecolorallocate($img, 69, 226, 104);
if($font == "./fonts/SHLOP___.ttf") {
	$txt_color = $vert;
}
else $txt_color = $noir;

# écriture du texte
imagettftext($img, $font_size, 0, $margin, ($taile_texte[4]/2)+($font_size/2), $txt_color, $font, $text);

# redimmensionnement de l'image en $final_dim x $final_dim
$texture = imagecreate($final_dim, $final_dim);
imagecopyresized($texture, $img, 0, 0, 0, 0, $final_dim, $final_dim, $taille_image, $taille_image);
imagedestroy($img);

imagepng($texture);
imagedestroy($texture);
?>
