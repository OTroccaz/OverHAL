<?php
$fcgiRes = array();
$fcgi = 0;

if (file_exists("./PubMed.fcgi"))//fcgi file has been submitted
{
  $handle = fopen("./PubMed.fcgi", "r");
  $fcgi = 1;
}
if ($fcgi == 1)
{
  //$fcgi = 1;
  $page = "";
  while (!feof($handle))
  { //reading all the lines
    $page .= fgets($handle, 4096); //reading content of each line
  }
  fclose($handle);
}

//echo 'toto : '.$page;
$tabFCGI = explode("Pubmed-entry ::= {", trim($page));
//var_dump($tabFCGI);

for($j=1; $j<count($tabFCGI); $j++) {
	//var_dump($tabFCGI[$j]);
	$t = $j - 1;
	
	//echo '<b><u>Publication n°'.$j.'</u></b><br>';
	
	$tabFCGItit = explode("title {", $tabFCGI[$j]);
	//var_dump($tabFCGItit[1]);

	$tabFCGIautit = explode("authors {", $tabFCGItit[1]);
	//var_dump($tabFCGIautit);

	//Récupération du titre de la publication
	$titPub = ucfirst(trim(str_replace(array('name "', '"', '},'), '', $tabFCGIautit[0])));
	$titPub = substr($titPub, 0, (strlen($titPub) - 1));//Retirer le point final
	$titPub = str_replace(array("\r\n", "\n"), "", $titPub);//Retirer les sauts de ligne
	//echo $titPub;
	$fcgiRes[$t]['titPub'] = $titPub;

	//Récupération des auteurs et de leurs affiliations
	$tabAut = array();
	$tabAff = array();
	$tabFCGIaut = explode('name ml "', $tabFCGIautit[1]);
	//var_dump($tabFCGIaut);

	for($i=0; $i<count($tabFCGIaut); $i++) {
		$tabFCGIaut[$i] = explode("},", $tabFCGIaut[$i]);
	}
	for($i=0; $i<count($tabFCGIaut); $i++) {
		$tabFCGIaffil[$i] = explode('affil str "', $tabFCGIaut[$i][0]);
	}
	//var_dump($tabFCGIaut);
	//var_dump($tabFCGIaffil);
	for($i=1; $i<count($tabFCGIaffil); $i++) {
		$tabAut[$i-1] = str_replace(array("\r\n", "\n"), "", trim(str_replace(array('"', ","), '', $tabFCGIaffil[$i][0])));//Retirer les sauts de ligne
		$affil = trim(str_replace(array('"', "}"), '', $tabFCGIaffil[$i][1]));
		if (is_numeric(substr($affil, 0, 1))) {//Si chiffre au début de l'affiliation
			while (is_numeric(substr($affil, 0, 1))) {//Tant qu'il y a un chiffre au début
				$affil = substr($affil, 1, (strlen($affil) - 1));//On retire le premier caractère de l'affiliation
			}
		}
		$tabAff[$i-1] = str_replace(array("\r\n", "\n"), "", $affil);//Retirer les sauts de ligne
	}
	//var_dump($tabAut);
	//var_dump($tabAff);
	$fcgiRes[$t]['tabAut'] = $tabAut;
	$fcgiRes[$t]['tabAff'] = $tabAff;

	//Récupération de la revue + infos liées
	$tabFCGIrev = explode("from journal {", $tabFCGI[$j]);
	//var_dump($tabFCGIrev);

	//Titre de la revue
	$tabFCGIinfrev = explode("title {", $tabFCGIrev[1]);
	//var_dump($tabFCGIinfrev);
	$tabFCGItitRevdeb = explode('name "', $tabFCGIinfrev[1]);
	$tabFCGItitRevfin = explode('},', $tabFCGItitRevdeb[1]);// Le tableau $tabFCGItitRevfin contient toutes les informations nécessaires pour la suite
	//var_dump($tabFCGItitRevfin);
	$titRev = ucfirst(trim(str_replace('"', '', $tabFCGItitRevfin[0])));
	//echo $titRev;
	$fcgiRes[$t]['titRev'] = str_replace(array("\r\n", "\n"), "", $titRev);//Retirer les sauts de ligne

	//ISSN
	$tabFCGIissn = explode('issn "', $tabFCGItitRevdeb[0]);
	//var_dump($tabFCGIissn);
	$issn = trim(str_replace(array('"', ','), '', $tabFCGIissn[1]));
	//echo '<br>'.$issn;
	$fcgiRes[$t]['ISSN'] = $issn;

	//Date publication
	$annee = "";
	$mois = "";
	$jour = "";
	$k = 0;
	$tabFCGIdatePubdeb = explode('imp {', $tabFCGItitRevfin[1]);
	$tabFCGIdatePubfin = explode('date std {', $tabFCGIdatePubdeb[1]);
	$tabFCGIdatePub = explode(',', $tabFCGIdatePubfin[1]);
	//var_dump($tabFCGIdatePub);
	if (isset($tabFCGIdatePub[$k]) && strpos($tabFCGIdatePub[$k], 'year') !== false) {
		$annee = trim(str_replace('year', '', $tabFCGIdatePub[$k]));
		$k++;
	}
	if (isset($tabFCGIdatePub[$k]) && strpos($tabFCGIdatePub[$k], 'month') !== false) {
		$mois = trim(str_replace('month', '', $tabFCGIdatePub[$k]));
		$k++;
	}
	if (isset($tabFCGIdatePub[$k]) && strpos($tabFCGIdatePub[$k], 'day') !== false) {
		$jour = trim(str_replace('day', '', $tabFCGIdatePub[$k]));
	}
	//echo '<br>Date de publication : '.$jour.'/'.$mois.'/'.$annee;
	$fcgiRes[$t]['jPub'] = $jour;
	$fcgiRes[$t]['mPub'] = $mois;
	$fcgiRes[$t]['aPub'] = $annee;
	

	//VNP + langue + statut
	$volume = "";
	$numero = "";
	$pages = "";
	$langue = "";
	$statut = "";
	$k = 0;
	$tabFCGIvnp = explode(',', $tabFCGItitRevfin[2]);
	//var_dump($tabFCGIvnp);
	if (isset($tabFCGIvnp[$k]) && strpos($tabFCGIvnp[$k], 'volume') !== false) {
		$volume = trim(str_replace(array('volume', '"'), '', $tabFCGIvnp[$k]));
		$k++;
	}
	if (isset($tabFCGIvnp[$k]) && strpos($tabFCGIvnp[$k], 'issue') !== false) {
		$numero = trim(str_replace(array('issue', '"'), '', $tabFCGIvnp[$k]));
		$k++;
	}
	if (isset($tabFCGIvnp[$k]) && strpos($tabFCGIvnp[$k], 'pages') !== false) {
		$pages = trim(str_replace(array('pages', '"'), '', $tabFCGIvnp[$k]));
		$k++;
	}
	if (isset($tabFCGIvnp[$k]) && strpos($tabFCGIvnp[$k], 'language') !== false) {
		$langue = trim(str_replace(array('language', '"'), '', $tabFCGIvnp[$k]));
		$k++;
	}
	if (isset($tabFCGIvnp[$k]) && strpos($tabFCGIvnp[$k], 'pubstatus') !== false) {
		$statut = trim(str_replace(array('pubstatus', '"'), '', $tabFCGIvnp[$k]));
	}
	//echo '<br>VNP : '.$volume.', '.$numero.', '.$pages;
	//echo '<br>Langue : '.$langue;
	//echo '<br>Statut : '.$statut;
	$fcgiRes[$t]['Volume'] = $volume;
	$fcgiRes[$t]['Numero'] = $numero;
	$fcgiRes[$t]['Pagination'] = $pages;
	$fcgiRes[$t]['langue'] = $langue;
	$fcgiRes[$t]['statut'] = $statut;

	//Pubmed + DOI
	$pubmed = "";
	$doi = "";
	//var_dump($tabFCGItitRevfin);
	//Il faut d'abord rechercher la cellule du tableau $tabFCGItitRevfin qui contient 'ids {'
	$c = 2;
	while (strpos($tabFCGItitRevfin[$c], 'ids {') === false) {
		$c++;
	}
	$tabFCGIPMDOIdeb = explode('ids {', $tabFCGItitRevfin[$c]);
	//var_dump($tabFCGIPMDOIdeb);
	$tabFCGIPMDOIfin = explode(',', $tabFCGIPMDOIdeb[1]);
	//var_dump($tabFCGIPMDOIfin);
	$k = 0;
	while (strpos($tabFCGIPMDOIfin[$k], 'pubmed') === false) {
		$k++;
	}
	$pubmed = trim(str_replace(array('pubmed', '"'), '', $tabFCGIPMDOIfin[$k]));
	$k = 0;
	while (strpos($tabFCGIPMDOIfin[$k], 'doi') === false) {
		$k++;
	}
	$doi = trim(str_replace(array('doi', '"'), '', $tabFCGIPMDOIfin[$k]));
	//echo '<br>Pubmed : '.$pubmed;
	//echo '<br>DOI : '.$doi;
	$fcgiRes[$t]['Pubmed'] = $pubmed;
	$fcgiRes[$t]['DOI'] = $doi;

	//PMC
	$pmc = "";
	$tabFCGIPMC = explode('tag str', $tabFCGItitRevfin[$c]);
	//var_dump($tabFCGIPMC);
	if (strpos($tabFCGIPMC[1], 'PMC') !== false) {
		$pmc = trim(str_replace(array('"', '}'), '', $tabFCGIPMC[1]));
		//echo '<br>Identifiant PMC : '.$pmc;
	}
	$fcgiRes[$t]['idPMC'] = $pmc;

	//Résumé
	$resume = "";
	//Vérification initiale qu'un résumé est bien présent > test sur la chaîne de caractère extraite à la base
	if (strpos($tabFCGI[$j], 'abstract') !== false) {
		//Il faut ensuite rechercher la cellule du tableau $tabFCGItitRevfin qui contient 'abstract'
		while (strpos($tabFCGItitRevfin[$c], 'abstract') === false) {
			$c++;
		}
		$tabFCGIresume = explode('pmid', $tabFCGItitRevfin[$c]);
		//var_dump($tabFCGIresume);
		if (strpos($tabFCGIresume[0], 'mesh {') !== false) {
			$tabFCGIresumefin = explode('mesh {', $tabFCGIresume[0]);
			$resume = trim(str_replace(array('abstract', '"', ','), '', $tabFCGIresumefin[0]));
		}else{
			$resume = trim(str_replace(array('abstract', '"', ','), '', $tabFCGIresume[0]));
		}
		//echo '<br>Résumé : '.$resume;
	}
	$fcgiRes[$t]['resume'] = str_replace(array("\r\n", "\n"), "", $resume);//Retirer les sauts de ligne

	//Type publication
	$tabFCGItypedeb = explode('pub-type {', $tabFCGIinfrev[1]);
	$tabFCGItypefin = explode(',', $tabFCGItypedeb[1]);
	//var_dump($tabFCGItypefin);
	$type = trim(str_replace(array('"', '}'), '', $tabFCGItypefin[0]));
	//echo '<br>Type : '.$type;
	$fcgiRes[$t]['Type'] = $type;
	
	//echo '<br><br>';
}
//var_dump($fcgiRes);

