<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Procédure HTML - HTML procedure
 */
 
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "./HAL_SCD.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="fr">

<head>
  <title>OverHAL : Importation HTML</title>
  <meta name="Description" content="OverHAL : HTML import">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
</head>

<body>
<?php
$wos = 0;
$pubmed = 0;

if (file_exists("./WoS.html"))//HTML Wos file has been submitted
{
  $handle = fopen("./WoS.html", "r");
  $wos = 1;
}
if ($wos == 1)
{
  //$wos = 1;
  $page = "";
  while (!feof($handle))
  { //reading all the lines
    $page .= fgets($handle, 4096); //reading content of each line
  }
  fclose($handle);
}

if ($wos == 1)
{
  $rec1 = strpos($page, "Record 1 of "); //searching the first record
  if ($rec1 !== false) //there is at least 1 record
  {
    //export results in a CSV file
    $Fnm = "./HAL/wos_html.csv"; 
    $inF = fopen($Fnm,"w"); 
    fseek($inF, 0);
    $chaine = "\xEF\xBB\xBF";
    fwrite($inF,$chaine);
    
    $inF = fopen($Fnm,"a+"); 
    fseek($inF, 0);
    fwrite($inF, "Title^Authors^Source title^DOI^Year^ISSN^Document Type".chr(13).chr(10));
    
    $pos1 = $rec1 + strlen("Record 1 of ");
    $point = strpos($page, ".", $pos1);
    $recMax = substr($page, $pos1, $point-$pos1);//total number of records found
    $pos = 0;
    
    for ($i = 1; $i <= $recMax; $i++)
    {
      $chaine = "";
      
      $rec = strpos($page, "Record ".$i." of ", $pos); //searching the start of the record
      
      $postit = strpos($page, "Title:", $rec); //searching the beginning of the title
      $valdeb = strpos($page, "<value>", $postit) + strlen("<value>");
      $valfin = strpos($page, "</value>", $valdeb);
      $titre = substr($page, $valdeb, $valfin - $valdeb);
      $titre = str_replace("\r\n", " ", $titre);
      
      //echo $i.'- '.$titre.' -<br>';
      $chaine .= htmlspecialchars($titre)."^";
      
      $posaut = strpos($page, "Authors:", $postit); //searching the beginning of the authors
      $valdeb = strpos($page, "</b>", $posaut) + strlen("</b>")+2;
      $valfin = strpos($page, "</td>", $valdeb);
      $authors = substr($page, $valdeb, $valfin - $valdeb);
      $authors = str_replace("\r\n", " ", $authors);
      
      //echo '- '.$authors.' -<br>';
      $chaine .= $authors."^";
      
      $possou = strpos($page, "Source:", $posaut); //searching the beginning of the source
      $valdeb = strpos($page, "<value>", $possou) + strlen("<value>");
      $valfin = strpos($page, "</value>", $valdeb);
      $source = substr($page, $valdeb, $valfin - $valdeb);
      $source = str_replace("\r\n", " ", $source);
      
      //echo '- '.$source.' -<br>';
      $chaine .= $source."^";
      
      //searching the beginning of the DOI
      $valdeb = strpos($page, "<a href=\"http://dx.doi.org/", $possou) + strlen("<a href=\"http://dx.doi.org/");
      $valfin = strpos($page, "\">", $valdeb);
      $doi = substr($page, $valdeb, $valfin - $valdeb);
      $doi = str_replace("\r\n", " ", $doi);
      
      //echo '- '.$doi.' -<br>';
      $chaine .= $doi."^";
      
      //searching the beginning of the publication year
      $valdeb = strpos($page, "<value>", $valfin) + strlen("<value>");
      $valfin = strpos($page, "</value>", $valdeb);
      $year = substr(substr($page, $valdeb, $valfin - $valdeb), -4);
      $year = str_replace("\r\n", " ", $year);
      
      //echo '- '.$year.' -<br>';
      $chaine .= $year."^";
      //echo $i.' - '.$chaine.'<br>';
      
      //searching the beginning of the ISSN
      $valdeb = strpos($page, "<b>ISSN:</b>", $valfin);
      $valdeb = strpos($page, "<value>", $valdeb) + strlen("<value>");
      $valfin = strpos($page, "</value>", $valdeb);
      $issn = substr($page, $valdeb, $valfin - $valdeb);
      $issn = str_replace("\r\n", " ", $issn);
      
      //echo '- '.$issn.' -<br>';
      $chaine .= $issn."^";
      //echo $i.' - '.$chaine.'<br>';
      
      //searching the beginning of the Document Type
      $postyp = strpos($page, "<b>Document Type:</b>", $possou);
      $valdeb = strpos($page, "</b>", $postyp) + strlen("</b>")+2;
      $valfin = strpos($page, "</td>", $valdeb);
      $type = substr($page, $valdeb, $valfin - $valdeb);
      $type = str_replace("\r\n", " ", $type);
      
      //echo '- '.$type.' -<br>';
      $chaine .= $type.chr(13).chr(10);
      //echo $i.' - '.$chaine.'<br>';

      //save results in the CSV file
      $inF = fopen($Fnm,"a+"); 
      fseek($inF, 0);
      fwrite($inF, $chaine);
      
      $pos = $valfin;
    }
  }
  fclose($inF);
}

