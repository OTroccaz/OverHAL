<?php
//M
$subjectM = "Copy request for deposit in Nom de votre structure Open Access Repository: new article";
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

$body .= "<br><b>Would you please send us <a href=&quot;url ressource&quot;>the final draft post-refereeing</a></b> of your article, that is the <b>final version that hasn’t been formatted by the publisher.</b><br>\
<br>\
It is usually a doc file, which does not bear the look and feel of the journal, nor the publisher's copyright notice on it. (2)<br>\
<br>\
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed and so forth) and social networks (ResearchGate...).<br>\
<br>\
Best Regards,<br>\
<br>\
Your name<br>\
Your Institution<br>\
Tel : + 33 (0)0 00 00 00 00<br>\
<a href=&quot;https://votre-site-web.fr/&quot;></a><br>\
<br>\
(1) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies.<br>\
(2) If your article was typed in a template, you may easily copy and paste its content into a doc file. Please, do not send the publisher's PDF nor the proof version, since such versions cannot be deposited in a repository.<br>\
";

$bodyM = str_replace("'", "\'", $body);

//P
$subjectP = "POSTPRINT Copy request for deposit in Nom de votre structure Open Access Repository: new article";
$body = "<font face='corbel'>Dear Colleague,\
<br>\
<br>\
I am writing to you again as you are the author of an article that was published in a journal allowing deposit of author manuscripts in open access repositories (1):<br>\
";

//strtr($data[$colTitle],'"<>','   ')
if ($refdoi != "")
{
  $body .= "<br><b><a href=&quot;https://dx.doi.org/".$refdoi."&quot;>".$data[$colTitle]."</a></b><br>\\";
}else{
  $body .= "<br><b>".$data[$colTitle]."</b><br>\\";
}

$body .= "<br>This journal allows use of accepted manuscripts in repositories, ie. <a href=&quot;url ressource&quot;>final drafts post-refereeing</a> that haven’t been formatted by the publisher (2).<br>\
<br>\
<b>I was able to retrieve the accepted manuscript from the publisher's platform.<br>\
I shall deposit it in our institutional repository, as is authorized by the publisher, unless you tell us otherwise.</b><br>\
<br>\
Making your manuscripts open access will enhance their visibility on the Internet, through search engines (Google Scholar, PubMed...) and social networks (ResearchGate...).<br>\
<br>\
Best Regards,<br>\
<br>\
Your name<br>\
Your Institution<br>\
Tel : + 33 (0)0 00 00 00 00<br>\
<a href=&quot;https://votre-site-web.fr/&quot;></a><br>\
<br>\
(1) Publisher and journal archiving policies can be checked in the <a href=&quot;http://www.sherpa.ac.uk/romeo/&quot;>Sherpa Romeo</a> database. We always check publisher policies for any deposit. Moreover, no article will be visible before the end of the embargo period, depending on journal policies.<br>\
(2) If your article was typed in a template, you may easily copy and paste its content into a doc file. Please, do not send the publisher's PDF nor the proof version, since such versions cannot be deposited in a repository.<br>\
";

$bodyP = str_replace("'", "\'", $body);
?>