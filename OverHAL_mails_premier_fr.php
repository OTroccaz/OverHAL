<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Contenu du corps du premier mail envoyé en français - Content of the body of the first e-mail sent in French
 */

//M
$subjectM = "Texte integral de votre article pour diffusion dans HAL";
$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je suis chargé de diffuser en open access les publications des laboratoires de l'Université de Rennes dans <a href=&quot;https://univ-rennes.hal.science/&quot;>l'archive ouverte HAL-Rennes</a> (<a href=&quot;https://scienceouverte.univ-rennes.fr/la-strategie-archive-ouverte&quot;>en savoir plus</a>).<br>\
";

switch($team)
{
  case "IETR":
    $body .= "Nous travaillons en collaboration avec <b>Ronan Sauleau</b>, directeur de l'IETR UMR CNRS 6164.<br>\\";
    break;
  case "FOTON":
    $body .= "Nous travaillons en collaboration avec <b>Pascal Besnard</b>, directeur de FOTON UMR CNRS 6082.<br>\\";
    break;
  case "LTSI":
    $body .= "Nous travaillons en collaboration avec <b>Mireille Garreau</b>, directrice du LTSI, Laboratoire traitement du signal et de l'image - U Inserm 1099.<br>\\";
    break;
  case "ISCR":
    $body .= "Nous travaillons en collaboration avec <b>Marc Fourmigué</b>, directeur de l'Institut des Sciences chimiques de Rennes UMR CNRS 6226.<br>\\";
    break;
  case "GR":
    $body .= "Nous travaillons en collaboration avec <b>l'UMR CNRS 6118 - Géosciences / OSUR</b>.<br>\\";
    break;
  case "IPR":
    $body .= "Nous travaillons en collaboration avec <b>Jean-Luc Autran</b>, directeur de l'Institut de Physique de Rennes - UMR CNRS 6251.<br>\\";
    break;
  case "LGCGM":
    $body .= "Nous travaillons en collaboration avec <b>Christophe Lanos</b>, co-directeur du Laboratoire de génie civil et génie mécanique (LGCGM) EA 3913.<br>\\";
    break;
  case "ECOBIO":
    $body .= "Nous travaillons en collaboration avec <b>Joan van Baaren</b>, directrice de l'UMR CNRS 6553 (ECOBIO).<br>\\";
    break;
  case "IGEPP":
    $body .= "Nous travaillons en collaboration avec <b>l'IGEPP</b> - UMR INRAE 1349 - Institut de génétique, environnement et protection des plantes.<br>\\";
    break;
  case "ETHOS":
    $body .= "Nous travaillons en collaboration avec <b>Ludovic Dickel</b>, directeur de l'UMR CNRS 6552 - ETHoS.<br>\\";
    break;
  case "CNGC":
    $body .= "Nous travaillons en collaboration avec les unités de recherche de l'université.<br>\\";
    break;
  case "IGDR":
    $body .= "Nous travaillons en collaboration avec <b>Reynald Gillet</b>, directeur de l'UMR CNRS 6290 - Institut de génétique et développement de Rennes (IGDR).<br>\\";
    break;
  case "BGC":
    $body .= "Nous travaillons en collaboration avec <b>Reynald Gillet</b>, directeur de l'ERL U 1305 - Biologie et génétique du cancer.<br>\\";
    break;
  case "IRSET":
    $body .= "Nous travaillons en collaboration avec <b>Michel Samson</b>, directeur de l'Institut de recherche en santé, environnement et travail (IRSET) U Inserm 1085.<br>\\";
    break;
  case "U991":
    $body .= "Nous travaillons en collaboration avec <b>Olivier Loréal</b>, directeur de l'U Inserm 991 « Foie, Métabolismes et Cancer ».<br>\\";
    break;
  case "U835":
    $body .= "Nous travaillons en collaboration avec <b>Vincent Cattoir</b>, directeur de l'<b>U Inserm 1230- ARN régulateurs bactériens et médecine</b>.<br>\\";
    break;
  case "U1230":
    $body .= "Nous travaillons en collaboration avec <b>Vincent Cattoir</b>, directeur de l'<b>U Inserm 1230- ARN régulateurs bactériens et médecine</b>.<br>\\";
    break;
  case "BRM":
    $body .= "Nous travaillons en collaboration avec <b>Vincent Cattoir</b>, directeur de l'<b>U Inserm 1230- ARN régulateurs bactériens et médecine</b>.<br>\\";
    break;
  case "U917":
    $body .= "Nous travaillons en collaboration avec <b>Karin Tarte</b>, directrice de l'U Inserm 1236 - Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC).<br>\\";
    break;
  case "MOBIDIC":
    $body .= "Nous travaillons en collaboration avec <b>Karin Tarte</b>, directrice de l'U Inserm - Microenvironment and B-cells: Immunopathology, Cell Differentiation, and Cancer (MOBIDIC).<br>\\";
    break;
  case "MICMAC":
    $body .= "Nous travaillons en collaboration avec <b>Karin Tarte</b>, directrice de l'U Inserm 1236 - Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC).<br>\\";
    break;
  case "CIC":
    $body .= "Nous travaillons en collaboration avec <b>Bruno Laviolle</b>, directeur du CIC 1414 Rennes.<br>\\";
    break;
  case "MRI":
    $body .= "Nous travaillons en collaboration avec <b>Martine Bonnaure-Mallet</b>, directrice de l'EA 1254.<br>\\";
    break;
  case "BIOSIT":
    $body .= "Nous travaillons en collaboration avec la <b>direction de l'UMS 3480/US Inserm 018 - SFR Biosit (Rennes)</b>.<br>\\";
    break;
  case "OSS":
    $body .= "Nous travaillons en collaboration avec <b>Eric Chevet</b>, directeur de l'U Inserm 1242 OSS (Oncogenesis, Stress and Signaling).<br>\\";
    break;
  case "COSS":
    $body .= "Nous travaillons en collaboration avec <b>Eric Chevet</b>, directeur de l'U Inserm 1242 OSS (Oncogenesis, Stress and Signaling).<br>\\";
    break;
  case "U1242":
    $body .= "Nous travaillons en collaboration avec <b>Eric Chevet</b>, directeur de l'U Inserm 1242 OSS (Oncogenesis, Stress and Signaling).<br>\\";
    break;
  case "NUMECAN":
    $body .= "Nous travaillons en collaboration avec <b>Olivier Loréal</b>, directeur de l'U Inserm 1317 NuMeCan.<br>\\";
    break;
  case "U1241":
    $body .= "Nous travaillons en collaboration avec <b>Olivier Loréal</b>, directeur de l'U Inserm 1317 NuMeCan.<br>\\";
    break;
  case "CREM":
    $body .= "Nous travaillons en collaboration avec <b>Fabien Moizeau</b>, directeur du Centre de Recherche en Économie et Management (CREM) - UMR CNRS 6211.<br>\\";
    break;
  case "CREAAH":
    $body .= "Nous travaillons en collaboration avec <b>Luc Laporte</b>, directeur de l'UMR CNRS 6556 - CREAAH / OSUR.<br>\\";
    break;
  case "CRAPE":
    $body .= "Nous travaillons en collaboration avec <b>Jean-Pierre Le Bourhis</b>, directeur de l'UMR CNRS 6051 - Arènes: politique, santé publique, environnement, médias (ARENES).<br>\\";
    break;
  case "IODE":
    $body .= "Nous travaillons en collaboration avec <b>Mme Moisdon-Chataigner</b>, directrice de UMR CNRS 6262 IODE.<br>\\";
    break;
  case "XXXX":
    $body .= "Nous travaillons en collaboration avec le <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "UNIV-RENNES":
    $body .= "Nous travaillons en collaboration avec les unités de recherche de l'université.<br>\\";
    break;
  case "SCANMAT":
    $body .= "Nous travaillons en collaboration avec <b>Maryline Guilloux-Viry</b>, directrice de l'UMS ScanMAT.<br>\\";
    break;
  case "RSMS":
    $body .= "Nous travaillons en collaboration avec <b>Emmanuelle Leray</b>, directrice de l'unité Recherche sur les services et le management en santé ERL Inserm U 1309.<br>\\";
    break;
  case "REPERES":
    $body .= "Nous travaillons en collaboration avec <b>Emmanuel Oger</b>, directeur de l'unité de recherche Repères EA 7449.<br>\\";
    break;
  case "CHU":
    $body .= "Nous travaillons en collaboration avec le <b>CHU de Rennes</b>.<br>\\";
    break;
  case "ISCR-VC":
    $body .= "Nous travaillons en collaboration avec <b>Xiang-Hua Zhang</b>, directeur de l'équipe Verres et Céramiques, UMR CNRS 6226.<br>\\";
    break;
}
$body .= "<br>\
Vous êtes l'auteur d'un article récent, dont la diffusion du <a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>manuscrit auteur final (pre-proof / clean copy)</a> est autorisée dans HAL (1) :<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>Le manuscrit auteur &quot;pre-proof&quot; (<a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>exemple</a>) est la version après relecture des pairs (clean copy) qui précède les corrections finales des épreuves et la mise en forme de l'éditeur. (2)<br>\
<br>\
<b>Merci de bien vouloir déposer cette version du manuscrit</b> dans HAL (3), <b>ou si vous préférez, nous la transmettre</b>. Nous la déposerons pour vous, après publication de l'article.<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Merci également d'ajouter si possible les <b>données de recherche</b> - supplementary data <b>(données brutes)</b> (4)<br>\
<br>\
Merci de votre contribution à la science ouverte.<br>\
<br>\
Bien à vous,<br>\
<br>\
Laurent Jonchère<br>\
= = = = = = = = = = <br>\
Université de Rennes<br>\
Tél : (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/&quot;>https://scienceouverte.univ-rennes.fr/</a><br>\
<br>\
(1) Les dépôts en texte intégral et avec par défaut une licence non commerciale de type <a href=&quot;https://creativecommons.org/licenses/by-nc/4.0/&quot;>CC-BY-NC</a> sont effectués dans le respect des <a href=&quot;https://scienceouverte.univ-rennes.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (<a href=&quot;https://scienceouverte.univ-rennes.fr/que-dit-la-loi&quot;>article 30</a>) en vigueur depuis septembre 2016.<br>\
(2) La diffusion du PDF publisher ou des proofs n'est pas autorisée. <b>Si vous avez perdu votre manuscrit</b>, consultez cette <a href=&quot;https://scienceouverte.univ-rennes.fr/je-nai-plus-mon-manuscrit/&quot;>page</a> pour le récupérer. Merci d'inclure figures, tables et, si possible, données supplémentaires.<br>\
(3) Voir le <a href=&quot;https://doc.archives-ouvertes.fr/deposer/&quot;>tutoriel du CCSD</a><br>\
(4) <b>Sauf opposition de votre part</b>, nous les déposerons dans l'entrepôt <a href=&quot;https://entrepot.recherche.data.gouv.fr/dataverse/univ-rennes/&quot;>Recherche Data Gouv</a> si cela s'avère pertinent.</font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Texte integral de votre article pour diffusion dans HAL";

$body = "<font face='corbel'>Bonjour,\
<br>\
<br>\
Je suis chargé de diffuser en open access les publications des laboratoires de l'Université de Rennes dans <a href=&quot;https://univ-rennes.hal.science/&quot;>l'archive ouverte HAL-Rennes</a> (<a href=&quot;https://scienceouverte.univ-rennes.fr/la-strategie-archive-ouverte/&quot;>en savoir plus</a>).<br>\
";

switch($team)
{
  case "IETR":
    $body .= "Nous travaillons en collaboration avec <b>Ronan Sauleau</b>, directeur de l'IETR UMR CNRS 6164.<br>\\";
    break;
  case "FOTON":
    $body .= "Nous travaillons en collaboration avec <b>Pascal Besnard</b>, directeur de FOTON UMR CNRS 6082.<br>\\";
    break;
  case "LTSI":
    $body .= "Nous travaillons en collaboration avec <b>Mireille Garreau</b>, directrice du LTSI, Laboratoire traitement du signal et de l'image - U Inserm 1099.<br>\\";
    break;
  case "ISCR":
    $body .= "Nous travaillons en collaboration avec <b>Marc Fourmigué</b>, directeur de l'Institut des Sciences chimiques de Rennes UMR CNRS 6226.<br>\\";
    break;
  case "GR":
    $body .= "Nous travaillons en collaboration avec <b>l'UMR CNRS 6118 - Géosciences / OSUR</b>.<br>\\";
    break;
  case "IPR":
    $body .= "Nous travaillons en collaboration avec <b>Jean-Luc Autran</b>, directeur de l'Institut de Physique de Rennes - UMR CNRS 6251.<br>\\";
    break;
  case "LGCGM":
    $body .= "Nous travaillons en collaboration avec <b>Christophe Lanos</b>, co-directeur du Laboratoire de génie civil et génie mécanique (LGCGM) EA 3913.<br>\\";
    break;
  case "ECOBIO":
    $body .= "Nous travaillons en collaboration avec <b>Joan van Baaren</b>, directrice de l'UMR CNRS 6553 (ECOBIO).<br>\\";
    break;
  case "IGEPP":
    $body .= "Nous travaillons en collaboration avec l'IGEPP.<br>\\";
    break;
  case "ETHOS":
    $body .= "Nous travaillons en collaboration avec <b>Ludovic Dickel</b>, directeur de l'UMR CNRS 6552 - ETHoS.<br>\\";
    break;
  case "CNGC":
    $body .= "Nous travaillons en collaboration avec les unités de recherche de l'université.<br>\\";
    break;
  case "IGDR":
    $body .= "Nous travaillons en collaboration avec <b>Reynald Gillet</b>, directeur de l'UMR CNRS 6290 - Institut de génétique et développement de Rennes (IGDR).<br>\\";
    break;
  case "BGC":
    $body .= "Nous travaillons en collaboration avec <b>Reynald Gillet</b>, directeur de l'ERL U 1305 - Biologie et génétique du cancer.<br>\\";
    break;
  case "IRSET":
    $body .= "Nous travaillons en collaboration avec <b>Michel Samson</b>, directeur de l'Institut de recherche en santé, environnement et travail (IRSET) U Inserm 1085.<br>\\";
    break;
  case "U991":
    $body .= "Nous travaillons en collaboration avec <b>Bruno Clément</b>, directeur de l'U Inserm 991 « Foie, Métabolismes et Cancer ».<br>\\";
    break;
  case "U835":
    $body .= "Nous travaillons en collaboration avec <b>Vincent Cattoir</b>, directeur de l'<b>U Inserm 1230- ARN régulateurs bactériens et médecine</b>.<br>\\";
    break;
  case "U1230":
    $body .= "Nous travaillons en collaboration avec <b>Vincent Cattoir</b>, directeur de l'<b>U Inserm 1230- ARN régulateurs bactériens et médecine</b>.<br>\\";
    break;
  case "BRM":
    $body .= "Nous travaillons en collaboration avec <b>Vincent Cattoir</b>, directeur de l'<b>U Inserm 1230- ARN régulateurs bactériens et médecine</b>.<br>\\";
    break;
  case "U917":
    $body .= "Nous travaillons en collaboration avec <b>Karin Tarte</b>, directrice de l'U Inserm 1236 - Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC).<br>\\";
    break;
  case "MOBIDIC":
    $body .= "Nous travaillons en collaboration avec <b>Karin Tarte</b>, directrice de l'U Inserm - Microenvironment and B-cells: Immunopathology, Cell Differentiation, and Cancer (MOBIDIC).<br>\\";
    break;
  case "MICMAC":
    $body .= "Nous travaillons en collaboration avec <b>Karin Tarte</b>, directrice de l'U Inserm 1236 - Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC).<br>\\";
    break;
  case "CIC":
    $body .= "Nous travaillons en collaboration avec <b>Bruno Laviolle</b>, directeur du CIC 1414 Rennes.<br>\\";
    break;
  case "MRI":
    $body .= "Nous travaillons en collaboration avec <b>Martine Bonnaure-Mallet</b>, directrice de l'EA 1254.<br>\\";
    break;
  case "BIOSIT":
    $body .= "Nous travaillons en collaboration avec la <b>direction de l'UMS 3480/US Inserm 018 - SFR Biosit (Rennes)</b>.<br>\\";
    break;
  case "OSS":
    $body .= "Nous travaillons en collaboration avec <b>Eric Chevet</b>, directeur de l'U Inserm 1242 OSS (Oncogenesis, Stress and Signaling).<br>\\";
    break;
  case "COSS":
    $body .= "Nous travaillons en collaboration avec <b>Eric Chevet</b>, directeur de l'U Inserm 1242 OSS (Oncogenesis, Stress and Signaling).<br>\\";
    break;
  case "U1242":
    $body .= "Nous travaillons en collaboration avec <b>Eric Chevet</b>, directeur de l'U Inserm 1242 OSS (Oncogenesis, Stress and Signaling).<br>\\";
    break;
  case "NUMECAN":
    $body .= "Nous travaillons en collaboration avec <b>Olivier Loréal</b>, directeur de l'U Inserm 1317 NuMeCan.<br>\\";
    break;
  case "U1241":
    $body .= "Nous travaillons en collaboration avec <b>Olivier Loréal</b>, directeur de l'U Inserm 1317 NuMeCan.<br>\\";
    break;
  case "CREM":
    $body .= "Nous travaillons en collaboration avec <b>Fabien Moizeau</b>, directeur du Centre de Recherche en Économie et Management (CREM) - UMR CNRS 6211.<br>\\";
    break;
  case "CREAAH":
    $body .= "Nous travaillons en collaboration avec <b>Luc Laporte</b>, directeur de l'UMR CNRS 6556 - CREAAH / OSUR.<br>\\";
    break;
  case "CRAPE":
    $body .= "Nous travaillons en collaboration avec <b>Jean-Pierre Le Bourhis</b>, directeur de l'UMR CNRS 6051 - Arènes: politique, santé publique, environnement, médias (ARENES).<br>\\";
    break;
  case "IODE":
    $body .= "Nous travaillons en collaboration avec <b>Mme Moisdon-Chataigner</b>, directrice de UMR CNRS 6262 IODE.<br>\\";
    break;
  case "XXXX":
    $body .= "Nous travaillons en collaboration avec le <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "UNIV-RENNES":
    $body .= "Nous travaillons en collaboration avec les unités de recherche de l'université.<br>\\";
    break;
  case "SCANMAT":
    $body .= "Nous travaillons en collaboration avec <b>Maryline Guilloux-Viry</b>, directrice de l'UMS ScanMAT.<br>\\";
    break;
  case "RSMS":
    $body .= "Nous travaillons en collaboration avec <b>Emmanuelle Leray</b>, directrice de l'unité Recherche sur les services et le management en santé ERL Inserm U 1309.<br>\\";
    break;
  case "REPERES":
    $body .= "Nous travaillons en collaboration avec <b>Emmanuel Oger</b>, directeur de l'unité de recherche Repères EA 7449.<br>\\";
    break;
  case "CHU":
    $body .= "Nous travaillons en collaboration avec <b>le CHU de Rennes</b>.<br>\\";
    break;
  case "ISCR-VC":
    $body .= "Nous travaillons en collaboration avec <b>Xiang-Hua Zhang</b>, directeur de l'équipe Verres et Céramiques, UMR CNRS 6226.<br>\\";
    break;
}

$body .= "<br>\
Vous êtes l'auteur d'un article récent, dont la diffusion du <a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>manuscrit auteur final (pre-proof)</a> est autorisée dans HAL (*) :<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>Le manuscrit auteur &quot;pre-proof&quot; (<a href=&quot;https://scienceouverte.univ-rennes.fr/quelles-versions-deposer-dans-hal&quot;>exemple</a>) est la version après relecture des pairs (clean copy) qui précède les corrections finales des épreuves et la mise en forme de l'éditeur.<br>\
<br>\
<b>J'ai pu récupérer le manuscrit</b> de votre article sur le site de l'éditeur.<br>\
<b>Sauf opposition de votre part, je le déposerai dans HAL</b>, à moins que vous ne préfériez vous en charger vous-même.<br>\
<br>\
L'intérêt de cette démarche est principalement de renforcer la visibilité de vos travaux sur le web : les documents ainsi déposés dans HAL sont visibles et accessibles au monde entier, à partir des principaux moteurs de recherche (Google Scholar, PubMed, Base, etc.) et des réseaux de recherche (ResearchGate...).<br>\
<br>\
Sauf opposition de votre part, nous déposerons également les données de recherche, s'il y en a, dans l'entrepôt <a href=&quot;https://entrepot.recherche.data.gouv.fr/dataverse/univ-rennes/&quot;>Recherche Data Gouv</a>, si cela s'avère pertinent.<br>\
<br>\
Merci de votre contribution à la science ouverte.<br>\
<br>\
Bien à vous,<br>\
<br>\
Laurent Jonchère<br>\
= = = = = = = = = = <br>\
Université de Rennes<br>\
Tél : (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/&quot;>https://scienceouverte.univ-rennes.fr/</a><br>\
<br>\
(*) Les dépôts en texte intégral et avec par défaut une licence non commerciale de type <a href=&quot;https://creativecommons.org/licenses/by-nc/4.0/&quot;>CC-BY-NC</a> sont effectués dans le respect des <a href=&quot;https://scienceouverte.univ-rennes.fr/connaitre-la-politique-des-editeurs&quot;>politiques d'éditeurs</a> et de la Loi sur le numérique (<a href=&quot;https://scienceouverte.univ-rennes.fr/que-dit-la-loi&quot;>article 30</a>) en vigueur depuis septembre 2016. La diffusion du PDF publisher ou des proofs n'est pas autorisée. Nous ajoutons au manuscrit les données de recherche (supplementary data) chaque fois que possible.<br>\
";

$bodyP = str_replace("'", "\'", $body);


?>
