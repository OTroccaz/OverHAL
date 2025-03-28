<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Autre forme de page d'accueil des résultats - Another form of results home page
 */
 
header('Content-type: text/html; charset=UTF-8');
mb_internal_encoding("UTF-8");

include "./oaDOI.php";
$root = 'http';
	if ( isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
		$root.= "s";
	}
$targetPDF = $root . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "/PDF/";
$targetPDF = str_replace("/OverHAL_results.php", "", $targetPDF);

//bibtex files deletion
if (file_exists("./HAL/OverHAL_scopus.bib")) {unlink("./HAL/OverHAL_scopus.bib");}
if (file_exists("./HAL/OverHAL_wos_csv.bib")) {unlink("./HAL/OverHAL_wos_csv.bib");}
if (file_exists("./HAL/OverHAL_scifin.bib")) {unlink("./HAL/OverHAL_scifin.bib");}
if (file_exists("./HAL/OverHAL_zotero.bib")) {unlink("./HAL/OverHAL_zotero.bib");}

//TEI files deletion
if (file_exists("./HAL/OverHAL_scopus.zip")) {unlink("./HAL/OverHAL_scopus.zip");}
if (file_exists("./HAL/OverHAL_wos_csv.zip")) {unlink("./HAL/OverHAL_wos_csv.zip");}
if (file_exists("./HAL/OverHAL_scifin.zip")) {unlink("./HAL/OverHAL_scifin.zip");}
if (file_exists("./HAL/OverHAL_zotero.zip")) {unlink("./HAL/OverHAL_zotero.zip");}

//Possibilité de désactiver temporairement SR : = oui ou non
$desactivSR = "non";
if (isset($_POST['desactSR']) && $_POST['desactSR'] == "oui")
{
  $desactivSR = "oui";
}
//Possibilité d'activer les procédures d'envoi de mails : = oui ou non
$activMailsM = "non";
if (isset($_POST['actMailsM']) && $_POST['actMailsM'] == "oui")
{
  $activMailsM = "oui";
}
$activMailsP = "non";
if (isset($_POST['actMailsP']) && $_POST['actMailsP'] == "oui")
{
  $activMailsP = "oui";
}

$limzot = "non";
if(isset($_POST['limzot']))
{
  $limzot = "oui";
}
//Joker
if(isset($_POST['joker']))
{
   $joker=htmlspecialchars($_POST['joker']);
}
$wos_html = 0;
if ($_FILES['wos_html']['error'] != 4)//Is there a wos HTML file ?
{
  if ($_FILES['wos_html']['error'])
  {
    switch ($_FILES['wos_html']['error'])
    {
       case 1: // UPLOAD_ERR_INI_SIZE
       Header("Location: "."OverHAL.php?erreur=1");
       break;
       case 2: // UPLOAD_ERR_FORM_SIZE
       Header("Location: "."OverHAL.php?erreur=2");
       break;
       case 3: // UPLOAD_ERR_PARTIAL
       Header("Location: "."OverHAL.php?erreur=3");
       break;
    }
  }
  $extension = strrchr($_FILES['wos_html']['name'], '.');
	if ($extension != ".html") {
    Header("Location: "."OverHAL.php?erreur=5");
  }
  move_uploaded_file($_FILES['wos_html']['tmp_name'], "WoS.html");
  include "./HTML_import.php";
  //unlink("WoS.html");
  $wos_html = 1;
}

$pubmed_html = 0;
if ($_FILES['pubmed_html']['error'] != 4)//Is there a pubmed HTML file ?
{
  if ($_FILES['pubmed_html']['error'])
  {
    switch ($_FILES['pubmed_html']['error'])
    {
       case 1: // UPLOAD_ERR_INI_SIZE
       Header("Location: "."OverHAL.php?erreur=1");
       break;
       case 2: // UPLOAD_ERR_FORM_SIZE
       Header("Location: "."OverHAL.php?erreur=2");
       break;
       case 3: // UPLOAD_ERR_PARTIAL
       Header("Location: "."OverHAL.php?erreur=3");
       break;
    }
  }
  $extension = strrchr($_FILES['pubmed_html']['name'], '.');
	if ($extension != ".html") {
    Header("Location: "."OverHAL.php?erreur=5");
  }
  move_uploaded_file($_FILES['pubmed_html']['tmp_name'], "PubMed.html");
  include "./HTML_import.php";
  //unlink("Pubmed.html");
  $pubmed_html = 1;
}

//Bibliographic sources array -> first key must correspond to form field file name
$souBib = array(
  "scopus" => array(
    "Maj" => "Scopus",
    "Sep" => ",",
    "Year" => "Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Authors",
    "Source" => "Source title",
    "Type" => "Document Type",
  ),
  "pubmed_csv" => array(
    "Maj" => "PubMed (CSV)",
    "Sep" => ",",
    "Year" => "ShortDetails",
    "Title" => "Title",
    "DOI" => "Details",
    "Authors" => "Description",
    "Source" => "ShortDetails",
    "Type" => "Type",
  ),
  "wos_csv" => array(
    "Maj" => "WoS (CSV)",
    "Sep" => "	",
    "Year" => "PY",
    "Title" => "TI",
    "DOI" => "DI",
    "Authors" => "AU",
    "Source" => "SO",
    "Type" => "PT",
  ),
  "scifin" => array(
    "Maj" => "SciFinder",
    "Sep" => ",",
    "Year" => "Publication Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Author",
    "Source" => "Journal Title",
    "Type" => "Document Type",
  ),
  "zotero" => array(
    "Maj" => "Zotero",
    "Sep" => ",",
    "Year" => "Publication Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Author",
    "Source" => "Publication Title",
    "Type" => "Item Type",
  )
);

if ($wos_html == 1)
{
  $wosHtmlTab = array(
    "Maj" => "WoS (HTML)",
    "Sep" => "^",
    "Year" => "Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Authors",
    "Source" => "Source title",
    "Type" => "Document Type",
  );
  $souBib["wos_html"] = $wosHtmlTab;
}

if ($pubmed_html == 1)
{
  $pubmedHtmlTab = array(
    "Maj" => "PubMed (HTML)",
    "Sep" => "^",
    "Year" => "Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Authors",
    "Source" => "Source title",
    "Type" => "Type",
  );
  $souBib["pubmed_html"] = $pubmedHtmlTab;
}
//var_dump($souBib);
$nbSouBib = count($souBib);

//Tests errors on file submit
foreach ($souBib as $key => $subTab)
{
  if ($_FILES[$key]['name'] != "") //File has been submitted
  {
    if ($_FILES[$key]['error'])
    {
      switch ($_FILES[$key]['error'])
      {
         case 1: // UPLOAD_ERR_INI_SIZE
         Header("Location: "."OverHAL.php?erreur=1");
         break;
         case 2: // UPLOAD_ERR_FORM_SIZE
         Header("Location: "."OverHAL.php?erreur=2");
         break;
         case 3: // UPLOAD_ERR_PARTIAL
         Header("Location: "."OverHAL.php?erreur=3");
         break;
         //case 4: // UPLOAD_ERR_NO_FILE
         //Header("Location: "."OverHAL.php?erreur=4");
         //break;
      }
    }
    $extension = strrchr($_FILES[$key]['name'], '.');
    if ($extension != ".csv") {
      Header("Location: "."OverHAL.php?erreur=5");
      break;
    }
  }
}

if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "https://halur1.univ-rennes1.fr/HAL_SCD.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <title>OverHAL : Comparaison HAL vs sources bibliographiques</title>
  <meta name="Description" content="OverHAL : Comparaison HAL vs sources bibliographiques">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script type="text/javascript" src="./js/overlib.js"></script>
  <script type="text/javascript" src="./OverHAL_results.js"></script>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body>
<?php

