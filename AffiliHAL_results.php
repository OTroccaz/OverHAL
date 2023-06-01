<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Interrogation des affiliations des auteurs - Querying authors affiliations
 */
 
//Bibliographic sources array -> first key must correspond to form field file name
$souBib = array(
  "scopus" => array(
    "Maj" => "Scopus",
    "Sep" => ",",
  ),
  "wos_csv" => array(
    "Maj" => "WoS (CSV)",
    "Sep" => "	",
  )
);
//bibtex files deletion
//if (file_exists("./HAL/OverHAL_scopus.bib")) {unlink("./HAL/OverHAL_scopus.bib");}
//var_dump($souBib);
$nbSouBib = count($souBib);

function normAut($aut)
{
  $aut = trim($aut);
  $autTab = explode(",", $aut);
  $qui = $autTab[0].substr($autTab[1], 0, 2);
  return $qui;
}

function trimUltime($chaine){
  $chaine = trim($chaine);
  $chaine = str_replace("###antiSlashe###t", " ", $chaine);
  $chaine = preg_replace("!\s+!", " ", $chaine);
  return $chaine;
}

function idaureHal($urlHAL){
  $docid = 0;
  $contents = file_get_contents($urlHAL);
  $resHAL = json_decode($contents);
  $numFound = $resHAL->response->numFound;
  foreach($resHAL->response->docs as $entry)
  {
    if ($entry->valid_s == "VALID")
    {
      if ($docid == 0)
      {
        $docid = $entry->docid;
      }else{
        if (strlen($entry->docid) < strlen($docid))
        {
          $docid = $entry->docid;
        }
      }
    }
  }
  return $docid;
}

