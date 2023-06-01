<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Interrogation des affiliations des auteurs - Querying authors affiliations
 */
 
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "https://halur1.univ-rennes1.fr/HAL_SCD.css";
}

// récupération de l'adresse IP du client (on cherche d'abord à savoir s'il est derrière un proxy)
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
}else {
  $ip = $_SERVER['REMOTE_ADDR'];
}
?>
<html lang="fr">
<head>
  <title>AffiliHAL : Interrogation des affiliations des auteurs</title>
  <meta name="Description" content="AffiliHAL : Interrogation des affiliations des auteurs">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <script type="text/javascript" language="Javascript" src="OverHAL.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="./OverHAL.css">
</head>
<body>

<noscript>
<div class='center, red' id='noscript'><strong>ATTENTION !!! JavaScript est désactivé ou non pris en charge par votre navigateur : cette procédure ne fonctionnera pas correctement.</strong><br>
<strong>Pour modifier cette option, voir <a target='_blank' rel='noopener noreferrer' href='http://www.libellules.ch/browser_javascript_activ.php'>ce lien</a>.</strong></div><br>
</noscript>

<table class="table100" aria-describedby="Entêtes">
<tr>
<th scope="col" style="text-align: left;"><img alt="AffiliHAL" title="AffiliHAL" width="250px" src="./img/logo_AffiliHAL2.jpg"></th>
<th scope="col" style="text-align: right;"><img alt="Université de Rennes 1" title="Université de Rennes 1" width="150px" src="./img/logo_UR1_gris_petit.jpg"></th>
</tr>
</table>
<hr style="color: #467666;">
<?php
if (isset($_GET["erreur"]))
{
	$erreur = $_GET["erreur"];
	if ($erreur == 1) {echo("<script type=\"text/javascript\">alert(\"Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !\")</script>");}
	if ($erreur == 2) {echo("<script type=\"text/javascript\">alert(\"Le fichier dépasse la limite autorisée dans le formulaire HTML !\")</script>");}
	if ($erreur == 3) {echo("<script type=\"text/javascript\">alert(\"L'envoi du fichier a été interrompu pendant le transfert !\")</script>");}
	//if ($erreur == 4) {echo("<script type=\"text/javascript\">alert(\"Aucun fichier envoyé ou bien il a une taille nulle !\")</script>");}
  if ($erreur == 5) {echo("<script type=\"text/javascript\">alert(\"Mauvaise extension de fichier !\")</script>");}
}

?>
<div style="background-color:#FFFFFF;width:900px;padding:10px;font-family:calibri,verdana,sans-serif">
AffiliHAL permet de traduire les affiliations des auteurs par l'identifiant HAL correspondant dans le référentiel des laboratoires d'HAL. Ce script a été développé par Olivier Troccaz (ECOBIO - OSUR) pour l'Université de Rennes 1. Si vous souhaitez utiliser le script PHP pour une autre institution, consultez la <a target="_blank" rel="noopener noreferrer" href="http://www.bibliopedia.fr/wiki/D%C3%A9veloppements_HAL">page Bibliopedia</a> (OverHAL).</p>

<strong>Charger le fichier</strong><br/>
<form enctype="multipart/form-data" action="AffiliHAL_results.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
Envoyez les fichiers résultat (10 Mo maximum) :<br/>
<strong>Web of Science (CSV)</strong> : <input name="wos_csv" type="file" /><br/>
<strong>Scopus</strong> : <input name="scopus" type="file" /><br/>
<input type="submit" value="Envoyer">
</form>
</p>
</body>
</html>
