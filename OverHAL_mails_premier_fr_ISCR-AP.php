<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Contenu du corps du premier mail envoyé en français pour l'ISCR - Content of the body of the first email sent in French for the ISCR
 */

//M
$subjectM = "ISCR / Texte integral de votre article pour diffusion dans HAL";
$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je suis chargé du suivi de l'open access des publications de l'UMR CNRS 6226 dans <a href=&quot;https://univ-rennes.hal.science/&quot;>l'archive ouverte HAL-Rennes</a>.<br>\
";

$body .= "<br>\
Vous êtes l'auteur d'un article récent, dont la diffusion du <a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>manuscrit auteur final (pre-proof)</a> est autorisée dans HAL (*) :<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br><b>Merci d'ajouter le manuscrit à la référence HAL</b> ainsi que les données de recherche (supplementary data), soit via <b>l'application <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a></b>, soit en demandant la <b>propriété du dépôt HAL</b> (bouton en bas de la page).<br>\
<br>\
Le manuscrit auteur &quot;pre-proof&quot; (<a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>exemple</a>) est la version après relecture des pairs (clean copy / pre-copy edit) qui précède les corrections finales des épreuves et la mise en forme de l'éditeur.<br>\
Si vous ne savez pas comment récupérer cette version de manuscrit, consultez notre page : <a href=&quot;https://scienceouverte.univ-rennes.fr/je-nai-plus-mon-manuscrit&quot;>je n'ai plus mon manuscrit</a>.<br>\
<br>\
Pour rappel, voici la procédure :<br>\
<br>\
1. Connectez vous à <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a> de préférence dans <b>Firefox</b><br>\
2. Dans <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a>, entrez votre nom et prénom (ou idHAL si vous en avez un)<br>\
3. Dans <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a>, ajoutez le fichier en cliquant sur le bouton ADD (ne saisissez <b>aucun DOI</b> dans le formulaire qui s'affiche)<br>\
<br>\
Merci de votre contribution à la science ouverte.<br>\
<br>\
Bien à vous,<br>\
<br>\
Laurent Jonchère<br>\
Université de Rennes<br>\
Tél : (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/&quot;>https://scienceouverte.univ-rennes.fr/</a><br>\
<br>\
(*) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;https://scienceouverte.univ-rennes.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (article 30) en vigueur depuis septembre 2016. La diffusion du PDF publisher ou des proofs n'est pas autorisée.<br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "ISCR / POSTPRINT Texte integral de votre article pour diffusion dans HAL";

$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je suis chargé du suivi de l'open access des publications de l'UMR CNRS 6226 dans <a href=&quot;https://univ-rennes.hal.science/&quot;>l'archive ouverte HAL-Rennes</a>.<br>\
";

$body .= "<br>\
Vous êtes l'auteur d'un article récent, dont la diffusion du <a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>manuscrit auteur final (pre-proof)</a> est autorisée dans HAL (*) :<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br><b>Merci d'ajouter le manuscrit à la référence HAL</b> ainsi que les données de recherche (supplementary data), soit via <b>l'application <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a></b>, soit en demandant la <b>propriété du dépôt HAL</b> (bouton en bas de la page).<br>\
<br>\
Le manuscrit auteur &quot;pre-proof&quot; (<a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>exemple</a>) est la version après relecture des pairs (clean copy / pre-copy edit) qui précède les corrections finales des épreuves et la mise en forme de l'éditeur.<br>\
Si vous ne savez pas comment récupérer cette version de manuscrit, consultez notre page : <a href=&quot;https://scienceouverte.univ-rennes.fr/je-nai-plus-mon-manuscrit&quot;>je n'ai plus mon manuscrit</a>.<br>\
<br>\
Pour rappel, voici la procédure :<br>\
<br>\
1. Connectez vous à <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a> dans <b>Firefox</b><br>\
2. Dans <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a>, entrez votre nom et prénom (ou idHAL si vous en avez un)<br>\
3. Dans <a href=&quot;https://ccsdrennesvip.ccsd.cnrs.fr/MyHAL.php&quot;>MyHAL</a>, ajoutez le fichier en cliquant sur le bouton ADD (ne saisissez <b>aucun DOI</b> dans le formulaire qui s'affiche)<br>\
<br>\
Merci de votre contribution à la science ouverte.<br>\
<br>\
Bien à vous,<br>\
<br>\
Laurent Jonchère<br>\
Université de Rennes<br>\
Tél : (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/&quot;>https://scienceouverte.univ-rennes.fr/</a><br>\
<br>\
(*) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;https://scienceouverte.univ-rennes.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (article 30) en vigueur depuis septembre 2016. La diffusion du PDF publisher ou des proofs n'est pas autorisée.<br>\
";

$bodyP = str_replace("'", "\'", $body);


?>