function affilId($diffQuoi, $idAffil) {
  global $autTab, $labTab, $autInd;
  $eltTab = explode("] ",$diffQuoi);
  //var_dump($eltTab);
  $autGrp = str_replace("[", "", $eltTab[0]);
  //echo $autGrp."<br>";
  $autQui = explode(";", $autGrp);
  //var_dump($autQui);
  for ($aut = 0; $aut < count($autQui); $aut++)
  {
    $pres = 0;
    for ($qui = 0; $qui < count($autTab); $qui++)
    {
      if (normAut($autTab[$qui]) == normAut($autQui[$aut]))//Author already present
      {
        $pres = 1;
        if ($labTab[$qui] != $idAffil)//Different affiliations > Which is the right one? > reset
        {
          $labTab[$qui] = 0;
        }
        break;
      }
    }
    if ($pres == 0)
    {
      $autTab[$autInd] = trim($autQui[$aut]);
      $labTab[$autInd] = $idAffil;
      $autInd++;
    }
  }
}

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
         Header("Location: "."AffiliHAL.php?erreur=1");
         break;
         case 2: // UPLOAD_ERR_FORM_SIZE
         Header("Location: "."AffiliHAL.php?erreur=2");
         break;
         case 3: // UPLOAD_ERR_PARTIAL
         Header("Location: "."AffiliHAL.php?erreur=3");
         break;
         //case 4: // UPLOAD_ERR_NO_FILE
         //Header("Location: "."OverHAL.php?erreur=4");
         //break;
      }
    }
    $extension = strrchr($_FILES[$key]['name'], '.');
    if ($extension != ".csv") {
      Header("Location: "."AffiliHAL.php?erreur=5");
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
<html lang="fr">

<head>
  <title>AffiliHAL : Interrogation des affiliations des auteurs</title>
  <meta name="Description" content="AffiliHAL : Interrogation des affiliations des auteurs">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="./OverHAL.css">
  <script type="text/javascript" src="./js/overlib.js"></script>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body>
<noscript>
<div class='center, red' id='noscript'><strong>ATTENTION !!! JavaScript est désactivé ou non pris en charge par votre navigateur : cette procédure ne fonctionnera pas correctement.</strong><br>
<strong>Pour modifier cette option, voir <a target='_blank' rel='noopener noreferrer' href='http://www.libellules.ch/browser_javascript_activ.php'>ce lien</a>.</strong></div><br>
</noscript>

<table class="table100" aria-describedby="Entêtes">
<tr>
<th scope="col" style="text-align: left;"><img alt="AffiliHAL" title="AffiliHAL" width="250px" src="./img/logo_AffiliHAL2.jpg"></th>
<th scope="col" style="text-align: right;"><img alt="Université de Rennes 1" title="Université de Rennes 1" width="150px" src="./img/logo_UR1_gris_petit.jpg"></th>
</tr>
</table>
<hr style="color: #467666;">
<?php
include("./normalize.php");

foreach ($souBib as $key => $subTab)
{
  if ($_FILES[$key]['error'] != 4)//File exists
  {
    $nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
    $typSep = $souBib[$key]["Sep"];// Type of separator of the CSV file
    $result[$key] = array();
    $handle = fopen($_FILES[$key]['tmp_name'],'r');

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
    $handle = fopen($_FILES[$key]['tmp_name'],'r');

    $j = 0;
    while (($data = fgetcsv($handle, 0, $typSep)) !== FALSE)
    {
      //$imax = count($data);
      for ($i = 0; $i < $imax ; $i++)
      {
        $result[$key][$j][$nomCol[$key][$i]]=trim($data[$i], "\xBB..\xEF");//Suppression of some special ASCII characters linked to CSV files
      }
      $j++;
    }
    //var_dump($result);

    $jmax = $j-1;

    echo ($jmax." enregistrement(s) :<br>");

    switch($key)
    {
      case "scopus":
        for ($j = 1; $j <= $jmax ; $j++)
        {
          $quoi = $result['scopus'][$j][$nomCol['scopus'][14]];
          //echo $j." - ".$quoi."<br>";
          echo "<br>".$j."<br>";
          $affil = explode(";", $quoi);
          $kmax = count($affil);
          for ($k = 0; $k < $kmax ; $k++)
          {
            if (strpos($affil[$k], "UMR") !== false)
            {
              //echo($affil[$k]."<br>");
              $affilTab = explode(",", $affil[$k]);
              //show author
              echo("&nbsp;&nbsp;&nbsp;.&nbsp;".$affilTab[0]." ".$affilTab[1]." => ");
              //searching the tab cell containing the "UMR" term
              $cmax = count($affilTab);
              for ($c = 2; $c < $cmax ; $c++)
              {
                if (strpos($affilTab[$c], "UMR") !== false)
                {
                  echo ($affilTab[$c]." => ");
                  //We keep only the number
                  $codeUnit = substr(preg_replace("/[^0-9]/","",$affilTab[$c]), 0, 4);
                  //echo "~".$codeUnit."~";
                  //Is it a CNRS or INRA unit?
                  if (strpos($affilTab[$c], "CNRS") !== false || strpos($affilTab[$c], "INRA") !== false)
                  {
                    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22UMR".$codeUnit."%22&fl=title_s,valid_s,label_s,docid";
                  }else{//INSERM or other unit > does the URL always still the same ???
                    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U".$codeUnit."%22&fl=title_s,valid_s,label_s,docid";
                  }
                  //echo $urlHAL;
                  $contents = file_get_contents($urlHAL);
                  $resHAL = json_decode($contents);
                  $numFound = $resHAL->response->numFound;
                  //echo 'toto : '.$numFound;
                  $docid = "";
                  foreach($resHAL->response->docs as $entry)
                  {
                    if ($entry->valid_s == "VALID")
                    {
                      if ($docid == "")
                      {
                        $docid = $entry->docid;
                      }else{
                        if (strlen($entry->docid) < strlen($docid))
                        {
                          $docid = $entry->docid;
                        }
                      }
                    }
                  }
                  echo "docid = ".$docid;
                }
              }
              echo("<br>");
            }
          }
        }
        break;
      case "wos_csv":
        $autTab = array();
        $labTab = array();
        $autInd = 0;
        //$labInd = 0; Tjrs nécessaire ???
        for ($j = 1; $j <= $jmax ; $j++)//$j = n° ligne enregistrement
        {
          $quoi = $result['wos_csv'][$j][$nomCol['wos_csv'][22]];
          $quoi = trimUltime($quoi);
          //echo "<br>".$j." - ".$quoi."<br>";
          $diffQuoi = explode("; [", $quoi);
          //var_dump($diffQuoi);
          for ($d = 0; $d < count($diffQuoi); $d++)
          {
            $urlHAL = "";
            //Search for distinctive acronyms
            if (strpos(strtolower($diffQuoi[$d]), "cnrs") !== false || strpos(strtolower($diffQuoi[$d]), "inra") !== false)//CNRS ou INRA
            {
              $detail = explode(",",$diffQuoi[$d]);
              for ($det = 0; $det < count($detail); $det++)
              {
                if (strpos($detail[$det], "UMR") !== false)
                {
                  //We keep only the number
                  $codeUnit = substr(preg_replace("/[^0-9]/","",$detail[$det]), 0, 4);
                  $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22UMR".$codeUnit."%22&fl=title_s,valid_s,label_s,docid";
                }
              }
            }
            if (strpos(strtolower($diffQuoi[$d]), "inserm") !== false)//INSERM
            {
              $detail = explode(",",$diffQuoi[$d]);
              for ($det = 0; $det < count($detail); $det++)
              {
                $cu = trim($detail[$det]);
                if (strpos($detail[$det], "INSERM") !== false)//in case of INSERMxxxx but not always the case
                {
                  //We keep only the number
                  $codeUnit = substr(preg_replace("/[^0-9]/","",$detail[$det]), 0, 4);
                  if ($codeUnit != "" )
                  {
                    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U".$codeUnit."%22&fl=title_s,valid_s,label_s,docid";
                    break;
                  }
                }
                if (strlen($cu) == 5) {//cas INSERM par exemple U1085
                  if (is_numeric($cu[0]) == false && is_numeric($cu[1]) == true && is_numeric($cu[2]) == true && is_numeric($cu[3]) == true && is_numeric($cu[4]) == true) {
                    $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22".$cu."%22&fl=title_s,valid_s,label_s,docid";
                    break;
                  }
                }
              }
            }
            if (strpos(strtolower($diffQuoi[$d]), "ecobio") !== false) {//ECOBIO
              $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22ecobio%22&fl=title_s,valid_s,label_s,docid";
            }
            if (strpos(strtolower($diffQuoi[$d]), "leest") !== false) {//LEEST
              $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22leest%22&fl=title_s,valid_s,label_s,docid";
            }
            if (strpos(strtolower($diffQuoi[$d]), "ehesp") !== false) {//EHESP
              $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22ehesp%22&fl=title_s,valid_s,label_s,docid";
            }
            if (strpos(strtolower($diffQuoi[$d]), "pacte") !== false) {//PACTE
              $urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22pacte%22&fl=title_s,valid_s,label_s,docid";
            }
            //echo $urlHAL.'<br>';
            if ($urlHAL != "")
            {
              $idAffil = idaureHal($urlHAL);
              affilId($diffQuoi[$d], $idAffil);//Assigning idAffil to authors
            }
          }
        }
        //var_dump($autTab);
        //var_dump($labTab);
        for ($j = 1; $j <= $jmax ; $j++)//$j = n° ligne enregistrement
        {
          $phr = "";
          $quoi = $result['wos_csv'][$j][$nomCol['wos_csv'][1]];
          //echo "<br>".$j." - ".$quoi."<br>";
          $autDet = explode(";", $quoi);
          for ($a = 0; $a < count($autDet); $a++)
          {
            $affAut = 0;
            for ($r = 0; $r < count($autTab); $r++)
            {
              //echo normAut($resTab["aut"][$res])." - ".normAut($autTab[$a])."<br>";
              if (normAut($autTab[$r]) == normAut($autDet[$a]))
              {
                break;
              }
            }
            if ($r != count($autTab))//An affiliated author/lab was found
            {
              $affAut = $labTab[$r];
            }
            if ($phr != "") {$phr .= "; ";}
            $phr .= $autDet[$a]."[".$affAut."]";
          }
          echo $phr."<br>";
        }
        break;
      }
  }
}


?>

<br/><br/>
<a href="AffiliHAL.php">Retour à l'accueil du site</a>
</body></html>
