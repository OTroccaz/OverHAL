<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Procédure OpenAlex - OpenAlex procedure
 */

//Export CSV
function expcsv($string) {
	$string = str_replace(array(';', '<br />', '<br/>', '<br>', '"'), array('-', '', '', '', ''), $string ?? '');
	$string = trim(preg_replace('/\s+/', ' ', $string));
	return $string;
}

function var_dump_pre($mixed = null) {
  echo '<pre>';
  var_dump($mixed);
  echo '</pre>';
  return null;
}

$OAurl = htmlspecialchars($_POST['openalex_url']);
$OAurl = str_replace(" ", "%20", $OAurl);
$OAurl = str_replace("&amp;", "&", $OAurl);
$OAurlinit = $OAurl;
echo '<a target="_blank" href="'.$OAurl.'">URL requête OpenAlex</a><br>';

//Recherche du nombre total de notices puis traitement
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $OAurl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur1.univ-rennes1.fr)');
curl_setopt($ch, CURLOPT_USERAGENT, 'PROXY (http://siproxy.univ-rennes1.fr)');
if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
}else{
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
}
$contOA = curl_exec($ch);
curl_close($ch);
//echo $contOA;
$resOA = json_decode($contOA);
//var_dump($resOA);
echo $resOA->meta->count.' notice(s)';
$numFound = $resOA->meta->count;
echo '<br><br>';

//Traitement

if (file_exists("./openalex.csv")) {unlink("./openalex.csv");}

$Fnm = "./openalex.csv";
$inF = fopen($Fnm,"w+");
fseek($inF, 0);
$chaine = "\xEF\xBB\xBF";
fwrite($inF,$chaine);

$chaine .= "Id;";//N° de la notice dans la liste
$chaine .= "Source;";//Source OpenAlex
$chaine .= "Type notice;";//Type de notice
$chaine .= "DOI;";//DOI
$chaine .= "PMID;";//PMID
$chaine .= "Title;";//Titre de la notice
$chaine .= "Date;";//Date de publication
$chaine .= "Language;";//Langue
$chaine .= "OA;";//Is OA ?
$chaine .= "PDF;";//URL PDF
$chaine .= "License;";//License
//Chaque financement sera séparé par des '~|~'
//$chaine .= "Funder_DN;";//Funder display name
//$chaine .= "Funder_AI;";//Funder award ID
$chaine .= "Funder;";//Funder DN : AI
//Chaque auteur et ses attributs seront séparés par des '~|~'
$chaine .= "Author_DN;";//Author display name
$chaine .= "ORCID;";//Author ORCID
$chaine .= "Is_cor;";//Auteur correspondant ?

//$chaine .= "Domaine;";//Domaine mail > procédure trop longue !!!

//Pour chaque auteur, chaque institution et ses attributs seront séparés par des '~||~'
$chaine .= "Inst_DN;";//Nom de l'institution
$chaine .= "Inst_RO;";//ROR de l'institution
$chaine .= "Inst_CY;";//Pays de l'institution
$chaine .= "Inst_TY;";//Type de l'institution
$chaine .= "Inst_RW;";//Raw affiliation string

$chaine .= "ISSN;";//ISSN
$chaine .= "EISSN;";//EISSN  > si plusieurs numéros, séparés par '~|~'
$chaine .= "Type source;";//Type de source (revue)
$chaine .= "Titre revue;";//Titre de la revue
$chaine .= "Editor;";//Nom de l'éditeur

$chaine .= "Volume;";//Volume
$chaine .= "Issue;";//Issue
$chaine .= "Pages;";//Pages

$chaine .= "Keywords;";//Mots-clés

//$chaine .= "Abstract;";//Résumé


$chaine .= chr(13).chr(10);
fwrite($inF,$chaine);

//Parcourir les notices par bloc(s) de 200 si $numFound > 200
$imin = 0;
//$imax = ($numFound > 200) ? 200 : $numFound;
$imax = 200;
$ipag = 1;
$cpt = 1;//Compteur total : 1 > $numFound
$tpc = 1;//Compteur par itération : 1 > 200

