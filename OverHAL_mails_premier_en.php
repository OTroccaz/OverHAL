<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Contenu du corps du premier mail envoyé en anglais - Content of the body of the first e-mail sent in English
 */

//M
$subjectM = "Copy request for deposit in Rennes Open Access Repository";
$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am the manager of the University of Rennes <a href=&quot;https://univ-rennes.hal.science/&quot;>Open Access Repository</a>, containing the research output of the University's academic community.<br>\
";

switch($team)
{
  case "IETR":
    $body .= "We work in collaboration with <b>Prof Ronan Sauleau</b>, director of the IETR UMR CNRS 6164 research unit.<br>\\";
    break;
  case "FOTON":
    $body .= "We work in collaboration with the FOTON UMR CNRS 6082 research unit.<br>\\";
    break;
  case "LTSI":
    $body .= "We work in collaboration with <b>Prof Mirelle Garreau</b>, director of the Signal and Image Processing research unit (LTSI) - U Inserm 1099.<br>\\";
    break;
  case "ISCR":
    $body .= "We work in collaboration with <b>Prof Marc Fourmigue</b>, director of the Chemical Institute of Rennes - UMR CNRS 6226.<br>\\";
    break;
  case "GR":
    $body .= "We work in collaboration with the <b>Geosciences / OSUR research unit</b> at the University of Rennes 1.<br>\\";
    break;
  case "IPR":
    $body .= "We work in collaboration with <b>Prof Jean-Luc Autran</b>, director of the Institute of Physics - Rennes (IPR) UMR CNRS 6251.<br>\\";
    break;
  case "LGCGM":
    $body .= "We work in collaboration with <b>Prof Christophe Lanos</b>, co-director of the Mechanical and Civil Engineering research unit(LGCGM) EA 3913.<br>\\";
    break;
  case "ECOBIO":
    $body .= "We work in collaboration with <b>Prof Joan van Baaren</b>, director of the Ecosystems, Biodiversity, Evolution (ECOBIO) research unit UMR CNRS 6553.<br>\\";
    break;
  case "IGEPP":
    $body .= "We work in collaboration with the IGEPP Research unit.<br>\\";
    break;
  case "ETHOS":
    $body .= "We work in collaboration with <b>Prof Ludovic Dickel</b>, director of the Animal and Human Ethology (EthoS) research unit UMR CNRS 6552.<br>\\";
    break;
  case "CNGC":
    $body .= "We work in collaboration with <b>Rennes University Hospital.</b>.<br>\\";
    break;
  case "IGDR":
    $body .= "We work in collaboration with <b>Prof Reynald Gillet</b>, director of the Institute of Genetics and Development of Rennes (IGDR) UMR CNRS 6290.<br>\\";
    break;
  case "IGDR":
    $body .= "We work in collaboration with <b>Prof Reynald Gillet</b>, director of the Biology Genetics and Cancer research unit ERL U 1305.<br>\\";
    break;
  case "IRSET":
    $body .= "We work in collaboration with <b>Prof Michel Samson</b>, director of the Research Institute in Health, Environment and Occupation (IRSET) U Inserm 1085.<br>\\";
    break;
  case "U991":
    $body .= "We work in collaboration with <b>Prof Olivier Loreal</b> (Liver, Metabolisms and Cancer - U inserm 991 research unit) at the University of Rennes 1.<br>\\";
    break;
  case "U835":
    $body .= "We work in collaboration with <b>Prof Vincent Cattoir</b>, director of the <b>UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine</b>.<br>\\";
    break;
  case "U1230":
    $body .= "We work in collaboration with <b>Prof Vincent Cattoir</b>, director of the <b>UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine</b>.<br>\\";
    break;
  case "BRM":
    $body .= "We work in collaboration with <b>Prof Vincent Cattoir</b>, director of the <b>UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine</b>.<br>\\";
    break;
  case "U917":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
    break;
  case "MOBIDIC":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment and B-cells: Immunopathology, Cell Differentiation, and Cancer (MOBIDIC) - U Inserm research unit.<br>\\";
    break;
  case "MICMAC":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
    break;
  case "CIC":
    $body .= "We work in collaboration with <b>Prof Bruno Laviolle</b>, director of the Clinical Investigation Center - Rennes (CIC).<br>\\";
    break;
  case "MRI":
    $body .= "We work in collaboration with <b>Prof Martine Bonnaure-Mallet</b>, director of the Microbiology research unit.<br>\\";
    break;
  case "BIOSIT":
    $body .= "We work in collaboration with <b>Rennes University Hospital</b>.";
    break;
  case "OSS":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Oncogenesis, Stress and Signaling (OSS) research unit.<br>\\";
    break;
  case "COSS":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Oncogenesis, Stress and Signaling (OSS) research unit.<br>\\";
    break;
  case "U1242":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Oncogenesis, Stress and Signaling (OSS) research unit.<br>\\";
    break;
  case "NUMECAN":
    $body .= "We work in collaboration with <b>Prof Olivier Loreal</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1317.<br>\\";
    break;
  case "U1241":
    $body .= "We work in collaboration with <b>Prof Olivier Loreal</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1317.<br>\\";
    break;
  case "CREM":
    $body .= "We work in collaboration with the <b>UMR CNRS 6211 - CREM</b> research unit.<br>\\";
    break;
  case "CREAAH":
    $body .= "We work in collaboration with <b>Prof Luc Laporte</b>, director of the Archaeology, Archaeoscience and History (CReAAH) research unit UMR CNRS 6566.<br>\\";
    break;
  case "CRAPE":
    $body .= "We work in collaboration with <b>Prof Jean-Pierre Le Bourhis</b>, director of the Politics, Public Health, Environment, Media (ARENES) research unit UMR CNRS 6051.<br>\\";
    break;
  case "IODE":
    $body .= "We work in collaboration with <b>Mrs Moisdon-Chataigner</b>, director of Lab CNRS 6262 IODE</b>.<br>\\";
    break;
  case "XXXX":
    $body .= "We work in collaboration with the <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "UNIV-RENNES":
    $body .= "We work in collaboration with the <b>University research units</b>.<br>\\";
    break;
  case "SCANMAT":
    $body .= "We work in collaboration with Prof <b>Maryline Guilloux-Viry</b>, director of ScanMAT Research Platforms.<br>\\";
    break;
  case "RSMS":
    $body .= "We work in collaboration with Prof <b>Emmanuelle Leray</b>, director of the RSMS Inserm ERL u 1309 research unit.<br>\\";
    break;
  case "REPERES":
    $body .= "We work in collaboration with Prof <b>Emmanuel Oger</b>, director of the REPERES EA 7449 research unit.<br>\\";
    break;
  case "CHU":
    $body .= "We work in collaboration with <b>Rennes University Hospital</b>.<br>\\";
    break;
  case "ISCR-VC":
    $body .= "We work in collaboration with <b>Prof Xiang-Hua Zhang</b>, director of the Glass & Ceramics Team / Chemical Institute of Rennes - UMR CNRS 6226.<br>\\";
    break;
}

