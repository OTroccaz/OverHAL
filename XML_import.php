<?php
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "https://ecobio.univ-rennes1.fr/HAL_SCD.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <title>CouvHAL : Importation XML</title>
  <meta name="Description" content="CouvHAL : HTML import">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
</head>

<body>
<?php
function encrypt($str) {
  $str = str_replace(array("<b>","</b>"), array("[[b]]","[[/b]]"), $str);
  $str = str_replace(array("<i>","</i>"), array("[[i]]","[[/i]]"), $str);
  $str = str_replace(array("<u>","</u>"), array("[[u]]","[[/u]]"), $str);
  return $str;
}

function decrypt($str) {
  $str = str_replace(array("[[b]]","[[/b]]"), array("<b>","</b>"), $str);
  $str = str_replace(array("[[i]]","[[/i]]"), array("<i>","</i>"), $str);
  $str = str_replace(array("[[u]]","[[/u]]"), array("<u>","</u>"), $str);
  $str = str_replace("â€ƒ", "", $str);
  return $str;
}

function extrSimp ($qui, &$chaine) {
 	if (isset($qui) && $qui != "") {
    $chaine .= decrypt($qui)."^";
  }else{
    $chaine .= "^";
  }
}

function extrList($qui, $quoi, $val, &$chaine) {
  $typ = 0;
  if (isset($qui) && $qui != "") {
    foreach ($qui as $elt) {
      if ($elt[$quoi] == $val) {
        $chaine .= decrypt($elt)."^";
        $typ = 1;
      }
    }
  }else{
    $chaine .= "^";
    $typ = 1;
  }
  if ($typ == 0) {
    $chaine .= "^";
  }
}

function extrUnit($qui) {
 	if (isset($qui) && $qui != "") {
    $unit = decrypt($qui);
  }else{
    $unit = "";
  }
  return $unit;
}

$pubmed = 0;

if (file_exists('./PubMed.xml')) {//HTML PubMed file has been submitted
  $content = "";
 
  $lines = file('./PubMed.xml', FILE_IGNORE_NEW_LINES);
  foreach($lines as $n => $line){
    $line = encrypt($line);
    //echo $line . "<br />";
    $content .= trim($line);
  }
  
  $dom = new DOMDocument( "1.0", "UTF-8" );
  $dom->formatOutput = true;
  $dom->preserveWhiteSpace = FALSE;
  $dom->loadXML($content);
  $dom->save('./PubMed.xml');
  $xml = simplexml_load_file('./PubMed.xml');  
  //print_r($dom);
}

//export results in a CSV file
$Fnm = "./HAL/pubmed_xml.csv"; 
$inF = fopen($Fnm,"w"); 
fseek($inF, 0);
$chaine = "\xEF\xBB\xBF";
fwrite($inF,$chaine);

$inF = fopen($Fnm,"a+"); 
fseek($inF, 0);
fwrite($inF, "PMID^PMCID^DOI^Type^Langue^Volume^Numero^Pagination^DatePub^Statut^DateMel^Revue^RevAbr^ISSN^EISSN^Titre^Auteurs^Affiliation^idORCID^MC^Finance^Resume".chr(13).chr(10));

//print_r($xml);

