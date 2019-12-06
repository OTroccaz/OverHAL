<?php
//M
$subjectM = "Texte intégral de votre article pour diffusion dans HAL : nouvel article";
$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je me permets de vous écrire à nouveau au sujet d'un article dont vous êtes co-auteur :<br>\
";

//strtr($data[$colTitle],'"<>','   ')
if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>La diffusion du manuscrit &quot;<a href=&quot;url ressource&quot;>final draft post-refereeing</a>&quot; est autorisée dans HAL (1) :<br>\
<br>\
Le &quot;final draft post-refereeing&quot; (<a href=&quot;url ressource&quot;>exemple</a>) est la version post-refeering qui précède les corrections finales des proofs et la mise en forme du publisher. (2)<br>\
<br>\
<b>Merci de bien vouloir déposer cette version du manuscrit</b> dans HAL (3), <b>ou si vous préférez, nous la transmettre</b>. Nous la déposerons pour vous, après publication de l'article.<br>\
<br>\
Pour rappel, je suis chargé de diffuser en open access les publications des laboratoires de nom de votre institution dans <a href=&quot;https://hal-XXXXX.archives-ouvertes.fr/&quot;>l'archive ouverte HAL-XXXXX</a> (<a &quot;https://ressource&quot;>en savoir plus</a>).<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Bien à vous,<br>\
<br>\
Votre nom<br>\
Votre Institution<br>\
Tel : + 33 (0)0 00 00 00 00<br>\
<a href=&quot;https://votre-site-web.fr/&quot;></a><br>\
<br>\
(1) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;url ressource&quot;>politiques d'éditeurs</a> et de la Loi numérique (<a href=&quot;https://ressource&quot;>article 30</a>) en vigueur depuis octobre 2016.<br>\
(2) La diffusion du PDF publisher ou des proofs n'est pas autorisée. Si votre article a été saisi dans un template, vous pouvez créer le manuscrit HAL en faisant un copier-coller du contenu du template dans un document Word, par exemple. Merci d'inclure figures, tables et, si possible, données supplémentaires.<br>\
(3) Voir le <a href=&quot;https://doc.archives-ouvertes.fr/deposer/&quot;>tutoriel du CCSD</a></font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Texte intégral de votre article pour diffusion dans HAL : nouvel article";
$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je me permets de vous écrire à nouveau au sujet d'un article dont vous êtes co-auteur :<br>\
";

//strtr($data[$colTitle],'"<>','   ')
if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>La diffusion du manuscrit &quot;<a href=&quot;url ressource&quot;>final draft post-refereeing</a>&quot; est autorisée dans HAL (1) :<br>\
<br>\
<b>J'ai pu récupérer le manuscrit accepté</b> et corrigé de votre article sur le site de l'éditeur.<br>\
<b>Sauf opposition de votre part, je le déposerai dans HAL</b>, à moins que vous ne préfériez vous en charger vous-même.<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Bien à vous,<br>\
<br>\
Votre nom<br>\
Votre Institution<br>\
Tel : + 33 (0)0 00 00 00 00<br>\
<a href=&quot;https://votre-site-web.fr/&quot;></a><br>\
<br>\
(1) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;url ressource&quot;>politiques d'éditeurs</a> et de la Loi numérique (<a href=&quot;https://ressource&quot;>article 30</a>) en vigueur depuis octobre 2016. La diffusion du PDF publisher ou des proofs n'est pas autorisée.<br>\
";

$bodyP = str_replace("'", "\'", $body);
?>