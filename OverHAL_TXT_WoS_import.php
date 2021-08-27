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
  <title>OverHAL : Importation TXT WoS</title>
  <meta name="Description" content="OverHAL : TXT WoS import">
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

if (file_exists('./WoS.txt')) {//TXT WoS file has been submitted
	
	$res = fopen('./WoS.txt', 'rb');
	//Stockage des lignes dans un tableau pour permettre de concaténer des champs présents sur plusieurs lignes
	while(!feof($res)){
		$ligne = fgets($res);
		//echo $ligne.'<br>';
		$tabFI[$i] = $ligne;
		$i++;
	}
	
	for ($i = 0; $i < count($tabFI); $i++) {
		$ligne = $tabFI[$i];
		$extr = substr($ligne, 0, 3);
	
		switch ($extr) {
			case "PT ":
				$j++;
				$tabPM['aut'][$j] = "";
				$tabPM['auteurs'][$j] = "";
				$tabPM['affiliation'][$j] = "";
				$tabPM['motscles'][$j] = "";
				$tabPM['PT'][$j] = str_replace(array("PT ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "AU ":
				$auteur = str_replace(array("AU ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				$tabPM['aut'][$j] .= $auteur."; ";
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['aut'][$j] .= $ligne."; ";
					$i++;
				}
				if (substr($tabPM['aut'][$j], -2) == "; ") {$tabPM['aut'][$j] = substr($tabPM['aut'][$j], 0, -2);}
				break;
			
			case "AF ":
				$auteur = str_replace(array("AF ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				$tabPM['auteurs'][$j] .= $auteur."; ";
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['auteurs'][$j] .= $ligne."; ";
					$i++;
				}
				if (substr($tabPM['auteurs'][$j], -2) == "; ") {$tabPM['auteurs'][$j] = substr($tabPM['auteurs'][$j], 0, -2);}
				break;
				
			case "TI ":
				$tabPM['titre'][$j] = str_replace(array("TI ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['titre'][$j] .= $ligne;
					$i++;
				}
				break;
				
			case "SO ":
				$tabPM['journal'][$j] = str_replace(array("SO ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "LA ":
				$tabPM['langue'][$j] = str_replace(array("LA ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "DT ":
				$tabPM['typologie'][$j] = str_replace(array("DT ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "DE ":
				$tabPM['motscles'][$j] .= str_replace(array("DE ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['motscles'][$j] .= " ".$ligne;
					$i++;
				}
				break;
				
			case "AB ":
				$tabPM['abstract'][$j] = str_replace(array("AB ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "C1 ":
				$tabPM['affiliation'][$j] .= str_replace(array("C1 ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['affiliation'][$j] .= "; ".$ligne;
					$i++;
				}
				if (substr($tabPM['affiliation'][$j], -2) == "; ") {$tabPM['affiliation'][$j] = substr($tabPM['affiliation'][$j], 0, -2);}
				break;
				
			case "RP ":
				$tabPM['correspaut'][$j] = str_replace(array("RP ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "EM ":
				$tabPM['email'][$j] = str_replace(array("EM ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['email'][$j] .= "; ".$ligne;
					$i++;
				}
				if (substr($tabPM['email'][$j], -2) == "; ") {$tabPM['email'][$j] = substr($tabPM['email'][$j], 0, -2);}
				break;
				
			case "RI ":
				$tabPM['resid'][$j] = str_replace(array("RI ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['resid'][$j] .= $ligne;
					$i++;
				}
				break;
				
			case "OI ":
				$tabPM['orcid'][$j] = str_replace(array("OI ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['orcid'][$j] .= $ligne;
					$i++;
				}
				break;
				
			case "FU ":
				$tabPM['financ'][$j] = str_replace(array("FU ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				//Champ sur plusieurs lignes ?
				while (substr($tabFI[$i+1], 0, 3) == "   ") {
					$ligne = str_replace("   ", "", $tabFI[$i+1]);
					$ligne = str_replace(array("\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
					$tabPM['financ'][$j] .= $ligne;
					$i++;
				}
				break;
				
			case "PU ":
				$tabPM['publisher'][$j] = str_replace(array("PU ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "SN ":
				$tabPM['issn'][$j] = str_replace(array("SN ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "EI ":
				$tabPM['eissn'][$j] = str_replace(array("EI ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "J9 ":
				$tabPM['revabr'][$j] = str_replace(array("J9 ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "JI ":
				$tabPM['journal'][$j] = str_replace(array("JI ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "VL ":
				$tabPM['volume'][$j] = str_replace(array("VL ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "IS ":
				$tabPM['issue'][$j] = str_replace(array("IS ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "BP ":
				$tabPM['bp'][$j] = str_replace(array("BP ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "EP ":
				$tabPM['ep'][$j] = str_replace(array("EP ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "AR ":
				$tabPM['ar'][$j] = str_replace(array("AR ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "DI ":
				$tabPM['doi'][$j] = str_replace(array("DI ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "EA ":
				$tabPM['ea'][$j] = str_replace(array("EA ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "PY ":
				$tabPM['py'][$j] = str_replace(array("PY ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "WC ":
				$tabPM['domaine'][$j] = str_replace(array("WC ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "PM ":
				$tabPM['pmid'][$j] = str_replace(array("PM ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "OA ":
				$tabPM['openaccess'][$j] = str_replace(array("OA ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "UT ":
				$tabPM['WoS'][$j] = str_replace(array("UT ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "PT ":
				$tabPM['PT'][$j] = str_replace(array("PT ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "CT ":
				$tabPM['CT'][$j] = str_replace(array("CT ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "EY ":
				$tabPM['EY'][$j] = str_replace(array("EY ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "CY ":
				$tabPM['CY'][$j] = str_replace(array("CY ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "CL ":
				$tabPM['CL'][$j] = str_replace(array("CL ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "BE ":
				$tabPM['BE'][$j] = str_replace(array("BE ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "BN ":
				$tabPM['BN'][$j] = str_replace(array("BN ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
				
			case "SE ":
				$tabPM['SE'][$j] = str_replace(array("SE ", "\r\n", "\r", "\n", PHP_EOL, chr(10), chr(13), chr(10).chr(13)), "", $ligne);
				break;
		}
	}
	
	//var_dump($tabPM);
	
	//export results in a CSV file
	$Fnm = "./HAL/wos_txt.csv"; 
	$inF = fopen($Fnm,"w"); 
	fseek($inF, 0);
	$chaine = "\xEF\xBB\xBF";
	fwrite($inF,$chaine);

	$inF = fopen($Fnm,"a+"); 
	fseek($inF, 0);
	fwrite($inF, "UT^PM^SE^BN^BE^CL^CY^EY^CT^PT^DI^DT^LA^VL^IS^BP^EP^AR^EA^PY^SO^JI^SN^EI^TI^AF^AU^C1^RI^OI^DE^FU^AB^RP^EM^PU^WC^OA".chr(13).chr(10));
		
	for ($i = 0; $i < count($tabPM['WoS']); $i++) {
		$chaine = "";
		
		//WoS
		if (isset($tabPM['WoS'][$i])) {
			$chaine .= $tabPM['WoS'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//PM
		if (isset($tabPM['PM'][$i])) {
			$chaine .= $tabPM['PM'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//SE
		if (isset($tabPM['SE'][$i])) {
			$chaine .= $tabPM['SE'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//BN
		if (isset($tabPM['BN'][$i])) {
			$chaine .= $tabPM['BN'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//BE
		if (isset($tabPM['BE'][$i])) {
			$chaine .= $tabPM['BE'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//CL
		if (isset($tabPM['CL'][$i])) {
			$chaine .= $tabPM['CL'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//CY
		if (isset($tabPM['CY'][$i])) {
			$chaine .= $tabPM['CY'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//EY
		if (isset($tabPM['EY'][$i])) {
			$chaine .= $tabPM['EY'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//CT
		if (isset($tabPM['CT'][$i])) {
			$chaine .= $tabPM['CT'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//PT
		if (isset($tabPM['PT'][$i])) {
			$chaine .= $tabPM['PT'][$i]."^";
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
		
		//BP
		if (isset($tabPM['bp'][$i])) {
			$chaine .= $tabPM['bp'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//EP
		if (isset($tabPM['ep'][$i])) {
			$chaine .= $tabPM['ep'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//AR
		if (isset($tabPM['ar'][$i])) {
			$chaine .= $tabPM['ar'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//EA
		if (isset($tabPM['ea'][$i])) {
			$chaine .= $tabPM['ea'][$i]."^";
		}else{
			$chaine .= "^";
		}
		
		//PY
		if (isset($tabPM['py'][$i])) {
			$chaine .= $tabPM['py'][$i]."^";
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
		
		//Aut
		if (isset($tabPM['aut'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['aut'][$i])."^";
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
		
		//Researcher ID
		if (isset($tabPM['resid'][$i])) {
			$chaine .= $tabPM['resid'][$i]."^";
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
		
		//Financements
		if (isset($tabPM['financ'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['financ'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Résumé
		if (isset($tabPM['abstract'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['abstract'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Auteur correspondant
		if (isset($tabPM['correspaut'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['correspaut'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Email
		if (isset($tabPM['email'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['email'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Editeur
		if (isset($tabPM['publisher'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['publisher'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Domaine scientifique
		if (isset($tabPM['domaine'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['domaine'][$i])."^";
		}else{
			$chaine .= "^";
		}
		
		//Open Access
		if (isset($tabPM['openaccess'][$i])) {
			$chaine .= str_replace("'", "’", $tabPM['openaccess'][$i])."^";
		}else{
			$chaine .= "^";
		}

		//Ajout au CSV
		fwrite($inF, $chaine.chr(13).chr(10));
	}
	
	
	
}
?>
</body></html>