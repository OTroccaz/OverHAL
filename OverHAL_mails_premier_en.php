<?php
//M
$subjectM = "Copy request for deposit in Rennes 1 Open Access Repository";
$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am the manager of the University of Rennes 1 <a href=&quot;https://hal-univ-rennes1.archives-ouvertes.fr/index/index/lang/en/&quot;>Open Access Repository</a>, containing the research output of the University's academic community.<br>\
";

switch($team)
{
  case "IETR":
    $body .= "We work in collaboration with <b>Prof Ronan Sauleau</b>, director of the IETR UMR CNRS 6164 research unit.<br>\\";
    break;
  case "FOTON":
    $body .= "We work in collaboration with <b>Prof Pascal Besnard</b>, director of the FOTON UMR CNRS 6082 research unit.<br>\\";
    break;
  case "LTSI":
    $body .= "We work in collaboration with <b>Prof Lotfi Senhadji</b>, director of the Signal and Image Processing research unit (LTSI) - U Inserm 1099.<br>\\";
    break;
  case "ISCR":
    $body .= "We work in collaboration with <b>Prof Marc Fourmigue</b>, director of the Chemical Institute of Rennes - UMR CNRS 6226.<br>\\";
    break;
  case "GR":
    $body .= "We work in collaboration with the <b>Geosciences / OSUR research unit</b> at the University of Rennes 1.<br>\\";
    break;
  case "IPR":
    $body .= "We work in collaboration with <b>Prof Jean-Christophe Sangleboeuf</b>, director of the Institute of Physics - Rennes (IPR) UMR CNRS 6251.<br>\\";
    break;
  case "LGCGM":
    $body .= "We work in collaboration with <b>Prof Christophe Lanos</b>, director of the Mechanical and Civil Engineering research unit(LGCGM) EA 3913.<br>\\";
    break;
  case "ECOBIO":
    $body .= "We work in collaboration with <b>Prof Joan van Baaren</b>, director of the Ecosystems, Biodiversity, Evolution (ECOBIO) research unit UMR CNRS 6553.<br>\\";
    break;
  case "IGEPP":
    $body .= "";
    break;
  case "ETHOS":
    $body .= "We work in collaboration with <b>Prof Alban Lemasson</b>, director of the Animal and Human Ethology (EthoS) research unit UMR CNRS 6552.<br>\\";
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
    $body .= "We work in collaboration with <b>Prof Brice Felden</b>, director of the UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine.<br>\\";
    break;
  case "U1230":
    $body .= "We work in collaboration with <b>Prof Brice Felden</b>, director of the UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine.<br>\\";
    break;
  case "BRM":
    $body .= "We work in collaboration with <b>Prof Brice Felden</b>, director of the UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine.<br>\\";
    break;
  case "U917":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
    break;
  case "U1236":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
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
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Chemistry, Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "COSS":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Chemistry, Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "U1242":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Chemistry, Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "NUMECAN":
    $body .= "We work in collaboration with <b>Prof Bruno Clement</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1241.<br>\\";
    break;
  case "U1241":
    $body .= "We work in collaboration with <b>Prof Bruno Clement</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1241.<br>\\";
    break;
  case "CREM":
    $body .= "We work in collaboration with the <b>UMR CNRS 6211 - CREM</b> research unit.<br>\\";
    break;
  case "CREAAH":
    $body .= "We work in collaboration with <b>Prof Marie-Yvane Daire</b>, director of the Archaeology, Archaeoscience and History (CReAAH) research unit UMR CNRS 6566.<br>\\";
    break;
  case "CRAPE":
    $body .= "We work in collaboration with <b>Prof Sylvie Ollitrault</b>, director of the Centre For Research On Political Action in Europe (ARENES) UMR CNRS 6051.<br>\\";
    break;
  case "PN":
    $body .= "We work in collaboration with the <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "CAPHI":
    $body .= "We work in collaboration with the <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "UNIV-RENNES1":
    $body .= "We work in collaboration with the <b>University research units</b>.<br>\\";
    break;
  case "SCANMAT":
    $body .= "We work in collaboration with Prof <b>Maryline Guilloux-Viry</b>, director of ScanMAT Research Platforms.<br>\\";
    break;
  case "GERMO":
    $body .= "We work in collaboration with Prof <b>Samer Kayal</b>, director of the GeRMO research unit.<br>\\";
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
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed and so forth) and social networks (ResearchGate...).<br>\
<br>\
Best Regards,<br>\
<br>\
Laurent Jonchère<br>\
University of Rennes 1<br>\
Tel : + 33 (0)2 23 23 34 78<br>\
<a href=&quot;https://openaccess.univ-rennes1.fr/&quot;>https://openaccess.univ-rennes1.fr/</a><br>\
<br>\
(1) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies.<br>\
(2) If you need help to <b>get your manuscript from the journal submission system</b>, please check <a href=&quot;https://openaccessbutton.org/direct2aam&quot;>Direct2AAM</a>. Please, do not send the publisher's PDF nor the proof version, since such versions cannot be deposited in a repository.</font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Copy request for deposit in Rennes 1 Open Access Repository";