//export results in a CSV file
$Fnm = "./HAL/pubmed_fcgi.csv"; 
$inF = fopen($Fnm,"w"); 
fseek($inF, 0);
$chaine = "\xEF\xBB\xBF";
fwrite($inF,$chaine);

$inF = fopen($Fnm,"a+"); 
fseek($inF, 0);
fwrite($inF, "titPub^tabAut^tabAff^titRev^ISSN^jPub^mPub^aPub^Volume^Numero^Pagination^langue^statut^Pubmed^DOI^idPMC^resume^Type".chr(13).chr(10));

for ($i=0; $i<count($fcgiRes); $i++) {
	$chaine = "";
	
	$chaine .= $fcgiRes[$i]['titPub'].'^';
	
	$auteur = "";
	for ($k=0; $k<count($fcgiRes[$i]['tabAut']); $k++) {
		$auteur .= $fcgiRes[$i]['tabAut'][$k].'; ';
	}
	$auteur = substr($auteur, 0, (strlen($auteur) - 2));
	$chaine .= $auteur.'^';
	
	$affili = "";
	for ($k=0; $k<count($fcgiRes[$i]['tabAff']); $k++) {
		$affili .="[".$fcgiRes[$i]['tabAut'][$k]."] ";
		$affili .= $fcgiRes[$i]['tabAff'][$k].'; ';
	}
	$affili = substr($affili, 0, (strlen($affili) - 2));
	$chaine .= $affili.'^';
	
	$chaine .= $fcgiRes[$i]['titRev'].'^';
	$chaine .= $fcgiRes[$i]['ISSN'].'^';
	$chaine .= $fcgiRes[$i]['jPub'].'^';
	$chaine .= $fcgiRes[$i]['mPub'].'^';
	$chaine .= $fcgiRes[$i]['aPub'].'^';
	$chaine .= $fcgiRes[$i]['Volume'].'^';
	$chaine .= $fcgiRes[$i]['Numero'].'^';
	$chaine .= $fcgiRes[$i]['Pagination'].'^';
	$chaine .= $fcgiRes[$i]['langue'].'^';
	$chaine .= $fcgiRes[$i]['statut'].'^';
	$chaine .= $fcgiRes[$i]['Pubmed'].'^';
	$chaine .= $fcgiRes[$i]['DOI'].'^';
	$chaine .= $fcgiRes[$i]['idPMC'].'^';
	$chaine .= $fcgiRes[$i]['resume'].'^';
	$chaine .= $fcgiRes[$i]['Type'];
	
	//Ajout au CSV
  fwrite($inF, $chaine.chr(13).chr(10));
}
?>