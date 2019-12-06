<?php
//M
$subjectM = "Texte integral de votre article pour diffusion dans HAL";
$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je suis chargé de diffuser en open access les publications des laboratoires de l'Université de XXXXXXX dans <a href=&quot;https://hal-XXXXX.archives-ouvertes.fr/&quot;>l'archive ouverte HAL-XXXXXX</a> (<a href=&quot;https://ressource&quot;>en savoir plus</a>).<br>\
";

switch($team)
{
  case "TRUC":
    $body .= "Nous travaillons en collaboration avec Titi Toto, directeur de TRUC UMR CNRS 9999.<br>\\";
    break;
  case "MACHIN":
    $body .= "Nous travaillons en collaboration avec Toto Titi, directeur de MACHIN UMR_S Inserm 0999.<br>\\";
    break;
  case "CHU":
    $body .= "Nous travaillons en collaboration avec le CHU de XXXXX.<br>\\";
    break;
}
$body .= "<br>\
Vous êtes l'auteur d'un article récent, dont la diffusion du manuscrit &quot;<a href=&quot;https://openaccess.univ-rennes1.fr/quelles-versions-deposer-dans-hal&quot;>final draft post-refereeing</a>&quot; est autorisée dans HAL (1) :<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>Le &quot;final draft post-refereeing&quot; (<a href=&quot;http://learn.library.ryerson.ca/c.php?g=325807&p=2198200&quot;>exemple</a>) est la version post-refeering qui précède les corrections finales des proofs et la mise en forme du publisher. (2)<br>\
<br>\
<b>Merci de bien vouloir déposer cette version du manuscrit</b> dans HAL (3), <b>ou si vous préférez, nous la transmettre</b>. Nous la déposerons pour vous, après publication de l'article.<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Merci de votre contribution à la science ouverte.<br>\
<br>\
Bien à vous,<br>\
<br>\
Votre nom<br>\
Votre Institution<br>\
Tel : + 33 (0)0 00 00 00 00<br>\
<a href=&quot;https://votre-site-web.fr/&quot;></a><br>\
<br>\
(1) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;https://openaccess.univ-rennes1.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (<a href=&quot;https://openaccess.univ-rennes1.fr/que-dit-la-loi&quot;>article 30</a>) en vigueur depuis septembre 2016.<br>\
(2) La diffusion du PDF publisher ou des proofs n'est pas autorisée. Si votre article a été saisi dans un template, vous pouvez créer le manuscrit HAL en faisant un copier-coller du contenu du template dans un document Word, par exemple. Merci d'inclure figures, tables et, si possible, données supplémentaires.<br>\
(3) Voir le <a href=&quot;https://doc.archives-ouvertes.fr/deposer/&quot;>tutoriel du CCSD</a></font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Texte integral de votre article pour diffusion dans HAL";

$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je suis chargé de diffuser en open access les publications des laboratoires de l'Université de XXXXXX dans <a href=&quot;https://hal-XXXXX.archives-ouvertes.fr/&quot;>l'archive ouverte HAL-XXXXXXX</a> (<a href=&quot;https://ressource&quot;>en savoir plus</a>).<br>\
";

switch($team)
{
  case "TRUC":
    $body .= "Nous travaillons en collaboration avec Titi Toto, directeur de TRUC UMR CNRS 9999.<br>\\";
    break;
  case "MACHIN":
    $body .= "Nous travaillons en collaboration avec Toto Titi, directeur de MACHIN UMR_S Inserm 0999.<br>\\";
    break;
  case "CHU":
    $body .= "Nous travaillons en collaboration avec le CHU de XXXXX.<br>\\";
    break;
}

$body .= "<br>\
Vous êtes l'auteur d'un article récent, dont la diffusion du manuscrit &quot;<a href=&quot;https://openaccess.univ-rennes1.fr/quelles-versions-deposer-dans-hal&quot;>final draft post-refereeing</a>&quot; est autorisée dans HAL (1) :<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>Le &quot;final draft post-refereeing&quot; (<a href=&quot;http://learn.library.ryerson.ca/c.php?g=325807&p=2198200&quot;>exemple</a>) est la version post-refeering qui précède les corrections finales des proofs et la mise en forme du publisher.<br>\
<br>\
<b>J'ai pu récupérer le manuscrit</b> de votre article sur le site de l'éditeur.<br>\
<b>Sauf opposition de votre part, je le déposerai dans HAL</b>, à moins que vous ne préfériez vous en charger vous-même.<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Merci de votre contribution à la science ouverte.<br>\
<br>\
Bien à vous,<br>\
<br>\
Votre nom<br>\
Votre Institution<br>\
Tel : + 33 (0)0 00 00 00 00<br>\
<a href=&quot;https://votre-site-web.fr/&quot;></a><br>\
<br>\
(1) Les dépôts en texte intégral sont effectués dans le respect des <a href=&quot;https://openaccess.univ-rennes1.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (<a href=&quot;https://openaccess.univ-rennes1.fr/que-dit-la-loi&quot;>article 30</a>) en vigueur depuis septembre 2016. La diffusion du PDF publisher ou des proofs n'est pas autorisée.<br>\
";

$bodyP = str_replace("'", "\'", $body);


?>
