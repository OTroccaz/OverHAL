<?php
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "https://ecobio.univ-rennes1.fr/HAL_SCD.css";
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="fr">
<head>
  <title>OverHAL : Comparaison HAL vs sources bibliographiques</title>
  <meta name="Description" content="OverHAL : Comparaison HAL vs sources bibliographiques">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <script type="text/javascript" language="Javascript" src="OverHAL.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="stylesheet" href="./OverHAL.css">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>

<noscript>
<div class='center, red' id='noscript'><strong>ATTENTION !!! JavaScript est désactivé ou non pris en charge par votre navigateur : cette procédure ne fonctionnera pas correctement.</font><br>
<strong>Pour modifier cette option, voir <a target='_blank' rel='noopener noreferrer' href='http://www.libellules.ch/browser_javascript_activ.php'>ce lien</a>.</strong></div><br>
</noscript>

<table class="table100" aria-describedby="Entêtes">
<tr>
<th scope="col" style="text-align: left;"><img alt="OverHAL" title="OverHAL" width="250px" src="./img/logo_OverHAL2.jpg"></td>
<th scope="col" style="text-align: right;"><img alt="Université de Rennes 1" title="Université de Rennes 1" width="150px" src="./img/logo_UR1_gris_petit.jpg"></td>
</tr>
</table>
<hr style="color: #467666;">
<?php


/*
    OverHAL - 2016-06-24
    Copyright (C) 2016 Philippe Gambette (HAL_UPEMLV[AT]univ-mlv.fr) et Olivier Troccaz (olivier.troccaz[AT]univ-rennes1.fr)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

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
OverHAL permet de comparer HAL et des listes de publications (Scopus, WoS, SciFinder, Zotero, etc), à partir d'un script PHP créé par <a target="_blank" rel="noopener noreferrer" href="http://igm.univ-mlv.fr/~gambette/ExtractionHAL/CouvertureHAL/">Philippe Gambette</a>, repris et modifié par Olivier Troccaz (ECOBIO - OSUR) pour l'Université de Rennes 1. Si vous souhaitez utiliser le script PHP pour une autre institution, consultez la <a target="_blank" rel="noopener noreferrer" href="http://www.bibliopedia.fr/wiki/D%C3%A9veloppements_HAL">page Bibliopedia</a> (OverHAL).</p>

<h2>Mode d'emploi</h2>
<a href="Manuel-OverHAL.pdf">Télécharger le manuel</a>
<br>

<h2>Paramétrage</h2>
<strong>1. Charger le fichier</strong><br/>
<br/>
<form enctype="multipart/form-data" action="OverHAL_CREAAH_results.php" method="post" accept-charset="UTF-8">
<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
Envoyez les fichiers résultat (500 Ko maximum, voir ci-dessus le "mode d'emploi") :<br/>
<strong>Web of Science (CSV)</strong> : <input name="wos_csv" type="file" /><br/>
<strong>Scopus (CSV)</strong> : <input name="scopus" type="file" /><br/>
<strong>Zotero (CSV)</strong> : <input name="zotero" type="file" /><br/>
<strong>SciFinder (CSV)</strong> : <input name="scifin" type="file" /><br/>
<strong>Pubmed (CSV)</strong> : <input name="pubmed_csv" type="file" /><br/>
<br/>
<em>Expérimental (enregistrer des alertes mail en html) :</em><br/>
<strong>Web of Science (HTML)</strong> : <input name="wos_html" type="file" /><br/>
<strong>Pubmed (HTML)</strong> : <input name="pubmed_html" type="file" /><br/>
<br/>
<strong>2. Construire la requête HAL</strong><br/>
<br/>
Requête libre (<a target="_blank" rel="noopener noreferrer" href="https://api.archives-ouvertes.fr/docs/search">consultez l'API de HAL</a>)<br/>
<?php
$reqHAL = "http://api.archives-ouvertes.fr/search/?q=collCode_s:CREAAH%20AND%20(producedDateY_i:\"".date('Y', time())."\")&rows=10000&fl=docType_s,docid,halId_s,authFullName_s,title_s,subTitle_s,journalTitle_s,volume_s,issue_s,page_s,producedDateY_i,proceedings_s,files_s,label_s,citationFull_s,bookTitle_s,doiId_s,conferenceStartDateY_i";
?>
<input type='text' id='reqHAL' name='hal' size=100 value='<?php echo $reqHAL;?>'><br/><br/>
Limiter l'affichage des résultats aux seules références non trouvées dans HAL : <input type="checkbox" checked name="limzot" value="ok"><br/>
<br/>
<strong>ou :</strong><br/>
<p><strong>Code collection HAL</strong> <a class=info onclick='return false' href="#">(qu’est-ce que c’est ?)<span>Code visible dans l’URL d’une collection.
Exemple : IPR-MOL est le code de la collection http://hal.archives-ouvertes.fr/<strong>IPR-PMOL</strong> de l’équipe Physique moléculaire
de l’unité IPR UMR CNRS 6251</span></a> :
<input type="text" id="team" name="team" value="CREAAH" size="40" onchange="majReqHAL();"><br/>
<br/>
Années de publication :
<input type="text" id="year1" name="year1" value="<?php echo date('Y', time())-1;?>" size="10" onchange="majReqHAL();">
&nbsp;à&nbsp;
<input type="text" id="year2" name="year2" value="<?php echo date('Y', time());?>" size="10" onchange="majReqHAL();"><br/>
<br/>
<input type="checkbox" id="txtint" name="txtint" value="ok" onchange="majReqHAL();"> requête uniquement sur le texte intégral (dépôt HAL ou lien arxiv ou lien Pubmed Central)<br/>
<input type="checkbox" checked name="desactSR" value="oui"> désactiver les recherches Sherpa/RoMEO<br/>
<?php
include("./IP_list.php");
if (in_array($ip, $IP_aut)) {
  echo("<input type=\"checkbox\" name=\"actMailsM\" value=\"oui\"> activer les procédures d'envoi de mails (demande du manuscrit à l'auteur = M)<br/>");
  echo("<input type=\"checkbox\" name=\"actMailsP\" value=\"oui\"> activer les procédures d'envoi de mails (autorisation de dépôt du post-print = P)<br/>");
}else{
  echo("<br/>La fonctionnalité d'envoi d'emails ne s'affiche que pour les utilisateurs autorisés (adresse IP). Voir le mode d'emploi / installation<br/>");
}
?>
<input type="checkbox" name="bibtex" value="oui"> générer un bibtex de publications en français, à partir d'un fichier Zotero, avec éventuellement,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;. ces mots-clés (séparés par "; "): <input type="text" name="keywords" size="40"><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;. cet auteur ("Mortier, Renaud", par exemple): <input type="text" name="author" size="40">
<br/>
<br/><br/>
URL Joker : <input type="text" name="joker" size=100 value=""><br/><br/>
<input type="submit" value="Envoyer">
</form>
</p>
</body>
</html>
