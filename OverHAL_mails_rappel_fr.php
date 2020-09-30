<?php
//M
$subjectM = "Texte integral de votre article pour diffusion dans HAL : nouvel article";
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

$body .= "<br>La diffusion du <a href=&quot;https://openaccess.univ-rennes1.fr/quelles-versions-deposer-dans-hal&quot;>manuscrit auteur final &quot;pre-proof</a>&quot; est autorisée dans HAL (1) :<br>\
<br>\
Le manuscrit auteur &quot;pre-proof&quot; (<a href=&quot;http://learn.library.ryerson.ca/c.php?g=325807&p=2198200&quot;>exemple</a>) est la version après relecture des pairs (clean copy) qui précède les corrections finales des épreuves (proof) et la mise en forme de l'éditeur. (2)<br>\
<br>\
<b>Merci de bien vouloir déposer cette version du manuscrit</b> dans HAL (3), <b>ou si vous préférez, nous la transmettre</b>. Nous la déposerons pour vous, après publication de l'article.<br>\
<br>\
Pour rappel, je suis chargé de diffuser en open access les publications des laboratoires de l'Université de Rennes 1 dans <a href=&quot;https://hal-univ-rennes1.archives-ouvertes.fr/&quot;>l'archive ouverte HAL-Rennes 1</a> (<a &quot;https://openaccess.univ-rennes1.fr/&quot;>en savoir plus</a>).<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Bien à vous,<br>\
<br>\
Laurent Jonchère<br>\
Université de Rennes 1<br>\
Tél : (0)2 23 23 34 78<br>\
<a href=&quot;https://openaccess.univ-rennes1.fr/&quot;>https://openaccess.univ-rennes1.fr/</a><br>\
<br>\
(1) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;https://openaccess.univ-rennes1.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (<a href=&quot;https://openaccess.univ-rennes1.fr/que-dit-la-loi&quot;>article 30</a>) en vigueur depuis septembre 2016.<br>\
(2) La diffusion du PDF publisher ou des proofs n'est pas autorisée. <b>Si vous avez perdu votre manuscrit</b>, consultez cette <a href=&quot;https://openaccess.univ-rennes1.fr/je-nai-plus-mon-manuscrit/&quot;>page</a> pour le récupérer. Merci d'inclure figures, tables et, si possible, données supplémentaires.<br>\
(3) Voir le <a href=&quot;https://doc.archives-ouvertes.fr/deposer/&quot;>tutoriel du CCSD</a></font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Texte integral de votre article pour diffusion dans HAL : nouvel article";
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

$body .= "<br>La diffusion du <a href=&quot;https://openaccess.univ-rennes1.fr/quelles-versions-deposer-dans-hal&quot;>manuscrit auteur final &quot;pre-proof</a>&quot; est autorisée dans HAL (*) :<br>\
<br>\
<b>J'ai pu récupérer le manuscrit accepté</b> et corrigé de votre article sur le site de l'éditeur.<br>\
<b>Sauf opposition de votre part, je le déposerai dans HAL</b>, à moins que vous ne préfériez vous en charger vous-même.<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Bien à vous,<br>\
<br>\
Laurent Jonchère<br>\
Université de Rennes 1<br>\
Tél : (0)2 23 23 34 78<br>\
<a href=&quot;https://openaccess.univ-rennes1.fr/&quot;>https://openaccess.univ-rennes1.fr/</a><br>\
<br>\
(*) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;https://openaccess.univ-rennes1.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (<a href=&quot;https://openaccess.univ-rennes1.fr/que-dit-la-loi&quot;>article 30</a>) en vigueur depuis septembre 2016. La diffusion du PDF publisher ou des proofs n'est pas autorisée.<br>\
";

$bodyP = str_replace("'", "\'", $body);
?>