$body .= "<br>\
You are the author of an article that was published in a journal allowing deposit of revised author manuscripts in open access repositories (1):<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br><b>Would you please send us <a href=&quot;https://ecm.univ-rennes1.fr/nuxeo/site/esupversions/a4f17fcd-8301-407f-b53b-14053a7362f6&quot;>the final post-refereeing pre-copyedit version (clean copy)</a></b> of your article, that is the <b>final version that hasn't been formatted by the publisher.</b><br>\
<br>\
It is usually a doc file, which does not bear the look and feel of the journal, nor the publisher's copyright notice on it. (2)<br>\
<br>\
If you could also please send us the <b>supplementary data (RAW data)</b> along the manuscript, that would be perfect. (3)<br>\
<br>\
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed and so forth) and social networks (ResearchGate...).<br>\
<br>\
Best Regards,<br>\
<br>\
Laurent Jonchère<br>\
= = = = = = = = = = <br>\
Université de Rennes<br>\
Tel : + 33 (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/&quot;>https://scienceouverte.univ-rennes.fr/</a><br>\
<br>\
(1) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies.<br>\
(2) If you need help to <b>get your manuscript from the journal submission system</b>, please check <a href=&quot;https://openaccessbutton.org/direct2aam&quot;>Direct2AAM</a>. Please, do not send the publisher's PDF nor the proof version, since such versions cannot be deposited in a repository.<br>\
(3) With your consent, we will deposit them in the <a href=&quot;https://entrepot.recherche.data.gouv.fr/dataverse/univ-rennes/&quot;>Research Data Gouv data repository</a> if this proves relevant.</font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Copy request for deposit in Rennes Open Access Repository";

