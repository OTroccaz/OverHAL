<?php
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "./HAL_SCD.css";
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
  <title>OverHAL : Comparaison HAL vs sources bibliographiques</title>
  <meta name="Description" content="OverHAL : Comparaison HAL vs sources bibliographiques">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link href="bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" language="Javascript" src="OverHAL.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <STYLE type="text/css">
  a.info{
      position:relative;
      z-index:24; background-color:#ccc;
      color:#000;
      text-decoration:none}

  a.info:hover{z-index:25; background-color:#ff0}

  a.info span{display: none}

  a.info:hover span{
  /*le contenu de la balise span ne sera visible que pour l'état a:hover */
  display:block;
  position:absolute;
  top:2em; left:2em; width:15em;
  border:1px solid #6699cc;
  background-color:#eeeeee; color:#6699cc;
  text-align: justify;
  font-weight: normal;
  padding:1px;
  }
  </STYLE>
</head>
<body>

<noscript>
<div align='center' id='noscript'><font color='red'><b>ATTENTION !!! JavaScript est désactivé ou non pris en charge par votre navigateur : cette procédure ne fonctionnera pas correctement.</b></font><br>
<b>Pour modifier cette option, voir <a target='_blank' href='http://www.libellules.ch/browser_javascript_activ.php'>ce lien</a>.</b></div><br>
</noscript>

<table width="100%">
<tr>
<td style="text-align: left;"><img alt="OverHAL" title="OverHAL" width="250px" src="./img/logo_OverHAL2.jpg"></td>
<td style="text-align: right;"><img alt="Université de Rennes 1" title="Université de Rennes 1" width="150px" src="./img/logo_UR1_gris_petit.jpg"></td>
</tr>
</table>
<hr style="color: #467666; height: 1px; border-width: 1px; border-top-color: #467666; border-style: inset;">
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
<div style="background-color:#FFFFFF;width:900px;padding:10px;font-family:calibri,verdana">
OverHAL permet de comparer HAL et des listes de publications (Scopus, WoS, SciFinder, Zotero, etc), à partir d'un script PHP créé par <a target="_blank" href="http://igm.univ-mlv.fr/~gambette/ExtractionHAL/CouvertureHAL/">Philippe Gambette</a>, repris et modifié par <a target="_blank" href="https://ecobio.univ-rennes1.fr/personnel.php?qui=Olivier_Troccaz">Olivier Troccaz</a> (ECOBIO - OSUR) pour l'Université de Rennes 1.
<br>Pour tout renseignement, n'hésitez pas à contacter <a target="_blank" href="https://openaccess.univ-rennes1.fr/interlocuteurs/laurent-jonchere">Laurent Jonchère</a> ou <a target="_blank" href="https://ecobio.univ-rennes1.fr/personnel.php?qui=Olivier_Troccaz">Olivier Troccaz</a>.
<br>Si vous souhaitez utiliser le script PHP pour une autre institution, consultez <a target="_blank" href="https://wiki.ccsd.cnrs.fr/wikis/hal/index.php/Outils_et_services_d%C3%A9velopp%C3%A9s_localement_pour_am%C3%A9liorer_ou_faciliter_l%27utilisation_de_HAL#Autres_outils">le wiki du CCSD</a> (OverHAL).</p>

<h2>Mode d'emploi</h2>
<a href="Manuel-OverHAL.pdf">Télécharger le manuel</a>
<br>

<h2>Paramétrage</h2>
<a target="_blank" href="./FCGI_construct_import.php">Construire un fichier FCGI à partir d'une liste de PMID</a>, puis l'envoyer à OverHAL avec le formulaire ci-dessous.<br/>
<br/>
<b>1. Charger le fichier</b><br/>
<br/>
<form enctype="multipart/form-data" action="OverHAL_results.php" method="post" accept-charset="UTF-8">
<p class="form-inline">
<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
Envoyez les fichiers résultat (500 Ko maximum, voir ci-dessus le "mode d'emploi") :<br/>
<label for="wos_csv">Web of Science (CSV)</label> : <input class="form-control" id="wos_csv" style="height: 25px; font-size: 90%; padding: 0px;" name="wos_csv" type="file" /><br/>
<label for="scopus">Scopus (CSV)</label> : <input class="form-control" id="scopus" style="height: 25px; font-size: 90%; padding: 0px;" name="scopus" type="file" /><br/>
<label for="zotero">Zotero (CSV)</label> : <input class="form-control" id="zotero" style="height: 25px; font-size: 90%; padding: 0px;" name="zotero" type="file" /><br/>
<label for="scifin">SciFinder (CSV)</label> : <input class="form-control" id="scifin" style="height: 25px; font-size: 90%; padding: 0px;" name="scifin" type="file" /><br/>
<label for="pubmed_csv">Pubmed (CSV)</label> : <input class="form-control" id="pubmed_csv" style="height: 25px; font-size: 90%; padding: 0px;" name="pubmed_csv" type="file" /><br/>
<label for="pubmed_xml">Pubmed (XML)</label> : <input class="form-control" id="pubmed_xml" style="height: 25px; font-size: 90%; padding: 0px;" name="pubmed_xml" type="file" /><br/>
<label for="pubmed_fcgi">Pubmed (FCGI)</label> : <input class="form-control" id="pubmed_fcgi" style="height: 25px; font-size: 90%; padding: 0px;" name="pubmed_fcgi" type="file" /><br/>
<?php
include("./IP_list.php");
if (in_array($ip, $IP_aut)) {
  echo('<label for="dimensions">Dimensions (CSV)</label> : <input class="form-control" id="dimensions" style="height: 25px; font-size: 90%; padding: 0px;" name="dimensions" type="file" /><br/>');
}
?>
<br/>
<i>Expérimental (enregistrer des alertes mail en html) :</i><br/>
<label for="wos_html">Web of Science (HTML)</label> : <input class="form-control" id="wos_html" style="height: 25px; font-size: 90%; padding: 0px;" name="wos_html" type="file" /><br/>
<label for="pubmed_html">Pubmed (HTML)</label> : <input class="form-control" id="pubmed_html" style="height: 25px; font-size: 90%; padding: 0px;" name="pubmed_html" type="file" /><br/>
<br/>
<b>2. Construire la requête HAL</b><br/>
<br/>
<label for="reqHAL">Requête libre</label> (<a target="_blank" href="https://api.archives-ouvertes.fr/docs/search">consultez l'API de HAL</a>)<br/>
<?php
$reqHAL = "https://api.archives-ouvertes.fr/search/?q=collCode_s:\"IRSET\"&fq=(producedDateY_i:\"".date('Y', time())."\")&rows=10000&fl=docType_s,docid,halId_s,authFullName_s,title_s,subTitle_s,journalTitle_s,volume_s,issue_s,page_s,producedDateY_i,proceedings_s,files_s,label_s,citationFull_s,bookTitle_s,doiId_s,conferenceStartDateY_i";
?>
<input type='text' class="form-control" style="height: 25px; width: 800px;" id='reqHAL' name='hal' value='<?php echo $reqHAL;?>'><br/><br/>
<label for="limzot">Limiter l'affichage des résultats aux seules références non trouvées dans HAL :</label> <input class="form-control" style="height: 15px;" type="checkbox" checked id="limzot" name="limzot" value="ok"><br/>
<br/>
<b>ou :</b><br/>
<p class="form-inline"><label for="team">Code collection HAL</label> <a class=info onclick='return false' href="#">(qu’est-ce que c’est ?)<span>Code visible dans l’URL d’une collection.
Exemple : IPR-MOL est le code de la collection http://hal.archives-ouvertes.fr/<b>IPR-PMOL</b> de l’équipe Physique moléculaire
de l’unité IPR UMR CNRS 6251</span></a> :
<input type="text" class="form-control" style="height: 25px; width: 300px;" id="team" name="team" value="IRSET" onchange="majReqHAL();"><br/>
<br/>
<label for="year1">Années de publication :</label>
<input type="text" class="form-control" style="height: 25px; width: 100px;" id="year1" name="year1" value="<?php echo date('Y', time())-1;?>" size="10" onchange="majReqHAL();">
&nbsp;<label for="year2">à</label>&nbsp;
<input type="text" class="form-control" style="height: 25px; width: 100px;" id="year2" name="year2" value="<?php echo date('Y', time());?>" size="10" onchange="majReqHAL();"><br/>
<br/>
<input type="checkbox" class="form-control" id="aparai" style="height: 15px;" name="aparai" value="ok" onchange="majReqHAL();"> <label for="aparai">inclure les articles <i>"A paraître"</i></label><br/>
<input type="checkbox" class="form-control" id="txtint" style="height: 15px;" name="txtint" value="ok" onchange="majReqHAL();"> <label for="txtint">requête uniquement sur le texte intégral (dépôt HAL ou lien arxiv ou lien Pubmed Central)</label><br/>
<input type="checkbox" class="form-control" id="desactSR" style="height: 15px;" checked name="desactSR" value="oui"> <label for="desactSR">désactiver les recherches Sherpa/RoMEO</label><br/>
<?php
include("./IP_list.php");
if (in_array($ip, $IP_aut)) {
  echo("<input type=\"checkbox\" class=\"form-control\" style=\"height: 15px;\" id=\"actMailsM\" name=\"actMailsM\" value=\"oui\"> <label for=\"actMailsM\">activer les procédures d'envoi de mails (demande du manuscrit à l'auteur = M)</label><br/>");
  echo("<input type=\"checkbox\" class=\"form-control\" style=\"height: 15px;\" id=\"actMailsP\" name=\"actMailsP\" value=\"oui\"> <label for=\"actMailsP\">activer les procédures d'envoi de mails (autorisation de dépôt du post-print = P)</label><br/>");
}else{
  echo("<br/>La fonctionnalité d'envoi d'emails ne s'affiche que pour les utilisateurs autorisés (adresse IP). Voir le mode d'emploi / installation<br/>");
}
?>
<input type="checkbox" class="form-control" style="height: 15px;" id="bibtex" name="bibtex" value="oui"> <label for="bibtex">générer un bibtex de publications en français, à partir d'un fichier Zotero, avec éventuellement,</label><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;. <label for="keywords">ces mots-clés (séparés par "; "):</label> <input type="text" class="form-control" style="height: 25px; width: 300px;" id="keywords" name="keywords" size="40"><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;. <label for="author">cet auteur ("Mortier, Renaud", par exemple):</label> <input type="text" class="form-control" style="height: 25px; width: 300px;" id="author" name="author" size="40">
<br/>
<br/><br/>
<label for="joker">URL Joker :</label> <input type="text" class="form-control" style="height: 25px; width: 800px;" id="joker" name="joker" size=100 value=""><br/><br/>
<input type="hidden" name="ip" value="<?php echo $ip; ?>">
<input type="submit" class="form-control btn btn-md btn-primary" value="Envoyer">
</form>
</p>
<br>
<?php
include('./bas.php');
?>
</body>
</html>