$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am the manager of the University of Rennes 1 <a href=&quot;https://hal-univ-rennes1.archives-ouvertes.fr/index/index/lang/en/&quot;>Open Access Repository</a>, containing the research output of the University's academic community.<br>\
";

switch($team)
{
  case "IETR":
    $body .= "We work in collaboration with <b>Prof Ronan Sauleau</b>, director of the IETR UMR CNRS 6164 research unit.<br>\\";
    break;
  case "FOTON":
    $body .= "We work in collaboration with <b>Prof Pascal Besnard</b>, director of the FOTON UMR CNRS 6082 research unit.<br>\\";
    break;
  case "LTSI":
    $body .= "We work in collaboration with <b>Prof Lotfi Senhadji</b>, director of the Signal and Image Processing research unit (LTSI) - U Inserm 1099.<br>\\";
    break;
  case "ISCR":
    $body .= "We work in collaboration with <b>Prof Marc Fourmigue</b>, director of the Chemical Institute of Rennes - UMR CNRS 6226.<br>\\";
    break;
  case "GR":
    $body .= "We work in collaboration with the <b>Geosciences / OSUR</b> research unit at the University of Rennes 1.<br>\\";
    break;
  case "IPR":
    $body .= "We work in collaboration with <b>Prof Jean-Christophe Sangleboeuf</b>, director of the Institute of Physics - Rennes (IPR) UMR CNRS 6251.<br>\\";
    break;
  case "LGCGM":
    $body .= "We work in collaboration with <b>Prof Christophe Lanos</b>, director of the Mechanical and Civil Engineering research unit(LGCGM) EA 3913.<br>\\";
    break;
  case "ECOBIO":
    $body .= "We work in collaboration with <b>Prof Joan van Baaren</b>, director of the Ecosystems, Biodiversity, Evolution (ECOBIO) research unit UMR CNRS 6553.<br>\\";
    break;
  case "IGEPP":
    $body .= "";
    break;
  case "ETHOS":
    $body .= "We work in collaboration with <b>Prof Alban Lemasson</b>, director of the Animal and Human Ethology (EthoS) research unit UMR CNRS 6552.<br>\\";
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
    $body .= "We work in collaboration with <b>Prof Brice Felden</b>, director of the UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine.<br>\\";
    break;
  case "U1230":
    $body .= "We work in collaboration with <b>Prof Brice Felden</b>, director of the UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine.<br>\\";
    break;
  case "BRM":
    $body .= "We work in collaboration with <b>Prof Brice Felden</b>, director of the UMR_S Inserm 1230 research unit - Bacterial regulatory RNAs and Medicine.<br>\\";
    break;
  case "U917":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
    break;
  case "U1236":
    $body .= "We work in collaboration with <b>Prof Karin Tarte</b>, director of the Microenvironment, Cell Differentiation, Immunology and Cancer (MICMAC) - U Inserm 1236 research unit.<br>\\";
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
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Chemistry, Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "COSS":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Chemistry, Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "U1242":
    $body .= "We work in collaboration with <b>Prof Eric Chevet</b>, director of the U Inserm 1242 - Chemistry, Oncogenesis, Stress and Signaling (COSS) research unit.<br>\\";
    break;
  case "NUMECAN":
    $body .= "We work in collaboration with <b>Prof Bruno Clement</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1241.<br>\\";
    break;
  case "U1241":
    $body .= "We work in collaboration with <b>Prof Bruno Clement</b>, director of the Nutrition, Metabolisms and Cancer (NUMECAN) research unit U Inserm 1241.<br>\\";
    break;
  case "CREM":
    $body .= "We work in collaboration with the <b>UMR CNRS 6211 - CREM</b> research unit.<br>\\";
    break;
  case "CREAAH":
    $body .= "We work in collaboration with <b>Prof Marie-Yvane Daire</b>, director of the Archaeology, Archaeoscience and History (CReAAH) research unit UMR CNRS 6566.<br>\\";
    break;
  case "CRAPE":
    $body .= "We work in collaboration with <b>Prof Sylvie Ollitrault</b>, director of the Centre For Research On Political Action in Europe (ARENES) UMR CNRS 6051.<br>\\";
    break;
  case "PN":
    $body .= "We work in collaboration with the <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "CAPHI":
    $body .= "We work in collaboration with the <b>Centre Atlantique de Philosophie EA 7463</b>.<br>\\";
    break;
  case "UNIV-RENNES1":
    $body .= "We work in collaboration with the <b>University research units</b>.<br>\\";
    break;
  case "SCANMAT":
    $body .= "We work in collaboration with Prof <b>Maryline Guilloux-Viry</b>, director of ScanMAT Research Platforms.<br>\\";
    break;
  case "GERMO":
    $body .= "We work in collaboration with Prof <b>Samer Kayal</b>, director of the GeRMO research unit.<br>\\";
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
Best Regards,<br>\
<br>\
Laurent Jonchère<br>\
University of Rennes 1<br>\
Tel : + 33 (0)2 23 23 34 78<br>\
<a href=&quot;https://openaccess.univ-rennes1.fr/&quot;>https://openaccess.univ-rennes1.fr/</a><br>\
<br>\
(*) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies.<br>\
";

$bodyP = str_replace("'", "\'", $body);


?>