foreach($xml as $PubmedArticle){
	//echo $PubmedArticle->MedlineCitation->PMID.'<br>';	
	$chaine = "";
	
	//PMID
	extrSimp($PubmedArticle->MedlineCitation->PMID, $chaine);
  
  //PMCID
  extrSimp($PubmedArticle->MedlineCitation->PMCID, $chaine);
  
  //DOI
  extrList($PubmedArticle->MedlineCitation->Article->ELocationID, 'EIdType', 'doi', $chaine);

  //Type
  //extrList($PubmedArticle->MedlineCitation->Article->PublicationTypeList->PublicationType, 'UI', 'D016428', $chaine);
  $listTyp = "";
  $qui = $PubmedArticle->MedlineCitation->Article->PublicationTypeList->PublicationType;
  if (isset($qui) && $qui != "") {
    foreach ($qui as $elt) {
      if ($elt != "") {$listTyp .= $elt."~|~";}else{$listMC .= "~|~";}
    }
    $listTyp = substr($listTyp, 0, (strlen($listTyp) - 3));
    $chaine .= $listTyp."^";
  }else{
    $chaine .= "^";
    $listTyp = "ok";
  }
  if ($listTyp == "") {
    $chaine .= "^";
  }
  
  //Langue
  extrSimp($PubmedArticle->MedlineCitation->Article->Language, $chaine);
  
  //Volume
  extrSimp($PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->Volume, $chaine);
  
  //Numéro
  extrSimp($PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->Issue, $chaine);
  
  //Pagination
  extrSimp($PubmedArticle->MedlineCitation->Article->Pagination->MedlinePgn, $chaine);
  
  //Date de publication
  $MedlineDate = "";
  if (isset($PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year)) {
    $chaine .= $PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year."^";
  }else{
    $MedlineDate = substr($PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->PubDate->MedlineDate, 0, 4);
    $chaine .= $MedlineDate."^";
  }
  
  //Statut (publié / in press)
  extrSimp($PubmedArticle->PubmedData->PublicationStatus, $chaine);
  
  //Date de mise en ligne
  $dateMEL = "";
  $year = extrUnit($PubmedArticle->MedlineCitation->Article->ArticleDate->Year);
  $month = extrUnit($PubmedArticle->MedlineCitation->Article->ArticleDate->Month);
  $day = extrUnit($PubmedArticle->MedlineCitation->Article->ArticleDate->Day);
  $dateMEL = $year."-".$month."-".$day;
  if ($dateMEL == "--") {$dateMEL = "";}
  if (isset($dateMEL) && $dateMEL != "") {
    $chaine .= $dateMEL."^";
  }else{
    $chaine .= "^";
  }
  
  //Titre de la revue
  extrSimp($PubmedArticle->MedlineCitation->Article->Journal->Title, $chaine);
  
  //Titre abrégé de la revue (ISO)
  extrSimp($PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation, $chaine);
  
  //EISSN
  extrList($PubmedArticle->MedlineCitation->Article->Journal->ISSN, 'IssnType', 'Electronic', $chaine);
  
  //ISSN
  extrSimp($PubmedArticle->MedlineCitation->MedlineJournalInfo->ISSNLinking, $chaine);
  
  //Titre de l'article
  $titre = "";
  $titre = extrUnit($PubmedArticle->MedlineCitation->Article->ArticleTitle);
  if (substr($titre, -1, 1) == ".") {$titre = substr($titre, 0, (strlen($titre) - 1));}
  if (isset($titre) && $titre != "") {
    $chaine .= $titre."^";
  }else{
    $chaine .= "^";
  }
  
  //Auteurs, affiliations et ORCID
  $listAut = "";
  $autAff = "";
  $listAff = "";
  $listOrc = "";
  $qui = $PubmedArticle->MedlineCitation->Article->AuthorList->Author;
  //echo $qui;
  if (isset($qui) && $qui != "") {
    foreach ($qui as $elt) {
      if ($elt->LastName != "") {$listAut .= $elt->LastName;}
      //if ($elt->ForeName != "") {$listAut .= ", ".substr($elt->ForeName, 0, 1)."; ";}else{$listAut .= "; ";}
      if ($elt->ForeName != "") {$listAut .= ", ".$elt->ForeName."; ";}else{$listAut .= "; ";}
      if ($elt->LastName != "" && $elt->ForeName != "") {$autAff = "[".$elt->LastName.", ".$elt->ForeName."] ";}
      
      //Affiliations
      $quiA = $elt->AffiliationInfo;
      if (isset($quiA) && $quiA != "") {
        foreach ($quiA as $eltA) {
          //echo $autAff.$eltA->Affiliation."<br>";
          $tabAffil = explode(" ", $eltA->Affiliation);
          //echo 'toto : '.count($tabAffil).'<br>';
          if ($eltA->Affiliation != "" && count($tabAffil) < 200) {$listAff .= $autAff.$eltA->Affiliation."; ";}else{$listAff .= "; ";}
        }
        //$listAff = substr($listAff, 0, (strlen($listAff) - 3));
        //$listAff .= "~|~";
      }else{
        //$listAff .= "~|~";
      }
      
      //if ($elt->AffiliationInfo->Affiliation != "") {$listAff .= $autAff.$elt->AffiliationInfo->Affiliation."~|~";}else{$listAff .= "~|~";}
      if ($elt->Identifier != "" && $elt->Identifier["Source"] == "ORCID") {$listOrc .= $elt->Identifier."~|~";}else{$listOrc .= "~|~";}
    }
    $listAut = substr($listAut, 0, (strlen($listAut) - 2));
    $chaine .= $listAut."^";
    $listAff = substr($listAff, 0, (strlen($listAff) - 3));
    $chaine .= $listAff."^";
    $listOrc = substr($listOrc, 0, (strlen($listOrc) - 3));
    $chaine .= $listOrc."^";
  }else{
    $chaine .= "^";
    $listAut = "ok";
    $chaine .= "^";
    $listAff = "ok";
    $chaine .= "^";
    $listOrc = "ok";
  }
  if ($listAut == "") {
    $chaine .= "^";
  }
  if ($listAff == "") {
    $chaine .= "^";
  }
  if ($listOrc == "") {
    $chaine .= "^";
  }
  
  //Mots-clés
  $listMC = "";
  $qui = $PubmedArticle->MedlineCitation->KeywordList->Keyword;
  if (isset($qui) && $qui != "") {
    foreach ($qui as $elt) {
      if ($elt != "") {$listMC .= $elt.", ";}else{$listMC .= ", ";}
    }
    $listMC = substr($listMC, 0, (strlen($listMC) - 2));
    $chaine .= $listMC."^";
  }else{
    $chaine .= "^";
    $listMC = "ok";
  }
  if ($listMC == "") {
    $chaine .= "^";
  }

  //Financement
  $listFin = "";
  $qui = $PubmedArticle->MedlineCitation->Article->GrantList->Grant;
  if (isset($qui) && $qui != "") {
    foreach ($qui as $elt) {
      if ($elt->GrantID != "") {$listFin .= $elt->GrantID;}
      if ($elt->Acronym != "") {$listFin .= ", ".$elt->Acronym;}
      if ($elt->Agency != "") {$listFin .= ", ".$elt->Agency;}
      if ($elt->Country != "") {$listFin .= ", ".$elt->Country;}
      $listFin .= "~|~";
    }
    $listFin = substr($listFin, 0, (strlen($listFin) - 3));
    $chaine .= $listFin."^";
  }else{
    $chaine .= "^";
    $listFin = "ok";
  }
  if ($listFin == "") {
    $chaine .= "^";
  }

  //Résumé
  //echo $PubmedArticle->MedlineCitation->Article->Abstract->AbstractText->b.'<br><br>';
  /*
  if (!isset($PubmedArticle->MedlineCitation->Article->Abstract->AbstractText->b) && !isset($PubmedArticle->MedlineCitation->Article->Abstract->AbstractText->i)) {
    extrSimp($PubmedArticle->MedlineCitation->Article->Abstract->AbstractText, $chaine);
  }else{
    echo $PubmedArticle->MedlineCitation->Article->Abstract->AbstractText.'<br>';
    $abstract = str_replace("â€ƒ", "", $PubmedArticle->MedlineCitation->Article->Abstract->AbstractText);
    extrSimp(trim($abstract), $chaine);
  }
  */
  $abstract = $PubmedArticle->MedlineCitation->Article->Abstract->AbstractText;
  //$abstract = str_replace("â€ƒ", "", $abstract);
  $abstract = decrypt($abstract);
  //echo $abstract;
  extrSimp(trim($abstract), $chaine);

 
  //Ajout au CSV
  fwrite($inF, $chaine.chr(13).chr(10));
}
?>
</body></html>