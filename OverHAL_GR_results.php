<?php
header('Content-type: text/html; charset=UTF-8');
mb_internal_encoding("UTF-8");

//bibtex files deletion
if (file_exists("./HAL/OverHAL_zotero.bib")) {unlink("./HAL/OverHAL_zotero.bib");}

function utf8_fopen_read($fileName) {
    //$fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
    $fc = file_get_contents($fileName);
    $handle=fopen("php://memory", "rw");
    fwrite($handle, $fc);
    fseek($handle, 0);
    return $handle;
}

function nomCompEntier($nom) {
  $nom = trim(mb_strtolower($nom,'UTF-8'));
  if (strpos($nom,"-") !== false) {//Le nom comporte un tiret
    $postiret = strpos($nom,"-");
    $autg = substr($nom,0,$postiret);
    $autd = substr($nom,($postiret+1),strlen($nom));
    $nom = mb_ucwords($autg)."-".mb_ucwords($autd);
  }else{
    $nom = mb_ucwords($nom);
  }
  return $nom;
}

function mb_ucwords($str) {
  $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
  return ($str);
}

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

if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "https://ecobio.univ-rennes1.fr/HAL_SCD.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="fr">
<head>
  <title>OverHAL : Comparaison HAL vs sources bibliographiques</title>
  <meta name="Description" content="OverHAL : Comparaison HAL vs sources bibliographiques">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="stylesheet" href="./OverHAL.css">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script type="text/javascript" src="./js/overlib.js"></script>
  <script type="text/javascript" src="./OverHAL_results.js"></script>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body>
<?php

if ($limzot == "non")
{
?>
<a name='Résultats'></a><h1>Résultats</h1>

<strong>Le script ci-dessous ne se fonde que sur la détection d'un titre identique (après suppression des caractères spéciaux et passage en minuscules)
ou d'un même DOI pour identifier une référence d'une source bibliographique (Scopus, Pubmed, etc.) avec un dépôt HAL.</strong><br/><br/>
<a href='#Chargement de la requête HAL'>Chargement de la requête HAL</a><br />
<a href='#Bilan quantitatif'>Bilan quantitatif</a><br />

<br />
Récupération des résultats de HAL en cours...
<?php
}
include("./normalize.php");

if ($_FILES['zotero']['name'] != "") {
  $ext = strtolower(strrchr($_FILES['zotero']['name'], '.'));
  if ($ext != ".csv" && $ext != ".txt"){
    header("location:"."ExtractionHAL-liste-auteurs.php?erreur=extfic"); exit;
  }else{
    if ($_FILES['zotero']['size'] == "0") {
      header("location:"."ExtractionHAL-liste-auteurs.php?erreur=nulfic"); exit;
    }else{
      $temp = $_FILES['zotero']['tmp_name'];
      $complet = 'ok';
    }
  }
}