$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am the manager of the University of Rennes <a href=&quot;https://univ-rennes.hal.science/&quot;>Open Access Repository</a>, containing the research output of the University's academic community.<br>\
";

switch($team)
{
  case "IETR":
    $body .= "We work in collaboration with <b>Prof Ronan Sauleau</b>, director of the IETR UMR CNRS 6164 research unit.<br>\\";
    break;
  case "FOTON":
    $body .= "We work in collaboration with the FOTON UMR CNRS 6082 research unit.<br>\\";
    break;
  case "LTSI":
    $body .= "We work in collaboration with <b>Prof Mirelle Garreau</b>, director of the Signal and Image Processing research unit (LTSI) - U Inserm 1099.<br>\\";
    break;
  case "ISCR":
    $body .= "We work in collaboration with <b>Prof Marc Fourmigue</b>, director of the Chemical Institute of Rennes - UMR CNRS 6226.<br>\\";
    break;
  case "GR":
    $body .= "We work in collaboration with the <b>Geosciences / OSUR</b> research unit at the University of Rennes 1.<br>\\";
    break;
  case "IPR":
    $body .= "We work in collaboration with <b>Prof Jean-Luc Autran</b>, director of the Institute of Physics - Rennes (IPR) UMR CNRS 6251.<br>\\";
    break;
  case "LGCGM":
    $body .= "We work in collaboration with <b>Prof Christophe Lanos</b>, co-director of the Mechanical and Civil Engineering research unit(LGCGM) EA 3913.<br>\\";
    break;
  case "ECOBIO":
    $body .= "We work in collaboration with <b>Prof Joan van Baaren</b>, director of the Ecosystems, Biodiversity, Evolution (ECOBIO) research unit UMR CNRS 6553.<br>\\";
    break;
  case "IGEPP":
    $body .= "We work in collaboration with the IGEPP Research unit.<br>\\";
    break;
  case "ETHOS":
    $body .= "We work in collaboration with <b>Prof Ludovic Dickel</b>, director of the Animal and Human Ethology (EthoS) research unit UMR CNRS 6552.<br>\\";
    break;
  case "CNGC":
    $body .= "We work in collaboration with <b>Rennes University Hospital</b>.";
    break;
  case "IGDR":
    $body .= "We work in collaboration with <b>Prof Reynald Gillet</b>, director of the Institute of Genetics and Development of Rennes (IGDR) UMR CNRS 6290.<br>\\";
    break;
  case "IRSET":
    $body .= "We work in collaboration with <b>Prof Michel Samson</b>, director of the Research Institute in Health, Environment and Occupation (IRSET) U Inserm 1085.<br>\\";
    break;
  case "U991":
    $body .= "We work in collaboration with <b>Prof Bruno Clement</b> (Liver, Metabolisms and Cancer - U inserm 991 research unit) at the University of Rennes 1.<br>\\";
    break;
  case "U835":
    $body .= "We work in collaboration with <b>Prof Vincent Cattoir</b>, director of the <b>UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine</b>.<br>\\";
    break;
  case "U1230":
    $body .= "We work in collaboration with <b>Prof Vincent Cattoir</b>, director of the <b>UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine</b>.<br>\\";
    break;
  case "BRM":
    $body .= "We work in collaboration with <b>Prof Vincent Cattoir</b>, director of the <b>UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine</b>.<br>\\";
    break;
  case "U917":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
    break;
  case "MOBIDIC":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment and B-cells: Immunopathology, Cell Differentiation, and Cancer (MOBIDIC) - U inserm research unit.<br>\\";
    break;
  case "MICMAC":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
    break;
  case "CIC":
    $body .= "We work in collaboration with <b>Prof Bruno Laviolle</b>, director of the Clinical Investigation Center - Rennes (CIC).<br>\\";
    break;
  case "MRI":
    $body .= "We work in collaboration with <b>Prof Martine Bonnaure-Mallet</b>, director of the Microbiology research unit.<br>\\";
    break;
  case "BIOSIT":
    $body .= "We work in collaboration with <b>Rennes University Hospital</b>.";
    break;
  case "OSS":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "COSS":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "U1242":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "NUMECAN":
    $body .= "We work in collaboration with <b>Prof Olivier Loreal</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1317.<br>\\";
    break;
  case "U1241":
    $body .= "We work in collaboration with <b>Prof Olivier Loreal</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1317.<br>\\";
    break;
  case "CREM":
    $body .= "We work in collaboration with the <b>UMR CNRS 6211 - CREM</b> research unit.<br>\\";
    break;
  case "CREAAH":
    $body .= "We work in collaboration with <b>Prof Luc Laporte</b>, director of the Archaeology, Archaeoscience and History (CReAAH) research unit UMR CNRS 6566.<br>\\";
    break;
  case "CRAPE":
    $body .= "We work in collaboration with <b>Prof Jean-Pierre Le Bourhis</b>, director of the Politics, Public Health, Environment, Media (ARENES) research unit UMR CNRS 6051.<br>\\";
    break;
  case "IODE":
    $body .= "We work in collaboration with <b>Mrs Moisdon-Chataigner</b>, directror of lab CNRS 6262 IODE.<br>\\";
    break;
  case "XXXX":
    $body .= "We work in collaboration with the <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "UNIV-RENNES1":
    $body .= "We work in collaboration with the <b>University research units</b>.<br>\\";
    break;
  case "SCANMAT":
    $body .= "We work in collaboration with Prof <b>Maryline Guilloux-Viry</b>, director of ScanMAT Research Platforms.<br>\\";
    break;
  case "RSMS":
    $body .= "We work in collaboration with Prof <b>Emmanuelle Leray</b>, director of the RSMS Inserm ERL u 1309 research unit.<br>\\";
    break;
  case "REPERES":
    $body .= "We work in collaboration with Prof <b>Emmanuel Oger</b>, director of the REPERES EA 7449 research unit.<br>\\";
    break;
  case "CHU":
    $body .= "We work in collaboration with <b>Rennes University Hospital</b>.<br>\\";
    break;
  case "ISCR-VC":
    $body .= "We work in collaboration with <b>Prof Xiang-Hua Zhang</b>, director of the Glass & Ceramics Team / Chemical Institute of Rennes - UMR CNRS 6226.<br>\\";
    break;
}

