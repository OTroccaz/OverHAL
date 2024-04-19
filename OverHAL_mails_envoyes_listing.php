<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Caractéristiques des mails envoyés - Characteristics of e-mails sent
 */
 
// récupération de l'adresse IP du client (on cherche d'abord à savoir s'il est derrière un proxy)
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
}else {
  $ip = $_SERVER['REMOTE_ADDR'];
}
echo $ip.'<br>';
include("./Glob_IP_list.php");
if (in_array($ip, $IP_aut)) {
  //accès autorisé
  include "./OverHAL_mails_envoyes.php";
  
  if (isset($_GET["suppr"]) && $_GET["suppr"] != "") //Suppression d'une entrée
  {
    $suppr = $_GET["suppr"];
    unset($MAILS_LISTE[$suppr]);
    $MAILS_LISTE = array_values($MAILS_LISTE);
    $total = count($MAILS_LISTE);
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
      if ($i != $total-1) {$chaine .= ',';}
      $chaine .= chr(13).chr(10);
      fwrite($inF,$chaine);
    }
    $chaine = ');'.chr(13).chr(10);
    $chaine .= '?>';
    fwrite($inF,$chaine);
    fclose($inF);
    header('Location: OverHAL_mails_envoyes_listing.php');
  }
  
  if (isset($_POST["modif"]) && $_POST["modif"] != "") //Validation de la modification d'une entrée
  {
    $modif = $_POST["modif"];
    $quandTab = explode("/", $_POST["quand"]);   
    $MAILS_LISTE[$modif]["quand"] = mktime(0, 0, 0, $quandTab[1], $quandTab[0], $quandTab[2]);
    $qui = str_replace('"','&#039;',$_POST["qui1"]);
    if (isset($_POST["qui2"])) {$qui2 = str_replace('"','&#039;',$_POST["qui2"]);}else{$qui2 = "";}
    if (isset($_POST["qui3"])) {$qui3 = str_replace('"','&#039;',$_POST["qui3"]);}else{$qui3 = "";}
    if ($qui2 != "") {$qui .= ",".$qui2;}
    if ($qui3 != "") {$qui .= ",".$qui3;}
    $MAILS_LISTE[$modif]["qui"] = $qui;
    $MAILS_LISTE[$modif]["quoi1"] = str_replace('"','&#039;',$_POST["quoi1"]);
    $MAILS_LISTE[$modif]["quoi2"] = str_replace('"','&#039;',$_POST["quoi2"]);
    $MAILS_LISTE[$modif]["type"] = str_replace('"','&#039;',$_POST["type"]);
    $MAILS_LISTE[$modif]["file"] = str_replace('"','&#039;',$_POST["file"]);
    $MAILS_LISTE[$modif]["lang"] = str_replace('"','&#039;',$_POST["lang"]);
		$MAILS_LISTE[$modif]["labo"] = str_replace('"','&#039;',$_POST["labo"]);
		$MAILS_LISTE[$modif]["titre"] = str_replace('"','&#039;',$_POST["titre"]);
    $total = count($MAILS_LISTE);
    //export liste php
    $Fnm = "./OverHAL_mails_envoyes.php";
    $inF = fopen($Fnm,"w");
    fseek($inF, 0);
    $chaine = "";
    $chaine .= '<?php'.chr(13).chr(10);
    $chaine .= '$MAILS_LISTE = array('.chr(13).chr(10);
    fwrite($inF,$chaine);
    foreach($MAILS_LISTE AS $i => $valeur) {
      $chaine = $i.' => array("qui"=>"'.$MAILS_LISTE[$i]["qui"].'", ';
      $chaine .= '"quoi1"=>"'.$MAILS_LISTE[$i]["quoi1"].'", ';
      $chaine .= '"quoi2"=>"'.$MAILS_LISTE[$i]["quoi2"].'", ';
      $chaine .= '"type"=>"'.$MAILS_LISTE[$i]["type"].'", ';
      $chaine .= '"file"=>"'.$MAILS_LISTE[$i]["file"].'", ';
      $chaine .= '"lang"=>"'.$MAILS_LISTE[$i]["lang"].'", ';
			$chaine .= '"labo"=>"'.$MAILS_LISTE[$i]["labo"].'", ';
			$chaine .= '"titre"=>"'.$MAILS_LISTE[$i]["titre"].'", ';
      $chaine .= '"quand"=>"'.$MAILS_LISTE[$i]["quand"].'")';
      if ($i != $total) {$chaine .= ',';}
      $chaine .= chr(13).chr(10);
      fwrite($inF,$chaine);
    }
    $chaine = ');'.chr(13).chr(10);
    $chaine .= '?>';
    fwrite($inF,$chaine);
    fclose($inF);
    header('Location: OverHAL_mails_envoyes_listing.php');
  }
  if (isset($_POST["action"]) && $_POST["action"] == "ajout") {//Validation de l'ajout d'une entrée
    $modif = count($MAILS_LISTE)+1;
    $quand = str_replace('"','&#039;',$_POST["quand"]);
    $quandTab = explode("/", $quand);   
    $MAILS_LISTE[$modif]["quand"] = mktime(0, 0, 0, $quandTab[1], $quandTab[0], $quandTab[2]);
    $qui = str_replace('"','&#039;',$_POST["qui1"]);
    $qui2 = str_replace('"','&#039;',$_POST["qui2"]);
    $qui3 = str_replace('"','&#039;',$_POST["qui3"]);
    if ($qui2 != "") {$qui .= ",".$qui2;}
    if ($qui3 != "") {$qui .= ",".$qui3;}
    $MAILS_LISTE[$modif]["qui"] = $qui;
    $MAILS_LISTE[$modif]["quoi1"] = "titrenormaliseabsent";
    $MAILS_LISTE[$modif]["quoi2"] = str_replace('"','&#039;',$_POST["quoi2"]);
    $MAILS_LISTE[$modif]["type"] = str_replace('"','&#039;',$_POST["type"]);
    $MAILS_LISTE[$modif]["file"] = str_replace('"','&#039;',$_POST["file"]);
    $MAILS_LISTE[$modif]["lang"] = str_replace('"','&#039;',$_POST["lang"]);
		$MAILS_LISTE[$modif]["labo"] = str_replace('"','&#039;',$_POST["labo"]);
		$MAILS_LISTE[$modif]["titre"] = str_replace('"','&#039;',$_POST["titre"]);
    $total = count($MAILS_LISTE);
    //export liste php
    $Fnm = "./OverHAL_mails_envoyes.php";
    $inF = fopen($Fnm,"w");
    fseek($inF, 0);
    $chaine = "";
    $chaine .= '<?php'.chr(13).chr(10);
    $chaine .= '$MAILS_LISTE = array('.chr(13).chr(10);
    fwrite($inF,$chaine);
    foreach($MAILS_LISTE AS $i => $valeur) {
      $chaine = $i.' => array("qui"=>"'.$MAILS_LISTE[$i]["qui"].'", ';
      $chaine .= '"quoi1"=>"'.$MAILS_LISTE[$i]["quoi1"].'", ';
      $chaine .= '"quoi2"=>"'.$MAILS_LISTE[$i]["quoi2"].'", ';
      $chaine .= '"type"=>"'.$MAILS_LISTE[$i]["type"].'", ';
      $chaine .= '"file"=>"'.$MAILS_LISTE[$i]["file"].'", ';
      $chaine .= '"lang"=>"'.$MAILS_LISTE[$i]["lang"].'", ';
			$chaine .= '"labo"=>"'.$MAILS_LISTE[$i]["labo"].'", ';
			$chaine .= '"titre"=>"'.$MAILS_LISTE[$i]["titre"].'", ';
      $chaine .= '"quand"=>"'.$MAILS_LISTE[$i]["quand"].'")';
      if ($i != $total) {$chaine .= ',';}
      $chaine .= chr(13).chr(10);
      fwrite($inF,$chaine);
    }
    $chaine = ');'.chr(13).chr(10);
    $chaine .= '?>';
    fwrite($inF,$chaine);
    fclose($inF);
    array_multisort($MAILS_LISTE);
    header('Location: OverHAL_mails_envoyes_listing.php');
  }
}else{
  echo("Vous n'êtes pas autorisé à accéder à cette fonctionnalité (adresse IP). Voir le mode d'emploi / installation.");
} 
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
  <title>OverHAL : listing des mails envoyés</title>
  <meta name="Description" content="OverHAL : listing des mails envoyés">
  <meta name="robots" content="noindex">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="stylesheet" href="./OverHAL.css">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="icon" type="type/ico" href="HAL_favicon.ico" />
