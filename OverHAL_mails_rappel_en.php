<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Contenu du corps du mail de rappel envoyé en anglais - Content of the body of the reminder e-mail sent in English
 */

//M
$subjectM = "Copy request for deposit in Rennes Open Access Repository: new article";
$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am writing to you again as you are the author of an article that was published in a journal allowing deposit of author manuscripts in open access repositories (1):<br>\
";

if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br><b>Would you please send us <a href=&quot;https://ged.univ-rennes1.fr/nuxeo/site/esupversions/a4f17fcd-8301-407f-b53b-14053a7362f6&quot;>the final post-refereeing pre-copyedit version (clean copy)</a></b> of your article, that is the <b>final version that hasn't been formatted by the publisher.</b><br>\
<br>\
It is usually a doc file, which does not bear the look and feel of the journal, nor the publisher's copyright notice on it. (2)<br>\
<br>\
If you could also please send us the <b>supplementary data (RAW data)</b> along the manuscript, that would be perfect.(3)<br>\
<br>\
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed and so forth) and social networks (ResearchGate...).<br>\
<br>\
Best Regards,<br>\
<br>\
Laurent Jonchère<br>\
University of Rennes<br>\
Tel : + 33 (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/en/&quot;>https://scienceouverte.univ-rennes.fr/en/</a><br>\
<br>\
(1) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies.<br>\
(2) If you need help to <b>get your manuscript from the journal submission system</b>, please check <a href=&quot;https://openaccessbutton.org/direct2aam&quot;>Direct2AAM</a>. Please, do not send the publisher's PDF nor the proof version, since such versions cannot be deposited in a repository.<br>\
(3) With your consent, we will deposit them in the <a href=&quot;https://entrepot.recherche.data.gouv.fr/dataverse/univ-rennes/&quot;>Research Data Gouv data repository</a> if this proves relevant.</font><br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Copy request for deposit in Rennes Open Access Repository: new article";
$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am writing to you again as you are the author of an article that was published in a journal allowing deposit of author manuscripts in open access repositories (*):<br>\
";

//strtr($data[$colTitle],'"<>','   ')
if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>This journal allows use of accepted manuscripts in repositories, ie. <a href=&quot;https://ged.univ-rennes1.fr/nuxeo/site/esupversions/a4f17fcd-8301-407f-b53b-14053a7362f6&quot;>final drafts post-refereeing (clean copy)</a> that haven't been formatted by the publisher.<br>\
<br>\
<b>I was able to retrieve the accepted manuscript from the publisher's platform.<br>\
I shall deposit it in our institutional repository, as is authorized by the publisher, unless you tell us otherwise.</b><br>\
<br>\
With your consent, we will also deposit the supplementary data in the <a href=&quot;https://entrepot.recherche.data.gouv.fr/dataverse/univ-rennes/&quot;>Research Data Gouv data repository</a>, if applicable.<br>\
<br>\
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed...) and social networks (ResearchGate...).<br>\
<br>\
Best Regards,<br>\
<br>\
Laurent Jonchère<br>\
University of Rennes 1<br>\
Tel : + 33 (0)2 23 23 34 78<br>\
<a href=&quot;https://scienceouverte.univ-rennes.fr/en/&quot;>https://scienceouverte.univ-rennes.fr/en/</a><br>\
<br>\
(*) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies. We also add the supplementary data whenever possible<br>\
";

$bodyP = str_replace("'", "\'", $body);
?>