$cst = 0;
$listTit = "¤";
$handle = utf8_fopen_read($temp);
if ($handle)  {//Si on a réussi à ouvrir le fichier
  $Fnm = "./HAL/OverHAL_zotero.bib";
  $inF = fopen($Fnm,"a+");
  fseek($inF, 0);
  //$chaine = "\xEF\xBB\xBF";
  //fwrite($inF,$chaine);
  $inF = fopen($Fnm,"a+");
  fseek($inF, 0);
  
  $ligne = 1;
  $total = count(file($temp));
  while($tab = fgetcsv($handle, 0, ';')) {
    if ($ligne != 1) {
      //Item type
      if (isset($tab[9])) {
        $type = $tab[9];
        switch($type)
        {
          case "Article":
            $type = "article";
            break;
          case "congrès":
            $type = "inproceedings";
            break;
          case "Autre Document":
            $type = "unpublished";
            break;
          case "chapitre d'ouvrage":
            $type = "incollection";
            break;
          case "chapitre":
            $type = "incollection";
            break;
          case "livre":
            $type = "book";
            break;
          case "patent":
            $type = "patent";
            break;
          case "Fascicule de revue":
            $type = "misc";
            break;
          case "Rapport":
            $type = "manual";
            break;
          case "Carte":
            $type = "misc";
            break;
          case "Notice explicative":
            $type = "techreport";
            break;
          case "Thèse":
            $type = "phdthesis";
            break;
          case "misc":
            $type = "misc";
            break;
        }
        $chaine = chr(13).chr(10)."@".$type."{";
    }
    //Intro - Authors
    if (isset($tab[2]))
    {
      $auteurs = explode(", ", $tab[2]);
      $chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
    }
    //Intro - Titre
    if (isset($tab[0]))
    {
      $titre = explode(" ", $tab[0]);
      $chaine .= "_".mb_strtolower(normalize(str_replace(" ", "_", $titre[0])), 'UTF-8');
    }
    //add a constant to differenciate same initial identifier
    if (isset($auteurs) && isset($titre))
    {
      $tit = mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8')."_".mb_strtolower(normalize(str_replace(" ", "_", $titre[0])), 'UTF-8');
      if (strpos($listTit, "¤".$tit."¤") !== false)
      {
        $cst++;
        $chaine .= $cst;
      }
      $listTit .= $tit."¤";
    }
    
    //Intro - année
    if (isset($tab[1])) {$chaine .= "_".mb_strtolower($tab[1], 'UTF-8');}
    
    //Titre
    if (isset($tab[0])) {$chaine .= ",".chr(13).chr(10)."	title = {".$tab[0]."}";}
    
    //Volume
    if (isset($tab[4])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$tab[4]."}";}

    //Journal
    if (isset($tab[3]))
    {
      $titreJ = $tab[3];
      $titreJVal = $tab[3];
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
    
    //Auteurs
    if (isset($tab[2]))
    {
      $init = 0;
      $auteurs = str_replace("; ", " and ", $tab[2]);
      $autTab = explode(",", $auteurs);
      $i = 0;
      $auteurs = "";
      while (isset($autTab[$i])) {
        $prnm = explode(" ", trim($autTab[$i]));
        $nom = $prnm[0];
        if (isset($prnm[1])) {
          $prenom = $prnm[1];
          if ($init == 0) {
            $auteurs .= nomCompEntier($nom).", ".$prenom;
            $init = 1;
          }else{
            $auteurs .= " and ".nomCompEntier($nom).", ".$prenom;
          }
        }else{
          $auteurs .= nomCompEntier($nom);
        }
      $i++;
      }
      $chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
    }
    
    //Keywords
    if (isset($tab[6]))
    {
      $init = 0;
      $keywords = $tab[6];
      $keywordsTab = explode(",", $keywords);
      $i = 0;
      $keywords = "";
      while (isset($keywordsTab[$i])) {
        $word = $keywordsTab[$i];
        if ($init == 0) {
          $keywords .= nomCompEntier($word);
          $init = 1;
        }else{
          $keywords .= ",".nomCompEntier($word);
        }
      $i++;
      }
      $chaine .= ",".chr(13).chr(10)."	keywords = {".$keywords."}";
    }
    
    //Pages
    if (isset($tab[5])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$tab[5]."}";}
    
    //Notes
    if (isset($tab[10])) {$chaine .= ",".chr(13).chr(10)."	x-localreference = {".$tab[10]."}";}
    
    //Date
    if (isset($tab[1])) {$chaine .= ",".chr(13).chr(10)."	year = {".$tab[1]."}";}
    
    //Misc > note = tiré à part
    if ($type == "misc") {$chaine .= ",".chr(13).chr(10)."	note = {Tiré à part}";};
    
    //Ville
    if (isset($tab[8])) {$chaine .= ",".chr(13).chr(10)."	country = {".$tab[8]."}";}
    
    //Langage et audience
    if (isset($tab[14])) {
      if ($tab[14] == "fr") {
        $chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
        $chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
      }else{
        $chaine .= ",".chr(13).chr(10)."	x-language = {en}";
        $chaine .= ",".chr(13).chr(10)."	x-audience  = {International}";
      }
    }

      $chaine .= chr(13).chr(10)."}".chr(13).chr(10);
      fwrite($inF, $chaine);
    }
    $ligne++;
  }
}else{
  die("<font color='red'><big><big>Votre fichier source est incorrect.</big></big></font>");
}

if (file_exists("./HAL/OverHAL_zotero.bib"))
{
  echo "<br/><a target=\"_blank\" href=\"./HAL/OverHAL_zotero.bib\">Exporter les résultats Zotero pour Bib2HAL</a><br/>";
}
?>

<a href="OverHAL_GR.php">Retour à l'accueil du site</a>
</body></html>