</head>
<body style="font-family:corbel, sans-serif;font-size:12px;">
<h1>OverHAL : listing des mails envoyés</h1>
<?php
include("./Glob_IP_list.php");
if (in_array($ip, $IP_aut)) {
  //accès autorisé
  include "./OverHAL_mails_envoyes.php";
  
  if (isset($_GET["action"]) && $_GET["action"] == "ajout") {//Ajout d'une entrée
    echo('<form method="POST" accept-charset="utf-8" name="ajout" action="OverHAL_mails_envoyes_listing.php">');
    echo('<b>Ajout d\'une entrée :</b><br><br>');
    echo('<b>Quand (jj/mm/aaaa)</b> : <input type="text" name="quand"><br>');
    echo('<b>Destinataire 1</b> : <input type="text" name="qui1"><br>');
    echo('<b>Destinataire 2</b> : <input type="text" name="qui2"><br>');
    echo('<b>Destinataire 3</b> : <input type="text" name="qui3"><br>');
    echo('<b>Article (DOI)</b> : <input type="text" name="quoi2"><br>');
    echo('<b>Type</b> : <input type="text" name="type"><br>');
    echo('<b>Fichier</b> : <input type="text" name="file"><br>');
    echo('<b>Langue</b> : ');
    echo('<select size="1" name="lang">');
    echo('<option value="FR" selected>FR</option>');
    echo('<option value="EN">EN</option>');
    echo('</select><br>');
		echo('<b>Laboratoire</b> : <input type="text" name="labo"><br>');
		echo('<b>Titre</b> : <input type="text" name="titre"><br>');
    echo('<input type="hidden" value="ajout" name="action">');
    echo('<input type="submit" value="Valider" name="ajout">');
    echo('</form>');
  }else{
    if (isset($_GET["modif"]) && $_GET["modif"] != "") {//Modification d'une entrée
      $modif = $_GET["modif"];
      echo('<form method="POST" accept-charset="utf-8" name="modification" action="OverHAL_mails_envoyes_listing.php">');
      echo('<b>Modification de l\'entrée '.$modif.' :</b><br><br>');
      echo('<b>Quand</b> : <input type="text" value="'.date("d/m/Y",$MAILS_LISTE[$modif]['quand']).'" name="quand"><br>');
      $quiTab = explode(",", $MAILS_LISTE[$modif]['qui']);
      echo('<b>Destinataire 1</b> : <input type="text" value="'.$quiTab[0].'" name="qui1" size="50"><br>');
      if (isset($quiTab[1])) {echo('<b>Destinataire 2</b> : <input type="text" value="'.$quiTab[1].'" name="qui2" size="50"><br>');}
      if (isset($quiTab[2])) {echo('<b>Destinataire 3</b> : <input type="text" value="'.$quiTab[2].'" name="qui3" size="50"><br>');}
      echo('<b>Article</b> : <input type="text" value="'.$MAILS_LISTE[$modif]['quoi2'].'" name="quoi2" size="50"><br>');
      echo('<b>Type</b> : <input type="text" value="'.$MAILS_LISTE[$modif]['type'].'" name="type"><br>');
      echo('<b>Fichier</b> : <input type="text" value="'.$MAILS_LISTE[$modif]['file'].'" name="file"><br>');
      $lang = $MAILS_LISTE[$modif]['lang'];
      if ($lang == "FR") {$txtFR = "selected "; $txtEN = "";}else{$txtFR = ""; $txtEN = "selected ";}
      echo('<b>Langue</b> : ');
      echo('<select size="1" name="lang">');
      echo('<option '.$txtFR.'value="FR">FR</option>');
      echo('<option '.$txtEN.'value="EN">EN</option>');
      echo('</select><br>');
			if (isset($MAILS_LISTE[$modif]['labo'])) {$labo = $MAILS_LISTE[$modif]['labo'];}else{$labo = "";}
			echo('<b>Laboratoire</b> : <input type="text" value="'.$labo.'" name="labo"><br>');
			if (isset($MAILS_LISTE[$modif]['titre'])) {$titre = $MAILS_LISTE[$modif]['titre'];}else{$titre = "";}
			echo('<b>Titre</b> : <input type="text" value="'.$titre.'" name="titre"><br>');
      echo('<input type="hidden" value="'.$MAILS_LISTE[$modif]['quoi1'].'" name="quoi1">');
      echo('<input type="hidden" value="'.$modif.'" name="modif">');
      echo('<input type="submit" value="Valider" name="modification">');
      echo('</form>');
    }else{
      echo('<a href="OverHAL_mails_envoyes_listing.php?action=ajout">Ajouter une entrée</a>');
      //tableau résultat
      echo('<table width="100%">');
      echo('<tr><td colspan="7" align="center">');
      $total = count($MAILS_LISTE);
      $iaff = 1;
      $iaut = 0;
      $text = '';
      echo ('<b>Total de '.$total.' mails envoyés</b>');
      $text .= '</td></tr>';
      $text .= '<tr><td colspan="7">&nbsp;</td></tr>';
      $text .= '<tr><td>&nbsp;</td>';
      $text .= '<td valign=top><b>Quand</b></td>';
      $text .= '<td valign=top><b>Destinataire</b></td>';
      $text .= '<td valign=top><b>Article</b></td>';
      $text .= '<td valign=top><b>Type</b></td>';
      $text .= '<td valign=top><b>Fichier</b></td>';
      $text .= '<td valign=top><b>Langue</b></td>';
			$text .= '<td valign=top><b>Laboratoire</b></td>';
			$text .= '<td valign=top><b>Titre</b></td>';
      $text .= '<td valign=top>&nbsp;</td>';
      $text .= '<td valign=top>&nbsp;</td>';
      $text .= '</tr>';
      $iaff = 1;
      foreach($MAILS_LISTE AS $i => $valeur) {
				if ($MAILS_LISTE[$i]['type'] != 'R') {
					$text .= '<tr><td valign=top>'.$iaff.'</td>';
					$text .= '<td valign=top>'.date('d/m/Y', $MAILS_LISTE[$i]['quand']).'</td>';
					$text .= '<td valign=top><a href=\'mailto:'.$MAILS_LISTE[$i]['qui'].'\'>'.$MAILS_LISTE[$i]['qui'].'</a></td>';
					$refdoi = $MAILS_LISTE[$i]['quoi2'];
					if ($refdoi != "")
					{
						$text .= '<td valign=top><a target=\'_blank\' href=\'https://doi.org/'.$refdoi.'\'>https://doi.org/'.$refdoi.'</a></td>';
					}else{
						$text .= '<td valign=top>&nbsp;</td>';
					}
					$text .= '<td valign=top>'.$MAILS_LISTE[$i]['type'].'</td>';
					$fic = $MAILS_LISTE[$i]['file'];
					if ($fic != "")
					{
						$text .= '<td valign=top><u>Présent</u></td>';
					}else{
						$text .= '<td valign=top>Aucun</td>';
					}
					$text .= '<td valign=top>'.$MAILS_LISTE[$i]['lang'].'</td>';
					if (isset($MAILS_LISTE[$i]['labo'])) {$labo = $MAILS_LISTE[$i]['labo'];}else{$labo = "";}
					$text .= '<td valign=top>'.$labo.'</td>';
					if (isset($MAILS_LISTE[$i]['titre'])) {$titre = $MAILS_LISTE[$i]['titre'];}else{$titre = "";}
					$text .= '<td valign=top>'.$titre.'</td>';
					$text .= '<td valign=top><a href="OverHAL_mails_envoyes_listing.php?modif='.$i.'">Modifier</a></td>';
					$text .= '<td valign=top><a href="OverHAL_mails_envoyes_listing.php?suppr='.$i.'" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette entrée ?\');">Supprimer</a></td>';
					$text .= '</tr>';
					$iaff += 1;
				}
      }
      $text .= '</table>';
      echo $text;
    }
  }
}else{
  echo("Vous n'êtes pas autorisé à accéder à cette fonctionnalité");
}
?>
</body></html>