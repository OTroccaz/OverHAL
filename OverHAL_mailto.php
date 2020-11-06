<?php
function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

$adr = $_POST["qui"];
$adr = str_replace(";", ",", $adr);
$sub = stripAccents($_POST['quoi']);
$mes = $_POST['mess'];
$mes = wordwrap($mes, 70, "\r\n", false);

// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
$headers  = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type: text/html; charset="UTF-8"'."\r\n";
$headers .= "Content-Transfer-Encoding: 8bit"."\r\n";

// En-têtes additionnels
//$headers .= $adr."\r\n";
$jules = stripAccents(mb_encode_mimeheader("Jules César", "UTF-8"));
$headers .= 'From: '.$jules.' <jules.cesar@univ-rome.fr>'."\r\n";
$headers .= 'Reply-to: '.$jules.' <jules.cesar@univ-rome.fr>'."\r\n";
$headers .= 'Cc: votre.adresse1@mail.fr'."\r\n";
$headers .= 'BCc: votre.adresse2@mail.fr'."\r\n";

// Envoi
//$adr = "jules.cesar@univ-rome.fr";
//$adr = "caius.octavius@univ-rome.fr";
$sub = mb_encode_mimeheader($sub, "UTF-8");
mail($adr, $sub, $mes, $headers);
?>