<?php
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "https://ecobio.univ-rennes1.fr/HAL_SCD.css";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>OverHAL : Importation TXT</title>
  <meta name="Description" content="OverHAL : TXT import">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
</head>

<body>
<?php
$i = 0;
$j = -1;
$tabFI = array();
$tabPM = array();

if (file_exists('./PubMed.txt')) {//TXT PubMed file has been submitted
	
	$res = fopen('./PubMed.txt', 'rb');
	//Stockage des lignes dans un tableau pour permettre de concaténer des champs présents sur plusieurs lignes
	while(!feof($res)){
		$ligne = fgets($res);
		//echo $ligne.'<br>';
		$tabFI[$i] = $ligne;
		$i++;
	}
	
	for ($i = 0; $i < count($tabFI); $i++) {
		$ligne = $tabFI[$i];
		$extr = substr($ligne, 0, 6);
	
		switch ($extr) {
			case "PMID- ":
				$j++;
				$tabPM['auteurs'][$j] = "";
				$tabPM['affiliation'][$j] = "";
				$tabPM['orcid'][$j] = "";
				$tabPM['motscles'][$j] = "";
				$tabPM['pubmed'][$j] = str_replace(array("PMID- ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
			
			case "IS  - ":
				if (strpos($ligne, ("Electronic")) !== false) {$tabPM['eissn'][$j] = str_replace(array("IS  - ", " (Electronic)", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);}
				if (strpos($ligne, ("Print")) !== false) {$tabPM['issn'][$j] = str_replace(array("IS  - ", " (Print)", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);}
				if (strpos($ligne, ("Linking")) !== false) {$tabPM['issn'][$j] = str_replace(array("IS  - ", " (Linking)", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);}
				break;
				
			case "VI  - ":
				$tabPM['volume'][$j] = str_replace(array("VI  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "IP  - ":
				$tabPM['issue'][$j] = str_replace(array("IP  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "DP  - ":
				$tabPM['datePub'][$j] = str_replace(array("DP  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				$tabPM['datePub'][$j] = str_replace(array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"), array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"), $tabPM['datePub'][$j]);
				$tabPM['datePub'][$j] = str_replace(" ", "-", $tabPM['datePub'][$j]);
				break;
				
			case "TI  - ":
				$tabPM['titre'][$j] = str_replace(array("TI  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 6) == "      ") {
					$ligne = str_replace("      ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['titre'][$j] .= $ligne;
					$i++;
				}
				break;
				
			case "PG  - ":
				$tabPM['pp'][$j] = str_replace(array("PG  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "LID - ":
				if (strpos($ligne, ("[doi]")) !== false) {$tabPM['doi'][$j] = str_replace(array("LID - ", " [doi]", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);}
				
			case "AB  - ":
				$tabPM['abstract'][$j] = str_replace(array("AB  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 6) == "      ") {
					$ligne = str_replace("      ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['abstract'][$j] .= $ligne;
					$i++;
				}
				break;
				
			case "FAU - ":
				$auteur = str_replace(array("FAU - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				$orcid = 0;
				$tabPM['auteurs'][$j] .= $auteur."; ";
				$tabPM['affiliation'][$j] .= "[".$auteur."] ";
				
				while (substr($tabFI[$i+1], 0, 6) == "FAU - " || substr($tabFI[$i+1], 0, 6) == "AU  - " || substr($tabFI[$i+1], 0, 6) == "AUID- " || substr($tabFI[$i+1], 0, 6) == "AD  - ") {
					//echo $tabFI[$i+1].'<br>';
					if (substr($tabFI[$i+1], 0, 6) == "AUID- ") {
						$tabPM['orcid'][$j] .= "http://orcid.org/".str_replace(array("AUID- ORCID: ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $tabFI[$i+1])."~|~";
						$orcid = 1;
					}
					if (substr($tabFI[$i+1], 0, 6) == "AD  - ") {
						$tabPM['affiliation'][$j] .= str_replace(array("AD  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $tabFI[$i+1]);
						//Affiliations sur plusieurs lignes ?
						while (substr($tabFI[$i+2], 0, 6) == "      ") {
							$ligne = str_replace("      ", "", $tabFI[$i+2]);
							$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
							$tabPM['affiliation'][$j] .= $ligne;
							$i++;
						}
					}
					if (substr($tabFI[$i+1], 0, 6) == "FAU - ") {//Nouvel auteur
						if ($orcid == 0) {$tabPM['orcid'][$j] .= "~|~";}
						$tabPM['affiliation'][$j] .= "; ";
						$auteur = str_replace(array("FAU - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $tabFI[$i+1]);
						$orcid = 0;
						$tabPM['auteurs'][$j] .= $auteur."; ";
						$tabPM['affiliation'][$j] .= "[".$auteur."] ";
					}
					$i++;
				}
				break;
				
			case "LA  - ":
				$tabPM['langue'][$j] = str_replace(array("LA  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "PT  - ":
				if (strpos($ligne, ("Journal Article")) === false && strpos($ligne, ("Review")) === false && strpos($ligne, ("Proceedings Paper")) === false && strpos($ligne, ("Book")) === false) {
					$tabPM['commentaire'][$j] = str_replace(array("PT  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne).", ";
				}else{
					$tabPM['typologie'][$j] = str_replace(array("PT  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				}
				break;
				
			case "TA  - ":
				$tabPM['revabr'][$j] = str_replace(array("TA  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
			
			case "JT  - ":
				$tabPM['journal'][$j] = str_replace(array("JT  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "OT  - ":
				$tabPM['motscles'][$j] .= str_replace(array("OT  - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne)."; ";
				break;
				
			case "PMC - ":
				$tabPM['pubmedcentral'][$j] = str_replace(array("PMC - ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "CRDT- "://2020/09/08 17:12
				$res = str_replace(array("CRDT- ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				$tabRes = explode(" ", $res);
				$tabPM['dateEpub'][$j] = str_replace("/", "-", $tabRes[0]);
				break;
			
		}

		if (substr($tabPM['motscles'][$j], -2) == ", ") {$tabPM['motscles'][$j] = substr($tabPM['motscles'][$j], 0, (strlen($tabPM['motscles'][$j]) - 2));}
	}
	
	//var_dump($tabPM);
	
	//export results in a CSV file
	$Fnm = "./HAL/pubmed_txt.csv"; 
	$inF = fopen($Fnm,"w"); 
	fseek($inF, 0);
	$chaine = "\xEF\xBB\xBF";
	fwrite($inF,$chaine);

	$inF = fopen($Fnm,"a+"); 
	fseek($inF, 0);
	//fwrite($inF, "PMID^PMCID^DOI^Type^Langue^Volume^Numero^Pagination^DatePub^Statut^DateMel^Revue^RevAbr^ISSN^EISSN^Titre^Auteurs^Affiliation^idORCID^MC^Finance^Resume".chr(13).chr(10));
	fwrite($inF, "PMID^PMCID^DOI^Type^Langue^Volume^Numero^Pagination^DatePub^Statut^DateMel^Revue^RevAbr^ISSN^EISSN^Titre^Auteurs^Affiliation^idORCID^MC^Finance^Resume".chr(13).chr(10));
		
	for ($i = 0; $i < count($tabPM['pubmed']); $i++) {
		$chaine = "";
		
		//PMID
		if (isset($tabPM['pubmed'][$i])) {
			$chaine .= $tabPM['pubmed'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//PMID
		if (isset($tabPM['pubmedcentral'][$i])) {
			$chaine .= $tabPM['pubmedcentral'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//DOI
		if (isset($tabPM['doi'][$i])) {
			$chaine .= $tabPM['doi'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Type
		if (isset($tabPM['typologie'][$i])) {
			$chaine .= $tabPM['typologie'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Langue
		if (isset($tabPM['langue'][$i])) {
			$chaine .= $tabPM['langue'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Volume
		if (isset($tabPM['volume'][$i])) {
			$chaine .= $tabPM['volume'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Numéro
		if (isset($tabPM['issue'][$i])) {
			$chaine .= $tabPM['issue'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Pagination
		if (isset($tabPM['pp'][$i])) {
			$chaine .= $tabPM['pp'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//DatePub
		if (isset($tabPM['datePub'][$i])) {
			$chaine .= $tabPM['datePub'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Statut ?
		$chaine .= "^";
		
		//DateEpub
		if (isset($tabPM['dateEpub'][$i])) {
			$chaine .= $tabPM['dateEpub'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Revue
		if (isset($tabPM['journal'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['journal'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Revue abrégée
		if (isset($tabPM['revabr'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['revabr'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//ISSN
		if (isset($tabPM['issn'][$i])) {
			$chaine .= $tabPM['issn'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//EISSN
		if (isset($tabPM['eissn'][$i])) {
			$chaine .= $tabPM['eissn'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Titre
		if (isset($tabPM['titre'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['titre'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Auteurs
		if (isset($tabPM['auteurs'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['auteurs'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Affiliations
		if (isset($tabPM['affiliation'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['affiliation'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//idORCID
		if (isset($tabPM['orcid'][$i])) {
			$chaine .= $tabPM['orcid'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//Mots-clés
		if (isset($tabPM['motscles'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['motscles'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Financements ?
		$chaine .= "^";
		
		//Résumé
		if (isset($tabPM['abstract'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['abstract'][$i])."^";
		}else{
			$chaine .= "^";
		}

		//Ajout au CSV
		fwrite($inF, $chaine.chr(13).chr(10));
	}
}
?>
</body></html>