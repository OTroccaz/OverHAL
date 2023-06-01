<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Procédure FCGI - FCGI procedure
 */
 
if (isset($_FILES['fcgi_csv']))
{
  if ($_FILES['fcgi_csv']['error'] != 4)//Is there a wos HTML file ?
  {
    if ($_FILES['fcgi_csv']['error'])
    {
      switch ($_FILES['fcgi_csv']['error'])
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
    $extension = strrchr($_FILES['fcgi_csv']['name'], '.');
    if ($extension != ".csv") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['fcgi_csv']['tmp_name'], "fcgi.csv");
  }
}

$fcgi = 0;
$url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=";

if (file_exists("./fcgi.csv"))//PMID csv file has been submitted
{
  $handle = fopen("./fcgi.csv", "r");
  $fcgi = 1;
}
if ($fcgi == 1)
{
  //$fcgi = 1;
  $page = "";
  while (!feof($handle))
  { //reading all the lines
    $url .= trim(fgets($handle, 4096)); //reading content of each line
		$url .= ",";
  }
  fclose($handle);
	
	$url = substr($url, 0, (strlen($url) -1));
	//echo $url;

	$fp = fopen("./pmid.fcgi", "w");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur1.univ-rennes1.fr)');
	curl_setopt($ch, CURLOPT_USERAGENT, 'PROXY (http://siproxy.univ-rennes1.fr)');
	if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
	}else{
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	$resultat = curl_exec($ch);
	fwrite($fp, $resultat);
	
	echo ('<a href="./pmid.fcgi">Télécharger le fichier FCGI</a> (clic droit, enregistrer sous)');
	unlink("./fcgi.csv");
}

?>