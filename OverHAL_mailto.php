<?php
function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function clean_string($string) {
	$bad = array("content-type","bcc:","to:","cc:","href");
	return str_replace($bad,"",$string);
}

$adr = $_POST["qui"];
$adr = str_replace(";", ",", $adr);
$sub = stripAccents($_POST['quoi']);
$fic = $_POST['fich'];
//$fic = str_replace('\\', '\\\\', $fic);
$nom_fichier = substr(strrchr($fic, "\\"), 1);
$fic = './OverHAL_PDF/'.$nom_fichier;
$mes = $_POST['mess'];
$mes = wordwrap($mes, 70, "\r\n", false);

$boundary = md5(rand()); // clé aléatoire de limite

// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
$headers  = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-Type: multipart/mixed; boundary='.$boundary .' '."\r\n";
$headers .= "Content-Transfer-Encoding: 8bit"."\r\n";

// En-têtes additionnels
//$headers .= $adr."\r\n";
$laurent = stripAccents(mb_encode_mimeheader("Laurent Jonchere", "UTF-8"));
$headers .= 'From: '.$laurent.' <laurent.jonchere@univ-rennes.fr>'."\r\n";
$headers .= 'Reply-to: '.$laurent.' <laurent.jonchere@univ-rennes.fr>'."\r\n";
$headers .= 'Cc: laurent.jonchere@univ-rennes.fr'."\r\n";
//$headers .= 'BCc: olivier.troccaz@univ-rennes.fr'."\r\n";

//Message
$email_message = '--' . $boundary."\r\n"; //Séparateur d'ouverture
$email_message .= "Content-Type: text/html; charset=utf-8"."\r\n"; //Type du contenu
$email_message .= "Content-Transfer-Encoding: 8bit"."\r\n"; //Encodage
//$email_message .= "\r\n";
$email_message .= "\r\n".$mes."\r\n"; //Contenu du message

/*
//Pièce jointe
if (!empty($fic)) {
	$type_fichier = "application/pdf";
	$handle = fopen($fic, 'r'); //Ouverture du fichier
	$content = fread($handle, filesize($fic)); //Lecture du fichier
	$encoded_content = chunk_split(base64_encode($content)); //Encodage
	$f = fclose($handle); //Fermeture du fichier
							
	$email_message .= "\r\n"."--".$boundary."\r\n"; //Deuxième séparateur d'ouverture
	$email_message .= 'Content-type:'.$type_fichier.';name="'.$nom_fichier.'"'."\r\n"; //Type de contenu (application/pdf ou image/jpeg)
	$email_message .= 'Content-Disposition: attachment; filename="'.$nom_fichier.'"'."\r\n"; //Précision de pièce jointe
	$email_message .= 'Content-transfer-encoding:base64'."\r\n"; //Encodage
	$email_message .= "\r\n"; //Ligne blanche. IMPORTANT !
	$email_message .= $encoded_content."\r\n"; //Pièce jointe
	//$email_message .= "\r\n";
}
*/

$email_message .= "\r\n"."--".$boundary."--"."\r\n"; //Séparateur de fermeture

// Envoi
//$adr = "olivier.troccaz@univ-rennes1.fr";
//$adr = "laurent.jonchere@univ-rennes.fr";
$sub = mb_encode_mimeheader($sub, "UTF-8");

$success = mail($adr, $sub, $email_message, $headers);
if (!$success) {
	//$errorMessage = error_get_last()['message'];
	//var_dump(error_get_last());
}
?>