if (file_exists("./PubMed.html"))//HTML PubMed file has been submitted
{
  $handle = fopen("./PubMed.html", "r");
  $pubmed = 1;
}
if ($pubmed == 1)//HTML PubMed file was submitted
{
  //$pubmed = 1;
  $page = "";
  while (!feof($handle))
  { //reading all the lines
    $page .= fgets($handle, 4096); //reading content of each line
  }
  fclose($handle);
}

if ($pubmed == 1)
{
  $page = str_replace("\r\n", " ", $page);
  $rec1 = strpos($page, "Items 1 - "); //searching the first record
  if ($rec1 !== false) //there is at least 1 record
  {
    //export results in a CSV file
    $Fnm = "./HAL/pubmed_html.csv"; 
    $inF = fopen($Fnm,"w"); 
    fseek($inF, 0);
    $chaine = "\xEF\xBB\xBF";
    fwrite($inF,$chaine);
    
    $inF = fopen($Fnm,"a+"); 
    fseek($inF, 0);
    fwrite($inF, "Title^Authors^Source title^Year^DOI^Type".chr(13).chr(10));
    
    $pos1 = $rec1 + strlen("Items 1 - ");
    $point = strpos($page, " of ", $pos1);
    $recMax = substr($page, $pos1, $point-$pos1);//total number of records found
    $pos = 0;
    
    for ($i = 1; $i <= $recMax; $i++)
    {
      $chaine = "";
      
      $rec = strpos($page, "ordinalpos=".$i, $pos); //searching the start of the record
      
      $postit = $rec + strlen("ordinalpos=") + strlen($i) + strlen("\">"); //searching the beginning of the title
      $valdeb = $postit;
      $valfin = strpos($page, "</a>", $valdeb);
      $titre = substr($page, $valdeb, $valfin - $valdeb);
      $titre = str_replace("\r\n", " ", $titre);
      
      //echo $i.'- '.$titre.' -<br>';
      $chaine .= htmlspecialchars($titre)."^";
      
      $posaut = strpos($page, "<td align=\"left\" valign=\"top\">", $postit); //searching the beginning of the authors
      $valdeb = $posaut + strlen("<td align=\"left\" valign=\"top\">");
      $valfin = strpos($page, "</td></tr>", $valdeb);
      $authors = substr($page, $valdeb, $valfin - $valdeb);
      $authors = str_replace("\r\n", " ", $authors);
      
      //echo '- '.$authors.' -<br>';
      $chaine .= $authors."^";
      
      $possou = strpos($page, "class=\"jrnl\"", $posaut); //searching the beginning of the source
      $valdeb = strpos($page, "title=\"", $possou) + strlen("title=\"");
      $valfin = strpos($page, "\">", $valdeb);
      $source = substr($page, $valdeb, $valfin - $valdeb);
      $source = str_replace("\r\n", " ", $source);
      
      //echo '- '.$source.' -<br>';
      $chaine .= $source."^";
      
      //searching the beginning of the publication year
      $valdeb = strpos($page, "</span>. ", $valfin) + strlen("</span>. ");
      $valfin = $valdeb;
      $year = substr($page, $valdeb, +4);
      $year = str_replace("\r\n", " ", $year);
      
      //echo '- '.$year.' -<br>';
      $chaine .= $year."^";

      //searching the beginning of the DOI
      $valfinprec = $valfin;
      $valfin = strpos($page, "PMID: ", $valdeb);
      $doi = substr($page, $valdeb, $valfin - $valdeb);
      if (strpos($doi, "doi: ") === false) //testing if there is a DOI
      {
        $doi = "";
      }else{
        $valdeb = strpos($page, " doi: ", $valfinprec) + strlen(" doi: ");
        $doi = substr($page, $valdeb, $valfin - $valdeb);
        $doi = explode(". ", $doi);
        $doi = $doi[0];
        $doi = str_replace("\r\n", " ", $doi);
      }
      
      //echo '- '.$doi.' -<br>';
      $chaine .= $doi."^";
      
      //Type > non renseigné dans le html
      $chaine .= "".chr(13).chr(10);
      
      //echo $i.' - '.$chaine.'<br>';

      //save results in the CSV file
      $inF = fopen($Fnm,"a+"); 
      fseek($inF, 0);
      fwrite($inF, $chaine);
      
      $pos = strpos($page, "Similar articles", $valfin) + strlen("Similar articles");
    }
  }
}
?>
</body></html>