$body .= "<br>\
You are the author of an article that was published in a journal allowing deposit of revised author manuscripts in open access repositories (*):<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>This journal allows use of accepted manuscripts in repositories, ie. <a href=&quot;https://ecm.univ-rennes1.fr/nuxeo/site/esupversions/a4f17fcd-8301-407f-b53b-14053a7362f6&quot;>final drafts post-refereeing (clean copy)</a> that haven't been formatted by the publisher (2).<br>\
<br>\
<b>I was able to retrieve the accepted manuscript from the publisher's platform.<br>\
I shall deposit it in our institutional repository, as is authorized by the publisher, unless you tell us otherwise.</b><br>\
<br>\
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed...) and social networks (ResearchGate...).<br>\
<br>\
With your consent, we will also deposit the supplementary data in the <a href=&quot;https://entrepot.recherche.data.gouv.fr/dataverse/univ-rennes/&quot;>Research Data Gouv data repository</a>, if applicable.<br>\
<br>\
Best Regards,<br>\
<br>\
Laurent Jonchère<br>\
= = = = = = = = = = <br>\
Université de Rennes<br>\
Tel : + 33 (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/&quot;>https://scienceouverte.univ-rennes.fr/</a><br>\
<br>\
(*) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies. We also add the supplementary data whenever possible.<br>\
";

$bodyP = str_replace("'", "\'", $body);


?>
