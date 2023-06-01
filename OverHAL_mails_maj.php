<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Caractéristiques des mails envoyés - Characteristics of e-mails sent
 */
 
include "./OverHAL_mails_envoyes.php";

//suppression des entrées antérieures à 6 mois
$test = time() - 15724800;
//$test = time() - 518400; //> 6 jours
foreach($MAILS_LISTE AS $i => $valeur) {
  if ($MAILS_LISTE[$i]["quand"] < $test)
  {
    unset($MAILS_LISTE[$i]);
  }
}
$MAILS_LISTE = array_values($MAILS_LISTE);

//ajout uniquement si absent !
$absent = "oui";
foreach($MAILS_LISTE AS $i => $valeur) {
  if ($MAILS_LISTE[$i]["qui"] == $_POST["qui"] && $MAILS_LISTE[$i]["quoi2"] == $_POST["quoi2"])
  {
    if ($MAILS_LISTE[$i]["type"] == $_POST["type"])
    {
      $absent = "non";
    }else{
      $absent = "oui";
    }
  }
}

if ($absent == "oui")
{
  $ajout = count($MAILS_LISTE);
  $MAILS_LISTE[$ajout]["qui"] = $_POST["qui"];
  $MAILS_LISTE[$ajout]["quoi1"] = $_POST["quoi1"];
  $MAILS_LISTE[$ajout]["quoi2"] = $_POST["quoi2"];
  $MAILS_LISTE[$ajout]["type"] = $_POST["type"];
  $MAILS_LISTE[$ajout]["file"] = $_POST["fic"];
  $MAILS_LISTE[$ajout]["lang"] = $_POST["lang"];
	$MAILS_LISTE[$ajout]["labo"] = $_POST["labo"];
	if (isset($_POST["titre"])) {$MAILS_LISTE[$ajout]["titre"] = $_POST["titre"];}else{$MAILS_LISTE[$ajout]["titre"] = '';}
  $MAILS_LISTE[$ajout]["quand"] = time();

  //export liste php
  $Fnm = "./OverHAL_mails_envoyes.php";
  $inF = fopen($Fnm,"w");
  fseek($inF, 0);
  $chaine = "";
  $chaine .= '<?php'.chr(13).chr(10);
  $chaine .= '$MAILS_LISTE = array('.chr(13).chr(10);
  fwrite($inF,$chaine);
  foreach($MAILS_LISTE AS $i => $valeur) {
    $j = $i + 1;
    $chaine = $j.' => array("qui"=>"'.$MAILS_LISTE[$i]["qui"].'", ';
    $chaine .= '"quoi1"=>"'.$MAILS_LISTE[$i]["quoi1"].'", ';
    $chaine .= '"quoi2"=>"'.$MAILS_LISTE[$i]["quoi2"].'", ';
    $chaine .= '"type"=>"'.$MAILS_LISTE[$i]["type"].'", ';
    $chaine .= '"file"=>"'.$MAILS_LISTE[$i]["file"].'", ';
    $chaine .= '"lang"=>"'.$MAILS_LISTE[$i]["lang"].'", ';
		$chaine .= '"labo"=>"'.$MAILS_LISTE[$i]["labo"].'", ';
		$chaine .= '"titre"=>"'.$MAILS_LISTE[$i]["titre"].'", ';
    $chaine .= '"quand"=>"'.$MAILS_LISTE[$i]["quand"].'")';
    if ($i != $ajout) {$chaine .= ',';}
    $chaine .= chr(13).chr(10);
    fwrite($inF,$chaine);
  }
  $chaine = ');'.chr(13).chr(10);
  $chaine .= '?>';
  fwrite($inF,$chaine);
  fclose($inF);
}
?>