/*
    CouvertureHAL - 2016-05-26
    Copyright (C) 2016 Philippe Gambette (HAL_UPEMLV@univ-mlv.fr)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if ($limzot == "non")
{
?>
<a name='Résultats'></a><h1>Résultats</h1>

<b>Le script ci-dessous ne se fonde que sur la détection d'un titre identique (après suppression des caractères spéciaux et passage en minuscules)
ou d'un même DOI pour identifier une référence d'une source bibliographique (Scopus, Pubmed, etc.) avec un dépôt HAL.</b><br/><br/>
<a href='#Chargement de la requête HAL'>Chargement de la requête HAL</a><br />
<?php
foreach ($souBib as $key => $subTab)
{
  if ($_FILES[$key]['error'] != 4)
  {
    $nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
    echo "<a href='#Chargement du fichier ".$nomSouBib."'>Chargement du fichier ".$nomSouBib."</a><br />";
    echo "<a href='#Références de ".$nomSouBib." non trouvées dans HAL'>Références de ".$nomSouBib." non trouvées dans HAL</a><br />";
    echo "<a href='#Auteurs des références de ".$nomSouBib." non trouvées dans HAL'>Auteurs des références de ".$nomSouBib." non trouvées dans HAL</a><br />";
  }
}
?>
<a href='#Bilan quantitatif'>Bilan quantitatif</a><br />

<br />
Récupération des résultats de HAL en cours...
<?php
}

if(!function_exists("array_column")) {
  function array_column($array,$column_name) {
    return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
  }
}

function minRev ($rev){//terms in lowercase with first one in uppercase except for certain terms
  $revInt = ucwords(strtolower($rev));
  $deb = array(" Of ", " And ", " The ", " In ", " Et ", " D' ", " L' ", " En ", "De");
  $fin = array(" of ", " and ", " the ", " in ", " et ", " d' ", " l' ", " en ", "de");
  $revFin = str_replace($deb, $fin, $revInt);
  if (strpos($revFin, "-") !== false) {//first capital letter after the hyphen
    $pos = strpos($revFin, "-");
    $revG = trim(mb_substr($revFin, 0, $pos, 'UTF-8'));
    $revD = trim(mb_substr($revFin, ($pos+1), strlen($revFin), 'UTF-8'));
    $revFin = $revG."-".ucfirst($revD);
  }
  return $revFin;
}

function normAut($aut)
{
  $aut = trim($aut);
  if (strpos($aut, ",") !== false)
  {
    $autTab = explode(",", $aut);
    $qui = $autTab[0].substr($autTab[1], 0, 2);
  }else{
    $qui = $aut;
  }
  return $qui;
}

function trimUltime($chaine){
  $chaine = trim($chaine);
  $chaine = str_replace("###antiSlashe###t", " ", $chaine);
  $chaine = preg_replace("!\s+!", " ", $chaine);
  return $chaine;
}

function idaureHal($urlHAL, $cuHAL, &$docid, &$label, &$code, &$type){
  $docid = 0;
  //echo $urlHAL.'<br>';
  $contents = file_get_contents($urlHAL);
  $resHAL = json_decode($contents);
  $numFound = $resHAL->response->numFound;
  foreach($resHAL->response->docs as $entry)
  {
    //if ($entry->valid_s == "VALID" || $entry->valid_s == "INCOMING")
    //{
      if ($docid == 0)
      {
        $docid = $entry->docid;
      }else{
        if (strlen($entry->docid) < strlen($docid))
        {
          $docid = $entry->docid;
        }
      }
      $label = $entry->label_s;
      //echo 'toto : '.$label.'<br>';
      $code = $cuHAL;
      if (isset($entry->code_s)) {
        $codeMax = count($entry->code_s) - 1;
        $code = str_replace(" ", "",$entry->code_s[$codeMax]);
      }
      if (isset($entry->type_s)) {
        $type = $entry->type_s;
      }
    //}
  }
}

function affilId($diffQuoi, $docid, $label, $code, $type, $pays, $validHAL) {
  global $autTab, $labTab, $autInd;
  $eltTab = explode("] ",$diffQuoi);
  $autGrp = str_replace("[", "", $eltTab[0]);
  $autQui = explode(";", $autGrp);
  $eltTabP = explode(",", $eltTab[count($eltTab) - 1]);
  $pays = trim($eltTabP[count($eltTabP) - 1]);
  //echo $diffQuoi.' - '.$docid.' - '.$autGrp.'<br>';
  for ($aut = 0; $aut < count($autQui); $aut++)
  {
    $pres = 0;
    $autInd = count($autTab);
    for ($qui = 0; $qui < count($autTab); $qui++)
    {
      //echo $qui.'<br>';
      if (normAut(trim($autTab[$qui])) == normAut($autQui[$aut]))//Author already present
      {
        $pres = 1;
        $docTab = explode("~|~", $labTab[trim($autTab[$qui])][$qui]);
        $docidQui = $docTab[0];
        //echo $docidQui.' - '.$docid.' - '.$validHAL.' : '.$autQui[$aut].'<br>';
        if ($docidQui != $docid)//New affiliation
        {
          $pres = 0;
          //echo $docidQui.' - '.$docid.' - '.$validHAL.' : '.$autQui[$aut].'<br>';
          //$labTab[$qui] = 0;
        }
        //break;
      }
    }
    //echo $pres.' - '.$autQui[$aut].' - '.$docid.' - '.$label.'<br>';
    //if (($pres == 0 && $docid != 0) || $validHAL == "--ok")
    //if ($pres == 0 && $validAut != "")
    if ($pres == 0)
    {
      $autTab[$autInd] = trim($autQui[$aut]);
      $labTab[trim($autQui[$aut])][$autInd] = $docid."~|~".$label."~|~".$code."~|~".$type."~|~".$pays;
      $autInd++;
    }
  }
  //echo $diffQuoi.' - '.$autInd.'<br>';
  //var_dump($autTab);
  //var_dump($labTab);
}

function searchAcro($aTester, &$cuHAL, &$urlHAL, &$validHAL, &$retTest) {//Search for distinctive acronyms
  //echo $aTester.'<br>';
  $aTester = " ".$aTester;
  $retTest = "";
  if (stripos($aTester, " CNRS") !== false || stripos($aTester, " INRA") !== false || stripos($aTester, " UMR") !== false || stripos($aTester, " UMS") !== false || stripos($aTester, " UPR") !== false || stripos($aTester, " ERL") !== false || stripos($aTester, " IFR") !== false || stripos($aTester, " USR") !== false)//CNRS ou INRA
  {
    $detail = explode(",",$aTester);
    for ($det = 0; $det < count($detail); $det++)//search for CNRS 0000 or INSERM 0000 to replace by UMR0000
    {
      $umrT = trim($detail[$det]);
      if (stripos($umrT, " CNRS") !== false && strlen($umrT) == 9 && is_numeric(substr($umrT, -1, 1)) && is_numeric(substr($umrT, -2, 1)) && is_numeric(substr($umrT, -3, 1)) && is_numeric(substr($umrT, -4, 1)))
      {
        $umrT = str_replace("CNRS", "UMR", $umrT);
      }
      if (stripos($umrT, " INSERM") !== false && strlen($umrT) == 11 && is_numeric(substr($umrT, -1, 1)) && is_numeric(substr($umrT, -2, 1)) && is_numeric(substr($umrT, -3, 1)) && is_numeric(substr($umrT, -4, 1)))
      {
        $umrT = str_replace("INSERM", "UMR", $umrT);
      }
      $detail[$det] = " ".$umrT;
    }
    for ($det = 0; $det < count($detail); $det++)
    {
      if (stripos($detail[$det], " UMR S ") !== false)
      {
        extractCU($detail[$det], "UMR S ", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " UMR") !== false)
      {
        extractCU($detail[$det], "UMR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " UMS") !== false)
      {
        extractCU($detail[$det], "UMS", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " UPR") !== false)
      {
        extractCU($detail[$det], "UPR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " ERL") !== false)
      {
        extractCU($detail[$det], "ERL", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " IFR") !== false)
      {
        extractCU($detail[$det], "IFR", 3, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (strpos($detail[$det], " USR") !== false)
      {
        extractCU($detail[$det], "USR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
    }
  }
  if (stripos($aTester, " EA") !== false  && $urlHAL == "") {
    $detail = explode(",",$aTester);
    for ($det = 0; $det < count($detail); $det++)
    {
      if (stripos($detail[$det], " EA") !== false)
      {
        extractCU($detail[$det], "EA", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
    }
  }
  if (stripos($aTester, " CIC") !== false  && $urlHAL == "") {
    $detail = explode(",",$aTester);
    for ($det = 0; $det < count($detail); $det++)
    {
      if (stripos($detail[$det], " CIC-P") !== false)
      {
        extractCU($detail[$det], "CIC-P", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " CIC-IT") !== false)
      {
        extractCU($detail[$det], "CIC-IT", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
      if (stripos($detail[$det], " CIC") !== false)
      {
        extractCU($detail[$det], "CIC", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
        break;
      }
    }
  }
  //echo $aTester.'<br>';
  if (stripos($aTester, " INSERM") !== false  && $urlHAL == "")//INSERM
  {
    $detail = explode(",",$aTester);
    for ($det = 0; $det < count($detail); $det++)
    {
      $cu = trim($detail[$det]);
      $cu = str_replace(" ", "", $cu);
      //echo $cu.'<br>';
      if (stripos($detail[$det], " INSERM") !== false)//in case of INSERMxxxx but not always the case
      {
        //$codeUnit = substr(preg_replace("/[^0-9]/","",$detail[$det]), 0, 4);//We keep only the number
        $codeUnit = preg_replace("/[^0-9]/", "", $detail[$det]);//We keep only the number
        //echo 'toto : '.$detail[$det].' - '.$codeUnit.' - '.$validHAL.'<br>';
        //if ($codeUnit != "" && $validHAL != "ok")
        if ($codeUnit != "")
        {
          //$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U".$codeUnit."%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
          $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:(%22U".$codeUnit."%22OR%22U%20".$codeUnit."%22)&fl=title_s,valid_s,label_s,docid,code_s,type_s";
          $cuHAL = "U".$codeUnit;
          $validHAL = "ok";
          $retTest = str_ireplace(array("INSERM", $codeUnit), "", $aTester);
          break;
        }
      }
      if (strlen($cu) == 5) {//cas INSERM par exemple U1085
        if (is_numeric($cu[0]) == false && is_numeric($cu[1]) == true && is_numeric($cu[2]) == true && is_numeric($cu[3]) == true && is_numeric($cu[4]) == true) {
          $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:%22".$cu."%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
          $cuHAL = $cu;
          $validHAL = "ok";
          $retTest = str_ireplace(array("INSERM", $cu), "", $aTester);
          break;
        }
      }
      $detailP = explode(" ",$detail[$det]);
      //var_dump($detailP);
      for ($detP = 0; $detP < count($detailP); $detP++)
      {
        $cuP = trim($detailP[$detP]);
        $cuP = str_replace(" ", "", $cuP);
        if (strlen($cuP) == 5 && is_numeric($cuP[0]) == false && is_numeric($cuP[1]) == true && is_numeric($cuP[2]) == true && is_numeric($cuP[3]) == true && is_numeric($cuP[4]) == true) {
          $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:%22".$cuP."%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
          $cuHAL = $cuP;
          $validHAL = "ok";
          $retTest = str_ireplace(array("INSERM", $cuP), "", $aTester);
          break;
        }
      }
    }
  }
  if (stripos($aTester, " ECOBIO") !== false && $urlHAL == "") {//ECOBIO
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22ecobio%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "ECOBIO";
    $validHAL = "--";
    $retTest = str_ireplace("ECOBIO", "", $aTester);
  }
  if (stripos($aTester, " LEEST") !== false && $urlHAL == "") {//LEEST
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22leest%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "LEEST";
    $validHAL = "--";
    $retTest = str_ireplace("LEEST", "", $aTester);
  }
  if (stripos($aTester, " EHESP") !== false && $urlHAL == "") {//EHESP
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22ehesp%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "EHESP";
    $validHAL = "--";
    $retTest = str_ireplace("EHESP", "", $aTester);
  }
  if (stripos($aTester, " PACTE") !== false && $urlHAL == "") {//PACTE
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22pacte%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "PACTE";
    $validHAL = "--";
    $retTest = str_ireplace("PACTE", "", $aTester);
  }
  if (stripos($aTester, "1085") !== false && $urlHAL == "") {//U1085
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U1085%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "U1085";
    $validHAL = "ok";
    $retTest = str_ireplace("1085", "", $aTester);
  }
  if (stripos($aTester, " LERES") !== false && $urlHAL == "") {//LERES
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22LERES%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "PACTE";
    $validHAL = "--";
    $retTest = str_ireplace("LERES", "", $aTester);
  }
  if ((stripos($aTester, " Nutr Metab & Canc") !== false || stripos($aTester, " Numecan") !== false) && $urlHAL == "") {//ECOBIO
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U1241%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    $cuHAL = "U1241";
    $validHAL = "--";
    $retTest = str_ireplace(array("Nutr Metab & Canc", "Numecan"), "", $aTester);
  }
  //echo $retTest.'<br>';
}

function extractCU($quoi, $term, $numDig, $clean, $aTester, &$urlHAL, &$cuHAL, &$validHAL, &$retTest) {//$detail[$det] - $CIC-P - number of digit
  $codeBase = str_replace($clean, "", normalize($quoi));//first cleaning
  $primTerm = substr($term, 0, 1);
  //echo $codeBase.'<br>';
  //is the first next character after unit type numeric ?
  if (is_numeric(substr($codeBase, strpos($codeBase, $term) + strlen($term), 1)))//style UUXXXX
  {
    $codeUnit = substr($codeBase, strpos($codeBase, $term) + strlen($term), $numDig);//extraction unit type + code
  }else{//style UUsometermsXXXX
    $codeUnit = preg_replace("/[^0-9]/", "", $quoi);//We keep only the number
  }
  if ($codeUnit != "" )
  {
    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:(%22".$term.$codeUnit."%22OR%22".$term."%20".$codeUnit."%22OR%22".$primTerm."%20".$codeUnit."%22OR%22".$primTerm.$codeUnit."%22)&fl=title_s,valid_s,label_s,docid,code_s,type_s";
    //echo $urlHAL.'<br>';
    $cuHAL = $term.$codeUnit;
    $validHAL = "ok";
    $retTest = str_replace(array($codeUnit, $term), "", $aTester);
    $retTest = str_replace($clean, "", $retTest);
    //echo $cuHAL.'<br>';
    //break;
  }
}

function supprAmp($st) {
  $st = str_replace('&', 'and', $st);
  $st = str_replace(array('<bold>', '</bold>'), '', $st);
  $st = str_replace(array('<u>', '</u>'), '', $st);
  $st = str_replace(array('<i>', '</i>'), '', $st);
  $st = str_replace(array('<a>', '</a>'), '', $st);
  $st = htmlspecialchars($st, ENT_QUOTES, "UTF-8");
  return $st;
}

function normalize($st)
{
    //return preg_replace('/\W+/', '', $st);
    $st=strtr($st,' ()"-!?[]{}:,;./*+$^=\'\\','                       ');
    return preg_replace('/\s+/', '', $st);
    //strtr($st,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


$halId=array();
$halTitles=array();
$halFullRef=array();
$halYears=array();
$halAuthors=array();
$halWhere=array();



if(isset($_POST['hal']))
{
   $hal = htmlspecialchars($_POST['hal']);
   $team = htmlspecialchars($_POST['team']);
   if ($limzot == "non")
   {
     echo "<a name='Chargement de la requête HAL'></a><h2>Chargement de la requête HAL - <a href='#Résultats'><i>Retour aux résultats</i></a></h2>";
     echo "<div style='width: 900px; word-wrap: break-word;'>Requête : ".$hal."</div>";
   }
   $contents = file_get_contents($hal);
   $results = json_decode($contents);

   $nbHAL=0;
   $nbHalPerYear=array();
   //echo "<br/><br/>Résultats :<ul>";
   foreach($results->response->docs as $entry)
   {
      $doi = "";
      $titlePlus = "";
      //The title of the HAL file will be the main key to look for the article in HAL, we now simplify it (lowercase, no punctuation or spaces, etc.)
      //Does the title integrates a traduction with []?
      if(strpos($entry->title_s[0], "[") !== false && strpos($entry->title_s[0], "]") !== false)
      {
        $posi = strpos($entry->title_s[0], "[")+1;
        $posf = strpos($entry->title_s[0], "]");
        $tradTitle = substr($entry->title_s[0], $posi, $posf-$posi);
        $encodedTitle=normalize(utf8_encode(mb_strtolower($tradTitle)));
      }else{
        //Is there a subTitle ?
        $titlePlus = $entry->title_s[0];
        if (isset($entry->subTitle_s[0])) {
          $titreInit = $titlePlus;
          $titlePlus .= " : ".$entry->subTitle_s[0];
        }
        $encodedTitle=normalize(utf8_encode(mb_strtolower(utf8_decode($titlePlus))));
      }
      //So we store all parameters using the simplified HAL title
      //We also use the DOI as a key if it is present:
      $halId[$encodedTitle]=($entry->halId_s);

      //Saving the DOI
      if (isset($entry->doiId_s)) {$doi=strtolower($entry->doiId_s);}
      //if(strlen($doi)>0){$halId[$doi]=($entry->doiId_s);}
      if(strlen($doi)>0){$halId[$doi]=($entry->halId_s);}

      //Saving the year
      if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
      {
        $halYears[$encodedTitle]=($entry->conferenceStartDateY_i);
        if(strlen($doi)>0){$halYears[$doi]=($entry->conferenceStartDateY_i);}
      }else
      {
        $halYears[$encodedTitle]=($entry->producedDateY_i);
        if(strlen($doi)>0){$halYears[$doi]=($entry->producedDateY_i);}
      }

      //Record number of HAL publications per year
      if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
      {
        if(array_key_exists(($entry->conferenceStartDateY_i),$nbHalPerYear))
        {
           $nbHalPerYear[$entry->conferenceStartDateY_i]+=1;
        } else
        {
           $nbHalPerYear[$entry->conferenceStartDateY_i]=1;
        }
      }else
      {
        if(array_key_exists(($entry->producedDateY_i),$nbHalPerYear))
        {
           $nbHalPerYear[$entry->producedDateY_i]+=1;
        } else
        {
           $nbHalPerYear[$entry->producedDateY_i]=1;
        }
      }

      //Saving the title
      //Is there a subTitle ?
      $titlePlus = $entry->title_s[0];
      if (isset($entry->subTitle_s[0])) {
        $titlePlus .= " : ".$entry->subTitle_s[0];
      }
      $halTitles[$encodedTitle]=($titlePlus);
      if(strlen($doi)>0){$halTitles[$doi]=($titlePlus);}
      //Saving the publication location: journal, conference or book
      if(isset($entry->journalTitle_s) && strlen($entry->journalTitle_s)>0)
      {
         $halWhere[$encodedTitle]=($entry->journalTitle_s);
         if(strlen($doi)>0){$halWhere[$doi]=($entry->journalTitle_s);}
      }
      if(isset($entry->proceedings_s) && strlen($entry->proceedings_s)>0)
      {
         $halWhere[$encodedTitle]=($entry->proceedings_s);
         if(strlen($doi)>0){$halWhere[$doi]=($entry->proceedings_s);}
      }
      if(isset($entry->bookTitle_s) && strlen($entry->bookTitle_s)>0)
      {
         $halWhere[$encodedTitle]=($entry->bookTitle_s);
         if(strlen($doi)>0){$halWhere[$doi]=($entry->bookTitle_s);}
      }

      //Saving authors:
      $authors="";
      $initial = 1;
      foreach($entry->authFullName_s as $author)
      {
         if ($initial==1){
            $authors = $author;
            $initial=0;
         } else
         {
            $authors = $authors.", ".$author;
         }
      }
      $halAuthors[$encodedTitle]=$authors;
      if(strlen($doi)>0){$halAuthors[$doi]=$authors;}

      //Saving full citation
      $halFullRef[$encodedTitle]=($entry->citationFull_s);
      if(strlen($doi)>0){$halFullRef[$doi]=($entry->citationFull_s);}
      //echo "<li>".$entry->halId_s." - ".normalize(utf8_encode(mb_strtolower(utf8_decode($entry->title_s[0]))))."</li>";

      $nbHAL+=1;
      
      if (isset($entry->subTitle_s[0])) {//Duplication of the entry with the reducted title
        $encodedTitle=normalize(utf8_encode(mb_strtolower(utf8_decode($titreInit))));
      
        $halId[$encodedTitle]=($entry->halId_s);

        //Saving the DOI
        if (isset($entry->doiId_s)) {$doi=strtolower($entry->doiId_s);}
        //if(strlen($doi)>0){$halId[$doi]=($entry->doiId_s);}
        if(strlen($doi)>0){$halId[$doi]=($entry->halId_s);}

        //Saving the year
        if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
        {
          $halYears[$encodedTitle]=($entry->conferenceStartDateY_i);
          if(strlen($doi)>0){$halYears[$doi]=($entry->conferenceStartDateY_i);}
        }else
        {
          $halYears[$encodedTitle]=($entry->producedDateY_i);
          if(strlen($doi)>0){$halYears[$doi]=($entry->producedDateY_i);}
        }

        //Record number of HAL publications per year
        if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
        {
          if(array_key_exists(($entry->conferenceStartDateY_i),$nbHalPerYear))
          {
             $nbHalPerYear[$entry->conferenceStartDateY_i]+=1;
          } else
          {
             $nbHalPerYear[$entry->conferenceStartDateY_i]=1;
          }
        }else
        {
          if(array_key_exists(($entry->producedDateY_i),$nbHalPerYear))
          {
             $nbHalPerYear[$entry->producedDateY_i]+=1;
          } else
          {
             $nbHalPerYear[$entry->producedDateY_i]=1;
          }
        }

        //Saving the title
        $titlePlus = $entry->title_s[0];
        $halTitles[$encodedTitle]=($titlePlus);
        if(strlen($doi)>0){$halTitles[$doi]=($titlePlus);}
        //Saving the publication location: journal, conference or book
        if(isset($entry->journalTitle_s) && strlen($entry->journalTitle_s)>0)
        {
           $halWhere[$encodedTitle]=($entry->journalTitle_s);
           if(strlen($doi)>0){$halWhere[$doi]=($entry->journalTitle_s);}
        }
        if(isset($entry->proceedings_s) && strlen($entry->proceedings_s)>0)
        {
           $halWhere[$encodedTitle]=($entry->proceedings_s);
           if(strlen($doi)>0){$halWhere[$doi]=($entry->proceedings_s);}
        }
        if(isset($entry->bookTitle_s) && strlen($entry->bookTitle_s)>0)
        {
           $halWhere[$encodedTitle]=($entry->bookTitle_s);
           if(strlen($doi)>0){$halWhere[$doi]=($entry->bookTitle_s);}
        }

        //Saving authors:
        $authors="";
        $initial = 1;
        foreach($entry->authFullName_s as $author)
        {
           if ($initial==1){
              $authors = $author;
              $initial=0;
           } else
           {
              $authors = $authors.", ".$author;
           }
        }
        $halAuthors[$encodedTitle]=$authors;
        if(strlen($doi)>0){$halAuthors[$doi]=$authors;}

        //Saving full citation
        $halFullRef[$encodedTitle]=($entry->citationFull_s);
        if(strlen($doi)>0){$halFullRef[$doi]=($entry->citationFull_s);}

        $nbHAL+=1;
      
      }
   }
   //echo "</ul>";
   if ($nbHAL >= 10000)//10000 results max.
   {
    echo("<script type=\"text/javascript\">alert(\"Attention ! Votre requête HAL a atteint le seuil maximal des 10000 résultats interrogeables; vous devriez limiter la portée de la requête soit au niveau du corpus, soit en insérant des filtres (par année par exemple).\")</script>");
   }
}

foreach ($souBib as $key => $subTab)
{
  if ($_FILES[$key]['error'] != 4)//File exists
  {
    $nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
    $typSep = $souBib[$key]["Sep"];// Type of separator of the CSV file
    $result[$key] = array();
    if ($limzot == "non")
    {
      echo "<a name='Chargement du fichier ".$nomSouBib."'></a><h2>Chargement du fichier ".$nomSouBib." - <a href='#Résultats'><i>Retour aux résultats</i></a></h2>";
    }
    ini_set('auto_detect_line_endings',TRUE);
    //Extraction of the columns name
    if($key == "wos_html")
    {
      $handle = fopen("./HAL/wos_html.csv",'r');
    }else{
      if($key == "pubmed_html")
      {
        $handle = fopen("./HAL/pubmed_html.csv",'r');
      }else{
        $handle = fopen($_FILES[$key]['tmp_name'],'r');
      }
    }
    if (($data = fgetcsv($handle, 0, $typSep)) !== FALSE)
    {
      $imax = count($data);
      for ($i = 0; $i < $imax ; $i++)
      {
        $nomCol[$key][$i]=trim($data[$i], "\xBB..\xEF");//Suppression of some special ASCII characters
      }
    }
    //var_dump($nomCol[$key]);
    //Construction of a table with column names and not indexes in case of modification of the global structure of the CSV file
    if($key == "wos_html")
    {
      $handle = fopen("./HAL/wos_html.csv",'r');
    }else{
      if($key == "pubmed_html")
      {
        $handle = fopen("./HAL/pubmed_html.csv",'r');
      }else{
        $handle = fopen($_FILES[$key]['tmp_name'],'r');
      }
    }
    $j = 0;
    while (($data = fgetcsv($handle, 0, $typSep)) !== FALSE)
    {
      for ($i = 0; $i < $imax ; $i++)
      {
        //$result[$key][$j][$nomCol[$key][$i]]=trim($data[$i], "\xBB..\xEF");//Suppression of some special ASCII characters linked to CSV files
        $result[$key][$j][$nomCol[$key][$i]]=$data[$i];
      }
      $j++;
    }
    //var_dump($result);
    if ($limzot == "non")
    {
      echo "<table>";
    }
    $nbPerYear[$key] = array();
    $nbNotFoundPerYear[$key] = array();
    $nbNotFound[$key] = array();
    $nbNotFound[$key][0] = 0;
    $papers[$key] = array();
    $nbMax = count($result[$key]);
    $colYear = $souBib[$key]["Year"];// Name of the column of the CSV file containing/representing the year
    $colTitle = $souBib[$key]["Title"];// Name of the column of the CSV file containing/representing the title
    $colDOI = $souBib[$key]["DOI"];// Name of the column of the CSV file containing/representing the DOI
    $colAuthors = $souBib[$key]["Authors"];// Name of the column of the CSV file containing/representing the authors
    $colSource = $souBib[$key]["Source"];// Name of the column of the CSV file containing/representing the source
    $colType = $souBib[$key]["Type"];// Name of the column of the CSV file containing/representing the type

    for ($nb = 1; $nb < $nbMax; $nb++)
    {
       //Extract the type only for Scopus, Zotero or WoS communication
       $comm = "";
       $test = $result[$key][$nb][$colType];
       if ($test == "Conference Paper" || $test == "Conference Review" || $test == "conferencePaper" || $test == "S")
       {
         $comm = "ok";
       }

       // Extract the year
       if ($key == "pubmed_csv")
       {
         $yearPaper = substr($result[$key][$nb][$colYear], -4, 4);
       }else{
         $yearPaper = $result[$key][$nb][$colYear];
       }
       // Extract the revue
       if ($key == "pubmed_csv")
       {
         $revuePaper = substr($result[$key][$nb][$colSource], 0, -6);
       }else{
         $revuePaper = $result[$key][$nb][$colSource];
       }

       // Extract the DOI
       if ($key == "pubmed_csv")
       {
         if (strpos($result[$key][$nb][$colDOI], "doi:") !== false) // DOI found
         {
            $pos0 = strpos($result[$key][$nb][$colDOI], "doi:");
            if (strpos($result[$key][$nb][$colDOI], "pii:") !== false) // Existing previous characters before DOI
            {
              $pos1 = strpos($result[$key][$nb][$colDOI], " ", $pos0+10)+1;;
            }
            else
            {
              $pos1 = $pos0 + 5;
            }
            $pos2 = strpos($result[$key][$nb][$colDOI], " ", $pos1+1)-1;
            if ($pos2 == -1) {$pos2 = strlen($result[$key][$nb][$colDOI])-1;}// No blank space after the beginning of the DOI and the end of the string
            $doi = strtolower(substr($result[$key][$nb][$colDOI], $pos1, $pos2-$pos1));
         }
         else
         {
            $doi = "";
         }
       }else{
         $doi = strtolower($result[$key][$nb][$colDOI]);
       }

       // Increment the number of  papers for the year of this paper
       if (is_numeric($yearPaper))
       {
         if(array_key_exists($yearPaper,$nbPerYear[$key]))
         {
            $nbPerYear[$key][$yearPaper]+=1;
         } else
         {
            $nbPerYear[$key][$yearPaper]=1;
         }
       }

       // Extract the  title
       $Title = (utf8_encode(mb_strtolower(utf8_decode(str_replace(", Abstract", "", $result[$key][$nb][$colTitle])))));
       $tabTitle = explode(":", $Title);
       $redTitle = normalize(trim($tabTitle[0]));
       //echo array_key_exists('',$halTitles).'-<br>';
       //echo 'toto : '.$foundInHAL.' - '. $Title.'<br>';
       //print_r($halTitles);

       // Extract the English and French title if found
       $englishTitle="";
       $frenchTitle="";
       $words=preg_split('/[\[]+/u',$Title);
       if(sizeof($words)>1)
       {
          $englishTitle=normalize($words[0]);
          $frenchTitle=normalize($words[1]);
       } else
       {
          $Title=normalize($Title);
       }
       $foundInHAL=FALSE;
       //var_dump($result);

       //echo "<li><span style=\"background-color:#FFEEEE\">"" (".$data[2].") ".$data[1]." - <i>".$data[3]."</i></span>";

       // Trying to match with DOI
       if($doi != "" and array_key_exists($doi,$halTitles))
       {
         if ($limzot == "non")
         {
           echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
           echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$doi]."\">&hearts;</a></td><td valign='top'>".$halYears[$doi]."</td><td valign='top'>".$halAuthors[$doi]."</td><td valign='top'>".$halTitles[$doi][0]."</td><td valign='top'>".$halWhere[$doi]."</td><td valign='top'>DOI match</td></tr>";
         }
         $foundInHAL=TRUE;
       }

       // Trying to match with full title
       if((!$foundInHAL) and ($Title != "" and (array_key_exists($Title,$halTitles))) OR ($redTitle != "" and (array_key_exists($redTitle,$halTitles))))
       {
         if ($limzot == "non")
         {
           echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
           echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$Title]."\">&hearts;</a></td><td valign='top'>".$halYears[$Title]."</td><td valign='top'>".$halAuthors[$Title]."</td><td valign='top'>".$halTitles[$Title][0]."</td><td valign='top'>".$halWhere[$Title]."</td><td valign='top'>full title match</td></tr>";
         }
         $foundInHAL=TRUE;
       }

       // Trying to match with english title
       if((!$foundInHAL) and $englishTitle != "" and (array_key_exists($englishTitle,$halTitles)))
       {
         if ($limzot == "non")
         {
           echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
           echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$englishTitle]."\">&hearts;</a></td><td valign='top'>".$halYears[$englishTitle]."</td><td valign='top'>".$halAuthors[$englishTitle]."</td><td valign='top'>".$halTitles[$englishTitle][0]."</td><td valign='top'>".$halWhere[$englishTitle]."</td><td valign='top'>english title match</td></tr>";
         }
         $foundInHAL=TRUE;
       }

       // Trying to match with other language title
       if((!$foundInHAL) and $frenchTitle != "" and (array_key_exists($frenchTitle,$halTitles)))
       {
         if ($limzot == "non")
         {
           echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
           echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$frenchTitle]."\">&hearts;</a></td><td valign='top'>".$halYears[$frenchTitle]."</td><td valign='top'>".$halAuthors[$frenchTitle]."</td><td valign='top'>".$halTitles[$frenchTitle][0]."</td><td valign='top'>".$halWhere[$frenchTitle]."</td><td valign='top'>french title match</td></tr>";
         }
         $foundInHAL=TRUE;
       }

       // Search for possible duplicates for communications with year paper and year conference
       if($comm == "ok" && $foundInHAL === TRUE)
       {
         $encodedTitle=normalize($Title);
         if ($yearPaper != $halYears[$encodedTitle]) //duplicate
         {
           $foundInHAL=FALSE;
         }
       }

       if(!$foundInHAL)
       {
         if ($limzot == "non")
         {
           echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
         }
         $nbNotFound[$key][0] += 1;
         array_push($papers[$key],$result[$key][$nb]);
         if(array_key_exists($yearPaper,$nbNotFoundPerYear[$key]))
         {
            $nbNotFoundPerYear[$key][$yearPaper]+=1;
         } else
         {
            $nbNotFoundPerYear[$key][$yearPaper]=1;
         }
       }
    }
    if ($limzot == "non")
    {
      echo "</table>";
    }
    ini_set('auto_detect_line_endings',FALSE);
    ?>
    <a name='Références de <?php echo $nomSouBib;?> non trouvées dans HAL'></a><h2>Références de <?php echo $nomSouBib;?> non trouvées dans HAL - <a href='#Résultats'><i>Retour aux résultats</i></a></h2>
    <p><b>Attention, il est possible que la référence soit présente dans HAL mais qu'elle n'ait pas été trouvée en raison d'une légère différence dans le titre.</b></p>
    <?php
    //var_dump($papers[$key]);
    if ($desactivSR != "oui")
    {
      echo "<table border='1px' cellpadding='5px' cellspacing='5px' style='border-collapse: collapse' bordercolor='#eeeeee'>";
      echo "<tr>";
      echo "<td colspan='9'><b>Informations diverses collectées grâce à l'API SHERPA/RoMEO</b></td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td align='center'><img src='./img/embargo-6.jpg'></td>";
      echo "<td align='center'><img src='./img/embargo-12.jpg'></td>";
      echo "<td align='center'><img src='./img/embargo-24.jpg'></td>";
      echo "<td align='center'><img src='./img/embargo-e.jpg'></td>";
      echo "<td align='center'><img src='./img/post-print.png'></td>";
      echo "<td align='center'><img src='./img/pdf.png'></td>";
      echo "<td align='center'><img src='./img/red-cross.png'></td>";
      echo "<td align='center'><img src='./img/ungraded.png'></td>";
      echo "<td align='center'><img src='./img/doaj.png'></td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td valign='top' colspan='3'><small>Embargo sur la diffusion 6 mois, 12 mois ou 24 mois</small></td>";
      echo "<td valign='top' ><small>Embargo possible, voir conditions</small></td>";
      echo "<td valign='top' ><small>Dépôt du post-print auteur autorisé</small></td>";
      echo "<td valign='top' ><small>Dépôt du PDF éditeur autorisé</small></td>";
      echo "<td valign='top' ><small>Aucun dépôt autorisé</small></td>";
      echo "<td valign='top' ><small>Journal non référencé dans Sherpa-Romeo</small></td>";
      echo "<td valign='top' ><small>Journal open access référencé dans le DOAJ (Directory of Open Access Journals)</small></td>";
      echo "</tr>";
      echo "</table><br>";
    }
    $k = 1;
    echo "<table border='1px' cellpadding='5px' cellspacing='5px' style='border-collapse: collapse' bordercolor='#eeeeee'>";
    echo "<tr>";
    echo "<td>&nbsp;</td>";
    if ($desactivSR == "non")
    {
      echo"<td valign='top'><h3>Pprint</h3></td><td valign='top'><h3>PDF</h3></td><td valign='top'><h3>Doaj</h3></td>";
    }
    echo "<td valign='top'><h3>Références</h3></td><td valign='top'><h3>DOI</h3></td><td valign='top'><h3>Source</h3></td><td valign='top'><h3>Joker</h3></td>";
    if ($activMailsM == "oui" || $activMailsP == "oui")
    {
      echo "<td align='center' colspan='2' valign='top'><h3>Mails</h3></td>";
    }
    echo "</tr>";
    $cst = 0;
    $listTit = "¤";
    foreach($papers[$key] as $key2 => $data)
    {
      $imgMailM = "";
      $imgMailP = "";
      // Extract the year and the revue
      if ($key == "pubmed_csv")
      {
       $yearPaper = substr($data[$colYear], -4, 4);
       $revuePaper = substr($data[$colSource], 0, -6);
      }else{
       $yearPaper = $data[$colYear];
       $revuePaper = $data[$colSource];
      }
      // Trying to retrieve the ISSN number and the DOI
      $url = "";
      $resdoi = "";
      $refdoi = "";
      switch($key)
      {
        case "wos_csv":
          if ($papers[$key][$key2]['SN'] != "")
          {
            $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$papers[$key][$key2]['SN']."&ak=kn8p0JxcxGc";
          }
          if ($papers[$key][$key2]['DI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DI'];
          }
          break;
        case "wos_html":
        case "zotero":
          if ($papers[$key][$key2]['ISSN'] != "")
          {
            $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$papers[$key][$key2]['ISSN']."&ak=kn8p0JxcxGc";
          }
          if ($papers[$key][$key2]['DOI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DOI'];
          }
          break;
        case "scifin":
          if ($papers[$key][$key2]['DOI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DOI'];
          }
          if ($papers[$key][$key2]['Internat.Standard Doc. Number'] != "")
          {
            $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$papers[$key][$key2]['Internat.Standard Doc. Number']."&ak=kn8p0JxcxGc";
          }
          if ($papers[$key][$key2]['DOI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DOI'];
          }
          break;
        case "pubmed_zotero":
          if ($papers[$key][$key2]['ISSN'] != "")
          {
            $issn = $papers[$key][$key2]['ISSN'];
            if (strpos($issn, "-") === false)//No '-' in the ISSN number > 16310691 for example
            {
              $issn = substr($issn, 0, 4)."-".substr($issn, -4);
            }
            if (strlen($issn) > 9)//Several ISSN numbers > 0031-9007, 1079-7114 for example
            {
              $issn = substr($issn, 0, 9);
            }
            $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$issn."&ak=kn8p0JxcxGc";
          }
          if ($papers[$key][$key2]['DOI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DOI'];
          }
          break;
        case "pubmed_html":
          if ($papers[$key][$key2]['DOI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DOI'];
          }
          $revuePaper2 = str_replace(" ", "%20", $revuePaper);
          $urlinit = "http://www.sherpa.ac.uk/romeo/api29.php?jtitle=".$revuePaper2."&ak=kn8p0JxcxGc";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $urlinit);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur.univ-rennes.fr)');
          if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
						curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
					}
          $resultat = curl_exec($ch);
          curl_close($ch);
          $dom = new DOMDocument();
          $dom->loadXML($resultat);
          $issn = "";
          $res = $dom->getElementsByTagName("issn");//ISSN
          if ($res -> length > 0) {
            $issn = $res->item(0)->nodeValue;
            if ($issn != "")
            {
              $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$issn."&ak=kn8p0JxcxGc";
            }
          }
          break;
        case "scopus":
          if ($papers[$key][$key2]['DOI'] != "")
          {
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img src='./img/doi.jpg'></a>";
            $refdoi = $papers[$key][$key2]['DOI'];
          }
          $revuePaper2 = str_replace(" ", "%20", $revuePaper);
          $urlinit = "http://www.sherpa.ac.uk/romeo/api29.php?jtitle=".$revuePaper2."&ak=kn8p0JxcxGc";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $urlinit);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur.univ-rennes.fr)');
          if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
						curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
					}
          $resultat = curl_exec($ch);
          curl_close($ch);
          $dom = new DOMDocument();
          $dom->loadXML($resultat);
          $issn = "";
          $res = $dom->getElementsByTagName("issn");//ISSN
          if ($res -> length > 0) {
            $issn = $res->item(0)->nodeValue;
            if ($issn != "")
            {
              $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$issn."&ak=kn8p0JxcxGc";
            }
          }
          break;
        case "pubmed_csv":
          if (strpos($papers[$key][$key2]['Details'], "doi:") !== false) // DOI found
          {
            $pos0 = strpos($papers[$key][$key2]['Details'], "doi:");
            if (strpos($papers[$key][$key2]['Details'], "pii:") !== false) // Existing previous characters before DOI
            {
              $pos1 = strpos($papers[$key][$key2]['Details'], " ", $pos0+10)+1;;
            }
            else
            {
              $pos1 = $pos0 + 5;
            }
            $pos2 = strpos($papers[$key][$key2]['Details'], " ", $pos1+1)-1;
            if ($pos2 == -1) {$pos2 = strlen($papers[$key][$key2]['Details'])-1;}// No blank space after the beginning of the DOI and the end of the string
            $resdoi = substr($papers[$key][$key2]['Details'], $pos1, $pos2-$pos1);
            $refdoi = trim($resdoi);
            $resdoi = "<a target=\"_blank\" href=\"https://doi.org/".trim($resdoi)."\"><img src='./img/doi.jpg'></a>";
          }
          $revuePaper2 = str_replace(" ", "%20", $revuePaper);
          $urlinit = "http://www.sherpa.ac.uk/romeo/api29.php?jtitle=".$revuePaper2."&ak=kn8p0JxcxGc";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $urlinit);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur.univ-rennes.fr)');
          if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
						curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
					}
          $resultat = curl_exec($ch);
          curl_close($ch);
          $dom = new DOMDocument();
          $dom->loadXML($resultat);
          $issn = "";
          $res = $dom->getElementsByTagName("issn");//ISSN
          $issn = $res->item(0)->nodeValue;
          if ($issn != "")
          {
            $url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$issn."&ak=kn8p0JxcxGc";
          }
          break;
      }
      if ($desactivSR != "oui")
      {
        //var_dump($papers);
        if ($url == "")
        {
          $revuePaper2 = str_replace(" ", "%20", $revuePaper);
          $url = "http://www.sherpa.ac.uk/romeo/api29.php?jtitle=".$revuePaper2."&ak=kn8p0JxcxGc";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur.univ-rennes.fr)');
        if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
					curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
				}
        $resultat = curl_exec($ch);
        curl_close($ch);
        //echo $resultat;
        $dom = new DOMDocument();
        $dom->loadXML($resultat);
        $res = $dom->getElementsByTagName("outcome");//If the review is not found
        $resout = $res->item(0)->nodeValue;
        if ($resout != "notFound")//review has been found
        {
          $respar = "";
          $respre = "";
          $res = $dom->getElementsByTagName("postarchiving");//postarchiving conditions
          $respar = $res->item(0)->nodeValue;
          //echo $issn.' - '.$respar.'<br>';
          $res = $dom->getElementsByTagName("postrestriction");
          if (isset($res))//Is there postrestriction ?
          {
            $respre = $res->item(0)->nodeValue;;
          }
          switch($respar)
          {
            case "restricted":
              if (strpos($respre, "6") !== false)//Embargo 6 mois
              {
                $respar = "<img src='./img/embargo-6.jpg'>";
              }
              if (strpos($respre, "12") !== false)//Embargo 12 mois
              {
                $respar = "<img src='./img/embargo-12.jpg'>";
              }
              if (strpos($respre, "24") !== false)//Embargo 24 mois
              {
                $respar = "<img src='./img/embargo-24.jpg'>";
              }
              break;
            case "can":
              $respar = "<img src='./img/post-print.png'>";
              break;
            case "unknown":
              $respar = "<img src='./img/ungraded.png'>";
              break;
            case "cannot":
              $respar = "<img src='./img/red-cross.png'>";
              break;
          }
          $rescdt = "";
          $res = $dom->getElementsByTagName("condition");
          for ($c = 0; $c < $res->length; $c++)
          {
            $rescdt .= "&bull; ".$res->item($c)->nodeValue."<br>";
          }
          $rescdt = str_replace("'", "’", $rescdt);
          $rescdt = str_replace('"', '\"', $rescdt);
          if ((strpos($rescdt, "embargo") !== false || $respar == "restricted") && (strpos($respre, "6") === false && strpos($respre, "12") === false && strpos($respre, "24") === false && $respar != "<img src='./img/red-cross.png'>"))
          {
            $respar = "<img src='./img/embargo-e.jpg'>";
          }
          $urlisn = "";
          $resisn = "";
          $res = $dom->getElementsByTagName("issn");//ISSN
          $resisn = $res->item(0)->nodeValue;
          if ($resisn != "")
          {
            $urlisn = "http://www.sherpa.ac.uk/romeo/search.php?issn=".$resisn."&ak=kn8p0JxcxGc";
          }
          $respdf = "";
          $res = $dom->getElementsByTagName("pdfarchiving");//PDF archiving conditions
          $respdf = $res->item(0)->nodeValue;
          switch($respdf)
          {
            case "cannot":
              $respdf = "<img src='./img/red-cross.png'>";
              break;
            case "can":
              $respdf = "<img src='./img/pdf.png'>";
              break;
            case "unknown":
              $respdf = "<img src='./img/ungraded.png'>";
              break;
            case "restricted":
              $respdf = "<img src='./img/ungraded.png'>";
              break;
          }
          $resdiv = "";//Divers
          if ($resisn != "")
          {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlisn);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur.univ-rennes.fr)');
						if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
							curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
						}
            $temp = curl_exec($ch);
            curl_close($ch);
            if (strpos($temp, "DOAJ") !== false)//DOAJ
            {
              $resdiv = "<a target='_blank' href='https://doaj.org/'><img src='./img/doaj.png'></a>";
              $imgMailM = "./img/bouton-ccby.jpg";
              $imgMailP = "./img/bouton-ccby.jpg";
            }
          }
        }else{
          $urlisn = "OverHAL_noresult.php";
          $rescdt = "";
          $respar = "<img src='./img/ungraded.png'>";
          $respdf = "<img src='./img/ungraded.png'>";
          $resdiv = "";
          //$resdoi = "";
          //$refdoi = "";
        }
        if ($respar == "")//No information found for postarchiving
        {
          $respar = "<img src='./img/ungraded.png'>";
        }
        if ($respdf == "")//No information found for PDF archiving
        {
          $respdf = "<img src='./img/ungraded.png'>";
        }
        //echo "<tr><td colspan='4'>".$k." - ".$url."</td></tr>";
      }
      echo "<tr>";
      echo "<td valign=\"top\">".$k."</td>";
      if ($desactivSR == "non")
      {
        echo "<td align='center' valign='top'>";
        if ($resisn != "")
        {
          echo "<a target=\"_blank\" href='".$urlisn."' onmouseover='return overlib(\"".$rescdt."\",STICKY,CAPTION, \"&nbsp;<i>".$revuePaper."</i> conditions\",WIDTH, 320,HEIGHT, 130,RELX, 150,BORDER, 2,FGCOLOR,\"#FFFFFF\",BGCOLOR,\"#009933\",TEXTCOLOR,\"#000000\",CAPCOLOR,\"#000000\",CLOSECOLOR,\"#FFFFFF\");' onmouseout='return nd();'>".$respar."</a></td>";
        }else{
          echo "<a onmouseover='return overlib(\"".$rescdt."\",STICKY,CAPTION, \"&nbsp;<i>".$revuePaper."</i> conditions\",WIDTH, 320,HEIGHT, 130,RELX, 150,BORDER, 2,FGCOLOR,\"#FFFFFF\",BGCOLOR,\"#009933\",TEXTCOLOR,\"#000000\",CAPCOLOR,\"#000000\",CLOSECOLOR,\"#FFFFFF\");' onmouseout='return nd();'>".$respar."</a></td>";
        }
        echo "<td align='center' valign='top'>".$respdf."</td>";
        echo "<td align='center' valign='top'>".$resdiv."</td>";
      }
      $refdoiaff = "";
      if ($refdoi != "")
      {
        $refdoiaff = " - doi: <a target=\"_blank\" href=\"https://doi.org/".$refdoi."\">https://doi.org/".$refdoi."</a>";
      }
      //if more than 50 authors, just show the 10th first
      $nbaut = explode(";", $data[$colAuthors]);
      if (count($nbaut) > 50)
      {
        $affaut = "";
        for ($i = 0; $i <=10; $i++)
        {
          $affaut .= $nbaut[$i].";";
        }
        $affaut .= " <i> et al.</i>";
      }else{
        $affaut = $data[$colAuthors];
      }
      echo "<td align='justify' valign='top'>".$affaut." (".$yearPaper.") <a target=\"_blank\" href=\"https://scholar.google.fr/scholar?hl=fr&q=".$st=strtr($data[$colTitle],'"<>','   ')."\">".$data[$colTitle]."</a> - <i>".$revuePaper."</i>".$refdoiaff."</td>";
      echo "<td align='center' valign='top'>".$resdoi."</td>";
      //source column
      $linkSource = "";
      switch($key)
      {
        case "scopus":
          $linkSource = "<a target=\"_blank\" href=\"".$papers[$key][$key2]['Link']."\"><img src=\"./img/scopus3.png\"></a>";
          break;
        case "pubmed_csv":
          $linkSource = "<a target=\"_blank\" href=\"http://www.ncbi.nlm.nih.gov/pubmed/".$papers[$key][$key2]['EntrezUID']."\"><img src=\"./img/pubmed.png\"></a>";
          break;
        case "zotero":
          $linkSource = "<a target=\"_blank\" href=\"".$papers[$key][$key2]['Link Attachments']."\"><img src=\"./img/zotero_128.png\"></a>";
          break;
        case "wos_csv":
          $linkSource = "<a target=\"_blank\" href=\"http://ws.isiknowledge.com/cps/openurl/service?url_ver=Z39.88-2004&rft_id=info:ut/".str_replace("WOS:", "",$papers[$key][$key2]['UT'])."\"><img src=\"./img/wos.png\"></a>";
      }
      echo "<td align='center' valign='top'>".$linkSource."</td>";
      //joker column
      $linkJoker = "";
      if ($refdoi != "" && $joker != "")
      {
        $linkJoker = "<a target=\"_blank\" href=\"".$joker.$refdoi."\"><img src=\"./img/scihub.png\"></a>";
      }
      echo "<td align='center' valign='top'>".$linkJoker."</td>";
      //mail column
      if ($activMailsM == "oui" || $activMailsP == "oui")
      {
        $linkMailM = "";
        $linkMailP = "";
        $adr = "";
        $noadr = "";
        $lang = "";
        switch($key)
        {
          case "scopus":
            if (strpos($papers[$key][$key2]['Correspondence Address'], "@") !== false)
            {
              $pos = strpos($papers[$key][$key2]['Correspondence Address'], "email:");
              $adr = substr($papers[$key][$key2]['Correspondence Address'], ($pos + 6), strlen($papers[$key][$key2]['Correspondence Address']));
              $adr= str_replace(" ", "", $adr);
            }else{
              $noadr = "no mail";
            }
            break;
          case "pubmed_csv":
            break;
          case "zotero":
            $team = "";
            if ($papers[$key][$key2]['Rights'] != "")
            {
              $adr = str_replace(" ", "", $papers[$key][$key2]['Rights']);
              $coll = str_replace(" ", "", $papers[$key][$key2]['Call Number']);
              if ($coll != "") {$team = $coll;}
            }else{
              $noadr = "no mail";
            }
            break;
          case "wos_csv":
            if ($papers[$key][$key2]['EM'] != "")
            {
              $adr = str_replace(" ", "", $papers[$key][$key2]['EM']);
            }else{
              $noadr = "no mail";
            }
            break;
        }
        //Does sending mail to this author should be ignored
        include "./OverHAL_mails_a_exclure.php";
        foreach ($EXCLMAILS_LISTE as $value) {
          if ($adr == $value) {
            $noadr = "mail to be ignored";
            break;
          }
        }
        //Does a mail have already be sent to the corresponding author for this doi reference?
        $titreNorm = normalize(utf8_encode(mb_strtolower(utf8_decode($data[$colTitle]))));
        include ('./OverHAL_mails_envoyes.php');
        $nouvelEnvoiM = "non";
        $nouvelEnvoiP = "non";
        $mailOK = "";

        foreach($MAILS_LISTE AS $i => $valeur) {
          if ($refdoi != "" && $MAILS_LISTE[$i]["quoi2"] == $refdoi) //mail already send
          {
            $mailOK = "OK";
          }
          if ($adr != "" && $MAILS_LISTE[$i]["qui"] == $adr)
          {
            $nouvelEnvoiM = "oui";
            $nouvelEnvoiP = "oui";
            $lang = strtoupper($MAILS_LISTE[$i]["lang"]);
          }
        }
        //Language to use for the mail
        $mailValide = "ok";
        if ($lang == "" && $adr != "")
        {
          $lang = "FR";//French initially
          //Is there different adresses for the mail contact?
          if ((strpos($adr, ";") !== false) || (strpos($adr, ",") !== false))
          {
            if (strpos($adr, ";") !== false)
            {
              $adrMail = explode(";", $adr);
            }else{
              $adrMail = explode(",", $adr);
            }
            foreach ($adrMail as $a)
            {
              $dcp = explode("@", $a);
              //var_dump($dcp);
              if (strpos($dcp[1], ".") === false)
              {
                $mailValide = "non";
                break;
              }
              if (substr($a, -2) != "fr" && substr($a, -2) != "be")
              {
                $lang = "EN";
                break;
              }
            }
          }else{
            if (substr($adr, -2) != "fr" && substr($adr, -2) != "be")
              {
                $dcp = explode("@", $adr);
                if (strpos($dcp[1], ".") === false)
                {
                  $mailValide = "non";
                }
                $lang = "EN";
              }
          }
        }
        if ($mailOK == "")
        {
          if ($nouvelEnvoiM == "non")
          {
            if ($lang == "FR")
            {
              include "./OverHAL_mails_premier_fr.php";
            }else{
              include "./OverHAL_mails_premier_en.php";
            }
            if ($imgMailM == "")
            {
              $imgMailM = "./img/bouton-m.jpg";
            }
            $linkMailM = "<div id=\"".$titreNorm."M\"><a href=\"#".$titreNorm."\" onClick=\"majMailsM('".$adr."','".$titreNorm."','".$refdoi."','M','','".strtoupper($lang)."'); mailto('".$adr."','".$subjectM."','".$bodyM."');\"><img alt='".$adr."' title='".$adr."' src=\"".$imgMailM."\"></a></div>";
          }else{//new solicitation
            if ($lang == "FR")
            {
              include "./OverHAL_mails_rappel_fr.php";
            }else{
              include "./OverHAL_mails_rappel_en.php";
            }
            if ($imgMailM == "")
            {
              $imgMailM = "./img/bouton-m.jpg";
            }
            $linkMailM = "<div id=\"".$titreNorm."M\"><a href=\"#".$titreNorm."\" onClick=\"majMailsM('".$adr."','".$titreNorm."','".$refdoi."','M','','".strtoupper($lang)."'); mailto('".$adr."','".$subjectM."','".$bodyM."');\"><img alt='".$adr."' title='".$adr."' src=\"".$imgMailM."\"></a></div>";
          }
          if ($nouvelEnvoiP == "non")
          {
            if ($lang == "FR")
            {
              include "./OverHAL_mails_premier_fr.php";
            }else{
              include "./OverHAL_mails_premier_en.php";
            }
            if ($imgMailP == "")
            {
              $imgMailP = "./img/bouton-p.jpg";
            }
            $linkMailP = "<div id=\"".$titreNorm."P\"><a href=\"#".$titreNorm."\" onClick=\"majMailsP('".$adr."','".$titreNorm."','".$refdoi."','P','','".strtoupper($lang)."'); mailto('".$adr."','".$subjectP."','".$bodyP."');\"><img alt='".$adr."' title='".$adr."' src=\"".$imgMailP."\"></a></div>";
          }else{//new solicitation
            if ($lang == "FR")
            {
              include "./OverHAL_mails_rappel_fr.php";
            }else{
              include "./OverHAL_mails_rappel_en.php";
            }
            if ($imgMailP == "")
            {
              $imgMailP = "./img/bouton-p.jpg";
            }
            $linkMailP = "<div id=\"".$titreNorm."P\"><a href=\"#".$titreNorm."\" onClick=\"majMailsP('".$adr."','".$titreNorm."','".$refdoi."','P','','".strtoupper($lang)."'); mailto('".$adr."','".$subjectP."','".$bodyP."');\"><img alt='".$adr."' title='".$adr."' src=\"".$imgMailP."\"></a></div>";
          }
        }else{
          $linkMailM = "<b>OK</b>";
          $linkMailP = "<b>OK</b>";
        }
        if ($mailValide == "non")
        {
          $linkMailM = "<a href=\"#".$titreNorm."\" onClick=\"errAdr('".$adr."');\"><img alt='".$adr."' title='".$adr."' src=\"".$imgMailM."\"></a>";
          $linkMailP = "<a href=\"#".$titreNorm."\" onClick=\"errAdr('".$adr."');\"><img alt='".$adr."' title='".$adr."' src=\"".$imgMailP."\"></a>";
        }

        if ($noadr == "")
        {
          if ($activMailsM == "oui")
          {
            echo "<td align='center' valign='top'>".$linkMailM."<a name=\".$titreNorm.\"></a></td>";
          }else{
            echo "<td align='center' valign='top'>&nbsp;</td>";
          }
          if ($activMailsP == "oui")
          {
            echo "<td align='center' valign='top'>".$linkMailP."</td>";
          }else{
            echo "<td align='center' valign='top'>&nbsp;</td>";
          }
        }else{
          if ($imgMailM == "" && $imgMailP == "")
          {
            $imgMailM = "./img/bouton-mnoadr.jpg";
            $imgMailP = "./img/bouton-pnoadr.jpg";
          }else{
            if ($imgMailM == "./img/bouton-ccby.jpg" && $imgMailP == "./img/bouton-ccby.jpg")
            {
              $imgMailM = "./img/bouton-ccbynoadr.jpg";
              $imgMailP = "./img/bouton-ccbynoadr.jpg";
            }else{
              $imgMailM = "./img/bouton-mnoadr.jpg";
              $imgMailP = "./img/bouton-pnoadr.jpg";
            }
          }
          if ($activMailsM == "oui")
          {
            echo "<td align='center' valign='top'><img alt='".$noadr."' title='".$noadr."' src=\"".$imgMailM."\"></td>";
          }else{
            echo "<td align='center' valign='top'>&nbsp;</td>";
          }
          if ($activMailsP == "oui")
          {
            echo "<td align='center' valign='top'><img alt='".$noadr."' title='".$noadr."' src=\"".$imgMailP."\"></td>";
          }else{
            echo "<td align='center' valign='top'>&nbsp;</td>";
          }
        }
      }
      echo "</tr>";
      //export bibtex
      switch($key)
      {
        case "scopus":
          $Fnm = "./HAL/OverHAL_scopus.bib";
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          //$chaine = "\xEF\xBB\xBF";
          $chaine = "";
          fwrite($inF,$chaine);
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          if (isset($papers[$key][$key2]['Document Type']))
          {
            $type = $papers[$key][$key2]['Document Type'];
            switch($type)
            {
              case "Article":
              case "Article in Press":
              case "Review":
              case "Erratum":
              case "Editorial":
              case "Short Survey":
              case "Letter":
              case "Note":
                $type = "article";
                break;
              case "Conference Paper":
              case "Conference Review":
                $type = "inproceedings";
                break;
              case "Book":
                $type = "book";
                break;
              case "Book Chapter":
                $type = "inbook";
                break;
            }
            $chaine = chr(13).chr(10)."@".$type."{";
          }
          if (isset($papers[$key][$key2]['Authors']))
          {
            $auteurs = explode(", ", $papers[$key][$key2]['Authors']);
            $chaine .= mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8');
          }
          if (isset($papers[$key][$key2]['Title']))
          {
            $titre = explode(" ", $papers[$key][$key2]['Title']);
            $chaine .= "_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
          }
          //add a constant to differenciate same initial identifier
          if (isset($auteurs) && isset($titre))
          {
            $tit = mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8')."_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
            if (strpos($listTit, "¤".$tit."¤") !== false)
            {
              $cst++;
              $chaine .= $cst;
            }
            $listTit .= $tit."¤";
          }
          if (isset($papers[$key][$key2]['Year'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['Year'], 'UTF-8');}
          if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
          if (isset($papers[$key][$key2]['Volume'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['Volume']."}";}
          //if ($issn) {$chaine .= ",".chr(13).chr(10)."	issn = {".$issn."}";}
          if (isset($papers[$key][$key2]['ISSN'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['ISSN']."}";}
          if (isset($papers[$key][$key2]['DOI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";}
          if (isset($papers[$key][$key2]['Abstract'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", $papers[$key][$key2]['Abstract'])."}";}
          if (isset($papers[$key][$key2]['Source title'])) {$chaine .= ",".chr(13).chr(10)."	journal = {".$papers[$key][$key2]['Source title']."}";}
          if (isset($papers[$key][$key2]['Authors'])) {
            $auteurs = $papers[$key][$key2]['Authors'];
            //add a comma after the name
            $i = 0;
            $autvirg = "";
            $esp = explode(", ", $auteurs);
            while ($i < count($esp))
            {
              $autesp = strrchr(trim($esp[$i]), " ");
              if ($i != count($esp) - 1)
              {
                $autvirg .= str_replace($autesp, ",".$autesp.",", $esp[$i]);
              }else{
                $autvirg .= str_replace($autesp, ",".$autesp, $esp[$i]);
              }
              $i++;
            }
            $auteurs = str_replace(".,", ". and ", $autvirg);
            $chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
          }
          if (isset($papers[$key][$key2]['Authors with affiliations'])) {$chaine .= ",".chr(13).chr(10)."	affiliations = {".$papers[$key][$key2]['Authors with affiliations']."}";}
          if (isset($papers[$key][$key2]['Year'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Year']."}";}
          if (isset($papers[$key][$key2]['Author Keywords'])) {$chaine .= ",".chr(13).chr(10)."	keywords = {".$papers[$key][$key2]['Author Keywords']."}";}
          if (isset($papers[$key][$key2]['Page start']) && isset($papers[$key][$key2]['Page end']))
          {
            $chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Page start']."--".$papers[$key][$key2]['Page end']."}";
          }else{
            if (isset($papers[$key][$key2]['Art. No.'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".trim($papers[$key][$key2]['Art. No.'])."}";}
          }
          if (isset($papers[$key][$key2]['Funding Details'])) {$chaine .= ",".chr(13).chr(10)."	x-funding = {".$papers[$key][$key2]['Funding Details']."}";}
          if (isset($papers[$key][$key2]['PubMed ID'])) {$chaine .= ",".chr(13).chr(10)."	pmid = {".$papers[$key][$key2]['PubMed ID']."}";}
          if (isset($papers[$key][$key2]['Conference location'])) {$chaine .= ",".chr(13).chr(10)."	address = {".$papers[$key][$key2]['Conference location']."}";}
          if (isset($papers[$key][$key2]['Conference name'])) {$chaine .= ",".chr(13).chr(10)."	booktitle = {".$papers[$key][$key2]['Conference name']."}";}
          if (isset($papers[$key][$key2]['Conference date']))
          {
            //6 September 2015 through 9 September 2015 to 2015-09-06
            $confdate = $papers[$key][$key2]['Conference date'];
            if ($confdate != "") {
              $res = explode(" ", $confdate);
              if (count($res) > 2) {
                //year
                $confdate = trim($res[2])."-";
                //month
                switch($res[1])
                {
                  case "January":
                    $confdate .= "01-";
                    break;
                  case "February":
                    $confdate .= "02-";
                    break;
                  case "March":
                    $confdate .= "03-";
                    break;
                  case "April":
                    $confdate .= "04-";
                    break;
                  case "May":
                    $confdate .= "05-";
                    break;
                  case "June":
                    $confdate .= "06-";
                    break;
                  case "July":
                    $confdate .= "07-";
                    break;
                  case "August":
                    $confdate .= "08-";
                    break;
                  case "September":
                    $confdate .= "09-";
                    break;
                  case "October":
                    $confdate .= "10-";
                    break;
                  case "November":
                    $confdate .= "11-";
                    break;
                  case "December":
                    $confdate .= "12-";
                    break;
                }
                //day
                if (strlen($res[0]) == 1)
                {
                  $confdate .= "0".$res[0];
                }else{
                  $confdate .= $res[0];
                }
              }
            }
            $chaine .= ",".chr(13).chr(10)."	x-conferencestartdate = {".$confdate."}";
          }
          if (isset($papers[$key][$key2]['Editor(s)']))
          {
            $editors = str_replace("; ", " and ", $papers[$key][$key2]['Editor(s)']);
            $chaine .= ",".chr(13).chr(10)."	editor = {".$editors."}";
          }
          if (isset($papers[$key][$key2]['ISBN'])) {$chaine .= ",".chr(13).chr(10)."	isbn = {".$papers[$key][$key2]['ISBN']."}";}
          if (isset($papers[$key][$key2]['Publisher'])) {$chaine .= ",".chr(13).chr(10)."	publisher = {".$papers[$key][$key2]['Publisher']."}";}
          if (isset($papers[$key][$key2]['Language of Original Document']))
          {
            if ($papers[$key][$key2]['Language of Original Document'] != "English" || $papers[$key][$key2]['Language of Original Document'] == "English; French")
            {
              $chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
              $chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
            }
          }
          $chaine .= chr(13).chr(10)."}".chr(13).chr(10);
          fwrite($inF, $chaine);
          break;
        case "wos_csv":
          mb_internal_encoding('UTF-8');
          $Fnm = "./HAL/OverHAL_wos_csv.bib";
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          //$chaine = "\xEF\xBB\xBF";//UTF-8
          $chaine = "";//ANSI
          fwrite($inF,$chaine);
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          if (isset($papers[$key][$key2]['PT']))
          {
            $type = $papers[$key][$key2]['PT'];
            switch($type)
            {
              case "J":
                $type = "article";
                break;
              case "S":
                $type = "inproceedings";
                break;
              case "B":
                $type = "book";
                break;
              case "P":
                $type = "patent";
                break;
            }
            $chaine = chr(13).chr(10)."@".$type."{";
          }
          if (isset($papers[$key][$key2]['AF']))
          {
            $auteurs = explode(", ", $papers[$key][$key2]['AF']);
            $chaine .= mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8');
          }
          if (isset($papers[$key][$key2]['TI']))
          {
            $titre = explode(" ", $papers[$key][$key2]['TI']);
            $chaine .= "_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
          }
          //add a constant to differenciate same initial identifier
          if (isset($auteurs) && isset($titre))
          {
            $tit = mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8')."_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
            if (strpos($listTit, "¤".$tit."¤") !== false)
            {
              $cst++;
              $chaine .= $cst;
            }
            $listTit .= $tit."¤";
          }
          if (isset($papers[$key][$key2]['PY'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['PY'], 'UTF-8');}
          if (isset($papers[$key][$key2]['TI'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['TI']."}";}
          if (isset($papers[$key][$key2]['VL'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['VL']."}";}
          if (isset($papers[$key][$key2]['SN'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['SN']."}";}
          if (isset($papers[$key][$key2]['DI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DI']."}";}
          if (isset($papers[$key][$key2]['AB'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", trim($papers[$key][$key2]['AB']))."}";}
          if (isset($papers[$key][$key2]['SO'])) {$chaine .= ",".chr(13).chr(10)."	journal = {".minRev($papers[$key][$key2]['SO'])."}";}
          if (isset($papers[$key][$key2]['AF']))
          {
            $auteurs = str_replace("; ", " and ", $papers[$key][$key2]['AF']);
            $chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
          }
          if (isset($papers[$key][$key2]['C1'])) {$chaine .= ",".chr(13).chr(10)."	affiliations = {".$papers[$key][$key2]['C1']."}";}
          if (isset($papers[$key][$key2]['PY'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['PY']."}";}
          if (isset($papers[$key][$key2]['DE'])) {$chaine .= ",".chr(13).chr(10)."	keywords = {".strtolower($papers[$key][$key2]['DE'])."}";}
          if (isset($papers[$key][$key2]['BP']) && isset($papers[$key][$key2]['EP']))
          {
            $chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['BP']."--".$papers[$key][$key2]['EP']."}";
          }else{
            if (isset($papers[$key][$key2]['AR'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['AR']."}";}
          }
          if (isset($papers[$key][$key2]['FU'])) {$chaine .= ",".chr(13).chr(10)."	x-funding = {".$papers[$key][$key2]['FU']."}";}
          if (isset($papers[$key][$key2]['PM'])) {$chaine .= ",".chr(13).chr(10)."	pmid = {".$papers[$key][$key2]['PM']."}";}
          if (isset($papers[$key][$key2]['IS'])) {$chaine .= ",".chr(13).chr(10)."	number = {".$papers[$key][$key2]['IS']."}";}
          if (isset($papers[$key][$key2]['CL'])) {$chaine .= ",".chr(13).chr(10)."	address = {".$papers[$key][$key2]['CL']."}";}
          if (isset($papers[$key][$key2]['CT'])) {$chaine .= ",".chr(13).chr(10)."	booktitle = {".$papers[$key][$key2]['CT']."}";}
          if (isset($papers[$key][$key2]['CY']))
          {
            //OCT 19-23, 2014 to 2014-10-19
            $confdate = $papers[$key][$key2]['CY'];
            if ($confdate != "") {
              $res = explode(" ", $confdate);
              if (count($res) > 2) {
                //year
                $confdate = trim($res[2])."-";
                //month
                switch($res[0])
                {
                  case "JAN":
                    $confdate .= "01-";
                    break;
                  case "FEB":
                    $confdate .= "02-";
                    break;
                  case "MAR":
                    $confdate .= "03-";
                    break;
                  case "APR":
                    $confdate .= "04-";
                    break;
                  case "MAY":
                    $confdate .= "05-";
                    break;
                  case "JUN":
                    $confdate .= "06-";
                    break;
                  case "JUL":
                    $confdate .= "07-";
                    break;
                  case "AUG":
                    $confdate .= "08-";
                    break;
                  case "SEP":
                    $confdate .= "09-";
                    break;
                  case "OCT":
                    $confdate .= "10-";
                    break;
                  case "NOV":
                    $confdate .= "11-";
                    break;
                  case "DEC":
                    $confdate .= "12-";
                    break;
                }
                //day
                $confdate .= substr($res[1], 0, 2);
              }
            }
            $chaine .= ",".chr(13).chr(10)."	x-conferencestartdate = {".$confdate."}";
          }
          if (isset($papers[$key][$key2]['BE']))
          {
            $editors = str_replace("; ", " and ", $papers[$key][$key2]['BE']);
            $chaine .= ",".chr(13).chr(10)."	editor = {".$editors."}";
          }
          if (isset($papers[$key][$key2]['BN'])) {$chaine .= ",".chr(13).chr(10)."	isbn = {".$papers[$key][$key2]['BN']."}";}
          if (isset($papers[$key][$key2]['PU'])) {$chaine .= ",".chr(13).chr(10)."	publisher = {".$papers[$key][$key2]['PU']."}";}
          if (isset($papers[$key][$key2]['SE'])) {$chaine .= ",".chr(13).chr(10)."	series = {".$papers[$key][$key2]['SE']."}";}
          if (isset($papers[$key][$key2]['LA']))
          {
            if ($papers[$key][$key2]['LA'] != "English")
            {
              $chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
              $chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
            }
          }
          $chaine .= chr(13).chr(10)."}".chr(13).chr(10);
          fwrite($inF, $chaine);
          break;
        case "scifin":
          $Fnm = "./HAL/OverHAL_scifin.bib";
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          $chaine = "\xEF\xBB\xBF";
          fwrite($inF,$chaine);
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          if (isset($papers[$key][$key2]['Document Type']))
          {
            $type = $papers[$key][$key2]['Document Type'];
            switch($type)
            {
              case "Journal; Online Computer File":
              case "Journal":
              case "Preprint":
              case "Journal; General Review; Online Computer File":
                $type = "article";
                break;
              case "Patent":
                $type = "patent";
                break;
            }
            $chaine = chr(13).chr(10)."@".$type."{";
          }
          if (isset($papers[$key][$key2]['Author']))
          {
            $auteurs = explode(", ", $papers[$key][$key2]['Author']);
            $chaine .= mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8');
          }
          if (isset($papers[$key][$key2]['Title']))
          {
            $titre = explode(" ", $papers[$key][$key2]['Title']);
            $chaine .= "_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
          }
          //add a constant to differenciate same initial identifier
          if (isset($auteurs) && isset($titre))
          {
            $tit = mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8')."_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
            if (strpos($listTit, "¤".$tit."¤") !== false)
            {
              $cst++;
              $chaine .= $cst;
            }
            $listTit .= $tit."¤";
          }
          if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['Publication Year'], 'UTF-8');}
          if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
          if (isset($papers[$key][$key2]['Volume'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['Volume']."}";}
          if (isset($papers[$key][$key2]['Internat.Standard Doc. Number'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['Internat.Standard Doc. Number']."}";}
          if (isset($papers[$key][$key2]['DOI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";}
          if (isset($papers[$key][$key2]['Abstract'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", trim($papers[$key][$key2]['Abstract']))."}";}
          if (isset($papers[$key][$key2]['Journal Title'])) {$chaine .= ",".chr(13).chr(10)."	journal = {".$papers[$key][$key2]['Journal Title']."}";}
          if (isset($papers[$key][$key2]['Author']))
          {
            $auteurs = str_replace("; ", " and ",$papers[$key][$key2]['Author']);
            $chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
          }
          if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Publication Year']."}";}
          if (isset($papers[$key][$key2]['Page'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Page']."}";}
          if (isset($papers[$key][$key2]['Language']))
          {
            if ($papers[$key][$key2]['Language'] != "written in English")
            {
              $chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
              $chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
            }
          }
          $chaine .= chr(13).chr(10)."}".chr(13).chr(10);
          fwrite($inF, $chaine);
          break;
        case "zotero":
          $Fnm = "./HAL/OverHAL_zotero.bib";
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          $chaine = "\xEF\xBB\xBF";
          fwrite($inF,$chaine);
          $inF = fopen($Fnm,"a+");
          fseek($inF, 0);
          if (isset($papers[$key][$key2]['Item Type']))
          {
            $type = $papers[$key][$key2]['Item Type'];
            switch($type)
            {
              case "journalArticle":
                $type = "article";
                break;
              case "conferencePaper":
                $type = "inproceedings";
                break;
              case "book":
                $type = "book";
                break;
              case "bookSection":
                $type = "inbook";
                break;
              case "patent":
                $type = "patent";
                break;
            }
            $chaine = chr(13).chr(10)."@".$type."{";
          }
          if (isset($papers[$key][$key2]['Author']))
          {
            $auteurs = explode(", ", $papers[$key][$key2]['Author']);
            $chaine .= mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8');
          }
          if (isset($papers[$key][$key2]['Title']))
          {
            $titre = explode(" ", $papers[$key][$key2]['Title']);
            $chaine .= "_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
          }
          //add a constant to differenciate same initial identifier
          if (isset($auteurs) && isset($titre))
          {
            $tit = mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8')."_".mb_strtolower(str_replace(array("(", ")", ","), "_", $titre[0]), 'UTF-8');
            if (strpos($listTit, "¤".$tit."¤") !== false)
            {
              $cst++;
              $chaine .= $cst;
            }
            $listTit .= $tit."¤";
          }
          if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['Publication Year'], 'UTF-8');}
          if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
          if (isset($papers[$key][$key2]['Volume'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['Volume']."}";}
          if (isset($papers[$key][$key2]['ISSN'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['ISSN']."}";}
          if (isset($papers[$key][$key2]['DOI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";}
          if (isset($papers[$key][$key2]['Abstract Note'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", trim($papers[$key][$key2]['Abstract Note']))."}";}
          if (isset($papers[$key][$key2]['Publication Title']))
          {
            $titreJ = $papers[$key][$key2]['Publication Title'];
            $titreJVal = $papers[$key][$key2]['Publication Title'];
            if (substr($titreJ, 0, 4) == "The ")//Suppression of initials characters 'The ' for journal title
            {
              $titreJVal = ucfirst(substr($titreJ, 4));
            }
            if (strpos($titreJ, "(") !== false && strpos($titreJ, ")") !== false)//Suppression of text in brackets
            {
              $titreJTmp = preg_replace("#\([^\)]+\)#", "¤", $titreJ);
              $titreJVal = str_replace(" ¤", "", $titreJTmp);
            }
            $chaine .= ",".chr(13).chr(10)."	journal = {".$titreJVal."}";
          }
          
          if (isset($_POST['author']) && $_POST['author'] != "" && isset($papers[$key][$key2]['Author']) && $papers[$key][$key2]['Author'] == "")
          {
            $auteurs = str_replace("; ", " and ", $_POST['author']);
            $chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
          }else{
            if (isset($papers[$key][$key2]['Author']))
            {
              $auteurs = str_replace("; ", " and ", $papers[$key][$key2]['Author']);
              $chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
            }
          }
          $keywords = "";
          if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui") {
            if (isset($papers[$key][$key2]['Automatic Tags']) && $papers[$key][$key2]['Automatic Tags'] != "")
            {
              $keywords = $_POST['keywords'].'; ';
            }else{
              $keywords = $_POST['keywords'];
            }
          }
          if (isset($papers[$key][$key2]['Automatic Tags'])) {$chaine .= ",".chr(13).chr(10)."	keywords = {".$keywords.$papers[$key][$key2]['Automatic Tags']."}";}
          if (isset($papers[$key][$key2]['Pages'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Pages']."}";}
          if (isset($papers[$key][$key2]['Extra'])) {
            $pmid = $papers[$key][$key2]['Extra'];
            if (strpos($pmid, "PMCID") !== false)//PMID + PMCID
            {
              $pmidVal = str_replace(array("PMID: ", "PMCID: "), "", $pmid);
              $pmidValT = explode(" ", $pmidVal);
              $chaine .= ",".chr(13).chr(10)."	pmid = {".$pmidValT[0]."}";
              $chaine .= ",".chr(13).chr(10)."	pmcid = {".$pmidValT[1]."}";
            }else{//only PMID
              $pmidVal = str_replace("PMID: ", "", $pmid);
              $chaine .= ",".chr(13).chr(10)."	pmid = {".$pmidVal."}";
            }
          }
          if (isset($papers[$key][$key2]['Conference Name'])) {$chaine .= ",".chr(13).chr(10)."	booktitle = {".$papers[$key][$key2]['Conference Name']."}";}
          if (isset($papers[$key][$key2]['Editor']))
          {
            $editors = str_replace("; ", " and ", $papers[$key][$key2]['Editor']);
            $chaine .= ",".chr(13).chr(10)."	editor = {".$editors."}";
          }
          if (isset($papers[$key][$key2]['ISBN'])) {$chaine .= ",".chr(13).chr(10)."	isbn = {".$papers[$key][$key2]['ISBN']."}";}
          if (isset($papers[$key][$key2]['Publisher'])) {$chaine .= ",".chr(13).chr(10)."	publisher = {".$papers[$key][$key2]['Publisher']."}";}
          if (isset($papers[$key][$key2]['Series'])) {$chaine .= ",".chr(13).chr(10)."	series = {".$papers[$key][$key2]['Series']."}";}
          if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui") {
            if (isset($papers[$key][$key2]['Date'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Date']."}";}
          }else{
            if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Publication Year']."}";}
          }
          if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui")
          {
            $chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
            $chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
          }else{
            if (isset($papers[$key][$key2]['Language']))
            {
              if ($papers[$key][$key2]['Language'] == "")
              {
                $chaine .= ",".chr(13).chr(10)."	x-language = {en}";
                $chaine .= ",".chr(13).chr(10)."	x-audience  = {International}";
              }else{
                if ($papers[$key][$key2]['Language'] != "English" && $papers[$key][$key2]['Language'] != "ENG" && $papers[$key][$key2]['Language'] != "EN" && $papers[$key][$key2]['Language'] != "eng" && $papers[$key][$key2]['Language'] != "en")
                {
                  $chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
                  $chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
                }else{
                  $chaine .= ",".chr(13).chr(10)."	x-language = {en}";
                  $chaine .= ",".chr(13).chr(10)."	x-audience  = {International}";
                }
              }
            }
          }
          $chaine .= chr(13).chr(10)."}".chr(13).chr(10);
          fwrite($inF, $chaine);
          break;
      }
      //export TEI
      $limNbAut = 25;//If more authors than this limit, no export is done > too long time to process
      switch($key)
      {
        case "wos_csv":
          $aut = $papers[$key][$key2]['AU'];
          $autTab = explode("; ",$aut);
          if (count($autTab) < $limNbAut)
          {
            //affiliation
            $autTab = array();
            $labTab = array();
            $autInd = 0;
            $docid = 0;
            $label = "";
            $code = "";
            $cuHAL = "";
            $type = "";
            $pays = "";
            $quoi = $papers[$key][$key2]['C1'];
            $quoi = trimUltime($quoi);
            $validHAL = "";//to privilegy the search by the unit code number rather than acronym
            //echo "<br>".$j." - ".$quoi."<br>";
            $diffQuoi = explode("; [", $quoi);
            //var_dump($diffQuoi);
            for ($d = 0; $d < count($diffQuoi); $d++)
            {
              $urlHAL = "";
              $docid = 0;
              $label = "";
              $code = "";
              $cuHAL = "";
              $type = "";
              $pays = "";
              //Search for distinctive acronyms
              //echo $diffQuoi[$d].'<br>';
              searchAcro($diffQuoi[$d], $cuHAL, $urlHAL, $validHAL, $retTest);
              //echo $urlHAL.'<br>';
              if ($urlHAL != "")//affiliation with code unit or acronym found
              {
                //echo $diffQuoi[$d].'<br>';
                idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
              }
              //echo $docid.' - '.$urlHAL.'<br>';
              if($docid != 0)
              {
                affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
                while ($retTest != "") {
                  $aTester = $retTest;
                  $autInd = 0;
                  $urlHAL = "";
                  $docid = 0;
                  $label = "";
                  $code = "";
                  $cuHAL = "";
                  $type = "";
                  $pays = "";
                  //Search for distinctive acronyms
                  //echo 'toto : '.$aTester.'<br>';
                  searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
                  //echo 'titi : '.$urlHAL.'<br>';
                  if ($urlHAL != "")//affiliation with code unit or acronym found
                  {
                    idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
                  }
                  //echo $urlHAL.' - '.$docid.'<br>';
                  if($docid != 0)
                  {
                    affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
                  }
                }
              }else{//no specific affiliation found > we store whole results
                //echo $diffQuoi[$d].'<br>';
                $validHAL = "--";
                $eltTab = explode("] ",$diffQuoi[$d]);
                include './OverHAL_affiliation_termes.php';
                $label = supprAmp(trim($eltTab[1]));
                $label = str_ireplace($search, $replace, $label);
                //echo $label.'<br>';
                include './OverHAL_univ_termes.php';
                $queTab = explode(",", $label);
                $quePay = count($queTab) - 1;
                $labFin = "";
                $iq = 0;
                $trm = "University";
                foreach($queTab as $elt)
                {
                  $elt = trim($elt);
                  if(stripos($elt, "univ") !== false) {//term 'Univ' is present
                    $pays = trim($queTab[$quePay]);
                    $kT = array_search($pays, array_column($UNIV_LISTE, "pays"));
                    if ($kT !== false) {$trm = $UNIV_LISTE[$kT]["univ"];}
                  }
                  if(stripos($elt, "University") === false) {//term 'University' is absent
                    $elt = str_replace(array("Univ", "univ"), $trm, $elt);
                  }
                  if ($iq > 0) {$labFin .= ", ";}
                  $labFin .= $elt;
                  $iq++;
                }
                //$docid = 999999;
                $docid = $labFin;
                $code = $labFin;
                $type = 'institution';
                affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);
              }
            }
            //var_dump($autTab);
            //var_dump($labTab);
            $strTab = array();
            $ddn = supprAmp($papers[$key][$key2]['GA']);//Document Delivery Number
            mb_internal_encoding('UTF-8');
            $zip = new ZipArchive();
            $FnmZ = "./HAL/OverHAL_wos_csv.zip";
            if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
              exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
            }
            $Fnm = "./HAL/OverHAL_wos_csv_".$ddn.".xml";
            $inF = fopen($Fnm,"a+");
            fseek($inF, 0);
            //$chaine = "\xEF\xBB\xBF";//UTF-8
            $chaine = "";//ANSI
            $chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
            $chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
            $chaine .= '  <text>'."\r\n".
                       '    <body>'."\r\n".
                       '      <listBibl>'."\r\n".
                       '        <biblFull>'."\r\n".
                       '          <titleStmt>'."\r\n";
            //funder
            $chaine .= '            <funder>'.supprAmp($papers[$key][$key2]['FU']).'</funder>'."\r\n";
            $chaine .= '          </titleStmt>'."\r\n";
            //if DOI exists searching an OA PDF file
            if (isset($papers[$key][$key2]['DI']) && $papers[$key][$key2]['DI'] != "")
            {
              $urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DI'];
              $volT = $papers[$key][$key2]['VL'];
              $issT = $papers[$key][$key2]['IS'];
              $pagT = $papers[$key][$key2]['BP'];
              $datT = $papers[$key][$key2]['PY'];
              $pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

              testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $entry->halId_s, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $titPDF);

              if ($titPDF != "")//an OA PDF file has benn found
              {
                $chaine .= '          <editionStmt>'."\r\n".
                           '            <edition>'."\r\n".
                           '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$targetPDF.$entry->halId_s.'.pdf"></ref>'."\r\n".
                           '            </edition>'."\r\n".
                           '          </editionStmt>'."\r\n";
                $avail = 'http://creativecommons.org/licenses/by';
                if ($compNC != "") {$avail .= '-nc';}
                if ($compND != "") {$avail .= '-nd';}
                if ($compSA != "") {$avail .= '-sa';}
                $avail .= '/';
                $chaine .= '          <publicationStmt>'."\r\n".
                           '            <availability>'."\r\n".
                           '              <licence target="'.$avail.'"/>'."\r\n".
                           '            </availability>'."\r\n".
                           '          </publicationStmt>'."\r\n";
              }
            }
            $chaine .= '          <seriesStmt>'."\r\n".
                       '          </seriesStmt>'."\r\n".
                       '          <notesStmt>'."\r\n".
                       '          </notesStmt>'."\r\n".
                       '          <sourceDesc>'."\r\n".
                       '            <biblStruct>'."\r\n".
                       '              <analytic>'."\r\n";
            $lng = "";
            //langue + titre
            if (isset($papers[$key][$key2]['LA']))
            {
              if ($papers[$key][$key2]['LA'] == "French") {$lng = "fr";}
              if ($papers[$key][$key2]['LA'] == "English") {$lng = "en";}
            }
            $chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['TI']).'</title>'."\r\n";
            //auteurs
            $aut = explode(";", $papers[$key][$key2]['AF']);
            $iTp = 0;
            foreach ($aut as $qui) {
              $quiTab = explode(",", $qui);
              $chaine .= '                <author role="aut">'."\r\n";
              $chaine .= '                  <persName>'."\r\n";
              if (isset($quiTab[1]))//in case of no comma for one author
              {
                $prenom = supprAmp(trim($quiTab[1]));
              }else{
                $prenom = " ";
              }
              $nom = supprAmp(trim($quiTab[0]));
              $nompre = $nom .", ".$prenom;
              $chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
              $chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
              $chaine .= '                  </persName>'."\r\n";
              if (isset($papers[$key][$key2]['EM']))
              {//email
                $tabMail = explode(";", $papers[$key][$key2]['EM']);
                foreach ($tabMail as $elt) {
                  if (stripos(trim(normalize($elt)), normalize($nom)) !== false)
                  {
                    $chaine .= '                  <email>'.trim($elt).'</email>'."\r\n";
                  }
                }
              }
              $kT = array_search($nompre, $autTab);
              //echo $kT." - ".$nom."<br>";
              //var_dump($labTab);
              if ($kT !== FALSE) {
                foreach ($labTab[$nompre] as $lab) {
                  //$str = array_search($labTab[$nompre][$kT], $strTab);
                  $str = array_search($lab, $strTab);
                  if ($str === FALSE) {
                    $iTp += 1;
                    $kTp = $iTp;
                    array_push($strTab, $lab);
                  }else{
                    $kTp = $str + 1;
                  }
                  $chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
                  //echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
                }
              }
              $chaine .= '                </author>'."\r\n";
            }
            //var_dump($strTab);
            $chaine .= '              </analytic>'."\r\n";
            //journal
            $chaine .= '              <monogr>'."\r\n";
            //ISSN
            $chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['SN']).'</idno>'."\r\n";
            //EISSN
            $chaine .= '                <idno type="eissn">'.supprAmp($papers[$key][$key2]['EI']).'</idno>'."\r\n";
            if (isset($papers[$key][$key2]['PT']))
            {
              $typDoc = "";
              $typDocp = "";
              $type = $papers[$key][$key2]['PT'];
              switch($type)
              {
                case "J"://article
                  $chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['SO'])).'</title>'."\r\n";
                  $chaine .= '                <imprint>'."\r\n";
                  $chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
                  $chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['VL']).'</biblScope>'."\r\n";
                  $chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['IS']).'</biblScope>'."\r\n";
                  $chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['BP']).'-'.$papers[$key][$key2]['EP'].'</biblScope>'."\r\n";
                  $chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
                  $chaine .= '                </imprint>'."\r\n";
                  $typeDoc = "ART";
                  $typeDocp = "Journal articles";
                  break;
                case "S"://inproceedings or review or book chapter
                  if ($papers[$key][$key2]['DT'] == "Proceedings Paper") {
                    $chaine .= '                <meeting>'."\r\n";
                    $chaine .= '                  <title>'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
                    $confStartDate = "";
                    $confEndDate = "";
                    if (isset($papers[$key][$key2]['CY']))
                    {
                      //OCT 19-23, 2014 to 2014-10-19
                      $confdate = $papers[$key][$key2]['CY'];
                      if ($confdate != "") {
                        $res = explode(" ", $confdate);
                        if (count($res) > 2) {
                          //year
                          $confStartDate = trim($res[2])."-";
                          $confEndDate = trim($res[2])."-";
                          //month
                          switch($res[0])
                          {
                            case "JAN":
                              $confStartDate .= "01-";
                              $confEndDate .= "01-";
                              break;
                            case "FEB":
                              $confStartDate .= "02-";
                              $confEndDate .= "02-";
                              break;
                            case "MAR":
                              $confStartDate .= "03-";
                              $confEndDate .= "03-";
                              break;
                            case "APR":
                              $confStartDate .= "04-";
                              $confEndDate .= "04-";
                              break;
                            case "MAY":
                              $confStartDate .= "05-";
                              $confEndDate .= "05-";
                              break;
                            case "JUN":
                              $confStartDate .= "06-";
                              $confEndDate .= "06-";
                              break;
                            case "JUL":
                              $confStartDate .= "07-";
                              $confEndDate .= "07-";
                              break;
                            case "AUG":
                              $confStartDate .= "08-";
                              $confEndDate .= "08-";
                              break;
                            case "SEP":
                              $confStartDate .= "09-";
                              $confEndDate .= "09-";
                              break;
                            case "OCT":
                              $confStartDate .= "10-";
                              $confEndDate .= "10-";
                              break;
                            case "NOV":
                              $confStartDate .= "11-";
                              $confEndDate .= "11-";
                              break;
                            case "DEC":
                              $confStartDate .= "12-";
                              $confEndDate .= "12-";
                              break;
                          }
                          //day
                          $confStartDate .= substr($res[1], 0, 2);
                          $confEndDate .= substr($res[1], 3, 2);
                        }
                      }
                    }
                    $chaine .= '                  <date type="start">'.$confStartDate.'</date>'."\r\n";//comment différencier date début et fin ?
                    $chaine .= '                  <date type="end">'.$confEndDate.'</date>'."\r\n";//comment différencier date début et fin ?
                    $chaine .= '                  <settlement>'.supprAmp($papers[$key][$key2]['CL']).'</settlement>'."\r\n";
                    $chaine .= '                </meeting>'."\r\n";
                    $typeDoc = "COMM";
                    $typeDocp = "Conference papers";
                  }
                  if ($papers[$key][$key2]['DT'] == "Book" || $papers[$key][$key2]['DT'] == "Review; Book Chapter") {
                    $chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['BN']).'</idno>'."\r\n";
                    $chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['TI']).'</title>'."\r\n";
                    $chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
                    $chaine .= '                <imprint>'."\r\n";
                    $chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
                    $chaine .= '                  <pubPlace>'.supprAmp($papers[$key][$key2]['PI']).'</pubPlace>'."\r\n";
                    $chaine .= '                  <biblScope unit="serie">'.supprAmp($papers[$key][$key2]['SE']).'</biblScope>'."\r\n";
                    $chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['VL']).'</biblScope>'."\r\n";
                    $chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['IS']).'</biblScope>'."\r\n";
                    $chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['BP']).'-'.$papers[$key][$key2]['EP'].'</biblScope>'."\r\n";
                    $chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
                    $chaine .= '                </imprint>'."\r\n";
                    $typeDoc = "BOOK";
                    $typeDocp = "Book";//???
                  }
                  break;
                case "B"://book
                case "P"://patent > il n'y en a pas dans WoS
                  $typeDoc = "PATENT";
                  $typeDocp = "Patent";//???
                  break;
              }
            }
            $chaine .= '              </monogr>'."\r\n";
            if (isset($papers[$key][$key2]['DI']) && $papers[$key][$key2]['DI'] != "")
            {
              $chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DI']).'</idno>'."\r\n";
            }
            if (isset($papers[$key][$key2]['PM']) && $papers[$key][$key2]['PM'] != "")
            {
              $chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['PM']).'</idno>'."\r\n";
            }
            $chaine .= '            </biblStruct>'."\r\n";
            $chaine .= '          </sourceDesc>'."\r\n";
            $chaine .= '          <profileDesc>'."\r\n";
            //langue
            $chaine .= '            <langUsage>'."\r\n";
            $chaine .= '              <language ident="'.$lng.'">'.supprAmp($papers[$key][$key2]['LA']).'</language>'."\r\n";
            $chaine .= '            </langUsage>'."\r\n";
            $chaine .= '            <textClass>'."\r\n";
            //mots-clés
            if (isset($papers[$key][$key2]['DE']) && $papers[$key][$key2]['DE'] != "")
            {
              $chaine .= '             <keywords scheme="author">'."\r\n";
              $aut = explode(";", $papers[$key][$key2]['DE']);
              foreach ($aut as $qui) {
                $chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
              }
              $chaine .= '             </keywords>'."\r\n";
            }
            //domaine HAL
            //$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
            //Typologie
            $chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
            $chaine .= '            </textClass>'."\r\n";
            $chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['AB']).'</abstract>'."\r\n";
            $chaine .= '          </profileDesc>'."\r\n";
            $chaine .= '        </biblFull>'."\r\n";
            $chaine .= '      </listBibl>'."\r\n";
            $chaine .= '    </body>'."\r\n";
            if (count($strTab) > 0) {//Existence of one or more affiliation(s)
              $chaine .= '      <back>'."\r\n";
              $chaine .= '        <listOrg type="structures">'."\r\n";
              $indT = 1;
              foreach ($strTab as $labElt) {
                $eltTab = explode("~|~", $labElt);
                $chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
                $orgName = $eltTab[2];
                $orgName = str_replace(array("UR1", " UR1"), "", $orgName);
                if ($eltTab[3] == "institution") {//abbreviation between crochet
                  if (strpos($orgName, "CHU") !== false) {
                    $nameTab = explode(",", $orgName);
                    $ville = $nameTab[count($nameTab)-2];
                    $villeTab = explode(" ", $ville);
                    $orgName = "CHU ".$villeTab[count($villeTab)-1];
                  }
                  $nameTab = explode(",", $orgName);
                  $orgName = "";
                  $oN = 0;
                  $iName = 0;
                  foreach ($nameTab as $name) {
                    if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
                      if ($name == "Universite CHU") {
                        $ville = $nameTab[count($nameTab)-2];
                        $villeTab = explode(" ", $ville);
                        $name = $name.' '.$villeTab[count($villeTab)-1];
                      }
                      if ($oN == 0) {
                        $oN = 1;
                      }else{
                        $orgName .= ", ";
                      }
                      $eltNameTab = explode(" ", trim($name));
                      $oNE = 0;
                      foreach ($eltNameTab as $elt) {
                        if ($oNE == 0) {
                          $oNE = 1;
                        }else{
                          $orgName .= " ";
                        }
                        if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA") {
                          $orgName .= "[".trim($elt)."]";
                        }else{
                          $orgName .= "".trim($elt);
                        }
                      }
                    }
                    $iName += 1;
                  }
                }
                $chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";
                $pays = strtoupper($eltTab[4]);
                if ($pays != "")
                {
                  include './OverHAL_codes_pays.php';
                  $keyP = array_search($pays, $countries, true); 
                  $chaine .= '            <desc>'."\r\n";
                  $chaine .= '              <address>'."\r\n";
                  $chaine .= '                <country key="'.$keyP.'"></country>'."\r\n";
                  $chaine .= '              </address>'."\r\n";
                  $chaine .= '            </desc>'."\r\n";
                }
                $chaine .= '          </org>'."\r\n";
                $indT++;
              }
              $chaine .= '        </listOrg>'."\r\n";
              $chaine .= '      </back>'."\r\n";
            }
            $chaine .= '  </text>'."\r\n";
            $chaine .= '</TEI>';
            fwrite($inF,$chaine);
            fclose($inF);
            $zip->addFile($Fnm);
            $zip->close();
            unlink($Fnm);
          }
          break;

        case "scopus":
          $aut = $papers[$key][$key2]['Authors'];
          $autTab = explode(", ",$aut);
          if (count($autTab) < $limNbAut)
          {
            //affiliation
            $autTab = array();
            $labTab = array();
            $autInd = 0;
            $docid = 0;
            $label = "";
            $code = "";
            $cuHAL = "";
            $type = "";
            $pays = "";
            $quoi = $papers[$key][$key2]['Authors with affiliations'];
            $quoi = trimUltime($quoi);
            $validHAL = "";//to privilegy the search by the unit code number rather than acronym
            //echo "<br>".$j." - ".$quoi."<br>";
            $diffQuoi = explode(";", $quoi);
            //var_dump($diffQuoi);
            for ($d = 0; $d < count($diffQuoi); $d++)
            {
              $urlHAL = "";
              $docid = 0;
              $label = "";
              $code = "";
              $cuHAL = "";
              $type = "";
              $pays = "";
              $eltTab = explode(",", $diffQuoi[$d]);
              $aTester = "[".$eltTab[0].",".$eltTab[1]."]";//to retrieve the WoS structure for affilId function
              for ($t = 2; $t < count($eltTab); $t++)
              {
                if ($t != 2) {$aTester .= ", ".trim($eltTab[$t]);}else{$aTester .= $eltTab[$t];}
              }
              //echo $aTester.'<br>';
              //Search for distinctive acronyms
              searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
              //echo 'toto : '.$aTester.' - '.$urlHAL.'<br>';
              if ($urlHAL != "")//affiliation with code unit or acronym found
              {
                //echo $aTester.'<br>';
                idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
              }
              //echo $docid.'<br>';
              if($docid != 0)
              {
                affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
                while ($retTest != "") {
                  $aTester = $retTest;
                  $autInd = 0;
                  $urlHAL = "";
                  $docid = 0;
                  $label = "";
                  $code = "";
                  $cuHAL = "";
                  $type = "";
                  $pays = "";
                  //Search for distinctive acronyms
                  //echo 'toto : '.$aTester.'<br>';
                  searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
                  //echo 'titi : '.$urlHAL.'<br>';
                  if ($urlHAL != "")//affiliation with code unit or acronym found
                  {
                    idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
                  }
                  //echo $urlHAL.' - '.$docid.'<br>';
                  if($docid != 0)
                  {
                    affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
                  }
                }
              }else{//no specific affiliation found > we store whole results
                //echo $aTester.'<br>';
                $validHAL = "--";
                $eltTab = explode("] ",$aTester);
                include './OverHAL_unites_termes.php';
                $label = supprAmp(trim($eltTab[1]));
                $label = str_ireplace($search, $replace, $label);
                //echo 'titi : '.$label.'<br>';
                //$docid = 999999;
                $docid = $label;
                $code = $label;
                $type = 'institution';
                //echo $aTester.'<br>';
                affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
              }
            }
            //var_dump($autTab);
            //var_dump($labTab);
            $strTab = array();
            $eid = supprAmp($papers[$key][$key2]['EID']);//unique academic work identifier assigned in Scopus bibliographic database
            mb_internal_encoding('UTF-8');
            $zip = new ZipArchive();
            $FnmZ = "./HAL/OverHAL_scopus.zip";
            if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
              exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
            }
            $Fnm = "./HAL/OverHAL_scopus_".$eid.".xml";
            $inF = fopen($Fnm,"a+");
            fseek($inF, 0);
            //$chaine = "\xEF\xBB\xBF";//UTF-8
            $chaine = "";//ANSI
            $chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
            $chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
            $chaine .= '  <text>'."\r\n".
                       '    <body>'."\r\n".
                       '      <listBibl>'."\r\n".
                       '        <biblFull>'."\r\n".
                       '          <titleStmt>'."\r\n";
            //funder
            $chaine .= '            <funder>'.supprAmp($papers[$key][$key2]['Funding Details']).'</funder>'."\r\n";
            $chaine .= '          </titleStmt>'."\r\n";

            //if DOI exists searching an OA PDF file
            if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
            {
              $urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DOI'];
              $volT = $papers[$key][$key2]['Volume'];
              $issT = $papers[$key][$key2]['Issue'];
              $pagT = $papers[$key][$key2]['Page start'];
              $datT = $papers[$key][$key2]['Year'];
              $pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

              testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $entry->halId_s, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $titPDF);

              if ($titPDF != "")//an OA PDF file has benn found
              {
                $chaine .= '          <editionStmt>'."\r\n".
                           '            <edition>'."\r\n".
                           '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$targetPDF.$entry->halId_s.'.pdf"></ref>'."\r\n".
                           '            </edition>'."\r\n".
                           '          </editionStmt>'."\r\n";
                $avail = 'http://creativecommons.org/licenses/by';
                if ($compNC != "") {$avail .= '-nc';}
                if ($compND != "") {$avail .= '-nd';}
                if ($compSA != "") {$avail .= '-sa';}
                $avail .= '/';
                $chaine .= '          <publicationStmt>'."\r\n".
                           '            <availability>'."\r\n".
                           '              <licence target="'.$avail.'"/>'."\r\n".
                           '            </availability>'."\r\n".
                           '          </publicationStmt>'."\r\n";
              }
            }
            $chaine .= '          <seriesStmt>'."\r\n".
                       '          </seriesStmt>'."\r\n".
                       '          <notesStmt>'."\r\n".
                       '          </notesStmt>'."\r\n".
                       '          <sourceDesc>'."\r\n".
                       '            <biblStruct>'."\r\n".
                       '              <analytic>'."\r\n";
            $lng = "";
            //langue + titre
            if (isset($papers[$key][$key2]['Language of Original Document']))
            {
              if ($papers[$key][$key2]['Language of Original Document'] == "French") {$lng = "fr";}
              if ($papers[$key][$key2]['Language of Original Document'] == "English") {$lng = "en";}
            }
            $chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Title']).'</title>'."\r\n";
            //auteurs
            $aut = explode(",", $papers[$key][$key2]['Authors']);
            //var_dump($aut);
            $iTp = 0;
            foreach ($aut as $qui) {
              $nom = "";
              $prenom = "";
              $quiTab = explode(" ", trim($qui));
              if (!isset($quiTab[1])) {$prenom = "";}//in case of no comma for one author
              //var_dump($quiTab);
              $chaine .= '                <author role="aut">'."\r\n";
              $chaine .= '                  <persName>'."\r\n";
              foreach($quiTab as $elt) {
                if (strpos($elt, ".") === false)//no point > part of the name
                {
                  $nom .= " ".supprAmp(trim($elt));
                }else{
                  $prenom .= supprAmp(trim($elt));
                }
              }
              $nom = trim($nom);
              $nompre = $nom .", ".$prenom;
              //echo $nompre.'<br>';
              $chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
              $chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
              $chaine .= '                  </persName>'."\r\n";
              $kT = array_search($nompre, $autTab);
              //echo $kT." - ".$nom." - ".$labTab[$nompre][$kT]."<br>";
              if ($kT !== FALSE) {
                foreach ($labTab[$nompre] as $lab) {
                  //$str = array_search($labTab[$nompre][$kT], $strTab);
                  $str = array_search($lab, $strTab);
                  if ($str === FALSE) {
                    $iTp += 1;
                    $kTp = $iTp;
                    array_push($strTab, $lab);
                  }else{
                    $kTp = $str + 1;
                  }
                  $chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
                  //echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
                }
              }
              $chaine .= '                </author>'."\r\n";
            }
            //var_dump($strTab);
            $chaine .= '              </analytic>'."\r\n";
            //journal
            $chaine .= '              <monogr>'."\r\n";
            //ISSN
            $chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['ISSN']).'</idno>'."\r\n";
            //ISBN
            $chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['ISBN']).'</idno>'."\r\n";
            if (isset($papers[$key][$key2]['Document Type']))
            {
              $typDoc = "";
              $typDocp = "";
              $type = $papers[$key][$key2]['Document Type'];
              switch($type)
              {
                case "Article"://article
                case "Article in Press":
                case "Review":
                case "Erratum":
                case "Editorial":
                case "Short Survey":
                case "Letter":
                case "Note":
                  $chaine .= '                <title level="j">'.supprAmp($papers[$key][$key2]['Source title']).'</title>'."\r\n";
                  $chaine .= '                <imprint>'."\r\n";
                  $chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Publisher']).'</publisher>'."\r\n";
                  $chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
                  $chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
                  $chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Page start']).'-'.$papers[$key][$key2]['Page end'].'</biblScope>'."\r\n";
                  $chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Year']).'</date>'."\r\n";
                  $chaine .= '                </imprint>'."\r\n";
                  $typeDoc = "ART";
                  $typeDocp = "Journal articles";
                  break;
                case "Conference Paper"://inproceedings
                case "Conference Review":
                  $chaine .= '                <meeting>'."\r\n";
                  $chaine .= '                  <title>'.supprAmp($papers[$key][$key2]['Conference name']).'</title>'."\r\n";
                  $confStartDate = "";
                  $confEndDate = "";
                  if (isset($papers[$key][$key2]['Conference date']))
                  {
                    //6 September 2015 through 9 September 2015 to 2015-09-06
                    $confdate = $papers[$key][$key2]['Conference date'];
                    if ($confdate != "") {
                      $res = explode(" ", $confdate);
                      if (count($res) > 2) {
                        //year
                        $confStartDate = trim($res[2])."-";
                        $confEndDate = trim($res[6])."-";
                        //start month
                        switch($res[1])
                        {
                          case "January":
                            $confStartDate .= "01-";
                            break;
                          case "February":
                            $confStartDate .= "02-";
                            break;
                          case "March":
                            $confStartDate .= "03-";
                            break;
                          case "April":
                            $confStartDate .= "04-";
                            break;
                          case "May":
                            $confStartDate .= "05-";
                            break;
                          case "June":
                            $confStartDate .= "06-";
                            break;
                          case "July":
                            $confStartDate .= "07-";
                            $confEndDate .= "07-";
                            break;
                          case "August":
                            $confStartDate .= "08-";
                            break;
                          case "September":
                            $confStartDate .= "09-";
                            break;
                          case "October":
                            $confStartDate .= "10-";
                            break;
                          case "November":
                            $confStartDate .= "11-";
                            break;
                          case "December":
                            $confStartDate .= "12-";
                            break;
                        }
                        //start month
                        switch($res[5])
                        {
                          case "January":
                            $confEndDate .= "01-";
                            break;
                          case "February":
                            $confEndDate .= "02-";
                            break;
                          case "March":
                            $confEndDate .= "03-";
                            break;
                          case "April":
                            $confEndDate .= "04-";
                            break;
                          case "May":
                            $confEndDate .= "05-";
                            break;
                          case "June":
                            $confEndDate .= "06-";
                            break;
                          case "July":
                            $confEndDate .= "07-";
                            break;
                          case "August":
                            $confEndDate .= "08-";
                            break;
                          case "September":
                            $confEndDate .= "09-";
                            break;
                          case "October":
                            $confEndDate .= "10-";
                            break;
                          case "November":
                            $confEndDate .= "11-";
                            break;
                          case "December":
                            $confEndDate .= "12-";
                            break;
                        }
                        //start day
                        if (strlen($res[0]) == 1)
                        {
                          $confStartDate .= "0".$res[0];
                        }else{
                          $confStartDate .= $res[0];
                        }
                        //end day
                        if (strlen($res[4]) == 1)
                        {
                          $confEndDate .= "0".$res[4];
                        }else{
                          $confEndDate .= $res[4];
                        }
                      }
                    }
                  }else{
                    $confStartDate = $papers[$key][$key2]['Year'];
                    $confEndDate = $papers[$key][$key2]['Year'];
                  }
                  $chaine .= '                  <date type="start">'.$confStartDate.'</date>'."\r\n";//comment différencier date début et fin ?
                  $chaine .= '                  <date type="end">'.$confEndDate.'</date>'."\r\n";//comment différencier date début et fin ?
                  $chaine .= '                  <settlement>'.supprAmp($papers[$key][$key2]['Conference location']).'</settlement>'."\r\n";
                  $chaine .= '                </meeting>'."\r\n";
                  $typeDoc = "COMM";
                  $typeDocp = "Conference papers";
                  break;
                case "Book"://book
                case "Book Chapter":
                  $chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['ISBN']).'</idno>'."\r\n";
                  $chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['Title']).'</title>'."\r\n";
                  $chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['Editors']).'</editor>'."\r\n";
                  $chaine .= '                <imprint>'."\r\n";
                  $chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Publisher']).'</publisher>'."\r\n";
                  $chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
                  $chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
                  $chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Page start']).'-'.$papers[$key][$key2]['Page end'].'</biblScope>'."\r\n";
                  $chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Year']).'</date>'."\r\n";
                  $chaine .= '                </imprint>'."\r\n";
                  if ($type == "Book") {
                    $typeDoc = "OUV";
                    $typeDocp = "Ouv";
                  }else{
                    $typeDoc = "COUV";
                    $typeDocp = "Couv";
                  }
                  break;
                case "P"://patent > il n'y en a pas dans WoS
                  $typeDoc = "PATENT";
                  $typeDocp = "Patent";//???
                  break;
              }
            }
            $chaine .= '              </monogr>'."\r\n";
            if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
            {
              $chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DOI']).'</idno>'."\r\n";
            }
            if (isset($papers[$key][$key2]['PubMed ID']) && $papers[$key][$key2]['PubMed ID'] != "")
            {
              $chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['PubMed ID']).'</idno>'."\r\n";
            }
            $chaine .= '            </biblStruct>'."\r\n";
            $chaine .= '          </sourceDesc>'."\r\n";
            $chaine .= '          <profileDesc>'."\r\n";
            //langue
            $chaine .= '            <langUsage>'."\r\n";
            $chaine .= '              <language ident="'.$lng.'">'.supprAmp($papers[$key][$key2]['Language of Original Document']).'</language>'."\r\n";
            $chaine .= '            </langUsage>'."\r\n";
            $chaine .= '            <textClass>'."\r\n";
            //mots-clés
            if (isset($papers[$key][$key2]['Author Keywords']) && $papers[$key][$key2]['Author Keywords'] != "")
            {
              $chaine .= '             <keywords scheme="author">'."\r\n";
              $aut = explode(";", $papers[$key][$key2]['Author Keywords']);
              foreach ($aut as $qui) {
                $chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
              }
              $chaine .= '             </keywords>'."\r\n";
            }else{
              if (isset($papers[$key][$key2]['Index Keywords']) && $papers[$key][$key2]['Index Keywords'] != "")
              {
                $chaine .= '             <keywords scheme="author">'."\r\n";
                $aut = explode(";", $papers[$key][$key2]['Index Keywords']);
                foreach ($aut as $qui) {
                  $chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
                }
                $chaine .= '             </keywords>'."\r\n";
              }
            }
            //domaine HAL
            //$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
            //Typologie
            $chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
            $chaine .= '            </textClass>'."\r\n";
            $chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Abstract']).'</abstract>'."\r\n";
            $chaine .= '          </profileDesc>'."\r\n";
            $chaine .= '        </biblFull>'."\r\n";
            $chaine .= '      </listBibl>'."\r\n";
            $chaine .= '    </body>'."\r\n";
            if (count($strTab) > 0) {//Existence of one or more affiliation(s)
              $chaine .= '      <back>'."\r\n";
              $chaine .= '        <listOrg type="structures">'."\r\n";
              $indT = 1;
              foreach ($strTab as $labElt) {
                $eltTab = explode("~|~", $labElt);
                $chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
                $orgName = $eltTab[2];
                if ($eltTab[3] == "institution") {//abbreviation between crochet
                  if (strpos($orgName, "CHU") !== false) {
                    $nameTab = explode(",", $orgName);
                    $ville = $nameTab[count($nameTab)-2];
                    $villeTab = explode(" ", $ville);
                    $orgName = "CHU ".$villeTab[count($villeTab)-1];
                  }
                  $nameTab = explode(",", $orgName);
                  $orgName = "";
                  $oN = 0;
                  $iName = 0;
                  foreach ($nameTab as $name) {
                    if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
                      if ($name == "Universite CHU") {
                        $ville = $nameTab[count($nameTab)-2];
                        $villeTab = explode(" ", $ville);
                        $name = $name.' '.$villeTab[count($villeTab)-1];
                      }
                      if ($oN == 0) {
                        $oN = 1;
                      }else{
                        $orgName .= ", ";
                      }
                      $eltNameTab = explode(" ", trim($name));
                      $oNE = 0;
                      foreach ($eltNameTab as $elt) {
                        if ($oNE == 0) {
                          $oNE = 1;
                        }else{
                          $orgName .= " ";
                        }
                        if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA") {
                          $orgName .= "[".trim($elt)."]";
                        }else{
                          $orgName .= "".trim($elt);
                        }
                      }
                    }
                    $iName += 1;
                  }
                }
                $chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";

                $chaine .= '          </org>'."\r\n";
                $indT++;
              }
              $chaine .= '        </listOrg>'."\r\n";
              $chaine .= '      </back>'."\r\n";
            }
            $chaine .= '  </text>'."\r\n";
            $chaine .= '</TEI>';
            fwrite($inF,$chaine);
            fclose($inF);
            $zip->addFile($Fnm);
            $zip->close();
            unlink($Fnm);
          }
          break;
      }
      $k++;
    }
    echo "</table>";
    if ($limzot == "non")
    {
      ?>
      <a name='Auteurs des références de <?php echo $nomSouBib;?> non trouvées dans HAL'></a><h2>Auteurs des références de <?php echo $nomSouBib;?> non trouvées dans HAL - <a href='#Résultats'><i>Retour aux résultats</i></a></h2>

      <p>Vous pouvez utiliser le logiciel <a href="http://www.treecloud.org">TreeCloud</a> pour afficher une figure
      résumant les auteurs les plus présents dans cette liste d'articles manquants sur HAL, et les sensibiliser
      au dépôt dans cette archive ouverte.</p>
      <ul>
      <?php
      foreach($papers[$key] as $key2 => $data)
      {
         $formattedAuthors=$data[$colAuthors].', ';
         $formattedAuthors=preg_replace('#\., #', '|', $formattedAuthors);
         $formattedAuthors=preg_replace('#, #', '_', $formattedAuthors);
         $formattedAuthors=preg_replace('# #', '_', $formattedAuthors);
         $formattedAuthors=preg_replace('#\.#', '_', $formattedAuthors);
         $formattedAuthors=preg_replace('#-#', '_', $formattedAuthors);
         $formattedAuthors=preg_replace('#__#', '_', $formattedAuthors);
         $formattedAuthors=preg_replace('#\|#', ' ', $formattedAuthors);
         echo "<div style='width: 900px; word-wrap: break-word;'>".$formattedAuthors." de de de de de de de de de de de de de de de de de de de de de de de de de </div><br/>";
      }
      ?>
      </ul>
      <?php
    }
  }
}

if (file_exists("./HAL/OverHAL_scopus.bib"))
{
  echo "<br/><a target=\"_blank\" href=\"./HAL/OverHAL_scopus.bib\">Exporter les résultats Scopus pour Bib2HAL</a>";
}
if (file_exists("./HAL/OverHAL_wos_csv.bib"))
{
  echo "<br/><a target=\"_blank\" href=\"./HAL/OverHAL_wos_csv.bib\">Exporter les résultats WoS pour Bib2HAL</a>";
}
if (file_exists("./HAL/OverHAL_scifin.bib"))
{
  echo "<br/><a target=\"_blank\" href=\"./HAL/OverHAL_scifin.bib\">Exporter les résultats SciFinder pour Bib2HAL</a>";
}
if (file_exists("./HAL/OverHAL_zotero.bib"))
{
  echo "<br/><a target=\"_blank\" href=\"./HAL/OverHAL_zotero.bib\">Exporter les résultats Zotero pour Bib2HAL</a>";
}

if (file_exists("./HAL/OverHAL_scopus.zip"))
{
  echo " - <a target=\"_blank\" href=\"./HAL/OverHAL_scopus.zip\">Exporter les résultats Scopus au format TEI</a><br/>";
}
if (file_exists("./HAL/OverHAL_wos_csv.zip"))
{
  echo " - <a target=\"_blank\" href=\"./HAL/OverHAL_wos_csv.zip\">Exporter les résultats WoS au format TEI</a><br/>";
}
if (file_exists("./HAL/OverHAL_scifin.zip"))
{
  echo " - <a target=\"_blank\" href=\"./HAL/OverHAL_scifin.zip\">Exporter les résultats SciFinder au format TEI</a><br/>";
}
if (file_exists("./HAL/OverHAL_zotero.zip"))
{
  echo " - <a target=\"_blank\" href=\"./HAL/OverHAL_zotero.zip\">Exporter les résultats Zotero au format TEI</a><br/>";
}
echo "<br/>";
  ?>

  <a name='Bilan quantitatif'></a><h2>Bilan quantitatif - <a href='#Résultats'><i>Retour aux résultats</i></a></h2>

  <table border=1>
  <?php
  echo "<tr><th></th><th></th>";
  foreach ($souBib as $key => $subTab)
  {
    $nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
    if ($_FILES[$key]['error'] != 4) {echo"<th colspan='3'>".$nomSouBib."</th>";}
  }
  echo"</tr>";
  echo "<tr><th>Année</th><th>sur HAL</th>";
  foreach ($souBib as $key => $subTab)
  {
    if ($_FILES[$key]['error'] != 4) {echo"<th>Nb</th><th>non trouvé dans HAL</th><th>% trouvé dans HAL</th>";}
  }
  echo"</tr>";
  $years = array();
  $yearMin = date('Y', time());
  foreach ($souBib as $key => $subTab)
  {
    if ($_FILES[$key]['error'] != 4) //file exists
    {
      foreach ($nbPerYear[$key] as $key2 => $data)
      {
        if(intval($key2) < $yearMin) {$yearMin = intval($key2);}
      }
    }
  }

  for ($i = $yearMin; $i <= date('Y', time()); $i++)
  {
    array_push($years, $i);
  }
  foreach($years as $year)
  {
    if (isset($nbHalPerYear[$year]))
    {
      echo "<tr><td valign='top'>".$year."</td><td valign='top'>".$nbHalPerYear[$year]."</td>";
    }else{
      echo "<tr><td valign='top'>".$year."</td><td valign='top'>-</td>";
    }
    foreach ($souBib as $key => $subTab)
    {
      if ($_FILES[$key]['error'] != 4) //file exists
      {
        if (isset($nbPerYear[$key][$year])) // there are some articles for the year
        {
          if (isset($nbNotFoundPerYear[$key][$year])) {$nNFPY = $nbNotFoundPerYear[$key][$year];}else{$nNFPY = 0;}
          echo "<td valign='top'>".$nbPerYear[$key][$year]."</td><td valign='top'>".$nNFPY."</td><td valign='top'>".(round(10000*($nbPerYear[$key][$year]-$nNFPY)/($nbPerYear[$key][$year]))/100)."%</td>";
        }else{
          echo"<td valign='top'>-</td><td valign='top'>-</td><td valign='top'>-</td>";
        }
      }
    }
  echo "</tr>";
  }

  ?>
  </table>
<br/><br/>
<a href="OverHAL.php">Retour à l'accueil du site</a>
</body></html>