while ($cpt < ($numFound+1)) {
	$OAurl = $OAurlinit.'&page='.$ipag.'&per-page='.$imax;
	//echo $OAurl.'<br>';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $OAurl);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur1.univ-rennes1.fr)');
	curl_setopt($ch, CURLOPT_USERAGENT, 'PROXY (http://siproxy.univ-rennes1.fr)');
	if (isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
	}else{
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	$contOA = curl_exec($ch);
	curl_close($ch);
	$resOA = json_decode($contOA);

	//Export CSV
	for ($i = $imin; $i < $imax; $i++) {
		$chaine = "";
		$chaine .= $cpt.";";
		//Source OpenAlex
		$chaine .= (isset($resOA->results[$i]->id)) ? expcsv($resOA->results[$i]->id).";" : ";";
		//Type de notice
			//Si primary_location->source = objet, c'est un article, sinon c'est une communication, même si type = article
			if (is_object($resOA->results[$i]->primary_location->source)) {
				$chaine .= (isset($resOA->results[$i]->type)) ? expcsv($resOA->results[$i]->type).";" : ";";
			}else{
				$chaine .= 'comm;';
			}
		//DOI
		$chaine .= (isset($resOA->results[$i]->doi)) ? expcsv(str_replace('https://doi.org/', '', $resOA->results[$i]->doi)).";" : ";";
		//PMID
		$chaine .= (isset($resOA->results[$i]->ids->pmid)) ? expcsv(str_replace('https://pubmed.ncbi.nlm.nih.gov/', '', $resOA->results[$i]->ids->pmid)).";" : ";";
		//Titre
		$chaine .= (isset($resOA->results[$i]->title)) ? expcsv($resOA->results[$i]->title).";" : ";";
		//Date de publication
		$chaine .= (isset($resOA->results[$i]->publication_date)) ? expcsv(substr($resOA->results[$i]->publication_date, 0, 4)).";" : ";";
		//Langue
		$chaine .= (isset($resOA->results[$i]->language)) ? expcsv($resOA->results[$i]->language).";" : ";";
		//Is OA ?
		$chaine .= (isset($resOA->results[$i]->primary_location->is_oa)) ? expcsv($resOA->results[$i]->primary_location->is_oa).";" : ";";
		//URL PDF
		$chaine .= (isset($resOA->results[$i]->primary_location->pdf_url)) ? expcsv($resOA->results[$i]->primary_location->pdf_url).";" : ";";
		//URL PDF
		$chaine .= (isset($resOA->results[$i]->primary_location->license)) ? expcsv($resOA->results[$i]->primary_location->license).";" : ";";
		//Financement(s)
		$j = 0;
		//$funder_DN = '';
		//$funder_AI = '';
		$funder = '';//DN : AI
		if (isset($resOA->results[$i]->grants[$j])) {
			while (isset($resOA->results[$i]->grants[$j])) {
				$funder .= expcsv($resOA->results[$i]->grants[$j]->funder_display_name);
				$funder .= (!empty($resOA->results[$i]->grants[$j]->award_id)) ? ': '.expcsv($resOA->results[$i]->grants[$j]->award_id).'~|~' : '~|~';
				//$funder_DN .= (!empty($resOA->results[$i]->grants[$j]->funder_display_name)) ? expcsv($resOA->results[$i]->grants[$j]->funder_display_name).'~|~' : '';
				//$funder_AI .= (!empty($resOA->results[$i]->grants[$j]->award_id)) ? expcsv($resOA->results[$i]->grants[$j]->award_id).'~|~' : '';
				$j++;
			}
		}
		$chaine .= (substr($funder, -3) == '~|~') ? substr($funder, 0, -3).";" : ";";
		//$chaine .= (substr($funder_DN, -3) == '~|~') ? substr($funder_DN, 0, -3).";" : ";";
		//$chaine .= (substr($funder_AI, -3) == '~|~') ? substr($funder_AI, 0, -3).";" : ";";
		
		//Auteur(s)
		$j = 0;
		$author_DN = '';
		$orcid = '';
		$is_cor = '';
		//$domaine = '';
		$inst_DN = '';
		$inst_RO = '';
		$inst_CY = '';
		$inst_TY = '';
		$inst_RW = '';
		if (isset($resOA->results[$i]->authorships[$j])) {
			while (isset($resOA->results[$i]->authorships[$j])) {
				//Attribut(s) de l'auteur
				//$author_DN .= expcsv($resOA->results[$i]->authorships[$j]->author->display_name).'~|~';
				$author_DN .= expcsv($resOA->results[$i]->authorships[$j]->raw_author_name).'~|~';
				$orcid .= str_replace('https://orcid.org/', '', expcsv($resOA->results[$i]->authorships[$j]->author->orcid) ?? '').'~|~';
				$is_cor .= expcsv($resOA->results[$i]->authorships[$j]->is_corresponding).'~|~';
				
				//Domaine mail de l'auteur
				/*
				$auteur = $resOA->results[$i]->authorships[$j]->author->display_name;
				$req = "https://api.archives-ouvertes.fr/ref/author/?q=fullName_t:".urlencode($auteur)."&fl=emailId_t,fullName_s,emailId_s,emailDomain_s";
				$req = str_replace(" ", "%20", $req);
				$cont = file_get_contents($req);
				$res = json_decode($cont);
				$num = 0;
				if (isset($res->response->numFound)) {$num = $res->response->numFound;}
				if($num != 0) {
					$domaine .= (isset($res->response->docs[0]->emailDomain_s[0])) ? $res->response->docs[0]->emailDomain_s[0] : '';
					$domaine .= '~|~';
				}
				*/
				
				//Institution(s) et leurs attributs pour l'auteur
				$k = 0;
				$tabInst = array();
				$keysF = array();
				$keysE = array();
				if (isset($resOA->results[$i]->authorships[$j]->institutions[$k])) {
					while (isset($resOA->results[$i]->authorships[$j]->institutions[$k])) {
						//Institutions françaises
						if ($resOA->results[$i]->authorships[$j]->institutions[$k]->country_code == 'FR') {
							$tabInst['AR'][$k] = '1';
							switch($resOA->results[$i]->authorships[$j]->institutions[$k]->type) {
								case 'healthcare':
									$tabInst['AR'][$k] .= '1';
									break;
								case 'facility':
									$tabInst['AR'][$k] .= '2';
									break;
								case 'education':
									$tabInst['AR'][$k] .= '3';
									break;
								case 'government':
									$tabInst['AR'][$k] .= '4';
									break;
							}
						}else{//Institutions étrangères
							$tabInst['AR'][$k] = '2';
							switch($resOA->results[$i]->authorships[$j]->institutions[$k]->type) {
								case 'healthcare':
									$tabInst['AR'][$k] .= '1';
									break;
								case 'facility':
									$tabInst['AR'][$k] .= '4';
									break;
								case 'education':
									$tabInst['AR'][$k] .= '2';
									break;
								case 'government':
									$tabInst['AR'][$k] .= '3';
									break;
							}
						}
						
						$tabInst['DN'][$k] = str_replace(array(",", "'"), array("~troliv~", "~trolia~"), $resOA->results[$i]->authorships[$j]->institutions[$k]->display_name);
						$tabInst['RO'][$k] = $resOA->results[$i]->authorships[$j]->institutions[$k]->ror;
						$tabInst['CY'][$k] = $resOA->results[$i]->authorships[$j]->institutions[$k]->country_code;
						$tabInst['TY'][$k] = $resOA->results[$i]->authorships[$j]->institutions[$k]->type;
						$k++;
					}
				}
				
				if (!empty($tabInst)) {
					//Institutions françaises : on recherche d'abord 11, puis 12, puis 13
					if (array_keys($tabInst['AR'], '11')) {
						$keysF = array_keys($tabInst['AR'], '11');
					}else{
						if (array_keys($tabInst['AR'], '12')) {
							$keysF = array_keys($tabInst['AR'], '12');
						}else{
							if (array_keys($tabInst['AR'], '13')) {
								$keysF = array_keys($tabInst['AR'], '13');
							}
						}
					}
					
					//Institutions étrangères : on recherche d'abord 21, puis 22, puis 23
					if (array_keys($tabInst['AR'], '21')) {
						$keysE = array_keys($tabInst['AR'], '21');
					}else{
						if (array_keys($tabInst['AR'], '22')) {
							$keysE = array_keys($tabInst['AR'], '22');
						}else{
							if (array_keys($tabInst['AR'], '23')) {
								$keysE = array_keys($tabInst['AR'], '23');
							}
						}
					}
				}

				if (!empty($keysF)) {
					foreach ($keysF as $keys) {
						$inst_DN .= expcsv($tabInst['DN'][$keys]).'~||~';
						$inst_RO .= expcsv($tabInst['RO'][$keys]).'~||~';
						$inst_CY .= expcsv($tabInst['CY'][$keys]).'~||~';
						$inst_TY .= expcsv($tabInst['TY'][$keys]).'~||~';
					}
				}
				
				if (!empty($keysE)) {
					foreach ($keysE as $keys) {
						$inst_DN .= expcsv($tabInst['DN'][$keys]).'~||~';
						$inst_RO .= expcsv($tabInst['RO'][$keys]).'~||~';
						$inst_CY .= expcsv($tabInst['CY'][$keys]).'~||~';
						$inst_TY .= expcsv($tabInst['TY'][$keys]).'~||~';
					}
				}
				
				if (!empty($inst_DN) && substr($inst_DN, -4) == '~||~') {
					$inst_DN = substr($inst_DN, 0, -4);
					$inst_RO = substr($inst_RO, 0, -4);
					$inst_CY = substr($inst_CY, 0, -4);
					$inst_TY = substr($inst_TY, 0, -4);
				}

				$inst_DN .= '~|~';
				$inst_RO .= '~|~';
				$inst_CY .= '~|~';
				$inst_TY .= '~|~';
				$inst_RW .= expcsv(str_replace(array(",", "'"), array("~troliv~", "~trolia~"), $resOA->results[$i]->authorships[$j]->raw_affiliation_string)).'~|~';
				$j++;
			}
		}
		$chaine .= (substr($author_DN, -3) == '~|~') ? substr($author_DN, 0, -3).";" : ";";
		$chaine .= (substr($orcid, -3) == '~|~') ? substr($orcid, 0, -3).";" : ";";
		$chaine .= (substr($is_cor, -3) == '~|~') ? substr($is_cor, 0, -3).";" : ";";
		//$chaine .= (substr($domaine, -3) == '~|~') ? substr($domaine, 0, -3).";" : ";";
		$chaine .= (substr($inst_DN, -3) == '~|~') ? substr($inst_DN, 0, -3).";" : ";";
		$chaine .= (substr($inst_RO, -3) == '~|~') ? substr($inst_RO, 0, -3).";" : ";";
		$chaine .= (substr($inst_CY, -3) == '~|~') ? substr($inst_CY, 0, -3).";" : ";";
		$chaine .= (substr($inst_TY, -3) == '~|~') ? substr($inst_TY, 0, -3).";" : ";";
		$chaine .= (substr($inst_RW, -3) == '~|~') ? substr($inst_RW, 0, -3).";" : ";";
		
		//ISSN
		$chaine .= (isset($resOA->results[$i]->primary_location->source->issn_l)) ? expcsv($resOA->results[$i]->primary_location->source->issn_l).";" : ";";
		
		//EISSN
		$j = 0;
		$EISSN = '';
		if (isset($resOA->results[$i]->primary_location->source->issn)) {
			while (isset($resOA->results[$i]->primary_location->source->issn[$j])) {
				$EISSN .= expcsv($resOA->results[$i]->primary_location->source->issn[$j]).'~|~';
				$j++;
			}
		}
		$chaine .= substr($EISSN, 0, -3).";";
		
		//Type de source (revue)
		$chaine .= (isset($resOA->results[$i]->primary_location->source->type)) ? expcsv($resOA->results[$i]->primary_location->source->type).";" : ";";
		
		//Titre de la revue
		$chaine .= (isset($resOA->results[$i]->primary_location->source->display_name)) ? expcsv($resOA->results[$i]->primary_location->source->display_name).";" : ";";
		
		//Nom de l'éditeur
		$chaine .= (isset($resOA->results[$i]->primary_location->source->host_organization_lineage_names[0])) ? expcsv($resOA->results[$i]->primary_location->source->host_organization_lineage_names[0]).";" : ";";
		
		//Volume
		$chaine .= (isset($resOA->results[$i]->biblio->volume)) ? expcsv($resOA->results[$i]->biblio->volume).";" : ";";
		
		//Issue
		$chaine .= (isset($resOA->results[$i]->biblio->issue)) ? expcsv($resOA->results[$i]->biblio->issue).";" : ";";
		
		//Pages
		$chaine .= (isset($resOA->results[$i]->biblio->first_page)) ? expcsv($resOA->results[$i]->biblio->first_page)."-".expcsv($resOA->results[$i]->biblio->last_page).";" : ";";
		
		//Mots-clés
		$j = 0;
		$keywords = '';
		if (isset($resOA->results[$i]->concepts)) {
			while (isset($resOA->results[$i]->concepts[$j])) {
				$keywords .= expcsv($resOA->results[$i]->concepts[$j]->display_name).', ';
				$j++;
			}
		}
		$chaine .= substr($keywords, 0, -2).";";
		
		//Résumé
		//Le résumé d'OpenAlex n'est pas fiable
		/*
		$abstract = '';
		if (isset($resOA->results[$i]->abstract_inverted_index)) {
			$tab = get_object_vars($resOA->results[$i]->abstract_inverted_index);
			foreach($tab as $key => $value) {
				$abstract .= $key.' ';
			}
		}
		$chaine .= substr($abstract, 0, -1).";";
		*/
		
		$chaine .= chr(13).chr(10);
		fwrite($inF,$chaine);
		//echo $cpt.'<br>';
		$cpt += 1;
		$tpc += 1;
		if ($cpt >= ($numFound+1)) {break 2;}
	}
	$tpc = 1;
	$ipag += 1;
}

echo 'Extraction réalisée.<br>';
echo 'Si nécessaire, vous pouvez <a href="./openalex.csv">télécharger le fichier OpenAlex CSV</a> (clic droit, enregistrer sous)';


?>