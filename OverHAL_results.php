<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Page d'accueil des résultats - Results home page
 */

header('Content-Encoding: none');

header('Content-type: text/html; charset=UTF-8');
mb_internal_encoding("UTF-8");
$akSR = "13798F18-07B4-11EB-A9BD-C7111677CA68";//ApiKey Sherpa Romeo

include "./OverHAL_oaDOI.php";
include './OverHAL_codes_pays.php';
include './OverHAL_codes_langues.php';

$root = 'http';
	if ( isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")	{
		$root.= "s";
	}
$targetPDF = $root . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "/PDF/";
$targetPDF = str_replace("/OverHAL_results.php", "", $targetPDF);

//bibtex files deletion
if (file_exists("./HAL/OverHAL_scopus.bib")) {unlink("./HAL/OverHAL_scopus.bib");}
if (file_exists("./HAL/OverHAL_wos_csv.bib")) {unlink("./HAL/OverHAL_wos_csv.bib");}
if (file_exists("./HAL/OverHAL_scifin.bib")) {unlink("./HAL/OverHAL_scifin.bib");}
if (file_exists("./HAL/OverHAL_zotero.bib")) {unlink("./HAL/OverHAL_zotero.bib");}
if (file_exists("./HAL/OverHAL_pubmed_xml.bib")) {unlink("./HAL/OverHAL_pubmed_xml.bib");}
if (file_exists("./HAL/OverHAL_pubmed_txt.bib")) {unlink("./HAL/OverHAL_pubmed_txt.bib");}
if (file_exists("./HAL/OverHAL_pubmed_csv.bib")) {unlink("./HAL/OverHAL_pubmed_csv.bib");}
if (file_exists("./HAL/OverHAL_pubmed_fcgi.bib")) {unlink("./HAL/OverHAL_pubmed_fcgi.bib");}
if (file_exists("./HAL/OverHAL_dimensions.bib")) {unlink("./HAL/OverHAL_dimensions.bib");}
if (file_exists("./HAL/OverHAL_wos_txt.bib")) {unlink("./HAL/OverHAL_wos_txt.bib");}
if (file_exists("./HAL/OverHAL_openalex.bib")) {unlink("./HAL/OverHAL_openalex.bib");}

//TEI files deletion
if (file_exists("./HAL/OverHAL_scopus.zip")) {unlink("./HAL/OverHAL_scopus.zip");}
if (file_exists("./HAL/OverHAL_wos_csv.zip")) {unlink("./HAL/OverHAL_wos_csv.zip");}
if (file_exists("./HAL/OverHAL_scifin.zip")) {unlink("./HAL/OverHAL_scifin.zip");}
if (file_exists("./HAL/OverHAL_zotero.zip")) {unlink("./HAL/OverHAL_zotero.zip");}
if (file_exists("./HAL/OverHAL_pubmed_xml.zip")) {unlink("./HAL/OverHAL_pubmed_xml.zip");}
if (file_exists("./HAL/OverHAL_pubmed_txt.zip")) {unlink("./HAL/OverHAL_pubmed_txt.zip");}
if (file_exists("./HAL/OverHAL_pubmed_fcgi.zip")) {unlink("./HAL/OverHAL_pubmed_fcgi.zip");}
if (file_exists("./HAL/OverHAL_dimensions.zip")) {unlink("./HAL/OverHAL_dimensions.zip");}
if (file_exists("./HAL/OverHAL_wos_txt.zip")) {unlink("./HAL/OverHAL_wos_txt.zip");}
if (file_exists("./HAL/OverHAL_openalex.zip")) {unlink("./HAL/OverHAL_openalex.zip");}

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
$wos_html = 0;
if (isset($_FILES['wos_html']))
{
  if ($_FILES['wos_html']['error'] != 4)//Is there a wos HTML file ?
  {
    if ($_FILES['wos_html']['error'])
    {
      switch ($_FILES['wos_html']['error'])
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
    $extension = strrchr($_FILES['wos_html']['name'], '.');
    if ($extension != ".html") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['wos_html']['tmp_name'], "WoS.html");
    include "./OverHAL_HTML_import.php";
    unlink("./WoS.html");
    $wos_html = 1;
  }
}

$pubmed_html = 0;
if (isset($_FILES['pubmed_html']))
{
  if ($_FILES['pubmed_html']['error'] != 4)//Is there a pubmed HTML file ?
  {
    if ($_FILES['pubmed_html']['error'])
    {
      switch ($_FILES['pubmed_html']['error'])
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
    $extension = strrchr($_FILES['pubmed_html']['name'], '.');
    if ($extension != ".html") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['pubmed_html']['tmp_name'], "PubMed.html");
    include "./OverHAL_HTML_import.php";
    unlink("./PubMed.html");
    $pubmed_html = 1;
  }
}

$pubmed_xml = 0;
if (isset($_FILES['pubmed_xml']))
{
  if ($_FILES['pubmed_xml']['error'] != 4)//Is there a pubmed HTML file ?
  {
    if ($_FILES['pubmed_xml']['error'])
    {
      switch ($_FILES['pubmed_xml']['error'])
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
    $extension = strrchr($_FILES['pubmed_xml']['name'], '.');
    if ($extension != ".xml") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['pubmed_xml']['tmp_name'], "PubMed.xml");
    include "./OverHAL_XML_import.php";
    //unlink("Pubmed.html");
    $pubmed_xml = 1;
  }
}

$pubmed_txt= 0;
if (isset($_FILES['pubmed_txt']))
{
  if ($_FILES['pubmed_txt']['error'] != 4)//Is there a pubmed TXT file ?
  {
    if ($_FILES['pubmed_txt']['error'])
    {
      switch ($_FILES['pubmed_txt']['error'])
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
    $extension = strrchr($_FILES['pubmed_txt']['name'], '.');
    if ($extension != ".txt") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['pubmed_txt']['tmp_name'], "PubMed.txt");
    include "./OverHAL_TXT_import.php";
    //unlink("Pubmed.html");
    $pubmed_txt = 1;
  }
}

$pubmed_fcgi = 0;
if (isset($_FILES['pubmed_fcgi']))
{
  if ($_FILES['pubmed_fcgi']['error'] != 4)//Is there a pubmed HTML file ?
  {
    if ($_FILES['pubmed_fcgi']['error'])
    {
      switch ($_FILES['pubmed_fcgi']['error'])
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
    $extension = strrchr($_FILES['pubmed_fcgi']['name'], '.');
    if ($extension != ".fcgi") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['pubmed_fcgi']['tmp_name'], "PubMed.fcgi");
    include "./OverHAL_FCGI_import.php";
    //unlink("Pubmed.html");
    $pubmed_fcgi = 1;
  }
}

$wos_txt= 0;
if (isset($_FILES['wos_txt']))
{
  if ($_FILES['wos_txt']['error'] != 4)//Is there a wos TXT file ?
  {
    if ($_FILES['wos_txt']['error'])
    {
      switch ($_FILES['wos_txt']['error'])
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
    $extension = strrchr($_FILES['wos_txt']['name'], '.');
    if ($extension != ".txt") {
      Header("Location: "."OverHAL.php?erreur=5");
    }
    move_uploaded_file($_FILES['wos_txt']['tmp_name'], "WoS.txt");
    include "./OverHAL_TXT_WoS_import.php";
    $wos_txt = 1;
  }
}

//Bibliographic sources array -> first key must correspond to form field file name
$souBib = array(
  "scopus" => array(
    "Maj" => "Scopus",
    "Sep" => ",",
    "Year" => "Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "\"Authors\"",
    "Source" => "Source title",
    "Type" => "Document Type",
  ),
  "pubmed_csv" => array(
    "Maj" => "PubMed (CSV)",
    "Sep" => ",",
    "Year" => "ShortDetails",
    "Title" => "Title",
    "DOI" => "Details",
    "Authors" => "Description",
    "Source" => "ShortDetails",
    "Type" => "Type",
  ),
  "wos_csv" => array(
    "Maj" => "WoS (CSV)",
    "Sep" => "	",
    "Year" => "PY",
    "Title" => "TI",
    "DOI" => "DI",
    "Authors" => "AU",
    "Source" => "SO",
    "Type" => "PT",
  ),
  "scifin" => array(
    "Maj" => "SciFinder",
    "Sep" => ",",
    "Year" => "Publication Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Author",
    "Source" => "Journal Title",
    "Type" => "Document Type",
  ),
  "zotero" => array(
    "Maj" => "Zotero",
    "Sep" => ",",
    "Year" => "Publication Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Author",
    "Source" => "Publication Title",
    "Type" => "Item Type",
		"Url" => "Url",
  ),
	"openalex" => array(
    "Maj" => "OpenAlex",
    "Sep" => ";",
    "Year" => "Date",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Author_DN",
    "Source" => "Titre revue",
    "Type" => "Type notice",
  )
);

$ip = $_POST['ip'];
include("./Glob_IP_list.php");
if (in_array($ip, $IP_aut)) {
  $souBib["dimensions"] = array(
    "Maj" => "Dimensions",
    "Sep" => ",",
    "Year" => "PubYear",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Authors",
    "Source" => "Source title",
    "Type" => "Publication Type"
  );
}
if ($wos_html == 1)
{
  $wosHtmlTab = array(
    "Maj" => "WoS (HTML)",
    "Sep" => "^",
    "Year" => "Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Authors",
    "Source" => "Source title",
    "Type" => "Document Type",
  );
  $souBib["wos_html"] = $wosHtmlTab;
}

if ($pubmed_html == 1)
{
  $pubmedHtmlTab = array(
    "Maj" => "PubMed (HTML)",
    "Sep" => "^",
    "Year" => "Year",
    "Title" => "Title",
    "DOI" => "DOI",
    "Authors" => "Authors",
    "Source" => "Source title",
    "Type" => "Type",
  );
  $souBib["pubmed_html"] = $pubmedHtmlTab;
}

if ($pubmed_xml == 1)
{
  $pubmedXmlTab = array(
    "Maj" => "PubMed (XML)",
    "Sep" => "^",
    "Year" => "DatePub",
    "Title" => "Titre",
    "DOI" => "DOI",
    "Authors" => "Auteurs",
    "Source" => "Revue",
    "Type" => "Type",
  );
  $souBib["pubmed_xml"] = $pubmedXmlTab;
}

if ($pubmed_txt == 1)
{
  $pubmedTxtTab = array(
    "Maj" => "PubMed (TXT)",
    "Sep" => "^",
    "Year" => "DatePub",
    "Title" => "Titre",
    "DOI" => "DOI",
    "Authors" => "Auteurs",
    "Source" => "Revue",
    "Type" => "Type",
  );
  $souBib["pubmed_txt"] = $pubmedTxtTab;
}

if ($pubmed_fcgi == 1)
{
  $pubmedFCGITab = array(
    "Maj" => "PubMed (FCGI)",
    "Sep" => "^",
    "Year" => "aPub",
    "Title" => "titPub",
    "DOI" => "DOI",
    "Authors" => "tabAut",
    "Source" => "titRev",
    "Type" => "Type",
  );
  $souBib["pubmed_fcgi"] = $pubmedFCGITab;
}

if ($wos_txt == 1)
{
  $wosTxtTab = array(
    "Maj" => "WoS (TXT)",
    "Sep" => "^",
    "Year" => "PY",
    "Title" => "TI",
    "DOI" => "DI",
    "Authors" => "AU",
    "Source" => "SO",
    "Type" => "PT",
  );
  $souBib["wos_txt"] = $wosTxtTab;
}
//var_dump($souBib);
$nbSouBib = count($souBib);

//Tests errors on file submit
foreach ($souBib as $key => $subTab)
{
  if ($key != "wos_html" && $key != "pubmed_html" && $key != "pubmed_xml" && $key != "pubmed_txt" && $key != "pubmed_fcgi" && $key != "wos_txt")
  {
    if (isset($_FILES[$key]['name']) && $_FILES[$key]['name'] != "") //File has been submitted
    {
      if ($_FILES[$key]['error'])
      {
        switch ($_FILES[$key]['error'])
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
           //case 4: // UPLOAD_ERR_NO_FILE
           //Header("Location: "."OverHAL.php?erreur=4");
           //break;
        }
      }
      $extension = strrchr($_FILES[$key]['name'], '.');
      if ($extension != ".csv") {
        Header("Location: "."OverHAL.php?erreur=5");
        break;
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>OverHAL - HALUR</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="OverHAL permet de générer un fichier de publications TEI HAL pour Zip2HAL à partir d'un fichier source et de contacter les auteurs pour leur manuscrit" />
	<meta content="Coderthemes + Lizuka + OTroccaz + LJonchere" name="author" />
	<!-- App favicon -->
	<link rel="shortcut icon" href="favicon.ico">

	<!-- third party css -->
	<!-- <link href="./assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" /> -->
	<!-- third party css end -->

	<!-- App css -->
	<link href="./assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="./assets/css/app-hal-ur1.min.css" rel="stylesheet" type="text/css" id="light-style" />
	<!-- <link href="./assets/css/app-creative-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" /> -->
	
	<!-- third party js -->
	<script src="./OverHAL.js"></script>
	<script src="./OverHAL_results.js"></script>
	<!-- third party js end -->
	
	<!-- bundle -->
	<script src="./assets/js/vendor.min.js"></script>
	<script src="./assets/js/app.min.js"></script>

	<!-- third party js -->
	<script src="./assets/js/vendor/Chart.bundle.min.js"></script>
	<!-- third party js ends -->
	<script src="./assets/js/pages/hal-ur1.chartjs.js"></script>
				
</head>

<body class="loading" data-layout="topnav" >

<noscript>
<div class='text-primary' id='noscript'><strong>ATTENTION !!! JavaScript est désactivé ou non pris en charge par votre navigateur : cette procédure ne fonctionnera pas correctement.</strong><br>
<strong>Pour modifier cette option, voir <a target='_blank' rel='noopener noreferrer' href='http://www.libellules.ch/browser_javascript_activ.php'>ce lien</a>.</strong></div><br>
</noscript>

        <!-- Begin page -->
        <div class="wrapper">

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">
								
								<?php
								include "./Glob_haut.php";
								
								if(isset($_GET["erreur"]))
								{
									echo '<div id="warning-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">';
									echo '    <div class="modal-dialog modal-md modal-center">';
									echo '        <div class="modal-content">';
									echo '            <div class="modal-body p-4">';
									echo '                <div class="text-center">';
									echo '                    <i class="dripicons-warning h1 text-warning"></i>';
									echo '                    <h4 class="mt-2">Avertissement</h4>';
									
									$erreur = $_GET["erreur"];
									if($erreur == 1) {echo '                    <p class="mt-3">Le fichier dépasse la limite autorisée par le serveur(fichier php.ini) !</p></script>';}
									if($erreur == 2) {echo '                    <p class="mt-3">Le fichier dépasse la limite autorisée dans le formulaire HTML !</p></script>';}
									if($erreur == 3) {echo '                    <p class="mt-3">L&apos;envoi du fichier a été interrompu pendant le transfert !</p></script>';}
									//if($erreur == 4) {echo '                    <p class="mt-3">Aucun fichier envoyé ou bien il a une taille nulle !</p></script>';}
									if($erreur == 5) {echo '                    <p class="mt-3">Mauvaise extension de fichier !</p></script>';}
									
									echo '                    <button type="button" class="btn btn-warning my-2" data-dismiss="modal">Continuer</button>';
									echo '                </div>';
									echo '            </div>';
									echo '        </div><!-- /.modal-content -->';
									echo '    </div><!-- /.modal-dialog -->';
									echo '</div><!-- /.modal -->';
								}
								?>
								
								<!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb bg-light-lighten p-2">
                                                <li class="breadcrumb-item"><a href="index.php"><i class="uil-home-alt"></i> Accueil HALUR</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">Over<span class="font-weight-bold">HAL</span></li>
                                            </ol>
                                        </nav>
                                    </div>
                                    <h4 class="page-title">Convertissez vos imports éditeurs en TEI</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-8 col-lg-6 d-flex">
                                <!-- project card -->
                                <div class="card d-block w-100 shadow-lg">
                                    <div class="card-body">
                                        
                                        <!-- project title-->
                                        <h2 class="h1 mt-0">
                                            <i class="mdi mdi-widgets-outline text-primary"></i>
                                            <span class="font-weight-light">Over</span><span class="text-primary">HAL</span>
                                        </h2>
                                        <h5 class="badge badge-primary badge-pill">Présentation</h5>
																				
																				<img src="./img/ricardo-gomez-angel-horgenberg-horgen-switzerland-unsplash.jpg" alt="Accueil OverHAL" class="img-fluid"><br>
																				<p class="font-italic">Photo : Horgenberg, Horgen, Switzerland by Ricardo Gomez Angel on Unsplash (détail)</p>

                                        <p class=" mb-2 text-justify">
																					OverHAL permet d'une part de générer un fichier de publications TEI HAL pour Zip2HAL à partir d'un fichier source, d'autre part d'envoyer des mails aux auteurs pour récupération du manuscrit auteur. Conçu à partir d'un script de <a target="_blank" rel="noopener noreferrer" href="http://igm.univ-mlv.fr/~gambette/ExtractionHAL/CouvertureHAL/">Philippe Gambette</a> (CouvertureHAL), il a été créé par Olivier Troccaz (conception-développement) et Laurent Jonchère (conception). Son code est disponible <a target='_blank' rel='noopener noreferrer' href="https://github.com/OTroccaz/OverHAL">sur GitHub</a> sous licence <a target='_blank' rel='noopener noreferrer' href="https://www.gnu.org/licenses/gpl-3.0.fr.html">GPLv3</a>.
                                        </p>
																				
																				<p class="mb-4">
                                            Contacts : <a target='_blank' rel='noopener noreferrer' href="https://scienceouverte.univ-rennes.fr/interlocuteurs/laurent-jonchere">Laurent Jonchère</a> (Université de Rennes 1) / <a target='_blank' rel='noopener noreferrer' href="https://scienceouverte.univ-rennes.fr/interlocuteurs/olivier-troccaz">Olivier Troccaz</a> (CNRS CReAAH/OSUR).
                                        </p>

                                    </div> <!-- end card-body-->
                                    
                                </div> <!-- end card-->

                            </div> <!-- end col -->
                            <div class="col-lg-6 col-xl-4 d-flex">
                                <div class="card shadow-lg w-100">
                                    <div class="card-body">
                                        <h5 class="badge badge-primary badge-pill">Mode d'emploi</h5>
                                        <p class=" mb-2">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <a href="https://halur1.univ-rennes1.fr/Manuel-OverHAL.pdf"><i class="mdi mdi-file-pdf-box-outline mr-1"></i> Télécharger le manuel</a>
                                                </li>
                                               
                                            </ul> 
                                        </p>
                                    </div>
                                </div>
                                <!-- end card-->
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12 d-flex">
                                <!-- project card -->
                                <div class="card w-100 d-block shadow-lg">
                                    <div class="card-body">
<?php
if ($limzot == "non")
{
?>
<a name='Resultats'></a><h1>Résultats</h1>

<strong>Le script ci-dessous ne se fonde que sur la détection d'un titre identique (après suppression des caractères spéciaux et passage en minuscules)
ou d'un même DOI pour identifier une référence d'une source bibliographique (Scopus, Pubmed, etc.) avec un dépôt HAL.</strong><br/><br/>
<a href='#Chargement de la requête HAL'>Chargement de la requête HAL</a><br />
<?php
foreach ($souBib as $key => $subTab)
{
	if ($_FILES[$key]['error'] != 4)
	{
		$nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
		echo "<a href='#Chargement du fichier ".$nomSouBib."'>Chargement du fichier ".$nomSouBib."</a><br />";
		echo "<a href='#Références de ".$nomSouBib." non trouvées dans HAL'>Références de ".$nomSouBib." non trouvées dans HAL</a><br />";
		echo "<a href='#Auteurs des références de ".$nomSouBib." non trouvées dans HAL'>Auteurs des références de ".$nomSouBib." non trouvées dans HAL</a><br />";
	}
}
?>
<a href='#Bilan quantitatif'>Bilan quantitatif</a><br />

<br />
Récupération des résultats de HAL en cours...
<?php
}

if(!function_exists("array_column")) {
	function array_column($array,$column_name) {
		return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
	}
}
/*
function ctype_alpup($str) {
	$flag = true;
	for($i = 0; $i < strlen($str); $i++) {
		$tst = substr($str, $i, 1);
		if(!ctype_upper($tst) && !is_numeric($tst)){//pas que des majuscules et des chiffres
			$flag = false;
			break;
		}
	}
	return $flag;
}
*/
function testLab($labElt) {
	$eltTab = explode("~|~", $labElt);
	$orgName = $eltTab[2];
	$orgName = str_replace(array("UR1", " UR1"), "", $orgName);
	if ($eltTab[3] == "institution") {//abbreviation between crochet
		if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
			$nameTab = explode(",", $orgName);
			$ville = $nameTab[count($nameTab)-2];
			$ville = str_ireplace(" cedex", "", $ville);
			$villeTab = explode(" ", $ville);
			$orgName = "CHU ".$villeTab[count($villeTab)-1];
		}
		$nameTab = explode(",", $orgName);
		$orgName = "";
		$oN = 0;
		$iName = 0;
		foreach ($nameTab as $name) {
			if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
				if ($name == "Universite CHU") {
					$ville = $nameTab[count($nameTab)-2];
					$ville = str_ireplace(" cedex", "", $ville);
					$villeTab = explode(" ", $ville);
					$name = $name.' '.$villeTab[count($villeTab)-1];
				}
				if ($oN == 0) {
					$oN = 1;
				}else{
					$orgName .= ", ";
				}
				$eltNameTab = explode(" ", trim($name));
				$oNE = 0;
				foreach ($eltNameTab as $elt) {
					if ($oNE == 0) {
						$oNE = 1;
					}else{
						$orgName .= " ";
					}
					if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
						$orgName .= "[".trim($elt)."]";
					}else{
						$orgName .= "".trim($elt);
					}
				}
			}
			$iName += 1;
		}
	}
	//suppression/remplacement divers
	$orgName = str_replace(array("(", ")"), "", $orgName);
	$orgName = str_replace("/", " ", $orgName);
	//test présence 'Department' ou 'Service d' pour suppression
	$orgTab = explode(", ", $orgName);
	if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
		$orgTab[0] = "";
		$orgName = "";
		for($dpt = 0; $dpt < count($orgTab); $dpt++) {
			if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
		}
		$orgName = substr($orgName, 0, (strlen($orgName) - 2));
	}
	return $orgName;
}

function array2xml($array, $xml = false){
	if($xml === false){
			$xml = new SimpleXMLElement('<result/>');
	}

	foreach($array as $key => $value){
			if(is_array($value)){
					array2xml($value, $xml->addChild($key));
			} else {
					$xml->addChild($key, $value);
			}
	}

	return $xml->asXML();
}
					
function netCode($str) {
	$str = str_replace(array("  b "," b "," /b  "," /b "), " ", $str);
	$str = str_replace(array("  i "," i "," /i  "," /i "), " ", $str);
	$str = str_replace(array("  u "," u "," /u  "," /u "), " ", $str);
	return $str;
}

function netNum($str) {
	$str = str_replace(array("0","1","2","3","4","5","6","7","8","9"), "", $str);
	return $str;
}

function minRev ($rev){//terms in lowercase with first one in uppercase except for certain terms
	if (substr($rev, 0, 3) == "The") {$rev = substr($rev, 4, strlen($rev));}//to remove 'The' at the beginning
	$revInt = ucwords(strtolower($rev));
	$deb = array(" Of ", " And ", " The ", " In ", " Et ", " D' ", " L' ", " En ", " De ");
	$fin = array(" of ", " and ", " the ", " in ", " et ", " d' ", " l' ", " en ", " de ");
	$revFin = str_replace($deb, $fin, $revInt);
	if (strpos($revFin, "-") !== false) {//first capital letter after the hyphen
		$pos = strpos($revFin, "-");
		$revG = trim(mb_substr($revFin, 0, $pos, 'UTF-8'));
		$revD = trim(mb_substr($revFin, ($pos+1), strlen($revFin), 'UTF-8'));
		$revFin = $revG."-".ucfirst($revD);
	}
	return $revFin;
}

function normAut($aut)
{
	$aut = trim($aut);
	if (strpos($aut, ",") !== false)
	{
		$autTab = explode(",", $aut);
		$qui = $autTab[0].substr($autTab[1], 0, 2);
	}else{
		$qui = $aut;
	}
	return $qui;
}

function trimUltime($chaine){
	$chaine = trim($chaine);
	$chaine = str_replace("###antiSlashe###t", " ", $chaine);
	$chaine = preg_replace("!\s+!", " ", $chaine);
	return $chaine;
}

function objectToArray($object) {
	if(!is_object( $object) && !is_array($object)) {
		return $object;
	}
	if(is_object($object)) {
		$object = get_object_vars($object);
	}
	return array_map('objectToArray', $object);
}

function idaureHal($urlHAL, $cuHAL, &$docid, &$label, &$code, &$type){
	$urlHAL = str_replace(" ", "%20", $urlHAL);
	$docid = 0;
	//echo $urlHAL.'<br>';
	$contents = file_get_contents($urlHAL);
	$resHAL = json_decode($contents);
	$numFound = $resHAL->response->numFound;
	foreach($resHAL->response->docs as $entry)
	{
		//if ($entry->valid_s == "VALID" || $entry->valid_s == "INCOMING")
		//{
			if ($docid == 0)
			{
				$docid = $entry->docid;
			}else{
				if (strlen($entry->docid) < strlen($docid))
				{
					$docid = $entry->docid;
				}
			}
			$label = $entry->label_s;
			//echo 'toto : '.$label.'<br>';
			$code = $cuHAL;
			if (isset($entry->code_s)) {
				$codeMax = count($entry->code_s) - 1;
				$code = str_replace(" ", "",$entry->code_s[$codeMax]);
			}
			if (isset($entry->type_s)) {
				$type = $entry->type_s;
			}
		//}
	}
}

function affilId($diffQuoi, $docid, $label, $code, $type, $pays, $validHAL) {
	global $autTab, $labTab, $autInd;
	$eltTab = explode("] ",$diffQuoi);
	$autGrp = str_replace("[", "", $eltTab[0]);
	$autQui = explode(";", $autGrp);
	$eltTabP = explode(",", $eltTab[count($eltTab) - 1]);
	$pays = trim($eltTabP[count($eltTabP) - 1]);
	//echo $diffQuoi.' - '.$docid.' - '.$autGrp.'<br>';
	for ($aut = 0; $aut < count($autQui); $aut++)
	{
		$pres = 0;
		$autInd = count($autTab);
		for ($qui = 0; $qui < count($autTab); $qui++)
		{
			//echo $qui.'<br>';
			if (normAut(trim($autTab[$qui])) == normAut($autQui[$aut]))//Author already present
			{
				$pres = 1;
				$docTab = explode("~|~", $labTab[trim($autTab[$qui])][$qui]);
				$docidQui = $docTab[0];
				//echo $docidQui.' - '.$docid.' - '.$validHAL.' : '.$autQui[$aut].'<br>';
				if ($docidQui != $docid)//New affiliation
				{
					$pres = 0;
					//echo $docidQui.' - '.$docid.' - '.$validHAL.' : '.$autQui[$aut].'<br>';
					//$labTab[$qui] = 0;
				}
				//break;
			}
		}
		//echo $pres.' - '.$autQui[$aut].' - '.$docid.' - '.$label.'<br>';
		//if (($pres == 0 && $docid != 0) || $validHAL == "--ok")
		//if ($pres == 0 && $validAut != "")
		if ($pres == 0)
		{
			$autTab[$autInd] = str_replace(array('[', ']'), '', trim($autQui[$aut]));
			$labTab[trim($autQui[$aut])][$autInd] = $docid."~|~".$label."~|~".$code."~|~".$type."~|~".$pays;
			$autInd++;
		}
	}
	//echo $diffQuoi.' - '.$autInd.'<br>';
	//var_dump($autTab);
	//var_dump($labTab);
}

function searchAcro($aTester, &$cuHAL, &$urlHAL, &$validHAL, &$retTest) {//Search for distinctive acronyms
	//echo $aTester.'<br>';
	$aTester = " ".$aTester;
	$aTester = str_replace("-UMR", " UMR", $aTester);
	$aTester = str_replace(array("-", "/"), " ", $aTester);
	$aTester = str_replace(array("UMR S ","UMR_S ","UMRS "), " U", $aTester);
	$retTest = "";
	if (stripos($aTester, " CNRS") !== false || stripos($aTester, " INRA") !== false || stripos($aTester, " UMR") !== false || stripos($aTester, " UMS") !== false || stripos($aTester, " UPR") !== false || stripos($aTester, " ERL") !== false || stripos($aTester, " IFR") !== false || stripos($aTester, " USR") !== false || stripos($aTester, " IRD") !== false || stripos($aTester, " FRE") !== false)//CNRS ou INRA
	{
		$detail = explode(",",$aTester);
		for ($det = 0; $det < count($detail); $det++)//search for CNRS 0000 or INSERM 0000 to replace by UMR0000
		{
			$umrT = trim($detail[$det]);
			if (stripos($umrT, " CNRS") !== false && strlen($umrT) == 9 && is_numeric(substr($umrT, -1, 1)) && is_numeric(substr($umrT, -2, 1)) && is_numeric(substr($umrT, -3, 1)) && is_numeric(substr($umrT, -4, 1)))
			{
				$umrT = str_replace("CNRS", "UMR", $umrT);
			}
			if (stripos($umrT, " INSERM") !== false && strlen($umrT) == 11 && is_numeric(substr($umrT, -1, 1)) && is_numeric(substr($umrT, -2, 1)) && is_numeric(substr($umrT, -3, 1)) && is_numeric(substr($umrT, -4, 1)))
			{
				$umrT = str_replace("INSERM", "UMR", $umrT);
			}
			$detail[$det] = " ".$umrT;
		}
		for ($det = 0; $det < count($detail); $det++)
		{
			/*
			if (stripos($detail[$det], " UMRS ") !== false)
			{
				extractCU($detail[$det], "UMRS ", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
			if (stripos($detail[$det], " UMR_S ") !== false)
			{
				extractCU($detail[$det], "UMR_S ", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
			if (stripos($detail[$det], " UMR S ") !== false)
			{
				extractCU($detail[$det], "UMR S ", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
			*/
			if (stripos($detail[$det], " UMR") !== false)
			{
				extractCU($detail[$det], "UMR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (stripos($detail[$det], " UMS") !== false)
			{
				extractCU($detail[$det], "UMS", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (stripos($detail[$det], " UPR") !== false)
			{
				extractCU($detail[$det], "UPR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (stripos($detail[$det], " ERL") !== false)
			{
				extractCU($detail[$det], "ERL", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (stripos($detail[$det], " IFR") !== false)
			{
				extractCU($detail[$det], "IFR", 3, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (strpos($detail[$det], " USR") !== false)
			{
				extractCU($detail[$det], "USR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (strpos($detail[$det], " USC") !== false)
			{
				extractCU($detail[$det], "USC", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (strpos($detail[$det], " UR") !== false)
			{
				extractCU($detail[$det], "UR", 4, array("INRA", "UR1", "CNRS"), $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (strpos($detail[$det], " IRD") !== false)
			{
				extractCU($detail[$det], "UMR", 3, "IRD", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
			if (strpos($detail[$det], " FRE") !== false)
			{
				extractCU($detail[$det], "FRE", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
			}
		}
	}
	if (stripos($aTester, " EA") !== false  && $urlHAL == "") {
		$detail = explode(",",$aTester);
		for ($det = 0; $det < count($detail); $det++)
		{
			if (stripos($detail[$det], " EA") !== false)
			{
				extractCU($detail[$det], "EA", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
		}
	}
	if (stripos($aTester, " CIC") !== false  && $urlHAL == "") {
		$detail = explode(",",$aTester);
		for ($det = 0; $det < count($detail); $det++)
		{
			if (stripos($detail[$det], " CIC-P") !== false)
			{
				extractCU($detail[$det], "CIC-P", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
			if (stripos($detail[$det], " CIC-IT") !== false)
			{
				extractCU($detail[$det], "CIC-IT", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
			if (stripos($detail[$det], " CIC") !== false)
			{
				extractCU($detail[$det], "CIC", 4, "", $aTester, $urlHAL, $cuHAL, $validHAL, $retTest);
				break;
			}
		}
	}
	//echo $aTester.'<br>';
	if (stripos($aTester, " INSERM") !== false  && $urlHAL == "")//INSERM
	{
		$detail = explode(",",$aTester);
		for ($det = 0; $det < count($detail); $det++)
		{
			$cu = trim($detail[$det]);
			$cu = str_replace(" ", "", $cu);
			//echo $cu.'<br>';
			if (stripos($detail[$det], " INSERM") !== false)//in case of INSERMxxxx but not always the case
			{
				//$codeUnit = substr(preg_replace("/[^0-9]/","",$detail[$det]), 0, 4);//We keep only the number
				$codeUnit = preg_replace("/[^0-9]/", "", $detail[$det]);//We keep only the number
				//echo 'toto : '.$detail[$det].' - '.$codeUnit.' - '.$validHAL.'<br>';
				//if ($codeUnit != "" && $validHAL != "ok")
				if ($codeUnit != "")
				{
					//$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U".$codeUnit."%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
					$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:(%22U".$codeUnit."%22OR%22U%20".$codeUnit."%22)&fl=title_s,valid_s,label_s,docid,code_s,type_s";
					$cuHAL = "U".$codeUnit;
					$validHAL = "ok";
					$retTest = str_ireplace(array("INSERM", $codeUnit), "", $aTester);
					break;
				}
			}
			if (strlen($cu) == 5) {//cas INSERM par exemple U1085
				if (is_numeric($cu[0]) == false && is_numeric($cu[1]) == true && is_numeric($cu[2]) == true && is_numeric($cu[3]) == true && is_numeric($cu[4]) == true) {
					$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:%22".$cu."%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
					$cuHAL = $cu;
					$validHAL = "ok";
					$retTest = str_ireplace(array("INSERM", $cu), "", $aTester);
					break;
				}
			}
			$detailP = explode(" ",$detail[$det]);
			//var_dump($detailP);
			for ($detP = 0; $detP < count($detailP); $detP++)
			{
				$cuP = trim($detailP[$detP]);
				$cuP = str_replace(" ", "", $cuP);
				if (strlen($cuP) == 5 && is_numeric($cuP[0]) == false && is_numeric($cuP[1]) == true && is_numeric($cuP[2]) == true && is_numeric($cuP[3]) == true && is_numeric($cuP[4]) == true) {
					$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:%22".$cuP."%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
					$cuHAL = $cuP;
					$validHAL = "ok";
					$retTest = str_ireplace(array("INSERM", $cuP), "", $aTester);
					break;
				}
			}
		}
	}
	if (stripos($aTester, " ECOBIO") !== false && $urlHAL == "") {//ECOBIO
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22ecobio%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "ECOBIO";
		$validHAL = "--";
		$retTest = str_ireplace("ECOBIO", "", $aTester);
	}
	if (stripos($aTester, " LEEST") !== false && $urlHAL == "") {//LEEST
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22leest%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "LEEST";
		$validHAL = "--";
		$retTest = str_ireplace("LEEST", "", $aTester);
	}
	if (stripos($aTester, " EHESP") !== false && $urlHAL == "") {//EHESP
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22ehesp%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "EHESP";
		$validHAL = "--";
		$retTest = str_ireplace("EHESP", "", $aTester);
	}
	if (stripos($aTester, " PACTE") !== false && $urlHAL == "") {//PACTE
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22pacte%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "PACTE";
		$validHAL = "--";
		$retTest = str_ireplace("PACTE", "", $aTester);
	}
	if (stripos($aTester, "1085") !== false && $urlHAL == "") {//U1085
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U1085%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "U1085";
		$validHAL = "ok";
		$retTest = str_ireplace("1085", "", $aTester);
	}
	if (stripos($aTester, " LERES") !== false && $urlHAL == "") {//LERES
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22LERES%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "PACTE";
		$validHAL = "--";
		$retTest = str_ireplace("LERES", "", $aTester);
	}
	if ((stripos($aTester, " Nutr Metab & Canc") !== false || stripos($aTester, " Numecan") !== false) && $urlHAL == "") {//ECOBIO
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=text:%22U1241%22&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		$cuHAL = "U1241";
		$validHAL = "--";
		$retTest = str_ireplace(array("Nutr Metab & Canc", "Numecan"), "", $aTester);
	}
	//echo $retTest.'<br>';
}

function extractCU($quoi, $term, $numDig, $clean, $aTester, &$urlHAL, &$cuHAL, &$validHAL, &$retTest) {//$detail[$det] - $CIC-P - number of digit
	$codeBase = str_replace($clean, "", normalize($quoi));//first cleaning
	$primTerm = substr($term, 0, 1);
	//echo $codeBase.'<br>';
	//is the first next character after unit type numeric ?
	if (is_numeric(substr($codeBase, strpos($codeBase, $term) + strlen($term), 1)))//style UUXXXX
	{
		$codeUnit = substr($codeBase, strpos($codeBase, $term) + strlen($term), $numDig);//extraction unit type + code
	}else{//style UUsometermsXXXX
		$codeUnit = preg_replace("/[^0-9]/", "", $quoi);//We keep only the number
	}
	if ($codeUnit != "" )
	{
		$urlHAL = "https://api.archives-ouvertes.fr/ref/structure/?q=code_s:(%22".$term.$codeUnit."%22OR%22".$term."%20".$codeUnit."%22OR%22".$primTerm."%20".$codeUnit."%22OR%22".$primTerm.$codeUnit."%22)&fl=title_s,valid_s,label_s,docid,code_s,type_s";
		//echo $urlHAL.'<br>';
		$cuHAL = $term.$codeUnit;
		$validHAL = "ok";
		$retTest = str_replace(array($codeUnit, $term), "", $aTester);
		$retTest = str_replace($clean, "", $retTest);
		//echo $cuHAL.'<br>';
		//break;
	}
}

function supprAmp($st) {
	$st = str_replace('&', 'and', $st);
	$st = str_replace(array('<bold>', '</bold>'), '', $st);
	$st = str_replace(array('<u>', '</u>'), '', $st);
	$st = str_replace(array('<em>', '</em>'), '', $st);
	$st = str_replace(array('<a>', '</a>'), '', $st);
	//$st = str_replace(':', '', $st);
	$st = htmlspecialchars($st, ENT_QUOTES, "UTF-8");
	return $st;
}

function storePrm($entry, $nbHAL, $doi, $pubLink, $seeAlso, $titlePlus, $titreInit, $encodedTitle, &$halId, &$nbHalPerYear, &$halTitles, &$halWhere, &$halAuthors, &$halFullRef, &$halDoctyp, &$halArxivs, &$halPubmed, &$halYears) {
	//So we store all parameters using the simplified HAL title
	//We also use the DOI as a key if it is present:
	$halId[$encodedTitle]=($entry->halId_s);

	//Saving the DOI
	if (isset($entry->doiId_s)) {$doi=strtolower($entry->doiId_s);}
	//if(strlen($doi)>0){$halId[$doi]=($entry->doiId_s);}
	if (strlen($doi)>0){$halId[$doi]=($entry->halId_s);}

	//Saving the publisher link
	if (isset($entry->publisherLink_s[0])) {$pubLink = normalize(strtolower($entry->publisherLink_s[0]));}
	if (strlen($pubLink)>0){$halId[$pubLink] = ($entry->publisherLink_s[0]);}

	//Saving the 'see also' field
	if (isset($entry->seeAlso_s)) {$seeAlso = normalize(strtolower($entry->seeAlso_s[0]));}
	if (strlen($seeAlso)>0){$halId[$seeAlso] = ($entry->seeAlso_s[0]);}

	//Saving the year
	if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
	{
		$halYears[$encodedTitle]=($entry->conferenceStartDateY_i);
		if(strlen($doi)>0){$halYears[$doi]=($entry->conferenceStartDateY_i);}
	}else
	{
		$halYears[$encodedTitle]=($entry->producedDateY_i);
		if(strlen($doi)>0){$halYears[$doi]=($entry->producedDateY_i);}
	}

	//Record number of HAL publications per year
	if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
	{
		if(array_key_exists(($entry->conferenceStartDateY_i),$nbHalPerYear))
		{
			 $nbHalPerYear[$entry->conferenceStartDateY_i]+=1;
		} else
		{
			 $nbHalPerYear[$entry->conferenceStartDateY_i]=1;
		}
	}else
	{
		if(array_key_exists(($entry->producedDateY_i),$nbHalPerYear))
		{
			 $nbHalPerYear[$entry->producedDateY_i]+=1;
		} else
		{
			 $nbHalPerYear[$entry->producedDateY_i]=1;
		}
	}

	//Saving the title
	//Is there a subTitle ?
	$titlePlus = $entry->title_s[0];
	if (isset($entry->subTitle_s[0])) {
		$titlePlus .= " : ".$entry->subTitle_s[0];
	}
	$halTitles[$encodedTitle]=($titlePlus);
	if(strlen($doi)>0){$halTitles[$doi]=($titlePlus);}
	//Saving the publication location: journal, conference or book
	if(isset($entry->journalTitle_s) && strlen($entry->journalTitle_s)>0)
	{
		 $halWhere[$encodedTitle]=($entry->journalTitle_s);
		 if(strlen($doi)>0){$halWhere[$doi]=($entry->journalTitle_s);}
	}
	if(isset($entry->proceedings_s) && strlen($entry->proceedings_s)>0)
	{
		 $halWhere[$encodedTitle]=($entry->proceedings_s);
		 if(strlen($doi)>0){$halWhere[$doi]=($entry->proceedings_s);}
	}
	if(isset($entry->bookTitle_s) && strlen($entry->bookTitle_s)>0)
	{
		 $halWhere[$encodedTitle]=($entry->bookTitle_s);
		 if(strlen($doi)>0){$halWhere[$doi]=($entry->bookTitle_s);}
	}

	//Saving authors:
	$authors="";
	$initial = 1;
	foreach($entry->authFullName_s as $author)
	{
		 if ($initial==1){
				$authors = $author;
				$initial=0;
		 } else
		 {
				$authors = $authors.", ".$author;
		 }
	}
	$halAuthors[$encodedTitle]=$authors;
	if(strlen($doi)>0){$halAuthors[$doi]=$authors;}

	//Saving full citation
	$halFullRef[$encodedTitle]=($entry->citationFull_s);
	if(strlen($doi)>0){$halFullRef[$doi]=($entry->citationFull_s);}
	//echo "<li>".$entry->halId_s." - ".normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($entry->title_s[0], 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'))."</li>";
	if (isset($entry->docType_s)) {$halDoctyp[$encodedTitle] = ($entry->docType_s);}
	if (isset($entry->arxivId_s)) {$halArxivs[$encodedTitle] = ($entry->arxivId_s);}
	if (isset($entry->pubmedcentralId_s)) {$halPubmed[$encodedTitle] = ($entry->pubmedcentralId_s);}

	$nbHAL+=1;

	if (isset($entry->subTitle_s[0])) {//Duplication of the entry with the reducted title
		$encodedTitle = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($titreInit, 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));

		$halId[$encodedTitle]=($entry->halId_s);

		//Saving the DOI
		if (isset($entry->doiId_s)) {$doi=strtolower($entry->doiId_s);}
		//if(strlen($doi)>0){$halId[$doi]=($entry->doiId_s);}
		if(strlen($doi)>0){$halId[$doi]=($entry->halId_s);}
		
		//Saving the publisher link
		if (isset($entry->publisherLink_s[0])) {$pubLink = normalize(strtolower($entry->publisherLink_s[0]));}
		if (strlen($pubLink)>0){$halId[$pubLink] = ($entry->publisherLink_s[0]);}
		
		//Saving the 'see also' field
		if (isset($entry->seeAlso_s)) {$seeAlso = normalize(strtolower($entry->seeAlso_s[0]));}
		if (strlen($seeAlso)>0){$halId[$seeAlso] = ($entry->seeAlso_s[0]);}

		//Saving the year
		if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
		{
			$halYears[$encodedTitle]=($entry->conferenceStartDateY_i);
			if(strlen($doi)>0){$halYears[$doi]=($entry->conferenceStartDateY_i);}
		}else
		{
			$halYears[$encodedTitle]=($entry->producedDateY_i);
			if(strlen($doi)>0){$halYears[$doi]=($entry->producedDateY_i);}
		}

		//Record number of HAL publications per year
		if (isset($entry->conferenceStartDateY_i) && $entry->conferenceStartDateY_i != "")//It's a communication
		{
			if(array_key_exists(($entry->conferenceStartDateY_i),$nbHalPerYear))
			{
				 $nbHalPerYear[$entry->conferenceStartDateY_i]+=1;
			} else
			{
				 $nbHalPerYear[$entry->conferenceStartDateY_i]=1;
			}
		}else
		{
			if(array_key_exists(($entry->producedDateY_i),$nbHalPerYear))
			{
				 $nbHalPerYear[$entry->producedDateY_i]+=1;
			} else
			{
				 $nbHalPerYear[$entry->producedDateY_i]=1;
			}
		}

		//Saving the title
		$titlePlus = $entry->title_s[0];
		$halTitles[$encodedTitle]=($titlePlus);
		if(strlen($doi)>0){$halTitles[$doi]=($titlePlus);}
		//Saving the publication location: journal, conference or book
		if(isset($entry->journalTitle_s) && strlen($entry->journalTitle_s)>0)
		{
			 $halWhere[$encodedTitle]=($entry->journalTitle_s);
			 if(strlen($doi)>0){$halWhere[$doi]=($entry->journalTitle_s);}
		}
		if(isset($entry->proceedings_s) && strlen($entry->proceedings_s)>0)
		{
			 $halWhere[$encodedTitle]=($entry->proceedings_s);
			 if(strlen($doi)>0){$halWhere[$doi]=($entry->proceedings_s);}
		}
		if(isset($entry->bookTitle_s) && strlen($entry->bookTitle_s)>0)
		{
			 $halWhere[$encodedTitle]=($entry->bookTitle_s);
			 if(strlen($doi)>0){$halWhere[$doi]=($entry->bookTitle_s);}
		}

		//Saving authors:
		$authors="";
		$initial = 1;
		foreach($entry->authFullName_s as $author)
		{
			 if ($initial==1){
					$authors = $author;
					$initial=0;
			 } else
			 {
					$authors = $authors.", ".$author;
			 }
		}
		$halAuthors[$encodedTitle]=$authors;
		if(strlen($doi)>0){$halAuthors[$doi]=$authors;}

		//Saving full citation
		$halFullRef[$encodedTitle]=($entry->citationFull_s);
		if(strlen($doi)>0){$halFullRef[$doi]=($entry->citationFull_s);}
		
		if (isset($entry->docType_s)) {$halDoctyp[$encodedTitle] = ($entry->docType_s);}
		if (isset($entry->arxivId_s)) {$halArxivs[$encodedTitle] = ($entry->arxivId_s);}
		if (isset($entry->pubmedcentralId_s)) {$halPubmed[$encodedTitle] = ($entry->pubmedcentralId_s);}
		
		$nbHAL+=1;
	 }
}

include("./Glob_normalize.php");

$halId=array();
$halDoctyp=array();
$halArxivs=array();
$halPubmed=array();
$halTitles=array();
$halFullRef=array();
$halYears=array();
$halAuthors=array();
$halWhere=array();



if(isset($_POST['hal']))
{
	 $hal = htmlspecialchars($_POST['hal']);
	 $team = htmlspecialchars($_POST['team']);
	 $teamInit = $team;
	 if ($limzot == "non")
	 {
		 echo "<a name='Chargement de la requête HAL'></a><h4>Chargement de la requête HAL - <a href='#Resultats'><em>Retour aux résultats</em></a></h4>";
		 echo "<div style='width: 900px; word-wrap: break-word;'>Requête : ".$hal."</div>";
	 }
	 $contents = file_get_contents($hal);
	 $results = json_decode($contents);

	 $nbHAL=0;
	 $nbHalPerYear=array();
	 //echo $hal;
	 //echo "<br/><br/>Résultats :<ul>";
	 
	 foreach($results->response->docs as $entry)
	 {
			$doi = "";
			$pubLink = "";
			$seeAlso = "";
			$titlePlus = "";
			$titreInit = $entry->title_s[0];
			//The title of the HAL file will be the main key to look for the article in HAL, we now simplify it (lowercase, no punctuation or spaces, etc.)
			//Does the title integrates a traduction with []?
			if(strpos($entry->title_s[0], "[") !== false && strpos($entry->title_s[0], "]") !== false)
			{
				$posi = strpos($entry->title_s[0], "[")+1;
				$posf = strpos($entry->title_s[0], "]");
				$tradTitle = substr($entry->title_s[0], $posi, $posf-$posi);
				$encodedTitle = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($tradTitle, 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));

			}else{
				//Does the title integrates a traduction with title_s[1]?
				if (isset($entry->title_s[1]) && $entry->title_s[1] != "") {
					$encodedTitle = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($entry->title_s[1], 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));
				}else{
					//Is there a subTitle ?
					$titlePlus = $entry->title_s[0];
					if (isset($entry->subTitle_s[0])) {
						$titreInit = $titlePlus;
						$titlePlus .= " : ".$entry->subTitle_s[0];
					}
					$encodedTitle = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($titlePlus, 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));
				}
			}
			
			storePrm($entry, $nbHAL, $doi, $pubLink, $seeAlso, $titlePlus, $titreInit, $encodedTitle, $halId, $nbHalPerYear, $halTitles, $halWhere, $halAuthors, $halFullRef, $halDoctyp, $halArxivs, $halPubmed, $halYears);
	 }
	 
	 //Recherche sur CRAC
	 $hal = str_replace("https://api.archives-ouvertes.fr/search/", "https://api.archives-ouvertes.fr/crac/hal/", $hal);
	 
	 $contents = file_get_contents($hal);
	 $results = json_decode($contents);
	 
	 foreach($results->response->docs as $entry)
	 {
			$doi = "";
			$pubLink = "";
			$seeAlso = "";
			$titlePlus = "";
			$titreInit = $entry->title_s[0];
			//The title of the HAL file will be the main key to look for the article in HAL, we now simplify it (lowercase, no punctuation or spaces, etc.)
			//Does the title integrates a traduction with []?
			if(strpos($entry->title_s[0], "[") !== false && strpos($entry->title_s[0], "]") !== false)
			{
				$posi = strpos($entry->title_s[0], "[")+1;
				$posf = strpos($entry->title_s[0], "]");
				$tradTitle = substr($entry->title_s[0], $posi, $posf-$posi);
				$encodedTitle = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($tradTitle, 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));
			}else{
				//Does the title integrates a traduction with title_s[1]?
				if (isset($entry->title_s[1]) && $entry->title_s[1] != "") {
					$encodedTitle=normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($entry->title_s[1], 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));
				}else{
					//Is there a subTitle ?
					$titlePlus = $entry->title_s[0];
					if (isset($entry->subTitle_s[0])) {
						$titreInit = $titlePlus;
						$titlePlus .= " : ".$entry->subTitle_s[0];
					}
					$encodedTitle = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($titlePlus, 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));
				}
			}
			
			//Si titre encodé absent du tableau $halId, c'est une nouvelle notice à considérer
			if (!array_key_exists($encodedTitle, $halId)) {
				storePrm($entry, $nbHAL, $doi, $pubLink, $seeAlso, $titlePlus, $titreInit, $encodedTitle, $halId, $nbHalPerYear, $halTitles, $halWhere, $halAuthors, $halFullRef, $halDoctyp, $halArxivs, $halPubmed, $halYears);
			}
	 }
	 
	 

	 //echo "</ul>";
	 if ($nbHAL >= 10000)//10000 results max.
	 {
		echo("<script type=\"text/javascript\">alert(\"Attention ! Votre requête HAL a atteint le seuil maximal des 10000 résultats interrogeables; vous devriez limiter la portée de la requête soit au niveau du corpus, soit en insérant des filtres (par année par exemple).\")</script>");
	 }
}

foreach ($souBib as $key => $subTab)
{
	if ($_FILES[$key]['error'] != 4)//File exists
	{
		$nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
		$typSep = $souBib[$key]["Sep"];// Type of separator of the CSV file
		$result[$key] = array();
		if ($limzot == "non")
		{
			echo "<a name='Chargement du fichier ".$nomSouBib."'></a><h4>Chargement du fichier ".$nomSouBib." - <a href='#Resultats'><em>Retour aux résultats</em></a></h4>";
		}
		//ini_set('auto_detect_line_endings',TRUE);

		if($key == "wos_html")
		{
			$handle = fopen("./HAL/wos_html.csv",'r');
		}else{
			if($key == "pubmed_html")
			{
				$handle = fopen("./HAL/pubmed_html.csv",'r');
			}else{
				if($key == "pubmed_xml")
				{
					$handle = fopen("./HAL/pubmed_xml.csv",'r');
				}else{
					if($key == "pubmed_txt")
					{
						$handle = fopen("./HAL/pubmed_txt.csv",'r');
					}else{
						if($key == "pubmed_fcgi")
						{
							$handle = fopen("./HAL/pubmed_fcgi.csv",'r');
						}else{
							if($key == "wos_txt")
							{
								$handle = fopen("./HAL/wos_txt.csv",'r');
							}else{
								$handle = fopen($_FILES[$key]['tmp_name'],'r');
							}
						}
					}
				}
			}
		}
		//removing empty lines with creation of a new xml file
		if (file_exists("./HAL/new_xml.csv")) {unlink("./HAL/new_xml.csv");}
		if ($handle) {
			$inF = fopen("./HAL/new_xml.csv", "w+");
			fseek($inF, 0);
			while (($buffer = fgets($handle)) !== false)
			{
				$tabElt = explode($typSep, $buffer);
				if(trim($tabElt[0]) != "")//the first cell must not be empty to consider the whole line
				{
					fwrite($inF, $buffer);
				}
			}
			fclose($inF);
		}else{
			die("<font color='red'><big><big>Votre fichier source est incorrect.</big></big></font>");
		}
		
		$handle = fopen("./HAL/new_xml.csv","r");
		//Extraction of the columns name
		if ($handle) {
			if (($data = fgetcsv($handle, 0, $typSep)) !== FALSE)
			{
				$imax = count($data);
				for ($i = 0; $i < $imax ; $i++)
				{
					$nomCol[$key][$i]=trim($data[$i], "\xBB..\xEF");//Suppression of some special ASCII characters
				}
			}
		}
		//var_dump($nomCol[$key]);
		//Construction of a table with column names and not indexes in case of modification of the global structure of the CSV file
		$handle = fopen("./HAL/new_xml.csv","r");
		$j = 0;
		if ($handle) {
			while (($data = fgetcsv($handle, 0, $typSep)) !== FALSE)
			{
				for ($i = 0; $i < $imax ; $i++)
				{
					if (!empty($data[0]))
					{
						//$result[$key][$j][$nomCol[$key][$i]]=trim($data[$i], "\xBB..\xEF");//Suppression of some special ASCII characters linked to CSV files
						$result[$key][$j][$nomCol[$key][$i]] = $data[$i];
					}else{
						$i--;
					}
				}
				$j++;
			}
		}
		//var_dump($result);
		if ($limzot == "non")
		{
			echo "<table>";
		}
		$nbPerYear[$key] = array();
		$nbNotFoundPerYear[$key] = array();
		$nbNotFound[$key] = array();
		$nbNotFound[$key][0] = 0;
		$papers[$key] = array();
		$nbMax = count($result[$key]);
		$colYear = $souBib[$key]["Year"];// Name of the column of the CSV file containing/representing the year
		$colTitle = $souBib[$key]["Title"];// Name of the column of the CSV file containing/representing the title
		$colDOI = $souBib[$key]["DOI"];// Name of the column of the CSV file containing/representing the DOI
		$colAuthors = $souBib[$key]["Authors"];// Name of the column of the CSV file containing/representing the authors
		$colSource = $souBib[$key]["Source"];// Name of the column of the CSV file containing/representing the source
		$colType = $souBib[$key]["Type"];// Name of the column of the CSV file containing/representing the type

		for ($nb = 1; $nb < $nbMax; $nb++)
		{
			$titreTest = $result[$key][$nb][$colTitle];
			//Exclude publications which title begins by "Erratum to" or "corrigendum"
			if (substr(strtolower($titreTest), 0, 10) != "erratum to" && substr(strtolower($titreTest), 0, 11) != "corrigendum") { 
				//Extract the type only for Scopus, Zotero or WoS communication
				$comm = "";
				$test = $result[$key][$nb][$colType];
				if ($test == "Conference Paper" || $test == "Conference Review" || $test == "conferencePaper" || $test == "S" || $test == "C") 
				{
				 $comm = "ok";
				}

				// Extract the year
				if ($key == "pubmed_csv")
				{
				 $yearPaper = substr($result[$key][$nb][$colYear], -4, 4);
				}else{
					if ($key == "pubmed_txt")
					{
					 $yearPaper = substr($result[$key][$nb][$colYear], 0, 4);
					}else{
						if ($key != "wos_csv" && $key != "wos_txt")
						{
						 $yearPaper = $result[$key][$nb][$colYear];
						}else{
							if (isset($result[$key][$nb][$colYear]) && $result[$key][$nb][$colYear] != "")
							{
								$yearPaper = $result[$key][$nb][$colYear];
							}else{
								if (isset($result[$key][$nb]['EY']) && $result[$key][$nb]['EY'] != "")
								{
									$yearPaper = $result[$key][$nb]['EY'];
								}else{
									if (isset($result[$key][$nb]['PY']) && $result[$key][$nb]['PY'] != "")
									{
										$yearPaper = $result[$key][$nb]['PY'];
									}else{
										if (isset($result[$key][$nb]['EA']) && $result[$key][$nb]['EA'] != "")
										{
											$yearPaper = substr($result[$key][$nb]['EA'], -4);
										}
									}
								}
							}
						}
					}
				}
				// Extract the revue
				if ($key == "pubmed_csv")
				{
				 $revuePaper = substr($result[$key][$nb][$colSource], 0, -6);
				}else{
				 $revuePaper = $result[$key][$nb][$colSource];
				}

				// Extract the DOI
				if ($key == "pubmed_csv")
				{
				 if (strpos($result[$key][$nb][$colDOI], "doi:") !== false) // DOI found
				 {
						$pos0 = strpos($result[$key][$nb][$colDOI], "doi:");
						if (strpos($result[$key][$nb][$colDOI], "pii:") !== false) // Existing previous characters before DOI
						{
							$pos1 = strpos($result[$key][$nb][$colDOI], " ", $pos0+10)+1;;
						}
						else
						{
							$pos1 = $pos0 + 5;
						}
						$pos2 = strpos($result[$key][$nb][$colDOI], " ", $pos1+1)-1;
						if ($pos2 == -1) {$pos2 = strlen($result[$key][$nb][$colDOI])-1;}// No blank space after the beginning of the DOI and the end of the string
						$doi = strtolower(substr($result[$key][$nb][$colDOI], $pos1, $pos2-$pos1));
				 }
				 else
				 {
						$doi = "";
				 }
				}else{
				 $doi = strtolower($result[$key][$nb][$colDOI]);
				}

				// Increment the number of papers for the year of this paper
				if (is_numeric($yearPaper))
				{
				 if(array_key_exists($yearPaper,$nbPerYear[$key]))
				 {
						$nbPerYear[$key][$yearPaper]+=1;
				 } else
				 {
						$nbPerYear[$key][$yearPaper]=1;
				 }
				}

				// Extract the  title
				$Title = mb_convert_encoding(mb_strtolower(mb_convert_encoding(str_replace(", Abstract", "", $result[$key][$nb][$colTitle]), 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1');
				$tabTitle = explode(":", $Title);
				$redTitle = normalize(trim($tabTitle[0]));
				//echo array_key_exists('',$halTitles).'-<br>';
				//echo 'toto : '.$foundInHAL.' - '. $Title.'<br>';
				//print_r($halTitles);

				// Extract the English and French title if found
				$englishTitle="";
				$frenchTitle="";
				$words=preg_split('/[\[]+/u',$Title);
				if(sizeof($words)>1)
				{
					$englishTitle=normalize($words[0]);
					$frenchTitle=normalize($words[1]);
				} else
				{
					$Title=normalize($Title);
				}
				$foundInHAL=FALSE;
				//var_dump($result);

				//echo "<li><span style=\"background-color:#FFEEEE\">"" (".$data[2].") ".$data[1]." - <em>".$data[3]."</em></span>";

				// Trying to match with DOI
				if($doi != "" and array_key_exists($doi,$halTitles))
				{
				 if ($limzot == "non")
				 {
					 echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
					 echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$doi]."\">&hearts;</a></td><td valign='top'>".$halYears[$doi]."</td><td valign='top'>".$halAuthors[$doi]."</td><td valign='top'>".$halTitles[$doi][0]."</td><td valign='top'>".$halWhere[$doi]."</td><td valign='top'>DOI match</td></tr>";
				 }
				 $foundInHAL=TRUE;
				}

				// Trying to match with full title
				if((!$foundInHAL) and ($Title != "" and (array_key_exists($Title,$halTitles))) OR ($redTitle != "" and (array_key_exists($redTitle,$halTitles))))
				{
				 if ($limzot == "non")
				 {
					 echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
					 echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$Title]."\">&hearts;</a></td><td valign='top'>".$halYears[$Title]."</td><td valign='top'>".$halAuthors[$Title]."</td><td valign='top'>".$halTitles[$Title][0]."</td><td valign='top'>".$halWhere[$Title]."</td><td valign='top'>full title match</td></tr>";
				 }
				 $foundInHAL=TRUE;
				}

				// Trying to match with english title
				if((!$foundInHAL) and $englishTitle != "" and (array_key_exists($englishTitle,$halTitles)))
				{
				 if ($limzot == "non")
				 {
					 echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
					 echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$englishTitle]."\">&hearts;</a></td><td valign='top'>".$halYears[$englishTitle]."</td><td valign='top'>".$halAuthors[$englishTitle]."</td><td valign='top'>".$halTitles[$englishTitle][0]."</td><td valign='top'>".$halWhere[$englishTitle]."</td><td valign='top'>english title match</td></tr>";
				 }
				 $foundInHAL=TRUE;
				}

				// Trying to match with other language title
				if((!$foundInHAL) and $frenchTitle != "" and (array_key_exists($frenchTitle,$halTitles)))
				{
				 if ($limzot == "non")
				 {
					 echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
					 echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$frenchTitle]."\">&hearts;</a></td><td valign='top'>".$halYears[$frenchTitle]."</td><td valign='top'>".$halAuthors[$frenchTitle]."</td><td valign='top'>".$halTitles[$frenchTitle][0]."</td><td valign='top'>".$halWhere[$frenchTitle]."</td><td valign='top'>french title match</td></tr>";
				 }
				 $foundInHAL=TRUE;
				}
				
				//Trying to match with publisherLink or seeAlso
				if ($key == "zotero")
				{
					$Url = normalize(strtolower($result[$key][$nb]["Url"]));
					if((!$foundInHAL) and $Url != "" and (array_key_exists($Url,$halId)))
					{
					 if ($limzot == "non")
					 {
						 echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
						 echo "<tr style=\"background-color:#EEFFEE\"><td valign='top'><a target=\"_blank\" href=\"http://hal.archives-ouvertes.fr/".$halId[$frenchTitle]."\">&hearts;</a></td><td valign='top'>".$halYears[$frenchTitle]."</td><td valign='top'>".$halAuthors[$frenchTitle]."</td><td valign='top'>".$halTitles[$frenchTitle][0]."</td><td valign='top'>".$halWhere[$frenchTitle]."</td><td valign='top'>french title match</td></tr>";
					 }
					 $foundInHAL=TRUE;
					}
				}

				// Search for possible duplicates for communications with year paper and year conference
				/*
				if($comm == "ok" && $foundInHAL === TRUE)
				{
				 $encodedTitle=normalize($Title);
				 if ($yearPaper != $halYears[$encodedTitle]) //duplicate
				 {
					 $foundInHAL=FALSE;
				 }
				}
				*/
				
				//if docType_s = "UNDEFINED", the notice must be included
				if (isset($halDoctyp[$Title]) && $halDoctyp[$Title] == "UNDEFINED") {
					$foundInHAL = FALSE;
				}
				
				//if link arxiv or pubmed, the notice must be included
				if ((isset($halArxivs[$Title]) && $halArxivs[$Title] != "") || (isset($halPubmed[$Title]) && $halPubmed[$Title] != "")) {
					$foundInHAL = FALSE;
				}
				
				if(!$foundInHAL)
				{
				 if ($limzot == "non")
				 {
					 echo "<tr><td valign='top'></td><td valign='top'>".$yearPaper."</td><td valign='top'>".$result[$key][$nb][$colAuthors]."</td><td valign='top'>".$result[$key][$nb][$colTitle]."</td><td valign='top'>".$revuePaper."</td></tr>";
				 }
				 $nbNotFound[$key][0] += 1;
				 array_push($papers[$key],$result[$key][$nb]);
				 if(array_key_exists($yearPaper,$nbNotFoundPerYear[$key]))
				 {
						$nbNotFoundPerYear[$key][$yearPaper]+=1;
				 } else
				 {
						$nbNotFoundPerYear[$key][$yearPaper]=1;
				 }
				}
			}
		}
		if ($limzot == "non")
		{
			echo "</table>";
		}
		ini_set('auto_detect_line_endings',FALSE);
		$limTEI = 50;
		?>
		<a name='Références de <?php echo $nomSouBib;?> non trouvées dans HAL'></a><h4>Références de <?php echo $nomSouBib;?> non trouvées dans HAL - <a href='#Resultats'><em>Retour aux résultats</em></a></h4>
		<p><strong>Attention, il est possible que la référence soit présente dans HAL mais qu'elle n'ait pas été trouvée en raison d'une légère différence dans le titre.</strong>
		<br>
		Par ailleurs, les notices avec plus de <?php echo $limTEI;?> auteurs ne seront pas prises en compte dans l'export TEI et apparaîtront <s>barrées</s>.</p>
		<?php
		//var_dump($papers[$key]);
		if (!empty($papers[$key])) {//Affichage des tableaux uniquement si présence de résultats !
			if ($desactivSR != "oui")
			{
				echo "<table border='1px' cellpadding='5px' cellspacing='5px' style='border-collapse: collapse' bordercolor='#eeeeee'>";
				echo "<tr>";
				echo "<td colspan='9'><strong>Informations diverses collectées grâce à l'API SHERPA/RoMEO</strong></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td align='center'><img alt='Emargo 6 mois' src='./img/embargo-6.jpg'></td>";
				echo "<td align='center'><img alt='Emargo 12 mois' src='./img/embargo-12.jpg'></td>";
				echo "<td align='center'><img alt='Emargo 24 mois' src='./img/embargo-24.jpg'></td>";
				echo "<td align='center'><img alt='Emargo possible sous certaines conditions' src='./img/embargo-e.jpg'></td>";
				echo "<td align='center'><img alt='Dépôt du post-print auteur autorisé' src='./img/post-print.png'></td>";
				echo "<td align='center'><img alt='Dépôt du PDF éditeur autorisé' src='./img/pdf.png'></td>";
				echo "<td align='center'><img alt='Aucun dépôt autorisé' src='./img/red-cross.png'></td>";
				echo "<td align='center'><img alt='Journal non référencé dans Sherpa-Romeo' src='./img/ungraded.png'></td>";
				echo "<td align='center'><img alt='Journal open access référencé dans le DOAJ (Directory of Open Access Journals)' src='./img/doaj.png'></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td valign='top' colspan='3'><small>Embargo sur la diffusion 6 mois, 12 mois ou 24 mois</small></td>";
				echo "<td valign='top' ><small>Embargo possible, voir conditions</small></td>";
				echo "<td valign='top' ><small>Dépôt du post-print auteur autorisé</small></td>";
				echo "<td valign='top' ><small>Dépôt du PDF éditeur autorisé</small></td>";
				echo "<td valign='top' ><small>Aucun dépôt autorisé</small></td>";
				echo "<td valign='top' ><small>Journal non référencé dans Sherpa-Romeo</small></td>";
				echo "<td valign='top' ><small>Journal open access référencé dans le DOAJ (Directory of Open Access Journals)</small></td>";
				echo "</tr>";
				echo "</table><br>";
			}
			$k = 1;
			echo "<table class='table table-responsive table-bordered table-centered'>";
			echo "<thead class='thead-dark'>";
			echo "<tr>";
			echo "<th>&nbsp;</th>";
			if ($desactivSR == "non")
			{
				echo"<th valign='top'><h4>Pprint</h4></th><th valign='top'><h4>PDF</h4></th><th valign='top'><h4>Doaj</h4></th>";
			}
			echo "<th valign='top'><h4>Références</h4></th><th valign='top'><h4>DOI</h4></th><th valign='top'><h4>Source</h4></th><th valign='top'><h4>Joker</h4></th>";
			if ($activMailsM == "oui" || $activMailsP == "oui")
			{
				echo "<th align='center' colspan='2' valign='top'><h4>Mails</h4></th>";
			}
			echo "</tr>";
			$cst = 0;
			$listTit = "¤";
			foreach($papers[$key] as $key2 => $data)
			{
				$imgMailM = "";
				$imgMailP = "";
				// Extract the year and the revue
				if ($key == "pubmed_csv")
				{
				 $yearPaper = substr($data[$colYear], -4, 4);
				 $revuePaper = substr($data[$colSource], 0, -6);
				}else{
					if ($key == "pubmed_txt")
					{
					 $yearPaper = substr($data[$colYear], 0, 4);
					}else{
						if ($key != "wos_csv" && $key != "wos_txt")
						{
						 $yearPaper = $data[$colYear];
						}else{
							if (isset($data[$colYear]) && $data[$colYear] != "")
							{
								$yearPaper = $data[$colYear];
							}else{
								if (isset($data['EY']) && $data['EY'] != "")
								{
									$yearPaper = $data['EY'];
								}else{
									if (isset($data['PY']) && $data['PY'] != "")
									{
										$yearPaper = $data['PY'];
									}else{
										if (isset($data['EA']) && $data['EA'] != "")
										{
											$yearPaper = substr($data['EA'], -4);
										}
									}
								}
							}
						}
					}
					$revuePaper = $data[$colSource];
				}
				// Trying to retrieve the ISSN number and the DOI
				$url = "";
				$resdoi = "";
				$refdoi = "";
				switch($key)
				{
					case "wos_csv":
					case "wos_txt":
						if ($papers[$key][$key2]['SN'] != "")
						{
							$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$papers[$key][$key2]['SN'].'"]]';
						}
						if ($papers[$key][$key2]['DI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DI'];
						}
						break;
					case "wos_html":
					case "pubmed_xml":
					case "pubmed_txt":
					case "pubmed_fcgi":
					case "zotero":
					case "openalex":
						if ($papers[$key][$key2]['ISSN'] != "")
						{
							$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$papers[$key][$key2]['ISSN'].'"]]';
						}
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						break;
					case "scifin":
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						if ($papers[$key][$key2]['Internat.Standard Doc. Number'] != "")
						{
							$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$papers[$key][$key2]['Internat.Standard Doc. Number'].'"]]';
						}
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						break;
					case "pubmed_zotero":
						if ($papers[$key][$key2]['ISSN'] != "")
						{
							$issn = $papers[$key][$key2]['ISSN'];
							if (strpos($issn, "-") === false)//No '-' in the ISSN number > 16310691 for example
							{
								$issn = substr($issn, 0, 4)."-".substr($issn, -4);
							}
							if (strlen($issn) > 9)//Several ISSN numbers > 0031-9007, 1079-7114 for example
							{
								$issn = substr($issn, 0, 9);
							}
							$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$issn.'"]]';
						}
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						break;
					case "pubmed_html":
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						$revuePaper2 = str_replace(" ", "%20", $revuePaper);
						$urlinit = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["title","equals",".'.str_replace('&amp;', '%26', $revuePaper2).'"]]';
						$raw_data = file_get_contents($urlinit);
						$jSON = json_decode($raw_data, true);
						$resultat = array2xml($jSON, false);
						$dom = new DOMDocument();
						$dom->loadXML($resultat);
						$issn = "";
						$res = $dom->getElementsByTagName("issn");//ISSN
						if ($res -> length > 0) {
							$issn = $res->item(0)->nodeValue;
							if ($issn != "")
							{
								$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$issn.'"]]';
							}
						}
						break;
					case "scopus":
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						$revuePaper2 = str_replace(" ", "%20", $revuePaper);
						$urlinit = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["title","equals",".'.str_replace('&amp;', '%26', $revuePaper2).'"]]';
						$raw_data = file_get_contents($urlinit);
						$jSON = json_decode($raw_data, true);
						$resultat = array2xml($jSON, false);
						$dom = new DOMDocument();
						$dom->loadXML($resultat);
						$issn = "";
						$res = $dom->getElementsByTagName("issn");//ISSN
						if ($res -> length > 0) {
							$issn = $res->item(0)->nodeValue;
							if ($issn != "")
							{
								$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$issn.'"]]';
							}
						}
						break;
					case "pubmed_csv":
						if (strpos($papers[$key][$key2]['Details'], "doi:") !== false) // DOI found
						{
							$pos0 = strpos($papers[$key][$key2]['Details'], "doi:");
							if (strpos($papers[$key][$key2]['Details'], "pii:") !== false) // Existing previous characters before DOI
							{
								$pos1 = strpos($papers[$key][$key2]['Details'], " ", $pos0+10)+1;;
							}
							else
							{
								$pos1 = $pos0 + 5;
							}
							$pos2 = strpos($papers[$key][$key2]['Details'], " ", $pos1+1)-1;
							if ($pos2 == -1) {$pos2 = strlen($papers[$key][$key2]['Details'])-1;}// No blank space after the beginning of the DOI and the end of the string
							$resdoi = substr($papers[$key][$key2]['Details'], $pos1, $pos2-$pos1);
							$refdoi = trim($resdoi);
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".trim($resdoi)."\"><img alt='DOI' src='./img/doi.jpg'></a>";
						}
						$revuePaper2 = str_replace(" ", "%20", $revuePaper);
						$urlinit = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["title","equals",".'.str_replace('&amp;', '%26', $revuePaper2).'"]]';
						$raw_data = file_get_contents($urlinit);
						$jSON = json_decode($raw_data, true);
						$resultat = array2xml($jSON, false);
						$dom = new DOMDocument();
						$dom->loadXML($resultat);
						$issn = "";
						$res = $dom->getElementsByTagName("issn");//ISSN
						if (isset($res->item(0)->nodeValue)) {$issn = $res->item(0)->nodeValue;}
						if ($issn != "")
						{
							$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["issn","equals",".'.$issn.'"]]';
						}
						break;
					case "dimensions":
						if ($papers[$key][$key2]['DOI'] != "")
						{
							$resdoi = "<a target=\"_blank\" href=\"https://doi.org/".$papers[$key][$key2]['DOI']."\"><img alt='DOI' src='./img/doi.jpg'></a>";
							$refdoi = $papers[$key][$key2]['DOI'];
						}
						break;
				}
				if ($desactivSR != "oui")
				{
					//var_dump($papers);
					if ($url == "")
					{
						$revuePaper2 = str_replace(" ", "%20", $revuePaper);
						$url = 'https://v2.sherpa.ac.uk/cgi/retrieve?item-type=publication&api-key='.$akSR.'&format=Json&filter=[["title","equals",".'.str_replace('&amp;', '%26', $revuePaper2).'"]]';
					}
					$raw_data = file_get_contents($url);
					$jSON = json_decode($raw_data, true);
					$resultat = array2xml($jSON, false);
					$dom = new DOMDocument();
					$dom->loadXML($resultat);
					$res = $dom->getElementsByTagName("outcome");//If the review is not found
					$resout = $res->item(0)->nodeValue;
					if ($resout != "notFound")//review has been found
					{
						$respar = "";
						$respre = "";
						$res = $dom->getElementsByTagName("postarchiving");//postarchiving conditions
						$respar = $res->item(0)->nodeValue;
						//echo $issn.' - '.$respar.'<br>';
						$res = $dom->getElementsByTagName("postrestrictions");
						if (isset($res))//Is there postrestriction ?
						{
							$respre = $res->item(0)->nodeValue;;
						}
						switch($respar)
						{
							case "restricted":
								if (strpos($respre, "6") !== false)//Embargo 6 mois
								{
									$respar = "<img alt='Voir légende ci-dessus' src='./img/embargo-6.jpg'>";
								}
								if (strpos($respre, "12") !== false)//Embargo 12 mois
								{
									$respar = "<img alt='Voir légende ci-dessus' src='./img/embargo-12.jpg'>";
								}
								if (strpos($respre, "24") !== false)//Embargo 24 mois
								{
									$respar = "<img alt='Voir légende ci-dessus' src='./img/embargo-24.jpg'>";
								}
								break;
							case "can":
								$respar = "<img alt='Voir légende ci-dessus' src='./img/post-print.png'>";
								break;
							case "unknown":
								$respar = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
								break;
							case "cannot":
								$respar = "<img alt='Voir légende ci-dessus' src='./img/red-cross.png'>";
								break;
						}
						$rescdt = "";
						$res = $dom->getElementsByTagName("condition");
						for ($c = 0; $c < $res->length; $c++)
						{
							$rescdt .= "&bull; ".$res->item($c)->nodeValue."<br>";
						}
						$rescdt = str_replace("'", "&apos;", $rescdt);
						$rescdt = str_replace('"', '\"', $rescdt);
						if ((strpos($rescdt, "embargo") !== false || $respar == "restricted") && (strpos($respre, "6") === false && strpos($respre, "12") === false && strpos($respre, "24") === false && $respar != "<img alt='Voir légende ci-dessus' src='./img/red-cross.png'>"))
						{
							$respar = "<img alt='Voir légende ci-dessus' src='./img/embargo-e.jpg'>";
						}
						$urlisn = "";
						$resisn = "";
						$res = $dom->getElementsByTagName("issn");//ISSN
						$resisn = $res->item(0)->nodeValue;
						if ($resisn != "")
						{
							$urlisn = "http://www.sherpa.ac.uk/romeo/search.php?issn=".$resisn."&ak=kn8p0JxcxGc";
						}
						$respdf = "";
						$res = $dom->getElementsByTagName("pdfarchiving");//PDF archiving conditions
						$respdf = $res->item(0)->nodeValue;
						switch($respdf)
						{
							case "cannot":
								$respdf = "<img alt='Voir légende ci-dessus' src='./img/red-cross.png'>";
								break;
							case "can":
								$respdf = "<img alt='Voir légende ci-dessus' src='./img/pdf.png'>";
								break;
							case "unknown":
								$respdf = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
								break;
							case "restricted":
								$respdf = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
								break;
						}
						$resdiv = "";//Divers
						if ($resisn != "")
						{
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $urlisn);
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
							$temp = curl_exec($ch);
							curl_close($ch);
							if (strpos($temp, "DOAJ") !== false)//DOAJ
							{
								$resdiv = "<a target='_blank' href='https://doaj.org/'><img alt='DOAJ' src='./img/doaj.png'></a>";
								$imgMailM = "./img/bouton-ccby.jpg";
								$imgMailP = "./img/bouton-ccby.jpg";
							}
						}
					}else{
						$urlisn = "OverHAL_noresult.php";
						$rescdt = "";
						$respar = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
						$respdf = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
						$resdiv = "";
						//$resdoi = "";
						//$refdoi = "";
					}
					if ($respar == "")//No information found for postarchiving
					{
						$respar = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
					}
					if ($respdf == "")//No information found for PDF archiving
					{
						$respdf = "<img alt='Voir légende ci-dessus' src='./img/ungraded.png'>";
					}
					//echo "<tr><td colspan='4'>".$k." - ".$url."</td></tr>";
				}
				echo "<tbody><tr>";
				echo "<td valign=\"top\">".$k."</td>";
				if ($desactivSR == "non")
				{
					echo "<td align='center' valign='top'>";
					if ($resisn != "")
					{
						echo "<a target=\"_blank\" href='".$urlisn."' onmouseover='return overlib(\"".$rescdt."\",STICKY,CAPTION, \"&nbsp;<em>".$revuePaper."</em> conditions\",WIDTH, 320,HEIGHT, 130,RELX, 150,BORDER, 2,FGCOLOR,\"#FFFFFF\",BGCOLOR,\"#009933\",TEXTCOLOR,\"#000000\",CAPCOLOR,\"#000000\",CLOSECOLOR,\"#FFFFFF\");' onmouseout='return nd();'>".$respar."</a></td>";
					}else{
						echo "<a onmouseover='return overlib(\"".$rescdt."\",STICKY,CAPTION, \"&nbsp;<em>".$revuePaper."</em> conditions\",WIDTH, 320,HEIGHT, 130,RELX, 150,BORDER, 2,FGCOLOR,\"#FFFFFF\",BGCOLOR,\"#009933\",TEXTCOLOR,\"#000000\",CAPCOLOR,\"#000000\",CLOSECOLOR,\"#FFFFFF\");' onmouseout='return nd();'>".$respar."</a></td>";
					}
					echo "<td align='center' valign='top'>".$respdf."</td>";
					echo "<td align='center' valign='top'>".$resdiv."</td>";
				}
				$refdoiaff = "";
				if ($refdoi != "")
				{
					$refdoiaff = " - doi: <a target=\"_blank\" href=\"https://doi.org/".$refdoi."\">https://doi.org/".$refdoi."</a>";
				}
				//if more than 50 authors, just show the 10th first
				if (strpos($data[$colAuthors], '~|~') !== false) {
					$nbaut = explode("~|~", $data[$colAuthors]);
				}else{
					$nbaut = explode(";", $data[$colAuthors]);
				}
				if (count($nbaut) > $limTEI) {//If more than $limTEI authors, records will not be taken into account in the TEI export and we have to adopt a special marking
				 $deb = "<s>";
				 $fin = "</s>";
				}else{
				 $deb = "";
				 $fin = "";
				}
				if (count($nbaut) > 50)
				{
					$affaut = "";
					for ($i = 0; $i <=10; $i++)
					{
						$affaut .= $nbaut[$i].";";
					}
					$affaut .= " <em> et al.</em>";
				}else{
					if (strpos($data[$colAuthors], '~|~') !== false) {
						$affaut = str_replace('~|~', ', ', $data[$colAuthors]);
					}else{
						$affaut = $data[$colAuthors];
					}
				}
				echo "<td align='justify' valign='top'>".$deb.$affaut." (".$yearPaper.") <a target=\"_blank\" href=\"https://scholar.google.fr/scholar?hl=fr&q=".netCode($st=strtr($data[$colTitle],'"<>','   '))."\">".$data[$colTitle]."</a> - <em>".$revuePaper."</em>".$refdoiaff.$fin."</td>";
				echo "<td align='center' valign='top'>".$resdoi."</td>";
				//source column
				$linkSource = "";
				switch($key)
				{
					case "scopus":
						$linkSource = "<a target=\"_blank\" href=\"".$papers[$key][$key2]['Link']."\"><img alt='Scopus' src=\"./img/scopus3.png\"></a>";
						break;
					case "pubmed_csv":
						$linkSource = "<a target=\"_blank\" href=\"http://www.ncbi.nlm.nih.gov/pubmed/".$papers[$key][$key2]['EntrezUID']."\"><img alt='Pubmed' src=\"./img/pubmed.png\"></a>";
						break;
					case "pubmed_xml":
					case "pubmed_txt":
						$linkSource = "<a target=\"_blank\" href=\"http://www.ncbi.nlm.nih.gov/pubmed/".$papers[$key][$key2]['PMID']."\"><img alt='Pubmed' src=\"./img/pubmed.png\"></a>";
						break;
					case "pubmed_fcgi":
						$linkSource = "<a target=\"_blank\" href=\"http://www.ncbi.nlm.nih.gov/pubmed/".$papers[$key][$key2]['Pubmed']."\"><img alt='Pubmed' src=\"./img/pubmed.png\"></a>";
						break;
					case "openalex":
						$linkSource = "<a target=\"_blank\" href=\"".$papers[$key][$key2]['Source']."\"><img alt='OpenAlex' src=\"./img/openalex.png\"></a>";
						break;
					case "zotero":
						$linkSource = "<a target=\"_blank\" href=\"".$papers[$key][$key2]['Link Attachments']."\"><img alt='Zotero' src=\"./img/zotero_128.png\"></a>";
						break;
					case "wos_csv":
					case "wos_txt":
						$linkSource = "<a target=\"_blank\" href=\"http://ws.isiknowledge.com/cps/openurl/service?url_ver=Z39.88-2004&rft_id=info:ut/".str_replace("WOS:", "",$papers[$key][$key2]['UT'])."\"><img alt='WoS' src=\"./img/wos.png\"></a>";
						break;
					case "dimensions":
						$linkSource = "<a target=\"_blank\" href=\"https://app.dimensions.ai/details/publication/".$papers[$key][$key2]['Publication ID']."\"><img alt='Dimensions' src=\"./img/dimensions.png\"></a>";
						break;
				}
				echo "<td align='center' valign='top'>".$linkSource."</td>";
				//joker column
				$linkJoker = "";
				if ($refdoi != "" && $joker != "")
				{
					$linkJoker = "<a target=\"_blank\" href=\"".$joker.$refdoi."\"><img alt='Scihub' src=\"./img/scihub.png\"></a>";
				}
				echo "<td align='center' valign='top'>".$linkJoker."</td>";
				//mail column
				if ($activMailsM == "oui" || $activMailsP == "oui")
				{
					$linkMailM = "";
					$linkMailP = "";
					$adr = "";
					$noadr = "";
					$lang = "";
					switch($key)
					{
						case "scopus":
							if (strpos($papers[$key][$key2]['Correspondence Address'], "@") !== false)
							{
								$pos = strpos($papers[$key][$key2]['Correspondence Address'], "email:");
								$adr = substr($papers[$key][$key2]['Correspondence Address'], ($pos + 6), strlen($papers[$key][$key2]['Correspondence Address']));
								$adr= str_replace(" ", "", $adr);
							}else{
								$noadr = "no mail";
							}
							break;
						case "pubmed_csv":
						case "openalex":
							break;
						case "zotero":
							$team = "";
							if ($papers[$key][$key2]['Rights'] != "")
							{
								$adr = str_replace(" ", "", $papers[$key][$key2]['Rights']);
								$coll = str_replace(" ", "", $papers[$key][$key2]['Call Number']);
								if ($coll != "") {$team = $coll;}
							}else{
								$noadr = "no mail";
							}
							break;
						case "wos_csv":
							if ($papers[$key][$key2]['EM'] != "")
							{
								$adr = str_replace(" ", "", $papers[$key][$key2]['EM']);
							}else{
								$noadr = "no mail";
							}
							break;
					}
					//Does sending mail to this author should be ignored
					include "./OverHAL_mails_a_exclure.php";
					foreach ($EXCLMAILS_LISTE as $value) {
						if (strtolower($adr) == strtolower($value)) {
							$noadr = "mail to be ignored";
							break;
						}
					}
					//Does sending mail's domain to this author should be ignored
					include "./OverHAL_domaines_a_exclure.php";
					foreach ($EXCLDOMS_LISTE as $value) {
						if (stripos($adr, $value) !== false) {
							$noadr = "domain of mail to be ignored";
							break;
						}
					}
					//Does a mail have already be sent to the corresponding author for this doi reference?
					$titreNorm = normalize(mb_convert_encoding(mb_strtolower(mb_convert_encoding($data[$colTitle], 'ISO-8859-1', 'UTF-8')), 'UTF-8', 'ISO-8859-1'));
					include ('./OverHAL_mails_envoyes.php');
					$nouvelEnvoiM = "non";
					$nouvelEnvoiP = "non";
					$mailOK = "";
					$file = "";

					if ($key == "zotero" && isset($papers[$key][$key2]['Call Number']) && $papers[$key][$key2]['Call Number'] == "ISCR-AP") {
						//Blocage du mécanisme de nouvel envoi si Zotero et 'ISCR-AP'
					}else{
						foreach($MAILS_LISTE AS $i => $valeur) {
							if ($refdoi != "" && strtolower($MAILS_LISTE[$i]["quoi2"]) == strtolower($refdoi)) //mail already send
							{
								$mailOK = "OK";
							}
							if ($adr != "" && strtolower($MAILS_LISTE[$i]["qui"]) == strtolower($adr))
							{
								$nouvelEnvoiM = "oui";
								$nouvelEnvoiP = "oui";
								$lang = strtoupper($MAILS_LISTE[$i]["lang"]);
							}
						}
					}
					//Language to use for the mail
					$mailValide = "ok";
					if ($lang == "" && $adr != "")
					{
						$lang = "FR";//French initially
						//Is there different adresses for the mail contact?
						if ((strpos($adr, ";") !== false) || (strpos($adr, ",") !== false))
						{
							if (strpos($adr, ";") !== false)
							{
								$adrMail = explode(";", $adr);
							}else{
								$adrMail = explode(",", $adr);
							}
							foreach ($adrMail as $a)
							{
								$dcp = explode("@", $a);
								//var_dump($dcp);
								if (strpos($dcp[1], ".") === false)
								{
									$mailValide = "non";
									break;
								}
								if (substr($a, -2) != "fr")
								{
									$lang = "EN";
									break;
								}
							}
						}else{
							if (substr($adr, -2) != "fr")
								{
									$dcp = explode("@", $adr);
									if (strpos($dcp[1], ".") === false)
									{
										$mailValide = "non";
									}
									$lang = "EN";
								}
						}
					}
					//Si interrogation sur UNIV-RENNES1, si Zotero (CSV) et si 'Language' = FR, alors, mail en français
					if ($teamInit == "UNIV-RENNES1" && $key == "zotero" && $papers[$key][$key2]['Language'] == "FR") {$lang = "FR";}
					//Stats par laboratoire
					$callNumber = "";
					if ($key == "zotero" && isset($papers[$key][$key2]['Call Number'])) {$callNumber = $papers[$key][$key2]['Call Number'];}
					
					if ($mailOK == "")
					{
						if ($nouvelEnvoiM == "non")
						{
							if ($lang == "FR")
							{
								if ($callNumber == "ISCR-AP") {include "./OverHAL_mails_premier_fr_ISCR-AP.php";}else{include "./OverHAL_mails_premier_fr.php";}
							}else{
								include "./OverHAL_mails_premier_en.php";
							}
							if ($imgMailM == "")
							{
								if ($callNumber == "ISCR-AP") {$imgMailM = "./img/bouton-m-iscr-ap.jpg";}else{$imgMailM = "./img/bouton-m.jpg";}
							}
							//Titre = $data[$colTitle]
							$linkMailM = "<div id=\"".$titreNorm."M\"><a href=\"#".$titreNorm."\" onClick=\"majMailsM('".$adr."','".$titreNorm."','".$refdoi."','M','','".strtoupper($lang)."','".$callNumber."','".str_replace("'", "\'", $data[$colTitle])."'); mailto('".$file."','".$adr."','".$subjectM."','".$bodyM."');\"><img alt='".$adr."' title='".$adr."' src='".$imgMailM."'></a></div>";
						}else{//new solicitation
							if ($lang == "FR")
							{
								include "./OverHAL_mails_rappel_fr.php";
							}else{
								include "./OverHAL_mails_rappel_en.php";
							}
							if ($imgMailM == "")
							{
								if ($callNumber == "ISCR-AP") {$imgMailM = "./img/bouton-m-iscr-ap.jpg";}else{$imgMailM = "./img/bouton-m.jpg";}
							}
							$linkMailM = "<div id=\"".$titreNorm."M\"><a href=\"#".$titreNorm."\" onClick=\"majMailsM('".$adr."','".$titreNorm."','".$refdoi."','M','','".strtoupper($lang)."','".$callNumber."','".str_replace("'", "\'", $data[$colTitle])."'); mailto('".$file."','".$adr."','".$subjectM."','".$bodyM."');\"><img alt='".$adr."' title='".$adr."' src='".$imgMailM."'></a></div>";
						}
						if ($nouvelEnvoiP == "non")
						{
							if ($lang == "FR")
							{
								if ($callNumber == "ISCR-AP") {include "./OverHAL_mails_premier_fr_ISCR-AP.php";}else{include "./OverHAL_mails_premier_fr.php";}
							}else{
								include "./OverHAL_mails_premier_en.php";
							}
							if ($imgMailP == "")
							{
								if ($callNumber == "ISCR-AP") {
									$imgMailP = "./img/bouton-p-iscr-ap.jpg";
									if ($key == "zotero" && !empty($papers[$key][$key2]['File Attachments'])) {$file = str_replace('\\', '\\\\', $papers[$key][$key2]['File Attachments']);}
								}else{
									$imgMailP = "./img/bouton-p.jpg";
								}
							}
							$linkMailP = "<div id=\"".$titreNorm."P\"><a href=\"#".$titreNorm."\" onClick=\"majMailsP('".$adr."','".$titreNorm."','".$refdoi."','P','','".strtoupper($lang)."','".$callNumber."','".str_replace("'", "\'", $data[$colTitle])."'); mailto('".$file."','".$adr."','".$subjectP."','".$bodyP."');\"><img alt='".$adr."' title='".$adr."' src='".$imgMailP."'></a></div>";
						}else{//new solicitation
							if ($lang == "FR")
							{
								include "./OverHAL_mails_rappel_fr.php";
							}else{
								include "./OverHAL_mails_rappel_en.php";
							}
							if ($imgMailP == "")
							{
								if ($callNumber == "ISCR-AP") {$imgMailP = "./img/bouton-p-iscr-ap.jpg";}else{$imgMailP = "./img/bouton-p.jpg";}
							}
							$linkMailP = "<div id=\"".$titreNorm."P\"><a href=\"#".$titreNorm."\" onClick=\"majMailsP('".$adr."','".$titreNorm."','".$refdoi."','P','','".strtoupper($lang)."','".$callNumber."','".str_replace("'", "\'", $data[$colTitle])."'); mailto('".$file."','".$adr."','".$subjectP."','".$bodyP."');\"><img alt='".$adr."' title='".$adr."' src='".$imgMailP."'></a></div>";
						}
					}else{
						$linkMailM = "<strong>OK</strong>";
						$linkMailP = "<strong>OK</strong>";
					}
					if ($mailValide == "non")
					{
						$linkMailM = "<a href=\"#".$titreNorm."\" onClick=\"errAdr('".$adr."');\"><img alt='".$adr."' title='".$adr."' src='".$imgMailM."'></a>";
						$linkMailP = "<a href=\"#".$titreNorm."\" onClick=\"errAdr('".$adr."');\"><img alt='".$adr."' title='".$adr."' src='".$imgMailP."'></a>";
					}

					if ($noadr == "")
					{
						if ($activMailsM == "oui")
						{
							echo "<td align='center' valign='top'>".$linkMailM."<a name=\".$titreNorm.\"></a></td>";
						}else{
							echo "<td align='center' valign='top'>&nbsp;</td>";
						}
						if ($activMailsP == "oui")
						{
							echo "<td align='center' valign='top'>".$linkMailP."</td>";
						}else{
							echo "<td align='center' valign='top'>&nbsp;</td>";
						}
					}else{
						if ($imgMailM == "" && $imgMailP == "")
						{
							$imgMailM = "./img/bouton-mnoadr.jpg";
							$imgMailP = "./img/bouton-pnoadr.jpg";
						}else{
							if ($imgMailM == "./img/bouton-ccby.jpg" && $imgMailP == "./img/bouton-ccby.jpg")
							{
								$imgMailM = "./img/bouton-ccbynoadr.jpg";
								$imgMailP = "./img/bouton-ccbynoadr.jpg";
							}else{
								$imgMailM = "./img/bouton-mnoadr.jpg";
								$imgMailP = "./img/bouton-pnoadr.jpg";
							}
						}
						if ($activMailsM == "oui")
						{
							echo "<td align='center' valign='top'><img alt='".$noadr."' title='".$noadr."' src='".$imgMailM."'></td>";
						}else{
							echo "<td align='center' valign='top'>&nbsp;</td>";
						}
						if ($activMailsP == "oui")
						{
							echo "<td align='center' valign='top'><img alt='".$noadr."' title='".$noadr."' src='".$imgMailP."'></td>";
						}else{
							echo "<td align='center' valign='top'>&nbsp;</td>";
						}
					}
				}
				echo "</tr>";
				//export bibtex
				switch($key)
				{
					case "scopus":
						$Fnm = "./HAL/OverHAL_scopus.bib";
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						//$chaine = "\xEF\xBB\xBF";
						$chaine = "";
						fwrite($inF,$chaine);
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						if (isset($papers[$key][$key2]['Document Type']))
						{
							$type = strtolower($papers[$key][$key2]['Document Type']);
							switch($type)
							{
								case "article":
								case "article in press":
								case "review":
								case "erratum":
								case "editorial":
								case "short survey":
								case "letter":
								case "note":
									$type = "article";
									break;
								case "conference paper":
								case "conference review":
									$type = "inproceedings";
									break;
								case "book":
									$type = "book";
									break;
								case "book chapter":
									$type = "inbook";
									break;
							}
							$chaine = chr(13).chr(10)."@".$type."{";
						}
						if (isset($papers[$key][$key2]['Authors']))
						{
							$auteurs = explode(", ", $papers[$key][$key2]['Authors']);
							$chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title']))
						{
							$titre = explode(" ", $papers[$key][$key2]['Title']);
							$chaine .= "_".mb_strtolower(normalize(str_replace(" ", "_", $titre[0])), 'UTF-8');
						}
						//add a constant to differenciate same initial identifier
						if (isset($auteurs) && isset($titre))
						{
							$tit = mb_strtolower(str_replace(" ", "_", $auteurs[0]), 'UTF-8')."_".mb_strtolower(normalize(str_replace(" ", "_", $titre[0])), 'UTF-8');
							if (strpos($listTit, "¤".$tit."¤") !== false)
							{
								$cst++;
								$chaine .= $cst;
							}
							$listTit .= $tit."¤";
						}
						if (isset($papers[$key][$key2]['Year'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['Year'], 'UTF-8');}
						if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
						if (isset($papers[$key][$key2]['Volume'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['Volume']."}";}
						//if ($issn) {$chaine .= ",".chr(13).chr(10)."	issn = {".$issn."}";}
						if (isset($papers[$key][$key2]['ISSN'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['ISSN']."}";}
						if (isset($papers[$key][$key2]['DOI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";}
						if (isset($papers[$key][$key2]['Abstract'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", $papers[$key][$key2]['Abstract'])."}";}
						if (isset($papers[$key][$key2]['Source title'])) {$chaine .= ",".chr(13).chr(10)."	journal = {".$papers[$key][$key2]['Source title']."}";}
						if (isset($papers[$key][$key2]['Authors'])) {
							$auteurs = $papers[$key][$key2]['Authors'];
							//add a comma after the name
							$i = 0;
							$autvirg = "";
							$esp = explode(", ", $auteurs);
							while ($i < count($esp))
							{
								$autesp = strrchr(trim($esp[$i]), " ");
								if ($i != count($esp) - 1)
								{
									$autvirg .= str_replace($autesp, ",".$autesp.",", $esp[$i]);
								}else{
									$autvirg .= str_replace($autesp, ",".$autesp, $esp[$i]);
								}
								$i++;
							}
							$auteurs = str_replace(".,", ". and ", $autvirg);
							$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
						}
						if (isset($papers[$key][$key2]['Authors with affiliations'])) {$chaine .= ",".chr(13).chr(10)."	affiliations = {".$papers[$key][$key2]['Authors with affiliations']."}";}
						if (isset($papers[$key][$key2]['Year'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Year']."}";}
						if (isset($papers[$key][$key2]['Author Keywords'])) {$chaine .= ",".chr(13).chr(10)."	keywords = {".$papers[$key][$key2]['Author Keywords']."}";}
						if (isset($papers[$key][$key2]['Page start']) && isset($papers[$key][$key2]['Page end']))
						{
							$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Page start']."--".$papers[$key][$key2]['Page end']."}";
						}else{
							if (isset($papers[$key][$key2]['Art. No.'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".trim($papers[$key][$key2]['Art. No.'])."}";}
						}
						if (isset($papers[$key][$key2]['Funding Details'])) {$chaine .= ",".chr(13).chr(10)."	x-funding = {".$papers[$key][$key2]['Funding Details']."}";}
						if (isset($papers[$key][$key2]['PubMed ID'])) {$chaine .= ",".chr(13).chr(10)."	pmid = {".$papers[$key][$key2]['PubMed ID']."}";}
						if (isset($papers[$key][$key2]['Conference location'])) {$chaine .= ",".chr(13).chr(10)."	address = {".$papers[$key][$key2]['Conference location']."}";}
						if (isset($papers[$key][$key2]['Conference name'])) {$chaine .= ",".chr(13).chr(10)."	booktitle = {".$papers[$key][$key2]['Conference name']."}";}
						if (isset($papers[$key][$key2]['Conference date']))
						{
							//6 September 2015 through 9 September 2015 to 2015-09-06
							$confdate = $papers[$key][$key2]['Conference date'];
							if ($confdate != "") {
								$res = explode(" ", $confdate);
								if (count($res) > 2) {
									//year
									$confdate = trim($res[2])."-";
									//month
									switch(strtolower($res[1]))
									{
										case "january":
											$confdate .= "01-";
											break;
										case "february":
											$confdate .= "02-";
											break;
										case "march":
											$confdate .= "03-";
											break;
										case "april":
											$confdate .= "04-";
											break;
										case "may":
											$confdate .= "05-";
											break;
										case "june":
											$confdate .= "06-";
											break;
										case "july":
											$confdate .= "07-";
											break;
										case "august":
											$confdate .= "08-";
											break;
										case "september":
											$confdate .= "09-";
											break;
										case "october":
											$confdate .= "10-";
											break;
										case "november":
											$confdate .= "11-";
											break;
										case "december":
											$confdate .= "12-";
											break;
									}
									//day
									if (strlen($res[0]) == 1)
									{
										$confdate .= "0".$res[0];
									}else{
										$confdate .= $res[0];
									}
								}
							}
							$chaine .= ",".chr(13).chr(10)."	x-conferencestartdate = {".$confdate."}";
						}
						if (isset($papers[$key][$key2]['Editor(s)']))
						{
							$editors = str_replace("; ", " and ", $papers[$key][$key2]['Editor(s)']);
							$chaine .= ",".chr(13).chr(10)."	editor = {".$editors."}";
						}
						if (isset($papers[$key][$key2]['ISBN'])) {$chaine .= ",".chr(13).chr(10)."	isbn = {".$papers[$key][$key2]['ISBN']."}";}
						if (isset($papers[$key][$key2]['Publisher'])) {$chaine .= ",".chr(13).chr(10)."	publisher = {".$papers[$key][$key2]['Publisher']."}";}
						if (isset($papers[$key][$key2]['Language of Original Document']))
						{
							if ($papers[$key][$key2]['Language of Original Document'] != "English" || $papers[$key][$key2]['Language of Original Document'] == "English; French")
							{
								$chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
								$chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
							}
						}
						$chaine .= chr(13).chr(10)."}".chr(13).chr(10);
						fwrite($inF, $chaine);
						break;
					case "wos_csv":
					case "wos_txt":
						mb_internal_encoding('UTF-8');
						$Fnm = "./HAL/OverHAL_".$key.".bib";
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						//$chaine = "\xEF\xBB\xBF";//UTF-8
						$chaine = "";//ANSI
						fwrite($inF,$chaine);
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						if (isset($papers[$key][$key2]['PT']))
						{
							$type = strtolower($papers[$key][$key2]['PT']);
							switch($type)
							{
								case "j":
									$type = ($papers[$key][$key2]['DT'] == "Meeting Abstract") ? "inproceedings" : "article";
									break;
								case "s":
								case "c":
									$type = "inproceedings";
									break;
								case "b":
									$type = "book";
									break;
								case "p":
									$type = "patent";
									break;
							}
							$chaine = chr(13).chr(10)."@".$type."{";
						}
						if (isset($papers[$key][$key2]['AF']))
						{
							$auteurs = explode(", ", $papers[$key][$key2]['AF']);
							$chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
						}
						if (isset($papers[$key][$key2]['TI']))
						{
							$titre = explode(" ", $papers[$key][$key2]['TI']);
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
						if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
							$chaine .= "_".mb_strtolower($papers[$key][$key2]['PY'], 'UTF-8');
						}else{
							if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
								$chaine .= "_".mb_strtolower($papers[$key][$key2]['EY'], 'UTF-8');
							}else{
								if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
									$chaine .= "_".mb_strtolower(substr($papers[$key][$key2]['EA'], -4), 'UTF-8');
								}
							}
						}
						if (isset($papers[$key][$key2]['TI'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['TI']."}";}
						if (isset($papers[$key][$key2]['VL'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['VL']."}";}
						if (isset($papers[$key][$key2]['SN'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['SN']."}";}
						if (isset($papers[$key][$key2]['DI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DI']."}";}
						if (isset($papers[$key][$key2]['AB'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", trim($papers[$key][$key2]['AB']))."}";}
						if (isset($papers[$key][$key2]['SO'])) {$chaine .= ",".chr(13).chr(10)."	journal = {".minRev($papers[$key][$key2]['SO'])."}";}
						if (isset($papers[$key][$key2]['AF']))
						{
							$auteurs = str_replace("; ", " and ", $papers[$key][$key2]['AF']);
							$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
						}
						if (isset($papers[$key][$key2]['C1'])) {$chaine .= ",".chr(13).chr(10)."	affiliations = {".$papers[$key][$key2]['C1']."}";}
						if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
							$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['PY']."}";
						}else{
							if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
								$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['EY']."}";
							}else{
								if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
									$chaine .= ",".chr(13).chr(10)."	year = {".substr($papers[$key][$key2]['EA'], -4)."}";
								}
							}
						}
						if (isset($papers[$key][$key2]['DE'])) {$chaine .= ",".chr(13).chr(10)."	keywords = {".strtolower($papers[$key][$key2]['DE'])."}";}
						if (isset($papers[$key][$key2]['BP']) && isset($papers[$key][$key2]['EP']))
						{
							$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['BP']."--".$papers[$key][$key2]['EP']."}";
						}else{
							if (isset($papers[$key][$key2]['AR'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['AR']."}";}
						}
						if (isset($papers[$key][$key2]['FU'])) {$chaine .= ",".chr(13).chr(10)."	x-funding = {".$papers[$key][$key2]['FU']."}";}
						if (isset($papers[$key][$key2]['PM'])) {$chaine .= ",".chr(13).chr(10)."	pmid = {".$papers[$key][$key2]['PM']."}";}
						if (isset($papers[$key][$key2]['IS'])) {$chaine .= ",".chr(13).chr(10)."	number = {".$papers[$key][$key2]['IS']."}";}
						if (isset($papers[$key][$key2]['CL'])) {$chaine .= ",".chr(13).chr(10)."	address = {".$papers[$key][$key2]['CL']."}";}
						if (isset($papers[$key][$key2]['CT'])) {$chaine .= ",".chr(13).chr(10)."	booktitle = {".$papers[$key][$key2]['CT']."}";}
						if (isset($papers[$key][$key2]['CY']))
						{
							//OCT 19-23, 2014 to 2014-10-19
							$confdate = $papers[$key][$key2]['CY'];
							if ($confdate != "") {
								$res = explode(" ", $confdate);
								if (count($res) == 3) {
									//year
									$confdate = trim($res[2])."-";
									//month
									switch(strtolower($res[0]))
									{
										case "jan":
											$confdate .= "01-";
											break;
										case "feb":
											$confdate .= "02-";
											break;
										case "mar":
											$confdate .= "03-";
											break;
										case "apr":
											$confdate .= "04-";
											break;
										case "may":
											$confdate .= "05-";
											break;
										case "jun":
											$confdate .= "06-";
											break;
										case "jul":
											$confdate .= "07-";
											break;
										case "aug":
											$confdate .= "08-";
											break;
										case "sep":
											$confdate .= "09-";
											break;
										case "oct":
											$confdate .= "10-";
											break;
										case "nov":
											$confdate .= "11-";
											break;
										case "dec":
											$confdate .= "12-";
											break;
									}
									//day
									$confdate .= substr($res[1], 0, 2);
								}
								if (count($res) == 4) {//MAR 31-APR 05, 2019 to 2019-03-31 and 2019-04-05
									//year
									$confDate = trim($res[3])."-";
									//month
									switch(strtolower($res[0]))
									{
										case "jan":
											$confStartDate .= "01-";
											break;
										case "feb":
											$confStartDate .= "02-";
											break;
										case "mar":
											$confStartDate .= "03-";
											break;
										case "apr":
											$confStartDate .= "04-";
											break;
										case "may":
											$confStartDate .= "05-";
											break;
										case "jun":
											$confStartDate .= "06-";
											break;
										case "jul":
											$confStartDate .= "07-";
											break;
										case "aug":
											$confStartDate .= "08-";
											break;
										case "sep":
											$confStartDate .= "09-";
											break;
										case "oct":
											$confStartDate .= "10-";
											break;
										case "nov":
											$confStartDate .= "11-";
											break;
										case "dec":
											$confStartDate .= "12-";
											break;
									}
									//day
									$confdate .= substr($res[1], 0, 2);
								}
							}
							$chaine .= ",".chr(13).chr(10)."	x-conferencestartdate = {".$confdate."}";
						}
						if (isset($papers[$key][$key2]['BE']))
						{
							$editors = str_replace("; ", " and ", $papers[$key][$key2]['BE']);
							$chaine .= ",".chr(13).chr(10)."	editor = {".$editors."}";
						}
						if (isset($papers[$key][$key2]['BN'])) {$chaine .= ",".chr(13).chr(10)."	isbn = {".$papers[$key][$key2]['BN']."}";}
						if (isset($papers[$key][$key2]['PU'])) {$chaine .= ",".chr(13).chr(10)."	publisher = {".$papers[$key][$key2]['PU']."}";}
						if (isset($papers[$key][$key2]['SE'])) {$chaine .= ",".chr(13).chr(10)."	series = {".$papers[$key][$key2]['SE']."}";}
						if (isset($papers[$key][$key2]['LA']))
						{
							if ($papers[$key][$key2]['LA'] != "English")
							{
								$chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
								$chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
							}
						}
						$chaine .= chr(13).chr(10)."}".chr(13).chr(10);
						fwrite($inF, $chaine);
						break;
					case "scifin":
						$Fnm = "./HAL/OverHAL_scifin.bib";
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						$chaine = "\xEF\xBB\xBF";
						fwrite($inF,$chaine);
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						if (isset($papers[$key][$key2]['Document Type']))
						{
							$type = strtolower($papers[$key][$key2]['Document Type']);
							switch($type)
							{
								case "journal; online computer file":
								case "journal":
								case "preprint":
								case "journal; general review; online computer file":
									$type = "article";
									break;
								case "patent":
									$type = "patent";
									break;
							}
							$chaine = chr(13).chr(10)."@".$type."{";
						}
						if (isset($papers[$key][$key2]['Author']))
						{
							$auteurs = explode(", ", $papers[$key][$key2]['Author']);
							$chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title']))
						{
							$titre = explode(" ", $papers[$key][$key2]['Title']);
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
						if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['Publication Year'], 'UTF-8');}
						if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
						if (isset($papers[$key][$key2]['Volume'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".$papers[$key][$key2]['Volume']."}";}
						if (isset($papers[$key][$key2]['Internat.Standard Doc. Number'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['Internat.Standard Doc. Number']."}";}
						if (isset($papers[$key][$key2]['DOI'])) {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";}
						if (isset($papers[$key][$key2]['Abstract'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", trim($papers[$key][$key2]['Abstract']))."}";}
						if (isset($papers[$key][$key2]['Journal Title'])) {$chaine .= ",".chr(13).chr(10)."	journal = {".$papers[$key][$key2]['Journal Title']."}";}
						if (isset($papers[$key][$key2]['Author']))
						{
							$auteurs = str_replace("; ", " and ",$papers[$key][$key2]['Author']);
							$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
						}
						if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Publication Year']."}";}
						if (isset($papers[$key][$key2]['Page'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Page']."}";}
						if (isset($papers[$key][$key2]['Language']))
						{
							if ($papers[$key][$key2]['Language'] != "written in English")
							{
								$chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
								$chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
							}
						}
						$chaine .= chr(13).chr(10)."}".chr(13).chr(10);
						fwrite($inF, $chaine);
						break;
						
					case "zotero":
						$Fnm = "./HAL/OverHAL_zotero.bib";
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						$chaine = "\xEF\xBB\xBF";
						fwrite($inF,$chaine);
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						if (isset($papers[$key][$key2]['Item Type']))
						{
							$type = strtolower($papers[$key][$key2]['Item Type']);
							switch($type)
							{
								case "journalarticle":
									$type = "article";
									break;
								case "conferencepaper":
									$type = "inproceedings";
									break;
								case "book":
									$type = "book";
									break;
								case "booksection":
									$type = "inbook";
									break;
								case "patent":
									$type = "patent";
									break;
							}
							$chaine = chr(13).chr(10)."@".$type."{";
						}
						if (isset($papers[$key][$key2]['Author']))
						{
							$auteurs = explode(", ", $papers[$key][$key2]['Author']);
							$chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title']))
						{
							$titre = explode(" ", $papers[$key][$key2]['Title']);
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
						if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= "_".mb_strtolower($papers[$key][$key2]['Publication Year'], 'UTF-8');}
						if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
						if (isset($papers[$key][$key2]['Volume'])) {$chaine .= ",".chr(13).chr(10)."	volume = {".str_replace(array("N° ", "VOL. "), "", $papers[$key][$key2]['Volume'])."}";}
						if (isset($papers[$key][$key2]['ISSN'])) {$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['ISSN']."}";}
						if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "") {$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";}
						if (isset($papers[$key][$key2]['Abstract Note'])) {$chaine .= ",".chr(13).chr(10)."	abstract = {".str_replace(array("{", "}"), "_", trim($papers[$key][$key2]['Abstract Note']))."}";}
						if (isset($papers[$key][$key2]['Publication Title']))
						{
							$titreJ = $papers[$key][$key2]['Publication Title'];
							$titreJVal = $papers[$key][$key2]['Publication Title'];
							if (substr($titreJ, 0, 4) == "The ")//Suppression of initials characters 'The ' for journal title
							{
								$titreJVal = ucfirst(substr($titreJ, 4));
							}
							if (strpos($titreJ, "(") !== false && strpos($titreJ, ")") !== false)//Suppression of text in brackets
							{
								$titreJTmp = preg_replace("#\([^\)]+\)#", "¤", $titreJ);
								$titreJVal = str_replace(" ¤", "", $titreJTmp);
							}
							if ($type == "inbook") {
								$chaine .= ",".chr(13).chr(10)."	booktitle = {".$titreJVal."}";
							}else{
								$chaine .= ",".chr(13).chr(10)."	journal = {".$titreJVal."}";
							}
						}
						if (isset($papers[$key][$key2]['Url'])) {$chaine .= ",".chr(13).chr(10)."	publisherLink = {".$papers[$key][$key2]['Url']."}";}
						
						if (isset($_POST['author']) && $_POST['author'] != "" && isset($papers[$key][$key2]['Author']) && $papers[$key][$key2]['Author'] == "")
						{
							$auteurs = str_replace("; ", " and ", $_POST['author']);
							$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
						}else{
							if (isset($papers[$key][$key2]['Author']))
							{
								$auteurs = str_replace("; ", " and ", $papers[$key][$key2]['Author']);
								$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
							}
						}
						$keywords = "";
						if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui") {
							if (isset($papers[$key][$key2]['Automatic Tags']) && $papers[$key][$key2]['Automatic Tags'] != "")
							{
								$keywords = $_POST['keywords'].'; ';
							}else{
								$keywords = $_POST['keywords'];
							}
						}
						if (isset($papers[$key][$key2]['Automatic Tags'])) {$chaine .= ",".chr(13).chr(10)."	keywords = {".$keywords.$papers[$key][$key2]['Automatic Tags']."}";}
						if (isset($papers[$key][$key2]['Pages'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Pages']."}";}
						if (isset($papers[$key][$key2]['Extra'])) {
							$pmid = $papers[$key][$key2]['Extra'];
							if (strpos($pmid, "PMCID") !== false)//PMID + PMCID
							{
								$pmidVal = str_replace(array("PMID: ", "PMCID: "), "", $pmid);
								$pmidValT = explode(" ", $pmidVal);
								$chaine .= ",".chr(13).chr(10)."	pmid = {".$pmidValT[0]."}";
								$chaine .= ",".chr(13).chr(10)."	pmcid = {".$pmidValT[1]."}";
							}else{//only PMID
								$pmidVal = str_replace("PMID: ", "", $pmid);
								$chaine .= ",".chr(13).chr(10)."	pmid = {".$pmidVal."}";
							}
						}
						if (isset($papers[$key][$key2]['Conference Name'])) {$chaine .= ",".chr(13).chr(10)."	booktitle = {".$papers[$key][$key2]['Conference Name']."}";}
						if (isset($papers[$key][$key2]['Editor']))
						{
							$editors = str_replace("; ", " and ", $papers[$key][$key2]['Editor']);
							$chaine .= ",".chr(13).chr(10)."	editor = {".$editors."}";
						}
						if (isset($papers[$key][$key2]['ISBN'])) {$chaine .= ",".chr(13).chr(10)."	isbn = {".$papers[$key][$key2]['ISBN']."}";}
						if (isset($papers[$key][$key2]['Publisher'])) {$chaine .= ",".chr(13).chr(10)."	publisher = {".$papers[$key][$key2]['Publisher']."}";}
						if (isset($papers[$key][$key2]['Series'])) {$chaine .= ",".chr(13).chr(10)."	series = {".$papers[$key][$key2]['Series']."}";}
						if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui") {
							if (isset($papers[$key][$key2]['Date'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Date']."}";}
						}else{
							if (isset($papers[$key][$key2]['Publication Year'])) {$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Publication Year']."}";}
						}
						if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui")
						{
							$chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
							$chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
						}else{
							if (isset($papers[$key][$key2]['Language']))
							{
								if ($papers[$key][$key2]['Language'] == "")
								{
									$chaine .= ",".chr(13).chr(10)."	x-language = {en}";
									$chaine .= ",".chr(13).chr(10)."	x-audience  = {International}";
								}else{
									if ($papers[$key][$key2]['Language'] != "English" && $papers[$key][$key2]['Language'] != "ENG" && $papers[$key][$key2]['Language'] != "EN" && $papers[$key][$key2]['Language'] != "eng" && $papers[$key][$key2]['Language'] != "en")
									{
										$chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
										$chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
									}else{
										$chaine .= ",".chr(13).chr(10)."	x-language = {en}";
										$chaine .= ",".chr(13).chr(10)."	x-audience  = {International}";
									}
								}
							}
						}
						$chaine .= chr(13).chr(10)."}".chr(13).chr(10);
						fwrite($inF, $chaine);
						break;
						
						case "pubmed_csv":
						$Fnm = "./HAL/OverHAL_pubmed_csv.bib";
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						$chaine = "\xEF\xBB\xBF";
						fwrite($inF,$chaine);
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						if (isset($papers[$key][$key2]['Type']))
						{
							$type = $papers[$key][$key2]['Type'];
							$chaine = chr(13).chr(10)."@".$type."{";
						}
						if (isset($papers[$key][$key2]['Description']))
						{
							$auteurs = explode(", ", $papers[$key][$key2]['Description']);
							$chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title']))
						{
							$titre = explode(" ", $papers[$key][$key2]['Title']);
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
						//Publication year
						$pyear = "";
						if (isset($papers[$key][$key2]['ShortDetails'])) {
							$pyear = substr($papers[$key][$key2]['ShortDetails'], -4);
							$chaine .= "_".mb_strtolower($pyear, 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
						//Volume, number and pages
						if (isset($papers[$key][$key2]['Details']) && strpos($papers[$key][$key2]['Details'], ";") !== false) {
							$tabEXT = explode(";", $papers[$key][$key2]['Details']);
							$resi = $tabEXT[1];
							$resTAB = explode(" ", $resi);
							$resf = $resTAB[0];
							$volume = "";
							$number = "";
							$pages = "";
							if (strpos($resf, "(") !== false && strpos($resf, ")") !== false) {
								$number = substr($resf, (strpos($resf, "(") + 1), (strpos($resf, ")") - strpos($resf, "(") - 1));
								$resf = str_replace("(".$number.")", "", $resf);
							}
							$resu = explode(":", $resf);
							if (isset($resu[0])) {$volume = str_replace(".", "", $resu[0]);}
							if (isset($resu[1])) {$pages = str_replace(".", "", $resu[1]);}
							
							if ($volume != "") {$chaine .= ",".chr(13).chr(10)."	volume = {".$volume."}";}
							if ($number != "") {$chaine .= ",".chr(13).chr(10)."	number = {".$number."}";}
							if ($pages != "" && is_numeric(str_replace("-", "", $pages))) {$chaine .= ",".chr(13).chr(10)."	pages = {".$pages."}";}
						} 
						//DOI
						if (isset($papers[$key][$key2]['Details'])  && strpos($papers[$key][$key2]['Details'], "doi: ") !== false) {
							$tabEXT = explode("doi: ", $papers[$key][$key2]['Details']);
							$tabDOI = explode(" ", trim($tabEXT[1]));
							$chaine .= ",".chr(13).chr(10)."	doi = {".str_replace(".", "", $tabDOI[0])."}";
						}
						//Journal
						if (isset($papers[$key][$key2]['ShortDetails'])) {
							$titreJ = trim(str_replace(" ".$pyear, "", $papers[$key][$key2]['ShortDetails']));
							$titreJVal = $titreJ;
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
						//Authors
						if (isset($_POST['author']) && $_POST['author'] != "" && isset($papers[$key][$key2]['Description']) && $papers[$key][$key2]['Description'] == "")
						{
							$auteurs = str_replace("; ", " and ", $_POST['author']);
							$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
						}else{
							if (isset($papers[$key][$key2]['Description']))
							{
								$auteurs = str_replace("; ", " and ", $papers[$key][$key2]['Description']);
								$chaine .= ",".chr(13).chr(10)."	author = {".$auteurs."}";
							}
						}
						$keywords = "";
						if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui") {
							if (isset($papers[$key][$key2]['Automatic Tags']) && $papers[$key][$key2]['Automatic Tags'] != "")
							{
								$keywords = $_POST['keywords'].'; ';
							}else{
								$keywords = $_POST['keywords'];
							}
						}
						if (isset($papers[$key][$key2]['Pages'])) {$chaine .= ",".chr(13).chr(10)."	pages = {".$papers[$key][$key2]['Pages']."}";}
						if (isset($papers[$key][$key2]['Identifiers'])) {
							$pmid = $papers[$key][$key2]['Identifiers'];
							if (strpos($pmid, "PMCID:PMC") !== false)//PMID + PMCID
							{
								$pmidVal = str_replace(array("PMID:", "PMCID:PMC"), "", $pmid);
								$pmidValT = explode(" | ", $pmidVal);
								$chaine .= ",".chr(13).chr(10)."	pmid = {".$pmidValT[0]."}";
								$chaine .= ",".chr(13).chr(10)."	pmcid = {".$pmidValT[1]."}";
							}else{//only PMID
								$pmidVal = str_replace("PMID:", "", $pmid);
								$chaine .= ",".chr(13).chr(10)."	pmid = {".$pmidVal."}";
							}
						}
						if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui") {
							if (isset($pyear) && $pyear != "") {$chaine .= ",".chr(13).chr(10)."	year = {".$pyear."}";}
						}
						if (isset($_POST['bibtex']) && $_POST['bibtex'] == "oui")
						{
							$chaine .= ",".chr(13).chr(10)."	x-language = {fr}";
							$chaine .= ",".chr(13).chr(10)."	x-audience  = {National}";
						}
						$chaine .= chr(13).chr(10)."}".chr(13).chr(10);
						fwrite($inF, $chaine);
						break;
						
						case "openalex":
						$Fnm = "./HAL/OverHAL_openalex.bib";
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						$chaine = "\xEF\xBB\xBF";
						fwrite($inF,$chaine);
						$inF = fopen($Fnm,"a+");
						fseek($inF, 0);
						if (isset($papers[$key][$key2]['Type notice']))
						{
							$type = $papers[$key][$key2]['Type notice'];
							$chaine = chr(13).chr(10)."@".$type."{";
						}
						if (isset($papers[$key][$key2]['Author_DN']))
						{
							$auteurs = explode(", ", $papers[$key][$key2]['Author_DN']);
							$chaine .= mb_strtolower(normalize(str_replace(" ", "_", $auteurs[0])), 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title']))
						{
							$titre = explode(" ", $papers[$key][$key2]['Title']);
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
						//Publication year
						$pyear = "";
						if (isset($papers[$key][$key2]['Date'])) {
							$pyear = $papers[$key][$key2]['Date'];
							$chaine .= "_".mb_strtolower($pyear, 'UTF-8');
						}
						if (isset($papers[$key][$key2]['Title'])) {$chaine .= ",".chr(13).chr(10)."	title = {".$papers[$key][$key2]['Title']."}";}
						//Volume, number and pages
						if (isset($papers[$key][$key2]['Details']) && strpos($papers[$key][$key2]['Details'], ";") !== false) {
							$volume = (!empty($papers[$key][$key2]['Volume'])) ? $papers[$key][$key2]['Volume'] : '';
							$issue = (!empty($papers[$key][$key2]['Issue'])) ? $papers[$key][$key2]['Issue'] : '';
							$pages = (!empty($papers[$key][$key2]['Pages'])) ? $papers[$key][$key2]['Pages'] : '';
							
							if ($volume != "") {$chaine .= ",".chr(13).chr(10)."	volume = {".$volume."}";}
							if ($number != "") {$chaine .= ",".chr(13).chr(10)."	number = {".$number."}";}
							if ($pages != "" && is_numeric(str_replace("-", "", $pages))) {$chaine .= ",".chr(13).chr(10)."	pages = {".$pages."}";}
						} 
						//ISSN
						if (!empty($papers[$key][$key2]['ISSN'])) {
							$chaine .= ",".chr(13).chr(10)."	issn = {".$papers[$key][$key2]['ISSN']."}";
						}
						//DOI
						if (!empty($papers[$key][$key2]['DOI'])) {
							$chaine .= ",".chr(13).chr(10)."	doi = {".$papers[$key][$key2]['DOI']."}";
						}
						//Abstract
						if (!empty($papers[$key][$key2]['Abstract'])) {
							$chaine .= ",".chr(13).chr(10)."	abstract = {".$papers[$key][$key2]['Abstract']."}";
						}
						//Journal
						if (!empty($papers[$key][$key2]['Titre revue'])) {
							$chaine .= ",".chr(13).chr(10)."	journal = {".$papers[$key][$key2]['Titre revue']."}";
						}
						//Authors
						if (!empty($papers[$key][$key2]['Author_DN'])) {
							$chaine .= ",".chr(13).chr(10)."	authors = {".str_replace('~|~', ', ', $papers[$key][$key2]['Author_DN'])."}";
						}
						//Year
						if (!empty($papers[$key][$key2]['Date'])) {
							$chaine .= ",".chr(13).chr(10)."	year = {".$papers[$key][$key2]['Date']."}";
						}
						
						//PMID
						if (!empty($papers[$key][$key2]['PMID'])) {
							$chaine .= ",".chr(13).chr(10)."	pmid = {".$papers[$key][$key2]['PMID']."}";
						}
						
						$chaine .= chr(13).chr(10)."}".chr(13).chr(10);
						fwrite($inF, $chaine);
						break;
				}
				//export TEI
				$limNbAut = $limTEI;//If more authors than this limit, no export is done > too long time to process
				switch($key)
				{
					case "wos_csv":
					case "wos_txt":
						$aut = $papers[$key][$key2]['AU'];
						$autTab = explode("; ",$aut);
						if (count($autTab) <= $limNbAut)
						{
							//affiliation
							$autTab = array();
							$labTab = array();
							$autInd = 0;
							$docid = 0;
							$label = "";
							$code = "";
							$cuHAL = "";
							$type = "";
							$pays = "";
							$quoi = $papers[$key][$key2]['C1'];
							$quoi = trimUltime($quoi);
							$validHAL = "";//to privilegy the search by the unit code number rather than acronym
							//echo "<br>".$j." - ".$quoi."<br>";
							$diffQuoi = explode("; [", $quoi);
							//var_dump($diffQuoi);
							for ($d = 0; $d < count($diffQuoi); $d++)
							{
								$urlHAL = "";
								$docid = 0;
								$label = "";
								$code = "";
								$cuHAL = "";
								$type = "";
								$pays = "";
								//Search for distinctive acronyms
								//echo $diffQuoi[$d].'<br>';
								searchAcro($diffQuoi[$d], $cuHAL, $urlHAL, $validHAL, $retTest);
								//echo $urlHAL.'<br>';
								if ($urlHAL != "")//affiliation with code unit or acronym found
								{
									//echo $diffQuoi[$d].'<br>';
									idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
								}
								//echo $docid.' - '.$urlHAL.'<br>';
								if($docid != 0)
								{
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
									while ($retTest != "") {
										$aTester = $retTest;
										$autInd = 0;
										$urlHAL = "";
										$docid = 0;
										$label = "";
										$code = "";
										$cuHAL = "";
										$type = "";
										$pays = "";
										//Search for distinctive acronyms
										//echo 'toto : '.$aTester.'<br>';
										searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
										//echo 'titi : '.$urlHAL.'<br>';
										if ($urlHAL != "")//affiliation with code unit or acronym found
										{
											idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
										}
										//echo $urlHAL.' - '.$docid.'<br>';
										if($docid != 0)
										{
											affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
										}
									}
								}else{//no specific affiliation found > we store whole results
									//echo $diffQuoi[$d].'<br>';
									$validHAL = "--";
									$eltTab = explode("] ",$diffQuoi[$d]);
									include './OverHAL_affiliation_termes.php';
									if (isset($eltTab[1])) {$label = supprAmp(trim($eltTab[1]));}
									$label = str_ireplace($search, $replace, $label);
									//echo $label.'<br>';
									include './OverHAL_univ_termes.php';
									$queTab = explode(",", $label);
									$quePay = count($queTab) - 1;
									$labFin = "";
									$iq = 0;
									$trm = "University ";
									foreach($queTab as $elt)
									{
										$elt = trim($elt);
										if(stripos($elt, "univ") !== false) {//term 'Univ' is present
											$pays = trim($queTab[$quePay]);
											$kT = array_search($pays, array_column($UNIV_LISTE, "pays"));
											if ($kT !== false) {$trm = $UNIV_LISTE[$kT]["univ"];}
										}
										if(stripos($elt, "University") === false) {//term 'University' is absent
											$elt = str_replace(array("Univ ", "univ "), $trm, $elt);
										}
										if ($iq > 0) {$labFin .= ", ";}
										$labFin .= $elt;
										$iq++;
									}
									//$docid = 999999;
									$docid = $labFin;
									$code = $labFin;
									$type = 'institution';
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);
								}
							}
							//var_dump($autTab);
							//var_dump($labTab);
							$strTab = array();
							$unqOrg = array();//Tableau pour tester l'unicité des organismes
							$ddn = supprAmp(str_replace("WOS:", "", $papers[$key][$key2]['UT']));//Document Delivery Number > Accession Number
							mb_internal_encoding('UTF-8');
							$zip = new ZipArchive();
							$FnmZ = "./HAL/OverHAL_".$key.".zip";
							if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
								exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
							}
							$Fnm = "./HAL/OverHAL_".$key."_".$ddn.".xml";
							$inF = fopen($Fnm,"a+");
							fseek($inF, 0);
							//$chaine = "\xEF\xBB\xBF";//UTF-8
							$chaine = "";//ANSI
							$chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
							$chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
							$chaine .= '  <text>'."\r\n".
												 '    <body>'."\r\n".
												 '      <listBibl>'."\r\n".
												 '        <biblFull>'."\r\n".
												 '          <titleStmt>'."\r\n";
							//funder
							$chaine .= '            <funder>'.supprAmp($papers[$key][$key2]['FU']).'</funder>'."\r\n";
							$chaine .= '          </titleStmt>'."\r\n";
							//if DOI exists searching an OA PDF file
							if (isset($papers[$key][$key2]['DI']) && $papers[$key][$key2]['DI'] != "")
							{
								$urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DI'];
								$volT = $papers[$key][$key2]['VL'];
								$issT = $papers[$key][$key2]['IS'];
								$pagT = $papers[$key][$key2]['BP'];
								if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
									$datT = $papers[$key][$key2]['PY'];
								}else{
									if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
										$datT = $papers[$key][$key2]['EY'];
									}else{
										if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
											$datT = substr($papers[$key][$key2]['EA'], -4);
										}
									}
								}
								$pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

								testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $urlPDF);

								if ($urlPDF != "")//an OA PDF file has benn found
								{
									$urlPDF = htmlspecialchars($urlPDF);
									$chaine .= '          <editionStmt>'."\r\n".
														 '            <edition>'."\r\n".
														 '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$urlPDF.'"></ref>'."\r\n".
														 '            </edition>'."\r\n".
														 '          </editionStmt>'."\r\n";
									$avail = 'http://creativecommons.org/licenses/by';
									if ($compNC != "") {$avail .= '-nc';}
									if ($compND != "") {$avail .= '-nd';}
									if ($compSA != "") {$avail .= '-sa';}
									$avail .= '/';
									$chaine .= '          <publicationStmt>'."\r\n".
														 '            <availability>'."\r\n".
														 '              <licence target="'.$avail.'"/>'."\r\n".
														 '            </availability>'."\r\n".
														 '          </publicationStmt>'."\r\n";
								}
							}
							$chaine .= '          <seriesStmt>'."\r\n".
												 '          </seriesStmt>'."\r\n".
												 '          <notesStmt>'."\r\n".
							//Audience
							$aud = "";
							if (isset($papers[$key][$key2]['LA']))
							{
								if ($papers[$key][$key2]['LA'] == "English") {
									$chaine .= '          	<note type="audience" n="2"/>'."\r\n";
								}else{
									$chaine .= '          	<note type="audience" n="3"/>'."\r\n";
								}
							}
							$chaine .= '						<note type="popular" n="0">No</note>'."\r\n";
							$chaine .= '						<note type="peer" n="1">Yes</note>'."\r\n";
							$chaine .= '          </notesStmt>'."\r\n".
												 '          <sourceDesc>'."\r\n".
												 '            <biblStruct>'."\r\n".
												 '              <analytic>'."\r\n";
							$lng = "";
							//langue + titre
							if (isset($papers[$key][$key2]['LA']))
							{
								if ($papers[$key][$key2]['LA'] == "French") {$lng = "fr";}
								if ($papers[$key][$key2]['LA'] == "English") {$lng = "en";}
							}
							$chaine .= '                <title xml:lang="'.$lng.'">'.str_replace("troli2points", ": ", supprAmp(str_replace(": ", "troli2points", $papers[$key][$key2]['TI']))).'</title>'."\r\n";
							//auteurs
							$aut = explode(";", $papers[$key][$key2]['AF']);
							$iTp = 0;
							foreach ($aut as $qui) {
								$quiTab = explode(",", $qui);
								if (isset($quiTab[1]))//in case of no comma for one author
								{
									$prenom = supprAmp(trim($quiTab[1]));
								}else{
									$prenom = " ";
								}
								$nom = supprAmp(trim($quiTab[0]));
								$nompre = $nom .", ".$prenom;
								$rolAut = "aut";
								if (stripos($papers[$key][$key2]['RP'], $nom .", ".substr($prenom, 0, 1)) !== false) {$rolAut = "crp";}
								if ($prenom != "") {
									$chaine .= '                <author role="'.$rolAut.'">'."\r\n";
									$chaine .= '                  <persName>'."\r\n";
									$chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
									$chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
									$chaine .= '                  </persName>'."\r\n";
									if (isset($papers[$key][$key2]['EM']))
									{//email > il faut limiter à une seule entrée car il y en a parfois plusieurs et le TEI ne sera alors pas valide
										$tabMail = explode(";", $papers[$key][$key2]['EM']);
										foreach ($tabMail as $elt) {
											if (stripos(trim(normalize($elt)), normalize($nom)) !== false)
											{
												//$chaine .= '                  <email>'.trim($elt).'</email>'."\r\n";
												$chaine .= '                  <email type="domain">'.str_replace('@', '', strstr(trim($elt), '@')).'</email>'."\r\n";
												break;//email ajouté > on sort de la boucle
											}
										}
									}
									if (isset($papers[$key][$key2]['OI']))
									{//ORCID
										$tabOrcid = explode(";", $papers[$key][$key2]['OI']);
										foreach ($tabOrcid as $elt) {
											if (stripos(trim(normalize($elt)), normalize($nom.', '.$prenom)) !== false)
											{
												$chaine .= '                  <idno type="ORCID">https://orcid.org/'.str_ireplace($nom.", ".$prenom."/", "", trim($elt)).'</idno>'."\r\n";
												break;//Orcid ajouté > on sort de la boucle
											}
										}
									}
									if (isset($papers[$key][$key2]['RI']))
									{//ResearcherID
										$tabResid = explode(";", $papers[$key][$key2]['RI']);
										foreach ($tabResid as $elt) {
											if (stripos(trim(normalize($elt)), normalize($nom.', '.$prenom)) !== false)
											{
												$chaine .= '                  <idno type="http://www.researcherid.com/rid/">'.str_ireplace($nom.", ".$prenom."/", "", trim($elt)).'</idno>'."\r\n";
												break;//Orcid ajouté > on sort de la boucle
											}
										}
									}
									$kT = array_search($nompre, $autTab);
									//echo $kT." - ".$nom."<br>";
									//var_dump($labTab);
									if ($kT !== FALSE) {
										foreach ($labTab[$nompre] as $lab) {
											$orgName = testLab($lab);
											//echo $orgName.'<br>';
											//$str = array_search($labTab[$nompre][$kT], $strTab);
											$str = array_search($orgName, $unqOrg);
											if ($str === FALSE) {
												$iTp += 1;
												$kTp = $iTp;
												array_push($strTab, $lab);
												array_push($unqOrg, $orgName);
											}else{
												$kTp = $str + 1;
											}
											$chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
											//echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
										}
									}
									$chaine .= '                </author>'."\r\n";
								}
							}
							//var_dump($strTab);
							$chaine .= '              </analytic>'."\r\n";
							//journal
							$chaine .= '              <monogr>'."\r\n";
							//ISSN
							$chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['SN']).'</idno>'."\r\n";
							//EISSN
							$chaine .= '                <idno type="eissn">'.supprAmp($papers[$key][$key2]['EI']).'</idno>'."\r\n";
							if (isset($papers[$key][$key2]['PT']))
							{
								$typDoc = "";
								$typDocp = "";
								$type = strtolower($papers[$key][$key2]['PT']);
								switch($type)
								{
									case "j"://article (Article)
										if ($papers[$key][$key2]['DT'] == "Article" || $papers[$key][$key2]['DT'] == "Article; Early Access" || $papers[$key][$key2]['DT'] == "Letter" || $papers[$key][$key2]['DT'] == "Letter; Early Access" || $papers[$key][$key2]['DT'] == "Editorial Material" || $papers[$key][$key2]['DT'] == "Editorial Material; Early Access" || $papers[$key][$key2]['DT'] == "Review" || $papers[$key][$key2]['DT'] == "Review; Early Access") {
											$chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['SO'])).'</title>'."\r\n";
											$chaine .= '                <imprint>'."\r\n";
											$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
											$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['VL']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['IS']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['BP']).'-'.$papers[$key][$key2]['EP'].'</biblScope>'."\r\n";
											if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
											$chaine .= '                </imprint>'."\r\n";
											$typeDoc = "ART";
											$typeDocp = "Journal articles";
											break;
										}
										if ($papers[$key][$key2]['DT'] == "Correction" || $papers[$key][$key2]['DT'] == "Correction; Early Access") {//Ligne de type article mais à ignorer
											break;
										}
									case "j"://communication (Meeting Abstract)
									case "s"://inproceedings
									case "c":
									case "b"://book
										if ($papers[$key][$key2]['DT'] == "Proceedings Paper" || $papers[$key][$key2]['DT'] == "Meeting Abstract") {
											$chaine .= '                <title level="j">'.supprAmp($papers[$key][$key2]['SO']).'</title>'."\r\n";
											$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
											$chaine .= '                <meeting>'."\r\n";
											$chaine .= '                  <title>'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
											$confStartDate = "";
											$confEndDate = "";
											if (isset($papers[$key][$key2]['CY']))
											{
												$confdate = $papers[$key][$key2]['CY'];
												if ($confdate != "") {
													$res = explode(" ", $confdate);
													if (count($res) == 3) {//OCT 19-23, 2014 to 2014-10-19
														//year
														$confStartDate = trim($res[2])."-";
														$confEndDate = trim($res[2])."-";
														//month
														switch(strtolower($res[0]))
														{
															case "jan":
																$confStartDate .= "01-";
																$confEndDate .= "01-";
																break;
															case "feb":
																$confStartDate .= "02-";
																$confEndDate .= "02-";
																break;
															case "mar":
																$confStartDate .= "03-";
																$confEndDate .= "03-";
																break;
															case "apr":
																$confStartDate .= "04-";
																$confEndDate .= "04-";
																break;
															case "may":
																$confStartDate .= "05-";
																$confEndDate .= "05-";
																break;
															case "jun":
																$confStartDate .= "06-";
																$confEndDate .= "06-";
																break;
															case "jul":
																$confStartDate .= "07-";
																$confEndDate .= "07-";
																break;
															case "aug":
																$confStartDate .= "08-";
																$confEndDate .= "08-";
																break;
															case "sep":
																$confStartDate .= "09-";
																$confEndDate .= "09-";
																break;
															case "oct":
																$confStartDate .= "10-";
																$confEndDate .= "10-";
																break;
															case "nov":
																$confStartDate .= "11-";
																$confEndDate .= "11-";
																break;
															case "dec":
																$confStartDate .= "12-";
																$confEndDate .= "12-";
																break;
														}
														//day
														$confStartDate .= substr($res[1], 0, 2);
														$confEndDate .= substr($res[1], 3, 2);
													}
													if (count($res) == 4) {//MAR 31-APR 05, 2019 to 2019-03-31 and 2019-04-05
														//year
														$confStartDate = trim($res[3])."-";
														$confEndDate = trim($res[3])."-";
														//month
														switch(strtolower($res[0]))//startdate
														{
															case "jan":
																$confStartDate .= "01-";
																break;
															case "feb":
																$confStartDate .= "02-";
																break;
															case "mar":
																$confStartDate .= "03-";
																break;
															case "apr":
																$confStartDate .= "04-";
																break;
															case "may":
																$confStartDate .= "05-";
																break;
															case "jun":
																$confStartDate .= "06-";
																break;
															case "jul":
																$confStartDate .= "07-";
																break;
															case "aug":
																$confStartDate .= "08-";
																break;
															case "sep":
																$confStartDate .= "09-";
																break;
															case "oct":
																$confStartDate .= "10-";
																break;
															case "nov":
																$confStartDate .= "11-";
																break;
															case "dec":
																$confStartDate .= "12-";
																break;
														}
														//enddate
														$tabCD = explode("-", trim($res[1]));
														switch(strtolower($tabCD[1]))
														{
															case "jan":
																$confEndDate .= "01-";
																break;
															case "feb":
																$confEndDate .= "02-";
																break;
															case "mar":
																$confEndDate .= "03-";
																break;
															case "apr":
																$confEndDate .= "04-";
																break;
															case "may":
																$confEndDate .= "05-";
																break;
															case "jun":
																$confEndDate .= "06-";
																break;
															case "jul":
																$confEndDate .= "07-";
																break;
															case "aug":
																$confEndDate .= "08-";
																break;
															case "sep":
																$confEndDate .= "09-";
																break;
															case "oct":
																$confEndDate .= "10-";
																break;
															case "nov":
																$confEndDate .= "11-";
																break;
															case "dec":
																$confEndDate .= "12-";
																break;
														}
														//day
														$confStartDate .= $tabCD[0];
														$confEndDate .= substr($res[2], 0, 2);
													}
												}
											}
											$chaine .= '                  <date type="start">'.$confStartDate.'</date>'."\r\n";//comment différencier date début et fin ?
											$chaine .= '                  <date type="end">'.$confEndDate.'</date>'."\r\n";//comment différencier date début et fin ?
											if (strpos(supprAmp($papers[$key][$key2]['CL']), ", ") !== false) {
												$tabSettlement = explode(", ", supprAmp($papers[$key][$key2]['CL']));
												$chaine .= '                  <settlement>'.$tabSettlement[0].'</settlement>'."\r\n";
												$codePays = "";
												$codePays = array_search(strtoupper($tabSettlement[1]), $countries);
												if ($codePays != "") {$chaine .= '                  <country key="'.$codePays.'"/>'."\r\n";}
											}else{
												$chaine .= '                  <settlement>'.supprAmp($papers[$key][$key2]['CL']).'</settlement>'."\r\n";
											}
											$chaine .= '                </meeting>'."\r\n";
											$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
											$chaine .= '                <imprint>'."\r\n";
											$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
											if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
											$chaine .= '                </imprint>'."\r\n";
											$typeDoc = "COMM";
											$typeDocp = "Conference papers";
											break;
										}
										//review, book chapter
										if ($papers[$key][$key2]['DT'] == "Book" || $papers[$key][$key2]['DT'] == "Review; Book Chapter" || $papers[$key][$key2]['DT'] == "Editorial Material; Book Chapter" || $papers[$key][$key2]['DT'] == "Article; Book Chapter") {
											$chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['BN']).'</idno>'."\r\n";
											if ($papers[$key][$key2]['PT'] == "S" || $papers[$key][$key2]['PT'] == "B" || $papers[$key][$key2]['PT'] == "C") {
												$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['SO']).'</title>'."\r\n";
											}else{
												$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['SE']).'</title>'."\r\n";
											}
											$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
											$chaine .= '                <imprint>'."\r\n";
											$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
											$chaine .= '                  <pubPlace>'.supprAmp($papers[$key][$key2]['PI']).'</pubPlace>'."\r\n";
											$chaine .= '                  <biblScope unit="serie">'.supprAmp($papers[$key][$key2]['SE']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['VL']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['IS']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['BP']).'-'.$papers[$key][$key2]['EP'].'</biblScope>'."\r\n";
											if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
											$chaine .= '                </imprint>'."\r\n";
											if ($papers[$key][$key2]['PT'] == "S" || $papers[$key][$key2]['PT'] == "B" || $papers[$key][$key2]['PT'] == "C") {
												$typeDoc = "COUV";
												$typeDocp = "Book sections";//???
											}else{
												$typeDoc = "BOOK";
												$typeDocp = "Book";//???
											}
											break;
										}
									case "p"://patent > il n'y en a pas dans WoS
										$typeDoc = "PATENT";
										$typeDocp = "Patent";//???
										break;
								}
							}
							$chaine .= '              </monogr>'."\r\n";
							if (isset($papers[$key][$key2]['DI']) && $papers[$key][$key2]['DI'] != "")
							{
								$chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DI']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PM']) && $papers[$key][$key2]['PM'] != "")
							{
								$chaine .= '              <idno type="pubmed">'.supprAmp(str_replace("WOS:", "", $papers[$key][$key2]['PM'])).'</idno>'."\r\n";
							}
							$chaine .= '            </biblStruct>'."\r\n";
							$chaine .= '          </sourceDesc>'."\r\n";
							$chaine .= '          <profileDesc>'."\r\n";
							//langue
							$chaine .= '            <langUsage>'."\r\n";
							$chaine .= '              <language ident="'.$lng.'">'.supprAmp($papers[$key][$key2]['LA']).'</language>'."\r\n";
							$chaine .= '            </langUsage>'."\r\n";
							$chaine .= '            <textClass>'."\r\n";
							//mots-clés
							if (isset($papers[$key][$key2]['DE']) && $papers[$key][$key2]['DE'] != "")
							{
								$chaine .= '             <keywords scheme="author">'."\r\n";
								$aut = explode(";", $papers[$key][$key2]['DE']);
								foreach ($aut as $qui) {
									$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
								}
								$chaine .= '             </keywords>'."\r\n";
							}
							//domaine HAL
							//$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
							//Typologie
							$chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
							$chaine .= '            </textClass>'."\r\n";
							$chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['AB']).'</abstract>'."\r\n";
							$chaine .= '          </profileDesc>'."\r\n";
							$chaine .= '        </biblFull>'."\r\n";
							$chaine .= '      </listBibl>'."\r\n";
							$chaine .= '    </body>'."\r\n";
							if (count($strTab) > 0) {//Existence of one or more affiliation(s)
								$chaine .= '      <back>'."\r\n";
								$chaine .= '        <listOrg type="structures">'."\r\n";
								$indT = 1;
								foreach ($strTab as $labElt) {
									$pays = "";
									$codePays = "";
									$eltTab = explode("~|~", $labElt);
									$pays = $eltTab[count($eltTab)-1];
									$codePays = array_search(strtoupper($pays), $countries);
									$chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
									$orgName = $eltTab[2];
									$orgName = str_replace(array("UR1", " UR1"), "", $orgName);
									if ($eltTab[3] == "institution") {//abbreviation between crochet
										if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
											$nameTab = explode(",", $orgName);
											$ville = $nameTab[count($nameTab)-2];
											$ville = str_ireplace(" cedex", "", $ville);
											$villeTab = explode(" ", $ville);
											$orgName = "CHU ".$villeTab[count($villeTab)-1];
										}
										$nameTab = explode(",", $orgName);
										$orgName = "";
										$oN = 0;
										$iName = 0;
										foreach ($nameTab as $name) {
											if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
												if ($name == "Universite CHU") {
													$ville = $nameTab[count($nameTab)-2];
													$ville = str_ireplace(" cedex", "", $ville);
													$villeTab = explode(" ", $ville);
													$name = $name.' '.$villeTab[count($villeTab)-1];
												}
												if ($oN == 0) {
													$oN = 1;
												}else{
													$orgName .= ", ";
												}
												$eltNameTab = explode(" ", trim($name));
												$oNE = 0;
												foreach ($eltNameTab as $elt) {
													if ($oNE == 0) {
														$oNE = 1;
													}else{
														$orgName .= " ";
													}
													if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3) {
														$orgName .= "[".trim($elt)."]";
													}else{
														$orgName .= "".trim($elt);
													}
												}
											}
											$iName += 1;
										}
									}
									//suppression/remplacement divers
									$orgName = str_replace(array("(", ")"), "", $orgName);
									$orgName = str_replace("/", " ", $orgName);
									//test présence 'Department' ou 'Service d' pour suppression
									$orgTab = explode(", ", $orgName);
									if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
										$orgTab[0] = "";
										$orgName = "";
										for($dpt = 0; $dpt < count($orgTab); $dpt++) {
											if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
										}
										$orgName = substr($orgName, 0, (strlen($orgName) - 2));
									}
									$chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";
									$pays = str_replace(".", "", strtoupper($eltTab[4]));
									if ($pays != "")
									{
										$keyP = array_search($pays, $countries, true); 
										$chaine .= '            <desc>'."\r\n";
										$chaine .= '              <address>'."\r\n";
										$chaine .= '                <country key="'.$keyP.'"></country>'."\r\n";
										$chaine .= '              </address>'."\r\n";
										$chaine .= '            </desc>'."\r\n";
									}
									$chaine .= '          </org>'."\r\n";
									$indT++;
								}
								$chaine .= '        </listOrg>'."\r\n";
								$chaine .= '      </back>'."\r\n";
							}
							$chaine .= '  </text>'."\r\n";
							$chaine .= '</TEI>';
							fwrite($inF,$chaine);
							fclose($inF);
							$zip->addFile($Fnm);
							$zip->close();
							unlink($Fnm);
						}
						break;

					case "scopus":
						$aut = $papers[$key][$key2]['"Authors"'];
						$autTab = explode("; ",$aut);
						if (count($autTab) <= $limNbAut)
						{
							//affiliation
							$autTab = array();
							$labTab = array();
							$autInd = 0;
							$docid = 0;
							$label = "";
							$code = "";
							$cuHAL = "";
							$type = "";
							$pays = "";
							$quoi = $papers[$key][$key2]['Authors with affiliations'];
							$quoi = trimUltime($quoi);
							$aut = explode(";", $papers[$key][$key2]['"Authors"']);
							$validHAL = "";//to privilegy the search by the unit code number rather than acronym
							//echo "<br>".$j." - ".$quoi."<br>";
							$diffQuoi = explode(";", $quoi);
							//var_dump($diffQuoi);
							//Vérification d'une "fausse" affiliation
							for ($d = 0; $d < count($diffQuoi); $d++) {
								$verifTab = explode(',', $diffQuoi[$d]);
								$aff = 'no';
								for ($a = 0; $a < count($aut); $a++) {
									if (trim($verifTab[0]) == trim($aut[$a])) {
										$aff = 'ok';
									}
								}
								if ($aff == 'no') {unset($diffQuoi[$d]);}
							}
							//var_dump($diffQuoi);
							$diffQuoi = array_merge($diffQuoi);
							//var_dump($diffQuoi);
							for ($d = 0; $d < count($diffQuoi); $d++)
							{
								//Search for the author's name
								$nom = "";
								$quiTab = explode(" ", $aut[$d]);
								if (!isset($quiTab[1])) {$prenom = "";}//in case of no comma for one author
								foreach($quiTab as $elt) {
									if (strpos($elt, ".") === false)//no point > part of the name
									{
										$nom .= " ".supprAmp(trim($elt));
									}
								}
								$urlHAL = "";
								$docid = 0;
								$label = "";
								$code = "";
								$cuHAL = "";
								$type = "";
								$pays = "";
								$eltTab = explode(",", $diffQuoi[$d]);
								$aTester = "";
								//if (isset($eltTab[0]) && isset($eltTab[1])) {$aTester = "[".$eltTab[0].",".$eltTab[1]."]";}//to retrieve the WoS structure for affilId function
								if (isset($eltTab[0])) {$aTester = "[".trim($eltTab[0])."]";}//to retrieve the WoS structure for affilId function
								//Add a comma between the first and last name
								$aTester = str_replace(trim($nom), trim($nom).',', $aTester);
								for ($t = 1; $t < count($eltTab); $t++)
								{
									if ($t != 1) {$aTester .= ", ".trim($eltTab[$t]);}else{$aTester .= $eltTab[$t];}
								}
								//echo $aTester.'<br>';
								//Search for distinctive acronyms
								searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
								//echo 'toto : '.$aTester.' - '.$urlHAL.'<br>';
								if ($urlHAL != "")//affiliation with code unit or acronym found
								{
									//echo $aTester.'<br>';
									idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
								}
								//echo $docid.'<br>';
								if($docid != 0)
								{
									affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
									while ($retTest != "") {
										$aTester = $retTest;
										$autInd = 0;
										$urlHAL = "";
										$docid = 0;
										$label = "";
										$code = "";
										$cuHAL = "";
										$type = "";
										$pays = "";
										//Search for distinctive acronyms
										//echo 'toto : '.$aTester.'<br>';
										searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
										//echo 'titi : '.$urlHAL.'<br>';
										if ($urlHAL != "")//affiliation with code unit or acronym found
										{
											idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
										}
										//echo $urlHAL.' - '.$docid.'<br>';
										if($docid != 0)
										{
											affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
										}
									}
								}else{//no specific affiliation found > we store whole results
									//echo $aTester.'<br>';
									$validHAL = "--";
									$eltTab = explode("] ",$aTester);
									include './OverHAL_unites_termes.php';
									if (isset($eltTab[1])) {$label = supprAmp(trim($eltTab[1]));}
									$label = str_ireplace($search, $replace, $label);
									//echo 'titi : '.$label.'<br>';
									//$docid = 999999;
									$docid = $label;
									$code = $label;
									$type = 'institution';
									//echo $aTester.'<br>';
									affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
								}
							}
							//var_dump($autTab);
							//var_dump($labTab);
							$strTab = array();
							$unqOrg = array();//Tableau pour tester l'unicité des organismes
							$eid = supprAmp($papers[$key][$key2]['EID']);//unique academic work identifier assigned in Scopus bibliographic database
							mb_internal_encoding('UTF-8');
							$zip = new ZipArchive();
							$FnmZ = "./HAL/OverHAL_scopus.zip";
							if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
								exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
							}
							$Fnm = "./HAL/OverHAL_scopus_".$eid.".xml";
							$inF = fopen($Fnm,"a+");
							fseek($inF, 0);
							//$chaine = "\xEF\xBB\xBF";//UTF-8
							$chaine = "";//ANSI
							$chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
							$chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
							$chaine .= '  <text>'."\r\n".
												 '    <body>'."\r\n".
												 '      <listBibl>'."\r\n".
												 '        <biblFull>'."\r\n".
												 '          <titleStmt>'."\r\n";
							//funder
							if (isset($papers[$key][$key2]['Funding Details'])) {
								$chaine .= '            <funder>'.supprAmp($papers[$key][$key2]['Funding Details']).'</funder>'."\r\n";
							}
							$chaine .= '          </titleStmt>'."\r\n";

							//if DOI exists searching an OA PDF file
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DOI'];
								$volT = $papers[$key][$key2]['Volume'];
								$issT = $papers[$key][$key2]['Issue'];
								$pagT = $papers[$key][$key2]['Page start'];
								$datT = $papers[$key][$key2]['Year'];
								$pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

								testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $urlPDF);

								if ($urlPDF != "")//an OA PDF file has benn found
								{
									$urlPDF = htmlspecialchars($urlPDF);
									$chaine .= '          <editionStmt>'."\r\n".
														 '            <edition>'."\r\n".
														 '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$urlPDF.'"></ref>'."\r\n".
														 '            </edition>'."\r\n".
														 '          </editionStmt>'."\r\n";
									$avail = 'http://creativecommons.org/licenses/by';
									if ($compNC != "") {$avail .= '-nc';}
									if ($compND != "") {$avail .= '-nd';}
									if ($compSA != "") {$avail .= '-sa';}
									$avail .= '/';
									$chaine .= '          <publicationStmt>'."\r\n".
														 '            <availability>'."\r\n".
														 '              <licence target="'.$avail.'"/>'."\r\n".
														 '            </availability>'."\r\n".
														 '          </publicationStmt>'."\r\n";
								}
							}
							$chaine .= '          <seriesStmt>'."\r\n".
												 '          </seriesStmt>'."\r\n".
												 '          <notesStmt>'."\r\n".
							//Audience
							$aud = "";
							if (isset($papers[$key][$key2]['Language of Original Document']))
							{
								if ($papers[$key][$key2]['Language of Original Document'] == "English") {
									$chaine .= '          	<note type="audience" n="2"/>'."\r\n";
								}else{
									$chaine .= '          	<note type="audience" n="3"/>'."\r\n";
								}
							}
							$chaine .= '						<note type="popular" n="0">No</note>'."\r\n";
							$chaine .= '						<note type="peer" n="1">Yes</note>'."\r\n";
							$chaine .= '          </notesStmt>'."\r\n".
												 '          <sourceDesc>'."\r\n".
												 '            <biblStruct>'."\r\n".
												 '              <analytic>'."\r\n";
							$lng = "";
							//langue + titre
							if (isset($papers[$key][$key2]['Language of Original Document']))
							{
								if ($papers[$key][$key2]['Language of Original Document'] == "French") {$lng = "fr";}
								if ($papers[$key][$key2]['Language of Original Document'] == "English") {$lng = "en";}
							}
							$chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Title']).'</title>'."\r\n";
							//auteurs
							$aut = explode(";", $papers[$key][$key2]['"Authors"']);
							$autComplet = explode(";", $papers[$key][$key2]['Author full names']);
							//var_dump($aut);
							$iTp = 0;
							$iAut = 0;
							foreach ($aut as $qui) {
								$nom = "";
								$prenom = "";
								$prenomComplet = "";
								$quiTab = explode(" ", trim($qui));
								if (!isset($quiTab[1])) {$prenom = "";}//in case of no comma for one author
								//var_dump($quiTab);
								foreach($quiTab as $elt) {
									if (strpos($elt, ".") === false)//no point > part of the name
									{
										$nom .= " ".supprAmp(trim($elt));
									}else{
										$prenom .= supprAmp(trim($elt));
									}
								}
								$nom = trim($nom);
								$prenomComplet = $autComplet[$iAut];
								//If present, ignore numbers in brackets
								if (strpos($prenomComplet, ' (') !== false) {$prenomComplet = substr($prenomComplet, 0, strpos($prenomComplet, ' ('));}
								$prenomComplet = trim(str_replace(trim($nom).', ', '', $prenomComplet));
								$nompre = $nom .", ".$prenom;
								$rolAut = "aut";
								if (stripos($papers[$key][$key2]['Correspondence Address'], substr($prenom, 0, 1).". ".$nom) !== false) {$rolAut = "crp";}
								//echo $nompre.'<br>';
								if ($prenom != "") {
									$chaine .= '                <author role="'.$rolAut.'">'."\r\n";
									$chaine .= '                  <persName>'."\r\n";
									$chaine .= '                    <forename type="first">'.$prenomComplet.'</forename>'."\r\n";
									$chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
									$chaine .= '                  </persName>'."\r\n";
									//Si auteur correspondant, recherche du mail
									if ($rolAut == "crp") {
										if (stripos($papers[$key][$key2]['Correspondence Address'], "email") !== false) {
											$tabCrp = explode("email:", $papers[$key][$key2]['Correspondence Address']);
											//$chaine .= '                  <email>'.trim($tabCrp[1]).'</email>'."\r\n";
											$chaine .= '                  <email type="domain">'.str_replace('@', '', strstr(trim($tabCrp[1]), '@')).'</email>'."\r\n";
										}
									}
									$kT = array_search($nompre, $autTab);
									//echo $kT." - ".$nom." - ".$labTab[$nompre][$kT]."<br>";
									if ($kT !== FALSE) {
										foreach ($labTab[$nompre] as $lab) {
											$orgName = testLab($lab);
											//echo $orgName;
											//$str = array_search($labTab[$nompre][$kT], $strTab);
											$str = array_search($orgName, $unqOrg);
											if ($str === FALSE) {
												$iTp += 1;
												$kTp = $iTp;
												array_push($strTab, $lab);
												array_push($unqOrg, $orgName);
											}else{
												$kTp = $str + 1;
											}
											$chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
											//echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
										}
									}
									$chaine .= '                </author>'."\r\n";
								}
								$iAut++;
							}
							//var_dump($strTab);
							$chaine .= '              </analytic>'."\r\n";
							//journal
							$chaine .= '              <monogr>'."\r\n";
							//ISSN
							$chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['ISSN']).'</idno>'."\r\n";
							//ISBN
							$chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['ISBN']).'</idno>'."\r\n";
							if (isset($papers[$key][$key2]['Document Type']))
							{
								$typDoc = "";
								$typDocp = "";
								$type = strtolower($papers[$key][$key2]['Document Type']);
								switch($type)
								{
									case "article"://article
									case "article in press":
									case "review":
									case "erratum":
									case "editorial":
									case "short survey":
									case "letter":
									case "note":
										$chaine .= '                <title level="j">'.supprAmp($papers[$key][$key2]['Source title']).'</title>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Publisher']).'</publisher>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										if ($papers[$key][$key2]['Page start'] != "" && $papers[$key][$key2]['Page end'] != "") {
											$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Page start']).'-'.$papers[$key][$key2]['Page end'].'</biblScope>'."\r\n";
										}else{
											if ($papers[$key][$key2]['Art. No.'] != "") {
												$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Art. No.']).'</biblScope>'."\r\n";
											}
										}                  $chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Year']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "ART";
										$typeDocp = "Journal articles";
										break;
									case "conference paper"://inproceedings
									case "conference review":
										//Source Title
										$chaine .= '                <title level="j">'.supprAmp($papers[$key][$key2]['Source title']).'</title>'."\r\n";
										$chaine .= '                <meeting>'."\r\n";
										$chaine .= '                  <title>'.supprAmp($papers[$key][$key2]['Conference name']).'</title>'."\r\n";
										$confStartDate = "";
										$confEndDate = "";
										if (isset($papers[$key][$key2]['Conference date']))
										{
											//Is it a valid date?
											if (strpos($papers[$key][$key2]['Conference date'], "through") === false)
											{
												$chaine = "";
												fclose($inF);
												break 2;
											}else{
												//6 September 2015 through 9 September 2015 to 2015-09-06
												$confdate = $papers[$key][$key2]['Conference date'];
												if ($confdate != "") {
													$res = explode(" ", $confdate);
													if (count($res) > 2) {
														//year
														$confStartDate = trim($res[2])."-";
														$confEndDate = trim($res[6])."-";
														//start month
														switch(strtolower($res[1]))
														{
															case "january":
																$confStartDate .= "01-";
																break;
															case "february":
																$confStartDate .= "02-";
																break;
															case "march":
																$confStartDate .= "03-";
																break;
															case "april":
																$confStartDate .= "04-";
																break;
															case "may":
																$confStartDate .= "05-";
																break;
															case "june":
																$confStartDate .= "06-";
																break;
															case "july":
																$confStartDate .= "07-";
																$confEndDate .= "07-";
																break;
															case "august":
																$confStartDate .= "08-";
																break;
															case "september":
																$confStartDate .= "09-";
																break;
															case "october":
																$confStartDate .= "10-";
																break;
															case "november":
																$confStartDate .= "11-";
																break;
															case "december":
																$confStartDate .= "12-";
																break;
														}
														//start month
														switch(strtolower($res[5]))
														{
															case "january":
																$confEndDate .= "01-";
																break;
															case "february":
																$confEndDate .= "02-";
																break;
															case "march":
																$confEndDate .= "03-";
																break;
															case "april":
																$confEndDate .= "04-";
																break;
															case "may":
																$confEndDate .= "05-";
																break;
															case "june":
																$confEndDate .= "06-";
																break;
															case "july":
																$confEndDate .= "07-";
																break;
															case "august":
																$confEndDate .= "08-";
																break;
															case "september":
																$confEndDate .= "09-";
																break;
															case "october":
																$confEndDate .= "10-";
																break;
															case "november":
																$confEndDate .= "11-";
																break;
															case "december":
																$confEndDate .= "12-";
																break;
														}
														//start day
														if (strlen($res[0]) == 1)
														{
															$confStartDate .= "0".$res[0];
														}else{
															$confStartDate .= $res[0];
														}
														//end day
														if (strlen($res[4]) == 1)
														{
															$confEndDate .= "0".$res[4];
														}else{
															$confEndDate .= $res[4];
														}
													}
												}
											}
										}else{
											$confStartDate = $papers[$key][$key2]['Year'];
											$confEndDate = $papers[$key][$key2]['Year'];
										}
										$chaine .= '                  <date type="start">'.$confStartDate.'</date>'."\r\n";//comment différencier date début et fin ?
										$chaine .= '                  <date type="end">'.$confEndDate.'</date>'."\r\n";//comment différencier date début et fin ?
										$chaine .= '                  <settlement>'.supprAmp($papers[$key][$key2]['Conference location']).'</settlement>'."\r\n";
										$chaine .= '                </meeting>'."\r\n";
										$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['Editors']).'</editor>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Publisher']).'</publisher>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										if ($papers[$key][$key2]['Page start'] != "" && $papers[$key][$key2]['Page end'] != "") {
											$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Page start']).'-'.$papers[$key][$key2]['Page end'].'</biblScope>'."\r\n";
										}else{
											if ($papers[$key][$key2]['Art. No.'] != "") {
												$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Art. No.']).'</biblScope>'."\r\n";
											}
										}
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Year']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "COMM";
										$typeDocp = "Conference papers";
										break;
									case "book"://book
									case "book chapter":
										$chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['ISBN']).'</idno>'."\r\n";
										$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['Source title']).'</title>'."\r\n";
										$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['Editors']).'</editor>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Publisher']).'</publisher>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Page start']).'-'.$papers[$key][$key2]['Page end'].'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Year']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										if ($type == "book") {
											$typeDoc = "OUV";
											$typeDocp = "Ouv";
										}else{
											$typeDoc = "COUV";
											$typeDocp = "Couv";
										}
										break;
									case "p"://patent > il n'y en a pas dans WoS
										$typeDoc = "PATENT";
										$typeDocp = "Patent";//???
										break;
								}
							}
							$chaine .= '              </monogr>'."\r\n";
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DOI']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PubMed ID']) && $papers[$key][$key2]['PubMed ID'] != "")
							{
								$chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['PubMed ID']).'</idno>'."\r\n";
							}
							$chaine .= '            </biblStruct>'."\r\n";
							$chaine .= '          </sourceDesc>'."\r\n";
							$chaine .= '          <profileDesc>'."\r\n";
							//langue
							$chaine .= '            <langUsage>'."\r\n";
							$chaine .= '              <language ident="'.$lng.'">'.supprAmp($papers[$key][$key2]['Language of Original Document']).'</language>'."\r\n";
							$chaine .= '            </langUsage>'."\r\n";
							$chaine .= '            <textClass>'."\r\n";
							//mots-clés
							if (isset($papers[$key][$key2]['Author Keywords']) && $papers[$key][$key2]['Author Keywords'] != "")
							{
								$chaine .= '             <keywords scheme="author">'."\r\n";
								$aut = explode(";", $papers[$key][$key2]['Author Keywords']);
								foreach ($aut as $qui) {
									$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
								}
								$chaine .= '             </keywords>'."\r\n";
							}else{
								if (isset($papers[$key][$key2]['Index Keywords']) && $papers[$key][$key2]['Index Keywords'] != "")
								{
									$chaine .= '             <keywords scheme="author">'."\r\n";
									$aut = explode(";", $papers[$key][$key2]['Index Keywords']);
									foreach ($aut as $qui) {
										$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
									}
									$chaine .= '             </keywords>'."\r\n";
								}
							}
							//domaine HAL
							//$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
							//Typologie
							$chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
							$chaine .= '            </textClass>'."\r\n";
							$chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Abstract']).'</abstract>'."\r\n";
							$chaine .= '          </profileDesc>'."\r\n";
							$chaine .= '        </biblFull>'."\r\n";
							$chaine .= '      </listBibl>'."\r\n";
							$chaine .= '    </body>'."\r\n";
							if (count($strTab) > 0) {//Existence of one or more affiliation(s)
								$chaine .= '      <back>'."\r\n";
								$chaine .= '        <listOrg type="structures">'."\r\n";
								$indT = 1;
								foreach ($strTab as $labElt) {
									$pays = "";
									$codePays = "";
									$eltTab = explode("~|~", $labElt);
									$pays = $eltTab[count($eltTab)-1];
									$codePays = array_search(strtoupper($pays), $countries);
									$chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
									$orgName = $eltTab[2];
									if ($eltTab[3] == "institution") {//abbreviation between crochet
										if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
											$nameTab = explode(",", $orgName);
											$ville = trim(netNum($nameTab[count($nameTab)-2]));
											$ville = str_ireplace(" cedex", "", $ville);
											$villeTab = explode(" ", $ville);
											//$orgName = "CHU ".$villeTab[count($villeTab)-1];
											$orgName = "CHU ".$villeTab[0];
										}
										$nameTab = explode(",", $orgName);
										$orgName = "";
										$oN = 0;
										if (count($nameTab) > 2) {//penultimate term > only if there is more than 2 elements
											$iName = 0;
										}else{
											$iName = count($nameTab);
										}
										foreach ($nameTab as $name) {
											if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
												if ($name == "Universite CHU") {
													$ville = $nameTab[count($nameTab)-2];
													$ville = str_ireplace(" cedex", "", $ville);
													$villeTab = explode(" ", $ville);
													$name = $name.' '.$villeTab[count($villeTab)-1];
												}
												if ($oN == 0) {
													$oN = 1;
												}else{
													$orgName .= ", ";
												}
												$eltNameTab = explode(" ", trim($name));
												$oNE = 0;
												foreach ($eltNameTab as $elt) {
													if ($oNE == 0) {
														$oNE = 1;
													}else{
														$orgName .= " ";
													}
													if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) &&  strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
														$orgName .= "[".trim($elt)."]";
													}else{
														$orgName .= "".trim($elt);
													}
												}
											}
											$iName += 1;
										}
									}
									//suppression/remplacement divers
									$orgName = str_replace(array("(", ")"), "", $orgName);
									$orgName = str_replace("/", " ", $orgName);
									//test présence 'Department' ou 'Service d' pour suppression
									$orgTab = explode(", ", $orgName);
									if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
										$orgTab[0] = "";
										$orgName = "";
										for($dpt = 0; $dpt < count($orgTab); $dpt++) {
											if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
										}
										$orgName = substr($orgName, 0, (strlen($orgName) - 2));
									}
									$chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";
									$pays = str_replace(".", "", strtoupper($eltTab[4]));
									if ($codePays != "") {
										$chaine .= '            <desc>'."\r\n";
										$chaine .= '              <address>'."\r\n";
										$chaine .= '                <country key="'.$codePays.'"/>'."\r\n";
										$chaine .= '              </address>'."\r\n";
										$chaine .= '            </desc>'."\r\n";
									}
									$chaine .= '          </org>'."\r\n";
									$indT++;
								}
								$chaine .= '        </listOrg>'."\r\n";
								$chaine .= '      </back>'."\r\n";
							}
							$chaine .= '  </text>'."\r\n";
							$chaine .= '</TEI>';
							fwrite($inF,$chaine);
							fclose($inF);
							$zip->addFile($Fnm);
							$zip->close();
							unlink($Fnm);
						}
						break;
						
					case "pubmed_xml":
					case "pubmed_txt":
						$aut = $papers[$key][$key2]['Auteurs'];
						$autTab = explode("; ",$aut);
						if (count($autTab) <= $limNbAut)
						{
							//affiliation
							$autTab = array();
							$labTab = array();
							$autInd = 0;
							$docid = 0;
							$label = "";
							$code = "";
							$cuHAL = "";
							$type = "";
							$pays = "";
							$quoi = $papers[$key][$key2]['Affiliation'];
							$quoi = trimUltime($quoi);
							$validHAL = "";//to privilegy the search by the unit code number rather than acronym
							//echo "<br>".$j." - ".$quoi."<br>";
							$diffQuoi = explode("; [", $quoi);
							//var_dump($diffQuoi);
							for ($d = 0; $d < count($diffQuoi); $d++)
							{
								$urlHAL = "";
								$docid = 0;
								$label = "";
								$code = "";
								$cuHAL = "";
								$type = "";
								$pays = "";
								//Search for distinctive acronyms
								//echo $diffQuoi[$d].'<br>';
								searchAcro($diffQuoi[$d], $cuHAL, $urlHAL, $validHAL, $retTest);
								//echo $urlHAL.'<br>';
								if ($urlHAL != "")//affiliation with code unit or acronym found
								{
									//echo $diffQuoi[$d].'<br>';
									idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
								}
								//echo $docid.' - '.$urlHAL.'<br>';
								if($docid != 0)
								{
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
									while ($retTest != "") {
										$aTester = $retTest;
										$autInd = 0;
										$urlHAL = "";
										$docid = 0;
										$label = "";
										$code = "";
										$cuHAL = "";
										$type = "";
										$pays = "";
										//Search for distinctive acronyms
										//echo 'toto : '.$aTester.'<br>';
										searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
										//echo 'titi : '.$urlHAL.'<br>';
										if ($urlHAL != "")//affiliation with code unit or acronym found
										{
											idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
										}
										//echo $urlHAL.' - '.$docid.'<br>';
										if($docid != 0)
										{
											affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
										}
									}
								}else{//no specific affiliation found > we store whole results
									//echo $diffQuoi[$d].'<br>';
									$validHAL = "--";
									$eltTab = explode("] ",$diffQuoi[$d]);
									include './OverHAL_affiliation_termes.php';
									if (isset($eltTab[1])) {$label = supprAmp(trim($eltTab[1]));}
									//$label = str_ireplace($search, $replace, $label);
									//echo $label.'<br>';
									$queTab = explode(",", $label);
									$quePay = count($queTab) - 1;
									$labFin = "";
									$iq = 0;
									foreach($queTab as $elt)
									{
										$elt = trim($elt);
										if ($iq > 0) {$labFin .= ", ";}
										$labFin .= $elt;
										$iq++;
									}
									//$docid = 999999;
									$docid = $labFin;
									$code = $labFin;
									$type = 'institution';
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);
								}
							}
							//var_dump($autTab);
							//var_dump($labTab);
							$strTab = array();
							$unqOrg = array();//Tableau pour tester l'unicité des organismes
							$pmid = supprAmp($papers[$key][$key2]['PMID']);//PMID
							mb_internal_encoding('UTF-8');
							$zip = new ZipArchive();
							$FnmZ = "./HAL/OverHAL_".$key.".zip";
							if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
								exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
							}
							$Fnm = "./HAL/OverHAL_".$key."_".$pmid.".xml";
							$inF = fopen($Fnm,"a+");
							fseek($inF, 0);
							//$chaine = "\xEF\xBB\xBF";//UTF-8
							$chaine = "";//ANSI
							$chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
							$chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
							$chaine .= '  <text>'."\r\n".
												 '    <body>'."\r\n".
												 '      <listBibl>'."\r\n".
												 '        <biblFull>'."\r\n".
												 '          <titleStmt>'."\r\n";
							//funder
							if ($papers[$key][$key2]['Finance'] != "") {
								$eltFun = explode("~|~", $papers[$key][$key2]['Finance']);
								foreach($eltFun as $elt) {
									$chaine .= '            <funder>'.supprAmp($elt).'</funder>'."\r\n";
								}
							}
							$chaine .= '          </titleStmt>'."\r\n";
							//if DOI exists searching an OA PDF file
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DOI'];
								$volT = $papers[$key][$key2]['Volume'];
								$issT = $papers[$key][$key2]['Numero'];
								$pagT = $papers[$key][$key2]['Pagination'];
								$datT = $papers[$key][$key2]['DatePub'];
								$pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

								testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $urlPDF);

								if ($urlPDF != "")//an OA PDF file has benn found
								{
									$urlPDF = htmlspecialchars($urlPDF);
									$chaine .= '          <editionStmt>'."\r\n".
														 '            <edition>'."\r\n".
														 '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$urlPDF.'"></ref>'."\r\n".
														 '            </edition>'."\r\n".
														 '          </editionStmt>'."\r\n";
									$avail = 'http://creativecommons.org/licenses/by';
									if ($compNC != "") {$avail .= '-nc';}
									if ($compND != "") {$avail .= '-nd';}
									if ($compSA != "") {$avail .= '-sa';}
									$avail .= '/';
									$chaine .= '          <publicationStmt>'."\r\n".
														 '            <availability>'."\r\n".
														 '              <licence target="'.$avail.'"/>'."\r\n".
														 '            </availability>'."\r\n".
														 '          </publicationStmt>'."\r\n";
								}
							}
							$chaine .= '          <seriesStmt>'."\r\n".
												 '          </seriesStmt>'."\r\n".
												 '          <notesStmt>'."\r\n".
							//Audience
							$aud = "";
							if (isset($papers[$key][$key2]['Langue']))
							{
								if ($papers[$key][$key2]['Langue'] == "eng") {
									$chaine .= '          	<note type="audience" n="2"/>'."\r\n";
								}else{
									$chaine .= '          	<note type="audience" n="3"/>'."\r\n";
								}
							}
							$chaine .= '						<note type="popular" n="0">No</note>'."\r\n";
							$chaine .= '						<note type="peer" n="1">Yes</note>'."\r\n";
							$chaine .= '          </notesStmt>'."\r\n".
												 '          <sourceDesc>'."\r\n".
												 '            <biblStruct>'."\r\n".
												 '              <analytic>'."\r\n";
							$lng = "";
							//langue + titre
							if (isset($papers[$key][$key2]['Langue']))
							{
								if ($papers[$key][$key2]['Langue'] == "fre") {$lng = "fr";}
								if ($papers[$key][$key2]['Langue'] == "eng") {$lng = "en";}
							}
							$chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Titre']).'</title>'."\r\n";
							//auteurs
							$aut = explode("; ", $papers[$key][$key2]['Auteurs']);
							$iTp = 0;
							foreach ($aut as $qui) {
								$quiTab = explode(", ", $qui);
								if (isset($quiTab[1]))//in case of no comma for one author
								{
									$prenom = supprAmp(trim($quiTab[1]));
								}else{
									$prenom = " ";
								}
								$nom = supprAmp(trim($quiTab[0]));
								$nompre = $nom .", ".$prenom;
								if ($prenom != "") {
									$chaine .= '                <author role="aut">'."\r\n";
									$chaine .= '                  <persName>'."\r\n";
									$chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
									$chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
									$chaine .= '                  </persName>'."\r\n";
									$kT = array_search($nompre, $autTab);
									//echo $kT." - ".$nom."<br>";
									
									if (isset($papers[$key][$key2]['idORCID']))
									{//ORCID
										$tabOrcid = explode(";", $papers[$key][$key2]['idORCID']);
										foreach ($tabOrcid as $elt) {
											if (stripos(trim(normalize($elt)), normalize($nom.', '.$prenom)) !== false)
											{
												$chaine .= '                  <idno type="ORCID">https://orcid.org/'.str_ireplace($nom.", ".$prenom."/", "", trim($elt)).'</idno>'."\r\n";
												break;//Orcid ajouté > on sort de la boucle
											}
										}
									}
									
									//var_dump($autTab);
									if ($kT !== FALSE) {
										foreach ($labTab[$nompre] as $lab) {
											$orgName = testLab($lab);
											//echo $orgName;
											//$str = array_search($labTab[$nompre][$kT], $strTab);
											$str = array_search($orgName, $unqOrg);
											if ($str === FALSE) {
												$iTp += 1;
												$kTp = $iTp;
												array_push($strTab, $lab);
												array_push($unqOrg, $orgName);
											}else{
												$kTp = $str + 1;
											}
											$chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
											//echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
										}
									}
									$chaine .= '                </author>'."\r\n";
								}
							}
							//var_dump($strTab);
							$chaine .= '              </analytic>'."\r\n";
							//journal
							$chaine .= '              <monogr>'."\r\n";
							//ISSN
							$chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['ISSN']).'</idno>'."\r\n";
							//EISSN
							$chaine .= '                <idno type="eissn">'.supprAmp($papers[$key][$key2]['EISSN']).'</idno>'."\r\n";

							if (isset($papers[$key][$key2]['Type']))
							{
								$typDef = "";
								$typDoc = "";
								$typDocp = "";
								$type = $papers[$key][$key2]['Type'];
								//if (strpos($type, "Journal Article") !== false) {$typeDef = "Journal Article";}
								$typeDef = "Journal Article";
								switch(strtolower($typeDef))
								{
									case "journal article"://article
										$chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['Revue'])).'</title>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Numero']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Pagination']).'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['DatePub']).'</date>'."\r\n";
										$chaine .= '                  <date type="dateEpub">'.supprAmp($papers[$key][$key2]['DateMel']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "ART";
										$typeDocp = "Journal articles";
										break;
									/*
									case "s":
									case "c":
										//inproceedings
										if ($papers[$key][$key2]['DT'] == "Proceedings Paper") {
											$chaine .= '                <title level="j">'.supprAmp($papers[$key][$key2]['SO']).'</title>'."\r\n";
											$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
											$chaine .= '                <meeting>'."\r\n";
											$chaine .= '                  <title>'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
											$confStartDate = "";
											$confEndDate = "";
											if (isset($papers[$key][$key2]['CY']))
											{
												//OCT 19-23, 2014 to 2014-10-19
												$confdate = $papers[$key][$key2]['CY'];
												if ($confdate != "") {
													$res = explode(" ", $confdate);
													if (count($res) > 2) {
														//year
														$confStartDate = trim($res[2])."-";
														$confEndDate = trim($res[2])."-";
														//month
														switch($res[0])
														{
															case "JAN":
																$confStartDate .= "01-";
																$confEndDate .= "01-";
																break;
															case "FEB":
																$confStartDate .= "02-";
																$confEndDate .= "02-";
																break;
															case "MAR":
																$confStartDate .= "03-";
																$confEndDate .= "03-";
																break;
															case "APR":
																$confStartDate .= "04-";
																$confEndDate .= "04-";
																break;
															case "MAY":
																$confStartDate .= "05-";
																$confEndDate .= "05-";
																break;
															case "JUN":
																$confStartDate .= "06-";
																$confEndDate .= "06-";
																break;
															case "JUL":
																$confStartDate .= "07-";
																$confEndDate .= "07-";
																break;
															case "AUG":
																$confStartDate .= "08-";
																$confEndDate .= "08-";
																break;
															case "SEP":
																$confStartDate .= "09-";
																$confEndDate .= "09-";
																break;
															case "OCT":
																$confStartDate .= "10-";
																$confEndDate .= "10-";
																break;
															case "NOV":
																$confStartDate .= "11-";
																$confEndDate .= "11-";
																break;
															case "DEC":
																$confStartDate .= "12-";
																$confEndDate .= "12-";
																break;
														}
														//day
														$confStartDate .= substr($res[1], 0, 2);
														$confEndDate .= substr($res[1], 3, 2);
													}
												}
											}
											$chaine .= '                  <date type="start">'.$confStartDate.'</date>'."\r\n";//comment différencier date début et fin ?
											$chaine .= '                  <date type="end">'.$confEndDate.'</date>'."\r\n";//comment différencier date début et fin ?
											$chaine .= '                  <settlement>'.supprAmp($papers[$key][$key2]['CL']).'</settlement>'."\r\n";
											$chaine .= '                </meeting>'."\r\n";
											$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
											$chaine .= '                <imprint>'."\r\n";
											$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
											if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
											$chaine .= '                </imprint>'."\r\n";
											$typeDoc = "COMM";
											$typeDocp = "Conference papers";
										}
										//review, book chapter
										if ($papers[$key][$key2]['DT'] == "Book" || $papers[$key][$key2]['DT'] == "Review; Book Chapter") {
											$chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['BN']).'</idno>'."\r\n";
											$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['TI']).'</title>'."\r\n";
											$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
											$chaine .= '                <imprint>'."\r\n";
											$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
											$chaine .= '                  <pubPlace>'.supprAmp($papers[$key][$key2]['PI']).'</pubPlace>'."\r\n";
											$chaine .= '                  <biblScope unit="serie">'.supprAmp($papers[$key][$key2]['SE']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['VL']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['IS']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['BP']).'-'.$papers[$key][$key2]['EP'].'</biblScope>'."\r\n";
											if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
											$chaine .= '                </imprint>'."\r\n";
											$typeDoc = "BOOK";
											$typeDocp = "Book";//???
										}
										break;
									case "b"://book
									case "p"://patent > il n'y en a pas dans WoS
										$typeDoc = "PATENT";
										$typeDocp = "Patent";//???
										break;
									*/
								}
							}
							$chaine .= '              </monogr>'."\r\n";
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DOI']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PMID']) && $papers[$key][$key2]['PMID'] != "")
							{
								$chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['PMID']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PMCID']) && $papers[$key][$key2]['PMCID'] != "")
							{
								$chaine .= '              <idno type="pmc">'.supprAmp($papers[$key][$key2]['PMCID']).'</idno>'."\r\n";
							}
							$chaine .= '            </biblStruct>'."\r\n";
							$chaine .= '          </sourceDesc>'."\r\n";
							$chaine .= '          <profileDesc>'."\r\n";
							//langue
							$chaine .= '            <langUsage>'."\r\n";
							$codeP = strtoupper(substr(supprAmp($papers[$key][$key2]['Langue']), 0, 2));
							if ($codeP != "EN")
							{
								$keyP = array_search(strtolower($codeP), $languages);
							}else{
								$keyP = "English";
							}
							$chaine .= '              <language ident="'.$lng.'">'.$keyP.'</language>'."\r\n";
							$chaine .= '            </langUsage>'."\r\n";
							$chaine .= '            <textClass>'."\r\n";
							//mots-clés
							if (isset($papers[$key][$key2]['MC']) && $papers[$key][$key2]['MC'] != "")
							{
								$chaine .= '             <keywords scheme="author">'."\r\n";
								$aut = explode(";", $papers[$key][$key2]['MC']);
								foreach ($aut as $qui) {
									$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
								}
								$chaine .= '             </keywords>'."\r\n";
							}
							//domaine HAL
							//$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
							//Typologie
							$chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
							$chaine .= '            </textClass>'."\r\n";
							$chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Resume']).'</abstract>'."\r\n";
							$chaine .= '          </profileDesc>'."\r\n";
							$chaine .= '        </biblFull>'."\r\n";
							$chaine .= '      </listBibl>'."\r\n";
							$chaine .= '    </body>'."\r\n";
							if (count($strTab) > 0) {//Existence of one or more affiliation(s)
								$chaine .= '      <back>'."\r\n";
								$chaine .= '        <listOrg type="structures">'."\r\n";
								$indT = 1;
								foreach ($strTab as $labElt) {
									$eltTab = explode("~|~", $labElt);
									$chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
									$orgName = $eltTab[2];
									$orgName = str_replace(array("UR1", " UR1"), "", $orgName);
									if ($eltTab[3] == "institution") {//abbreviation between crochet
										if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
											$nameTab = explode(",", $orgName);
											$ville = $nameTab[count($nameTab)-2];
											$ville = str_ireplace(" cedex", "", $ville);
											$villeTab = explode(" ", $ville);
											$orgName = "CHU ".$villeTab[count($villeTab)-1];
										}
										$nameTab = explode(",", $orgName);
										$orgName = "";
										$oN = 0;
										$iName = 0;
										foreach ($nameTab as $name) {
											if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
												if ($name == "Universite CHU") {
													$ville = $nameTab[count($nameTab)-2];
													$ville = str_ireplace(" cedex", "", $ville);
													$villeTab = explode(" ", $ville);
													$name = $name.' '.$villeTab[count($villeTab)-1];
												}
												if ($oN == 0) {
													$oN = 1;
												}else{
													$orgName .= ", ";
												}
												$eltNameTab = explode(" ", trim($name));
												$oNE = 0;
												foreach ($eltNameTab as $elt) {
													if ($oNE == 0) {
														$oNE = 1;
													}else{
														$orgName .= " ";
													}
													if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3) {
														$orgName .= "[".trim($elt)."]";
													}else{
														$orgName .= "".trim($elt);
													}
												}
											}
											$iName += 1;
										}
									}
									//suppression/remplacement divers
									$orgName = str_replace(array("(", ")"), "", $orgName);
									$orgName = str_replace("/", " ", $orgName);
									//test présence 'Department' ou 'Service d' pour suppression
									$orgTab = explode(", ", $orgName);
									if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
										$orgTab[0] = "";
										$orgName = "";
										for($dpt = 0; $dpt < count($orgTab); $dpt++) {
											if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
										}
										$orgName = substr($orgName, 0, (strlen($orgName) - 2));
									}
									$chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";
									$pays = str_replace(".", "", strtoupper($eltTab[4]));
									if ($pays != "")
									{
										$keyP = array_search($pays, $countries, true); 
										$chaine .= '            <desc>'."\r\n";
										$chaine .= '              <address>'."\r\n";
										$chaine .= '                <country key="'.$keyP.'"></country>'."\r\n";
										$chaine .= '              </address>'."\r\n";
										$chaine .= '            </desc>'."\r\n";
									}
									$chaine .= '          </org>'."\r\n";
									$indT++;
								}
								$chaine .= '        </listOrg>'."\r\n";
								$chaine .= '      </back>'."\r\n";
							}
							$chaine .= '  </text>'."\r\n";
							$chaine .= '</TEI>';
							fwrite($inF,$chaine);
							fclose($inF);
							$zip->addFile($Fnm);
							$zip->close();
							unlink($Fnm);
						}
						break;
						
					case "pubmed_fcgi":
						$aut = $papers[$key][$key2]['tabAut'];
						$autTab = explode("~",$aut);
						if (count($autTab) <= $limNbAut)
						{
							//affiliation
							$autTab = array();
							$labTab = array();
							$autInd = 0;
							$docid = 0;
							$label = "";
							$code = "";
							$cuHAL = "";
							$type = "";
							$pays = "";
							$quoi = $papers[$key][$key2]['tabAff'];
							$quoi = trimUltime($quoi);
							$validHAL = "";//to privilegy the search by the unit code number rather than acronym
							//echo "<br>".$j." - ".$quoi."<br>";
							$diffQuoi = explode("; [", $quoi);
							//var_dump($diffQuoi);
							for ($d = 0; $d < count($diffQuoi); $d++)
							{
								$urlHAL = "";
								$docid = 0;
								$label = "";
								$code = "";
								$cuHAL = "";
								$type = "";
								$pays = "";
								//Search for distinctive acronyms
								//echo $diffQuoi[$d].'<br>';
								searchAcro($diffQuoi[$d], $cuHAL, $urlHAL, $validHAL, $retTest);
								//echo $urlHAL.'<br>';
								if ($urlHAL != "")//affiliation with code unit or acronym found
								{
									//echo $diffQuoi[$d].'<br>';
									idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
								}
								//echo $docid.' - '.$urlHAL.'<br>';
								if($docid != 0)
								{
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
									while ($retTest != "") {
										$aTester = $retTest;
										$autInd = 0;
										$urlHAL = "";
										$docid = 0;
										$label = "";
										$code = "";
										$cuHAL = "";
										$type = "";
										$pays = "";
										//Search for distinctive acronyms
										//echo 'toto : '.$aTester.'<br>';
										searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
										//echo 'titi : '.$urlHAL.'<br>';
										if ($urlHAL != "")//affiliation with code unit or acronym found
										{
											idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
										}
										//echo $urlHAL.' - '.$docid.'<br>';
										if($docid != 0)
										{
											affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
										}
									}
								}else{//no specific affiliation found > we store whole results
									//echo $diffQuoi[$d].'<br>';
									$validHAL = "--";
									$eltTab = explode("] ",$diffQuoi[$d]);
									include './OverHAL_affiliation_termes.php';
									if (isset($eltTab[1])) {$label = supprAmp(trim($eltTab[1]));}
									//$label = str_ireplace($search, $replace, $label);
									//echo $label.'<br>';
									$queTab = explode(",", $label);
									$quePay = count($queTab) - 1;
									$labFin = "";
									$iq = 0;
									foreach($queTab as $elt)
									{
										$elt = trim($elt);
										if ($iq > 0) {$labFin .= ", ";}
										$labFin .= $elt;
										$iq++;
									}
									//$docid = 999999;
									$docid = $labFin;
									$code = $labFin;
									$type = 'institution';
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);
								}
							}
							//var_dump($autTab);
							//var_dump($labTab);
							$strTab = array();
							$unqOrg = array();//Tableau pour tester l'unicité des organismes
							$pmid = supprAmp($papers[$key][$key2]['Pubmed']);//PMID
							mb_internal_encoding('UTF-8');
							$zip = new ZipArchive();
							$FnmZ = "./HAL/OverHAL_pubmed_fcgi.zip";
							if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
								exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
							}
							$Fnm = "./HAL/OverHAL_pubmed_fcgi_".$pmid.".xml";
							$inF = fopen($Fnm,"a+");
							fseek($inF, 0);
							//$chaine = "\xEF\xBB\xBF";//UTF-8
							$chaine = "";//ANSI
							$chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
							$chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
							$chaine .= '  <text>'."\r\n".
												 '    <body>'."\r\n".
												 '      <listBibl>'."\r\n".
												 '        <biblFull>'."\r\n";
							//if DOI exists searching an OA PDF file
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DOI'];
								$volT = $papers[$key][$key2]['Volume'];
								$issT = $papers[$key][$key2]['Numero'];
								$pagT = $papers[$key][$key2]['Pagination'];
								$datT = $papers[$key][$key2]['aPub'];
								$pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

								testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $urlPDF);

								if ($urlPDF != "")//an OA PDF file has benn found
								{
									$urlPDF = htmlspecialchars($urlPDF);
									$chaine .= '          <editionStmt>'."\r\n".
														 '            <edition>'."\r\n".
														 '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$urlPDF.'"></ref>'."\r\n".
														 '            </edition>'."\r\n".
														 '          </editionStmt>'."\r\n";
									$avail = 'http://creativecommons.org/licenses/by';
									if ($compNC != "") {$avail .= '-nc';}
									if ($compND != "") {$avail .= '-nd';}
									if ($compSA != "") {$avail .= '-sa';}
									$avail .= '/';
									$chaine .= '          <publicationStmt>'."\r\n".
														 '            <availability>'."\r\n".
														 '              <licence target="'.$avail.'"/>'."\r\n".
														 '            </availability>'."\r\n".
														 '          </publicationStmt>'."\r\n";
								}
							}
							$chaine .= '          <seriesStmt>'."\r\n".
												 '          </seriesStmt>'."\r\n".
												 '          <notesStmt>'."\r\n".
							//Audience
							$aud = "";
							if (isset($papers[$key][$key2]['langue']))
							{
								if ($papers[$key][$key2]['langue'] == "eng") {
									$chaine .= '          	<note type="audience" n="2"/>'."\r\n";
								}else{
									$chaine .= '          	<note type="audience" n="3"/>'."\r\n";
								}
							}
							$chaine .= '						<note type="popular" n="0">No</note>'."\r\n";
							$chaine .= '						<note type="peer" n="1">Yes</note>'."\r\n";
							$chaine .= '          </notesStmt>'."\r\n".
												 '          <sourceDesc>'."\r\n".
												 '            <biblStruct>'."\r\n".
												 '              <analytic>'."\r\n";
							$lng = "";
							//langue + titre
							if (isset($papers[$key][$key2]['langue']))
							{
								if ($papers[$key][$key2]['langue'] == "fre") {$lng = "fr";}
								if ($papers[$key][$key2]['langue'] == "eng") {$lng = "en";}
							}
							$chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['titPub']).'</title>'."\r\n";
							//auteurs
							$aut = explode("; ", $papers[$key][$key2]['tabAut']);
							$iTp = 0;
							foreach ($aut as $qui) {
								$quiTab = explode(" ", $qui);
								if (isset($quiTab[1]))//in case of no comma for one author
								{
									$prenom = supprAmp(trim($quiTab[1]));
								}else{
									$prenom = " ";
								}
								$nom = supprAmp(trim($quiTab[0]));
								$nompre = $nom ." ".$prenom;
								if ($prenom != "") {
									$chaine .= '                <author role="aut">'."\r\n";
									$chaine .= '                  <persName>'."\r\n";
									$chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
									$chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
									$chaine .= '                  </persName>'."\r\n";
									$kT = array_search($nompre, $autTab);
									//echo $kT." - ".$nom."<br>";
									
									
									//var_dump($autTab);
									if ($kT !== FALSE) {
										foreach ($labTab[$nompre] as $lab) {
											$orgName = testLab($lab);
											//echo $orgName;
											//$str = array_search($labTab[$nompre][$kT], $strTab);
											$str = array_search($orgName, $unqOrg);
											if ($str === FALSE) {
												$iTp += 1;
												$kTp = $iTp;
												array_push($strTab, $lab);
												array_push($unqOrg, $orgName);
											}else{
												$kTp = $str + 1;
											}
											$chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
											//echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
										}
									}
									$chaine .= '                </author>'."\r\n";
								}
							}
							//var_dump($strTab);
							$chaine .= '              </analytic>'."\r\n";
							//journal
							$chaine .= '              <monogr>'."\r\n";
							//ISSN
							$chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['ISSN']).'</idno>'."\r\n";

							if (isset($papers[$key][$key2]['Type']))
							{
								$typDef = "";
								$typDoc = "";
								$typDocp = "";
								$type = $papers[$key][$key2]['Type'];
								//if (strpos($type, "Journal Article") !== false) {$typeDef = "Journal Article";}
								$typeDef = "Journal Article";
								switch(strtolower($typeDef))
								{
									case "journal article"://article
									case "editorial"://???
									case "case reports"://???
										$chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['titRev'])).'</title>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Numero']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Pagination']).'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['aPub']).'</date>'."\r\n";
										//$chaine .= '                  <date type="dateEpub">'.supprAmp($papers[$key][$key2]['aMel']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "ART";
										$typeDocp = "Journal articles";
										break;
								}
							}
							$chaine .= '              </monogr>'."\r\n";
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DOI']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['Pubmed']) && $papers[$key][$key2]['Pubmed'] != "")
							{
								$chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['Pubmed']).'</idno>'."\r\n";
							}
							$chaine .= '            </biblStruct>'."\r\n";
							$chaine .= '          </sourceDesc>'."\r\n";
							$chaine .= '          <profileDesc>'."\r\n";
							//langue
							$chaine .= '            <langUsage>'."\r\n";
							$codeP = strtoupper(substr(supprAmp($papers[$key][$key2]['langue']), 0, 2));
							if ($codeP != "EN")
							{
								array_search(strtolower($codeP), $languages);
							}else{
								$keyP = "English";
							}
							$chaine .= '              <language ident="'.$lng.'">'.$keyP.'</language>'."\r\n";
							$chaine .= '            </langUsage>'."\r\n";
							$chaine .= '            <textClass>'."\r\n";
							/*
							//mots-clés
							if (isset($papers[$key][$key2]['MC']) && $papers[$key][$key2]['MC'] != "")
							{
								$chaine .= '             <keywords scheme="author">'."\r\n";
								$aut = explode(";", $papers[$key][$key2]['MC']);
								foreach ($aut as $qui) {
									$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
								}
								$chaine .= '             </keywords>'."\r\n";
							}
							*/
							//domaine HAL
							//$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
							//Typologie
							$chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
							$chaine .= '            </textClass>'."\r\n";
							$chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['resume']).'</abstract>'."\r\n";
							$chaine .= '          </profileDesc>'."\r\n";
							$chaine .= '        </biblFull>'."\r\n";
							$chaine .= '      </listBibl>'."\r\n";
							$chaine .= '    </body>'."\r\n";
							if (count($strTab) > 0) {//Existence of one or more affiliation(s)
								$chaine .= '      <back>'."\r\n";
								$chaine .= '        <listOrg type="structures">'."\r\n";
								$indT = 1;
								foreach ($strTab as $labElt) {
									$eltTab = explode("~|~", $labElt);
									$chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
									$orgName = $eltTab[2];
									$orgName = str_replace(array("UR1", " UR1"), "", $orgName);
									if ($eltTab[3] == "institution") {//abbreviation between crochet
										if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
											$nameTab = explode(",", $orgName);
											$ville = $nameTab[count($nameTab)-2];
											$ville = str_ireplace(" cedex", "", $ville);
											$villeTab = explode(" ", $ville);
											$orgName = "CHU ".$villeTab[count($villeTab)-1];
										}
										$nameTab = explode(",", $orgName);
										$orgName = "";
										$oN = 0;
										$iName = 0;
										foreach ($nameTab as $name) {
											if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
												if ($name == "Universite CHU") {
													$ville = $nameTab[count($nameTab)-2];
													$ville = str_ireplace(" cedex", "", $ville);
													$villeTab = explode(" ", $ville);
													$name = $name.' '.$villeTab[count($villeTab)-1];
												}
												if ($oN == 0) {
													$oN = 1;
												}else{
													$orgName .= ", ";
												}
												$eltNameTab = explode(" ", trim($name));
												$oNE = 0;
												foreach ($eltNameTab as $elt) {
													if ($oNE == 0) {
														$oNE = 1;
													}else{
														$orgName .= " ";
													}
													if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3) {
														$orgName .= "[".trim($elt)."]";
													}else{
														$orgName .= "".trim($elt);
													}
												}
											}
											$iName += 1;
										}
									}
									//suppression/remplacement divers
									$orgName = str_replace(array("(", ")"), "", $orgName);
									$orgName = str_replace("/", " ", $orgName);
									//test présence 'Department' ou 'Service d' pour suppression
									$orgTab = explode(", ", $orgName);
									if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
										$orgTab[0] = "";
										$orgName = "";
										for($dpt = 0; $dpt < count($orgTab); $dpt++) {
											if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
										}
										$orgName = substr($orgName, 0, (strlen($orgName) - 2));
									}
									$chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";
									$pays = str_replace(".", "", strtoupper($eltTab[4]));
									if ($pays != "")
									{
										$keyP = array_search($pays, $countries, true); 
										$chaine .= '            <desc>'."\r\n";
										$chaine .= '              <address>'."\r\n";
										$chaine .= '                <country key="'.$keyP.'"></country>'."\r\n";
										$chaine .= '              </address>'."\r\n";
										$chaine .= '            </desc>'."\r\n";
									}
									$chaine .= '          </org>'."\r\n";
									$indT++;
								}
								$chaine .= '        </listOrg>'."\r\n";
								$chaine .= '      </back>'."\r\n";
							}
							$chaine .= '  </text>'."\r\n";
							$chaine .= '</TEI>';
							fwrite($inF,$chaine);
							fclose($inF);
							$zip->addFile($Fnm);
							$zip->close();
							unlink($Fnm);
						}
						break;
					
					case "dimensions":
						$aut = $papers[$key][$key2]['Authors'];
						$autTab = explode("; ",$aut);
						if (count($autTab) <= $limNbAut)
						{
							//affiliation
							$autTab = array();
							$labTab = array();
							$autInd = 0;
							$docid = 0;
							$label = "";
							$code = "";
							$cuHAL = "";
							$type = "";
							$pays = "";
							$quoi = $papers[$key][$key2]['Authors Affiliations'];
							$quoi = trimUltime($quoi);
							$validHAL = "";//to privilegy the search by the unit code number rather than acronym
							//echo "<br>".$j." - ".$quoi."<br>";
							$diffQuoi = explode("); ", $quoi);
							//Mise en forme > Nom, prenom ] + affiliations séparées par des ','
							$indQ = 0;
							foreach ($diffQuoi as $dQ) {
								$dQTab = explode(" (", $dQ);
								$pnQTab = explode(" ", $dQTab[0]);
								$diffQuoi[$indQ] = $pnQTab[1].", ".$pnQTab[0]."] ".str_replace(array("; "," ; "), ", ",$dQTab[1]);
								$indQ++;
							}
							//var_dump($diffQuoi);            
							for ($d = 0; $d < count($diffQuoi); $d++)
							{
								$urlHAL = "";
								$docid = 0;
								$label = "";
								$code = "";
								$cuHAL = "";
								$type = "";
								$pays = "";
								//Search for distinctive acronyms
								//echo $diffQuoi[$d].'<br>';
								searchAcro($diffQuoi[$d], $cuHAL, $urlHAL, $validHAL, $retTest);
								//echo $urlHAL.'<br>';
								if ($urlHAL != "")//affiliation with code unit or acronym found
								{
									//echo $diffQuoi[$d].'<br>';
									idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
								}
								//echo $docid.' - '.$urlHAL.'<br>';
								if($docid != 0)
								{
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
									while ($retTest != "") {
										$aTester = $retTest;
										$autInd = 0;
										$urlHAL = "";
										$docid = 0;
										$label = "";
										$code = "";
										$cuHAL = "";
										$type = "";
										$pays = "";
										//Search for distinctive acronyms
										//echo 'toto : '.$aTester.'<br>';
										searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
										//echo 'titi : '.$urlHAL.'<br>';
										if ($urlHAL != "")//affiliation with code unit or acronym found
										{
											idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
										}
										//echo $urlHAL.' - '.$docid.'<br>';
										if($docid != 0)
										{
											affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
										}
									}
								}else{//no specific affiliation found > we store whole results
									//echo $diffQuoi[$d].'<br>';
									$validHAL = "--";
									$eltTab = explode("] ",$diffQuoi[$d]);
									include './OverHAL_affiliation_termes.php';
									if (isset($eltTab[1])) {$label = supprAmp(trim($eltTab[1]));}
									//$label = str_ireplace($search, $replace, $label);
									//echo $label.'<br>';
									$queTab = explode(", ", $label);
									$quePay = count($queTab) - 1;
									$labFin = "";
									$iq = 0;
									foreach($queTab as $elt)
									{
										$elt = trim($elt);
										if ($iq > 0) {$labFin .= ", ";}
										$labFin .= $elt;
										$iq++;
									}
									//$docid = 999999;
									$docid = $labFin;
									$code = $labFin;
									$type = 'institution';
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);
								}
							}
							//var_dump($autTab);
							//var_dump($labTab);
							$strTab = array();
							$unqOrg = array();//Tableau pour tester l'unicité des organismes
							$ddn = supprAmp($papers[$key][$key2]['Publication ID']);//Publication ID
							mb_internal_encoding('UTF-8');
							$zip = new ZipArchive();
							$FnmZ = "./HAL/OverHAL_dimensions.zip";
							if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
								exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
							}
							$Fnm = "./HAL/OverHAL_dimensions_".$ddn.".xml";
							$inF = fopen($Fnm,"a+");
							fseek($inF, 0);
							//$chaine = "\xEF\xBB\xBF";//UTF-8
							$chaine = "";//ANSI
							$chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
							$chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
							$chaine .= '  <text>'."\r\n".
												 '    <body>'."\r\n".
												 '      <listBibl>'."\r\n".
												 '        <biblFull>'."\r\n";
							//if DOI exists searching an OA PDF file
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DOI'];
								$volT = $papers[$key][$key2]['Volume'];
								$issT = $papers[$key][$key2]['Issue'];
								$pagT = $papers[$key][$key2]['Pagination'];
								$datT = $papers[$key][$key2]['PubYear'];
								$pdfCR = "";//Eventual URL PDF file found with crossRef via CrosHAL.php > null here

								testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $urlPDF);

								if ($urlPDF != "")//an OA PDF file has benn found
								{
									$urlPDF = htmlspecialchars($urlPDF);
									$chaine .= '          <editionStmt>'."\r\n".
														 '            <edition>'."\r\n".
														 '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$urlPDF.'"></ref>'."\r\n".
														 '            </edition>'."\r\n".
														 '          </editionStmt>'."\r\n";
									$avail = 'http://creativecommons.org/licenses/by';
									if ($compNC != "") {$avail .= '-nc';}
									if ($compND != "") {$avail .= '-nd';}
									if ($compSA != "") {$avail .= '-sa';}
									$avail .= '/';
									$chaine .= '          <publicationStmt>'."\r\n".
														 '            <availability>'."\r\n".
														 '              <licence target="'.$avail.'"/>'."\r\n".
														 '            </availability>'."\r\n".
														 '          </publicationStmt>'."\r\n";
								}
							}
							$chaine .= '          <seriesStmt>'."\r\n".
												 '          </seriesStmt>'."\r\n".
												 '          <notesStmt>'."\r\n".
												 '						<note type="popular" n="0">No</note>'."\r\n".
													'						<note type="peer" n="1">Yes</note>'."\r\n".
												 '          </notesStmt>'."\r\n".
												 '          <sourceDesc>'."\r\n".
												 '            <biblStruct>'."\r\n".
												 '              <analytic>'."\r\n";
							$lng = "";
							//langue + titre
							$lng = "en";
							$chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Title']).'</title>'."\r\n";
							//auteurs
							$aut = explode("; ", $papers[$key][$key2]['Authors']);
							$iTp = 0;
							foreach ($aut as $qui) {
								$quiTab = explode(" ", $qui);
								if (isset($quiTab[0]))//in case of no comma for one author
								{
									$prenom = supprAmp(trim($quiTab[0]));
								}else{
									$prenom = " ";
								}
								$nom = supprAmp(trim($quiTab[1]));
								$nompre = $nom .", ".$prenom;
								if ($prenom != "") {
									$chaine .= '                <author role="aut">'."\r\n";
									$chaine .= '                  <persName>'."\r\n";
									$chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
									$chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
									$chaine .= '                  </persName>'."\r\n";
									$kT = array_search($nompre, $autTab);
									//echo $kT." - ".$nom."<br>";
									//var_dump($labTab);
									if ($kT !== FALSE) {
										foreach ($labTab[$nompre] as $lab) {
											$orgName = testLab($lab);
											//echo $orgName;
											//$str = array_search($labTab[$nompre][$kT], $strTab);
											$str = array_search($orgName, $unqOrg);
											if ($str === FALSE) {
												$iTp += 1;
												$kTp = $iTp;
												array_push($strTab, $lab);
												array_push($unqOrg, $orgName);
											}else{
												$kTp = $str + 1;
											}
											$chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
											//echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
										}
									}
									$chaine .= '                </author>'."\r\n";
								}
							}
							//var_dump($strTab);
							$chaine .= '              </analytic>'."\r\n";
							//journal
							$chaine .= '              <monogr>'."\r\n";
							if (isset($papers[$key][$key2]['Publication Type']))
							{
								$typDoc = "";
								$typDocp = "";
								$type = strtolower($papers[$key][$key2]['Publication Type']);
								switch($type)
								{
									case "article"://article
										$chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['Source title'])).'</title>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Pagination']).'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PubYear']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "ART";
										$typeDocp = "Journal articles";
										break;
									/*  
									case "proceeding":
										//inproceedings
										$chaine .= '                <title level="j">'.supprAmp($papers[$key][$key2]['Source title']).'</title>'."\r\n";
										$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
										$chaine .= '                <meeting>'."\r\n";
										$chaine .= '                  <title>'.supprAmp($papers[$key][$key2]['CT']).'</title>'."\r\n";
										$confStartDate = "";
										$confEndDate = "";
										if (isset($papers[$key][$key2]['CY']))
										{
											//OCT 19-23, 2014 to 2014-10-19
											$confdate = $papers[$key][$key2]['CY'];
											if ($confdate != "") {
												$res = explode(" ", $confdate);
												if (count($res) > 2) {
													//year
													$confStartDate = trim($res[2])."-";
													$confEndDate = trim($res[2])."-";
													//month
													switch($res[0])
													{
														case "JAN":
															$confStartDate .= "01-";
															$confEndDate .= "01-";
															break;
														case "FEB":
															$confStartDate .= "02-";
															$confEndDate .= "02-";
															break;
														case "MAR":
															$confStartDate .= "03-";
															$confEndDate .= "03-";
															break;
														case "APR":
															$confStartDate .= "04-";
															$confEndDate .= "04-";
															break;
														case "MAY":
															$confStartDate .= "05-";
															$confEndDate .= "05-";
															break;
														case "JUN":
															$confStartDate .= "06-";
															$confEndDate .= "06-";
															break;
														case "JUL":
															$confStartDate .= "07-";
															$confEndDate .= "07-";
															break;
														case "AUG":
															$confStartDate .= "08-";
															$confEndDate .= "08-";
															break;
														case "SEP":
															$confStartDate .= "09-";
															$confEndDate .= "09-";
															break;
														case "OCT":
															$confStartDate .= "10-";
															$confEndDate .= "10-";
															break;
														case "NOV":
															$confStartDate .= "11-";
															$confEndDate .= "11-";
															break;
														case "DEC":
															$confStartDate .= "12-";
															$confEndDate .= "12-";
															break;
													}
													//day
													$confStartDate .= substr($res[1], 0, 2);
													$confEndDate .= substr($res[1], 3, 2);
												}
											}
										}
										$chaine .= '                  <date type="start">'.$confStartDate.'</date>'."\r\n";//comment différencier date début et fin ?
										$chaine .= '                  <date type="end">'.$confEndDate.'</date>'."\r\n";//comment différencier date début et fin ?
										$chaine .= '                  <settlement>'.supprAmp($papers[$key][$key2]['CL']).'</settlement>'."\r\n";
										$chaine .= '                </meeting>'."\r\n";
										$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
										if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "COMM";
										$typeDocp = "Conference papers";
									case "chapter":
										//review, book chapter
										if ($papers[$key][$key2]['DT'] == "Book" || $papers[$key][$key2]['DT'] == "Review; Book Chapter") {
											$chaine .= '                <idno type="isbn">'.supprAmp($papers[$key][$key2]['BN']).'</idno>'."\r\n";
											$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['TI']).'</title>'."\r\n";
											$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['BE']).'</editor>'."\r\n";
											$chaine .= '                <imprint>'."\r\n";
											$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['PU']).'</publisher>'."\r\n";
											$chaine .= '                  <pubPlace>'.supprAmp($papers[$key][$key2]['PI']).'</pubPlace>'."\r\n";
											$chaine .= '                  <biblScope unit="serie">'.supprAmp($papers[$key][$key2]['SE']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['VL']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['IS']).'</biblScope>'."\r\n";
											$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['BP']).'-'.$papers[$key][$key2]['EP'].'</biblScope>'."\r\n";
											if (isset($papers[$key][$key2]['PY']) && $papers[$key][$key2]['PY'] != "") {
												$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['PY']).'</date>'."\r\n";
											}else{
												if (isset($papers[$key][$key2]['EY']) && $papers[$key][$key2]['EY'] != "") {
													$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['EY']).'</date>'."\r\n";
												}else{
													if (isset($papers[$key][$key2]['EA']) && $papers[$key][$key2]['EA'] != "") {
														$chaine .= '                  <date type="datePub">'.supprAmp(substr($papers[$key][$key2]['EA'], -4)).'</date>'."\r\n";
													}
												}
											}
											$chaine .= '                </imprint>'."\r\n";
											$typeDoc = "BOOK";
											$typeDocp = "Book";//???
										}
										break;
										*/
								}
							}
							$chaine .= '              </monogr>'."\r\n";
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DOI']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PMID']) && $papers[$key][$key2]['PMID'] != "")
							{
								$chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['PMID']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PMCID']) && $papers[$key][$key2]['PMCID'] != "")
							{
								$chaine .= '              <idno type="pmc">'.supprAmp($papers[$key][$key2]['PMCID']).'</idno>'."\r\n";
							}
							$chaine .= '            </biblStruct>'."\r\n";
							$chaine .= '          </sourceDesc>'."\r\n";
							$chaine .= '          <profileDesc>'."\r\n";
							//langue
							$chaine .= '            <langUsage>'."\r\n";
							$chaine .= '              <language ident="en">English</language>'."\r\n";
							$chaine .= '            </langUsage>'."\r\n";
							$chaine .= '            <textClass>'."\r\n";
							/*
							//mots-clés
							if (isset($papers[$key][$key2]['DE']) && $papers[$key][$key2]['DE'] != "")
							{
								$chaine .= '             <keywords scheme="author">'."\r\n";
								$aut = explode(";", $papers[$key][$key2]['DE']);
								foreach ($aut as $qui) {
									$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
								}
								$chaine .= '             </keywords>'."\r\n";
							}
							*/
							$chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
							$chaine .= '            </textClass>'."\r\n";
							$chaine .= '            <abstract xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Abstract']).'</abstract>'."\r\n";
							$chaine .= '          </profileDesc>'."\r\n";
							$chaine .= '        </biblFull>'."\r\n";
							$chaine .= '      </listBibl>'."\r\n";
							$chaine .= '    </body>'."\r\n";
							if (count($strTab) > 0) {//Existence of one or more affiliation(s)
								$chaine .= '      <back>'."\r\n";
								$chaine .= '        <listOrg type="structures">'."\r\n";
								$indT = 1;
								foreach ($strTab as $labElt) {
									$eltTab = explode("~|~", $labElt);
									$chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
									$orgName = $eltTab[2];
									$orgName = str_replace(array("UR1", " UR1"), "", $orgName);
									if ($eltTab[3] == "institution") {//abbreviation between crochet
										if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
											$nameTab = explode(",", $orgName);
											$ville = $nameTab[count($nameTab)-2];
											$ville = str_ireplace(" cedex", "", $ville);
											$villeTab = explode(" ", $ville);
											$orgName = "CHU ".$villeTab[count($villeTab)-1];
										}
										$nameTab = explode(",", $orgName);
										$orgName = "";
										$oN = 0;
										$iName = 0;
										foreach ($nameTab as $name) {
											if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
												if ($name == "Universite CHU") {
													$ville = $nameTab[count($nameTab)-2];
													$ville = str_ireplace(" cedex", "", $ville);
													$villeTab = explode(" ", $ville);
													$name = $name.' '.$villeTab[count($villeTab)-1];
												}
												if ($oN == 0) {
													$oN = 1;
												}else{
													$orgName .= ", ";
												}
												$eltNameTab = explode(" ", trim($name));
												$oNE = 0;
												foreach ($eltNameTab as $elt) {
													if ($oNE == 0) {
														$oNE = 1;
													}else{
														$orgName .= " ";
													}
													if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3) {
														$orgName .= "[".trim($elt)."]";
													}else{
														$orgName .= "".trim($elt);
													}
												}
											}
											$iName += 1;
										}
									}
									//suppression/remplacement divers
									$orgName = str_replace(array("(", ")"), "", $orgName);
									$orgName = str_replace("/", " ", $orgName);
									//test présence 'Department' ou 'Service d' pour suppression
									$orgTab = explode(", ", $orgName);
									if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
										$orgTab[0] = "";
										$orgName = "";
										for($dpt = 0; $dpt < count($orgTab); $dpt++) {
											if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
										}
										$orgName = substr($orgName, 0, (strlen($orgName) - 2));
									}
									$chaine .= '            <orgName>'.$orgName.'</orgName>'."\r\n";
									$pays = str_replace(".", "", strtoupper($eltTab[4]));
									if ($pays != "")
									{
										$keyP = array_search($pays, $countries, true); 
										$chaine .= '            <desc>'."\r\n";
										$chaine .= '              <address>'."\r\n";
										$chaine .= '                <country key="'.$keyP.'"></country>'."\r\n";
										$chaine .= '              </address>'."\r\n";
										$chaine .= '            </desc>'."\r\n";
									}
									$chaine .= '          </org>'."\r\n";
									$indT++;
								}
								$chaine .= '        </listOrg>'."\r\n";
								$chaine .= '      </back>'."\r\n";
							}
							$chaine .= '  </text>'."\r\n";
							$chaine .= '</TEI>';
							fwrite($inF,$chaine);
							fclose($inF);
							$zip->addFile($Fnm);
							$zip->close();
							unlink($Fnm);
						}
						break;
						
						case "openalex":
						$aut = $papers[$key][$key2]['Author_DN'];
						$autTab = explode("~|~", $aut);
						$aff = $papers[$key][$key2]['Inst_DN'];
						$affTab = explode("~|~", $aff);
						$affMod = str_replace('~||~', '~|~', $papers[$key][$key2]['Inst_DN']);
						$affModTab = explode("~|~", $affMod);
						$cou = str_replace('~||~', '~|~', $papers[$key][$key2]['Inst_CY']);
						$couTab = explode("~|~", $cou);
						$ror = str_replace('~||~', '~|~', $papers[$key][$key2]['Inst_RO']);
						$rorTab = explode("~|~", $ror);
						//Pour les affiliations, reprendre la mise en forme [aut1; aut2] Aff1, Aff2; [aut3] Aff3 ...
						$a = 0;
						$affil = '';
						foreach ($autTab as $iaut){
							$autaffil = '['.$iaut.'] ';
							if (isset($affTab[$a])) {
								$autAff = explode('~||~',$affTab[$a]);
								foreach ($autAff as $iaff){
									$affil .= $autaffil.$iaff.'; ';
								}
							}
							//$affil = substr($affil, 0, -2).'; ';
							$a++;
						}
						$affil = substr($affil, 0, -2);
						//echo $affil.'<br>';
						if (count($autTab) <= $limNbAut)
						{
							//affiliation
							$autTab = array();
							$labTab = array();
							$autInd = 0;
							$docid = 0;
							$label = "";
							$code = "";
							$cuHAL = "";
							$type = "";
							$pays = "";
							$quoi = $affil;
							$quoi = trimUltime($quoi);
							$validHAL = "";//to privilegy the search by the unit code number rather than acronym
							//echo "<br>".$j." - ".$quoi."<br>";
							$diffQuoi = explode("; [", $quoi);
							//var_dump($diffQuoi);
							for ($d = 0; $d < count($diffQuoi); $d++)
							{
								$urlHAL = "";
								$docid = 0;
								$label = "";
								$code = "";
								$cuHAL = "";
								$type = "";
								$pays = "";
								//Search for distinctive acronyms
								//echo $diffQuoi[$d].'<br>';
								searchAcro($diffQuoi[$d], $cuHAL, $urlHAL, $validHAL, $retTest);
								//echo $urlHAL.'<br>';
								if ($urlHAL != "")//affiliation with code unit or acronym found
								{
									//echo $diffQuoi[$d].'<br>';
									idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
								}
								//echo $docid.' - '.$urlHAL.'<br>';
								if($docid != 0)
								{
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);//Assigning idAffil to authors
									while ($retTest != "") {
										$aTester = $retTest;
										$autInd = 0;
										$urlHAL = "";
										$docid = 0;
										$label = "";
										$code = "";
										$cuHAL = "";
										$type = "";
										$pays = "";
										//Search for distinctive acronyms
										//echo 'toto : '.$aTester.'<br>';
										searchAcro($aTester, $cuHAL, $urlHAL, $validHAL, $retTest);
										//echo 'titi : '.$urlHAL.'<br>';
										if ($urlHAL != "")//affiliation with code unit or acronym found
										{
											idaureHal($urlHAL, $cuHAL, $docid, $label, $code, $type);
										}
										//echo $urlHAL.' - '.$docid.'<br>';
										if($docid != 0)
										{
											affilId($aTester, $docid, $label, $code, $type, $pays, $validHAL);
										}
									}
								}else{//no specific affiliation found > we store whole results
									//echo $diffQuoi[$d].'<br>';
									$validHAL = "--";
									$eltTab = explode("] ",$diffQuoi[$d]);
									include './OverHAL_affiliation_termes.php';
									if (isset($eltTab[1])) {$label = supprAmp(trim($eltTab[1]));}
									//$label = str_ireplace($search, $replace, $label);
									//echo $label.'<br>';
									$queTab = explode(",", $label);
									$quePay = count($queTab) - 1;
									$labFin = "";
									$iq = 0;
									foreach($queTab as $elt)
									{
										$elt = trim($elt);
										if ($iq > 0) {$labFin .= ", ";}
										$labFin .= $elt;
										$iq++;
									}
									//$docid = 999999;
									$docid = $labFin;
									$code = $labFin;
									$type = 'institution';
									affilId($diffQuoi[$d], $docid, $label, $code, $type, $pays, $validHAL);
								}
							}
							//var_dump($autTab);
							//var_dump($labTab);
							$strTab = array();
							$unqOrg = array();//Tableau pour tester l'unicité des organismes
							$soa = str_replace('https://openalex.org/', '', $papers[$key][$key2]['Source']);//Source OpenAlex
							mb_internal_encoding('UTF-8');
							$zip = new ZipArchive();
							$FnmZ = "./HAL/OverHAL_openalex.zip";
							if ($zip->open($FnmZ, ZipArchive::CREATE)!==TRUE) {
								exit("Impossible d'ouvrir le fichier <$FnmZ>\n");
							}
							$Fnm = "./HAL/OverHAL_openalex_".$soa.".xml";
							$inF = fopen($Fnm,"a+");
							fseek($inF, 0);
							//$chaine = "\xEF\xBB\xBF";//UTF-8
							$chaine = "";//ANSI
							$chaine .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
							$chaine .= '<TEI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.tei-c.org/ns/1.0" xmlns:hal="http://hal.archives-ouvertes.fr/" xsi:schemaLocation="http://www.tei-c.org/ns/1.0 http://api.archives-ouvertes.fr/documents/aofr-sword.xsd">'."\r\n";
							$chaine .= '  <text>'."\r\n".
												 '    <body>'."\r\n".
												 '      <listBibl>'."\r\n".
												 '        <biblFull>'."\r\n".
												 '          <titleStmt>'."\r\n";
							//funder
							if ($papers[$key][$key2]['Funder'] != "") {
								$eltFun = explode("~|~", $papers[$key][$key2]['Funder']);
								foreach($eltFun as $elt) {
									$chaine .= '            <funder>'.supprAmp($elt).'</funder>'."\r\n";
								}
							}
							$chaine .= '          </titleStmt>'."\r\n";
							//if DOI exists searching an OA PDF file
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$urlT = "https://api.oadoi.org/v2/".$papers[$key][$key2]['DOI'];
								$volT = $papers[$key][$key2]['Volume'];
								$issT = $papers[$key][$key2]['Issue'];
								$pagT = $papers[$key][$key2]['Pages'];
								$datT = $papers[$key][$key2]['Date'];
								$urlPDF = $papers[$key][$key2]['PDF'];
								$isoa = $papers[$key][$key2]['OA'];

								//testOALic($urlT, $volT, $issT, $pagT, $datT, $pdfCR, $evd, $titLic, $typLic, $compNC, $compND, $compSA, $urlPDF);

								if ($urlPDF != "" && $isoa == 1)//an OA PDF file has benn found
								{
									$evd = "greenPublisher";
									$urlPDF = htmlspecialchars($urlPDF);
									$chaine .= '          <editionStmt>'."\r\n".
														 '            <edition>'."\r\n".
														 '              <ref type="file" subtype="'.$evd.'" n="1" target="'.$urlPDF.'"></ref>'."\r\n".
														 '            </edition>'."\r\n".
														 '          </editionStmt>'."\r\n";
									$avail = '';
									//$avail = 'https://creativecommons.org/licenses/by';
									//if ($compNC != "") {$avail .= '-nc';}
									//if ($compND != "") {$avail .= '-nd';}
									//if ($compSA != "") {$avail .= '-sa';}
									if ($papers[$key][$key2]['License'] == 'cc-by') $avail = "https://creativecommons.org/licenses/by/4.0/";
									$chaine .= '          <publicationStmt>'."\r\n".
														 '            <availability>'."\r\n".
														 '              <licence target="'.$avail.'"/>'."\r\n".
														 '            </availability>'."\r\n".
														 '          </publicationStmt>'."\r\n";
								}
							}
							$chaine .= '          <seriesStmt>'."\r\n".
												 '          </seriesStmt>'."\r\n".
												 '          <notesStmt>'."\r\n".
							//Audience
							$aud = "";
							if (isset($papers[$key][$key2]['Language']))
							{
								if ($papers[$key][$key2]['Language'] == "en") {
									$chaine .= '            <note type="audience" n="2"/>'."\r\n";
								}else{
									$chaine .= '            <note type="audience" n="3"/>'."\r\n";
								}
							}
							$chaine .= '            <note type="popular" n="0">No</note>'."\r\n";
							$chaine .= '            <note type="peer" n="1">Yes</note>'."\r\n";
							$chaine .= '          </notesStmt>'."\r\n".
												 '          <sourceDesc>'."\r\n".
												 '            <biblStruct>'."\r\n".
												 '              <analytic>'."\r\n";
							$lng = "";
							//langue + titre
							if (isset($papers[$key][$key2]['Language']))
							{
								if ($papers[$key][$key2]['Language'] == "fr") {$lng = "fr";}
								if ($papers[$key][$key2]['Language'] == "en") {$lng = "en";}
							}
							$chaine .= '                <title xml:lang="'.$lng.'">'.supprAmp($papers[$key][$key2]['Title']).'</title>'."\r\n";
							//auteurs
							$aut = explode("~|~", $papers[$key][$key2]['Author_DN']);
							$crp = explode("~|~", $papers[$key][$key2]['Is_cor']);//Auteur correspondant
							$orc = explode("~|~", $papers[$key][$key2]['ORCID']);//ORCID
							//Récupération du 'raw_affiliation_strings' pour insertion dans un noeud temporaire
							$raw = explode("~|~", str_replace(array("~troliv~", "~trolia~"), array(",", "'"), supprAmp($papers[$key][$key2]['Inst_RW'])));
							$iTp = 0;
							$a = 0;
							foreach ($aut as $qui) {
								//Règle : le premier terme avant le premier espace est le prénom et tout le reste est le nom
								$quiTab = explode(" ", $qui);
								$prenom = supprAmp(trim($quiTab[0] ?? ''));
								$nom = supprAmp(str_replace($prenom.' ', '', $qui) ?? ''); 
								$nompre = $prenom ." ".$nom;
								$role = ($crp[$a] == 1) ? '"crp"':'"aut"';
								if ($prenom != "") {
									$chaine .= '                <author role='.$role.'>'."\r\n";
									$chaine .= '                  <persName>'."\r\n";
									$chaine .= '                    <forename type="first">'.$prenom.'</forename>'."\r\n";
									$chaine .= '                    <surname>'.$nom.'</surname>'."\r\n";
									$chaine .= '                  </persName>'."\r\n";
									if (!empty($orc[$a])) {//ORCID présent
										$chaine .= '                  <idno type="ORCID">https://orcid.org/'.$orc[$a].'</idno>'."\r\n";
									}
									if (!empty($raw[$a])) {//Raw affiliation strings
										$tabRaw = explode ('~\/~', $raw[$a]);
										$r = 0;
										while (isset($tabRaw[$r])) {
											$chaine .= '                  <rawAffs>'.$tabRaw[$r].'</rawAffs>'."\r\n";
											$extRaw = '';
											$e = 0;
											//Recherche du terme 'UMR, xxxx' ou 'umr, xxxx'
											if (preg_match('/UMR, [0-9]{4}/', strtoupper($tabRaw[$r]), $match)) {$tabRaw[$r] = str_ireplace('UMR, ', 'UMR ', $tabRaw[$r]);}
											$tabExt = explode(",", $tabRaw[$r]);
											foreach ($tabExt as $ext) {
												//Recherche du terme 'UMR xxxx' ou 'umr xxxx'
												if (preg_match('/UMR [0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'UMR CNRS xxxx' ou 'umr cnrs xxxx'
												if (preg_match('/UMR CNRS [0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.str_ireplace('CNRS ', '', $match[0]).'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'UMRxxxx' ou 'umrxxxx'
												if (preg_match('/UMR[0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'UPR xxxx' ou 'upr xxxx'
												if (preg_match('/UPR [0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'UPRxxxx' ou 'uprxxxx'
												if (preg_match('/UPR[0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'UR xxxx' ou 'ur xxxx'
												if (preg_match('/UR [0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'URxxxx' ou 'urxxxx'
												if (preg_match('/UR[0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'U xxxx' ou 'u xxxx'
												if (preg_match('/U [0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
												//Recherche du terme 'Uxxxx' ou 'uxxxx'
												if (preg_match('/U[0-9]{4}/', strtoupper($ext), $match)) {$labTab[$nompre][] = '~|~~|~'.$match[0].'~|~researchteam~|~'.$tabExt[$e]; $e++;}
											}
											$r++;
										}
									}
									$kT = array_search($nompre, $autTab);
									//echo $kT." - ".$nom."<br>";
									
									//var_dump($labTab);
									if ($kT !== FALSE) {
										foreach ($labTab[$nompre] as $lab) {
											$orgName = testLab($lab);
											//echo $orgName;
											//$str = array_search($labTab[$nompre][$kT], $strTab);
											$str = array_search($orgName, $unqOrg);
											if ($str === FALSE) {
												$iTp += 1;
												$kTp = $iTp;
												array_push($strTab, $lab);
												array_push($unqOrg, $orgName);
											}else{
												$kTp = $str + 1;
											}
											$chaine .= '                  <affiliation ref="#localStruct-Aff'.$kTp.'"/>'."\r\n";
											//echo $kTp." - ".$nom." - ".$labTab[$kT]."<br>";
										}
									}
									$chaine .= '                </author>'."\r\n";
								}
								$a++;
							}
							//var_dump($strTab);
							$chaine .= '              </analytic>'."\r\n";
							//journal
							$chaine .= '              <monogr>'."\r\n";
							//ISSN
							if (!empty($papers[$key][$key2]['ISSN'])) {
								$testEISSN = '';
								$chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['ISSN']).'</idno>'."\r\n";
								//EISSN
								$testEISSN = str_replace($papers[$key][$key2]['ISSN'].'~|~', '', $papers[$key][$key2]['EISSN']);
								if (!empty($testEISSN) && $testEISSN != $papers[$key][$key2]['ISSN']) {
									$chaine .= '                <idno type="eissn">'.supprAmp($testEISSN).'</idno>'."\r\n";
								}
							}
							if (isset($papers[$key][$key2]['Type notice']))
							{
								$typDef = "";
								$typDoc = "";
								$typDocp = "";
								$type = $papers[$key][$key2]['Type notice'];
								switch(strtolower($type))
								{
									case "article"://article
										$chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['Titre revue'])).'</title>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Editor']).'</publisher>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Pages']).'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Date']).'</date>'."\r\n";
										//$chaine .= '                  <date type="dateEpub">'.supprAmp($papers[$key][$key2]['aMel']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "ART";
										$typeDocp = "Journal articles";
										break;
									
									case "comm"://communication
										$chaine .= '                <title level="j">'.supprAmp(minRev($papers[$key][$key2]['Titre revue'])).'</title>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <publisher>'.supprAmp($papers[$key][$key2]['Editor']).'</publisher>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Pages']).'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Date']).'</date>'."\r\n";
										//$chaine .= '                  <date type="dateEpub">'.supprAmp($papers[$key][$key2]['aMel']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "COMM";
										$typeDocp = "Conference papers";
										break;
										
									case "book-chapter":
										$chaine .= '                <idno type="issn">'.supprAmp($papers[$key][$key2]['ISSN']).'</idno>'."\r\n";
										$chaine .= '                <idno type="eissn">'.supprAmp($papers[$key][$key2]['EISSN']).'</idno>'."\r\n";
										$chaine .= '                <title level="m">'.supprAmp($papers[$key][$key2]['Titre revue']).'</title>'."\r\n";
										$chaine .= '                <editor>'.supprAmp($papers[$key][$key2]['Editor']).'</editor>'."\r\n";
										$chaine .= '                <imprint>'."\r\n";
										$chaine .= '                  <biblScope unit="volume">'.supprAmp($papers[$key][$key2]['Volume']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="issue">'.supprAmp($papers[$key][$key2]['Issue']).'</biblScope>'."\r\n";
										$chaine .= '                  <biblScope unit="pp">'.supprAmp($papers[$key][$key2]['Pages']).'</biblScope>'."\r\n";
										$chaine .= '                  <date type="datePub">'.supprAmp($papers[$key][$key2]['Date']).'</date>'."\r\n";
										$chaine .= '                </imprint>'."\r\n";
										$typeDoc = "COUV";
										$typeDocp = "Book chapter";
										break;
								}
							}
							$chaine .= '              </monogr>'."\r\n";
							if (isset($papers[$key][$key2]['DOI']) && $papers[$key][$key2]['DOI'] != "")
							{
								$chaine .= '              <idno type="doi">'.supprAmp($papers[$key][$key2]['DOI']).'</idno>'."\r\n";
							}
							if (isset($papers[$key][$key2]['PMID']) && $papers[$key][$key2]['PMID'] != "")
							{
								$chaine .= '              <idno type="pubmed">'.supprAmp($papers[$key][$key2]['PMID']).'</idno>'."\r\n";
							}
							$chaine .= '            </biblStruct>'."\r\n";
							$chaine .= '          </sourceDesc>'."\r\n";
							$chaine .= '          <profileDesc>'."\r\n";
							//langue
							$chaine .= '            <langUsage>'."\r\n";
							$codeP = $papers[$key][$key2]['Language'];
							if ($codeP != "en")
							{
								array_search(strtolower($codeP), $languages);
							}else{
								$keyP = "English";
							}
							$chaine .= '              <language ident="'.$lng.'">'.$keyP.'</language>'."\r\n";
							$chaine .= '            </langUsage>'."\r\n";
							$chaine .= '            <textClass>'."\r\n";
							
							//mots-clés
							if (isset($papers[$key][$key2]['Keywords']) && $papers[$key][$key2]['Keywords'] != "")
							{
								$chaine .= '             <keywords scheme="author">'."\r\n";
								$aut = explode(", ", $papers[$key][$key2]['Keywords']);
								foreach ($aut as $qui) {
									$chaine .= '              <term xml:lang="'.$lng.'">'.supprAmp(trim($qui)).'</term>'."\r\n";
								}
								$chaine .= '             </keywords>'."\r\n";
							}
							//domaine HAL
							//$chaine .= '             <classCode scheme="halDomain" n="">'.supprAmp($papers[$key][$key2]['WC']).'</classCode>'."\r\n";
							//Typologie
							$chaine .= '             <classCode scheme="halTypology" n="'.$typeDoc.'">'.$typeDocp.'</classCode>'."\r\n";
							$chaine .= '            </textClass>'."\r\n";
							$abstrTEI = (isset($papers[$key][$key2]['Abstract'])) ? supprAmp($papers[$key][$key2]['Abstract']) : '';
							$chaine .= '            <abstract xml:lang="'.$lng.'">'.$abstrTEI.'</abstract>'."\r\n";
							$chaine .= '          </profileDesc>'."\r\n";
							$chaine .= '        </biblFull>'."\r\n";
							$chaine .= '      </listBibl>'."\r\n";
							$chaine .= '    </body>'."\r\n";
							if (count($strTab) > 0) {//Existence of one or more affiliation(s)
								$chaine .= '      <back>'."\r\n";
								$chaine .= '        <listOrg type="structures">'."\r\n";
								$indT = 1;
								foreach ($strTab as $labElt) {
									$eltTab = explode("~|~", $labElt);
									$chaine .= '          <org type="'.$eltTab[3].'" xml:id="localStruct-Aff'.$indT.'">'."\r\n";
									$orgName = $eltTab[2];
									$orgName = str_replace(array("UR1", " UR1"), "", $orgName);
									if ($eltTab[3] == "institution") {//abbreviation between crochet
										if ((strpos($orgName, "CHU") !== false) && (substr_count($orgName, ',') > 2)) {
											$nameTab = explode(",", $orgName);
											$ville = $nameTab[count($nameTab)-2];
											$ville = str_ireplace(" cedex", "", $ville);
											$villeTab = explode(" ", $ville);
											$orgName = "CHU ".$villeTab[count($villeTab)-1];
										}
										$nameTab = explode(",", $orgName);
										$orgName = "";
										$oN = 0;
										$iName = 0;
										foreach ($nameTab as $name) {
											if ($iName != count($nameTab)-2) {//do not insert the penultimate term = address
												if ($name == "Universite CHU") {
													$ville = $nameTab[count($nameTab)-2];
													$ville = str_ireplace(" cedex", "", $ville);
													$villeTab = explode(" ", $ville);
													$name = $name.' '.$villeTab[count($villeTab)-1];
												}
												if ($oN == 0) {
													$oN = 1;
												}else{
													$orgName .= ", ";
												}
												$eltNameTab = explode(" ", trim($name));
												$oNE = 0;
												foreach ($eltNameTab as $elt) {
													if ($oNE == 0) {
														$oNE = 1;
													}else{
														$orgName .= " ";
													}
													if (ctype_upper(trim($elt)) && !is_numeric(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3 && trim($elt) != "CNRS" && trim($elt) != "INSERM" && trim($elt) != "INRA" && trim($elt) != "INRIA" && trim($elt) != "IRSTEA") {
													//if (ctype_upper(trim($elt)) && strlen(trim($elt)) > 3) {
														$orgName .= "[".trim($elt)."]";
													}else{
														$orgName .= "".trim($elt);
													}
												}
											}
											$iName += 1;
										}
									}
									//suppression/remplacement divers
									$orgName = str_replace(array("(", ")"), "", $orgName);
									$orgName = str_replace("/", " ", $orgName);
									//test présence 'Department' ou 'Service d' pour suppression
									$orgTab = explode(", ", $orgName);
									if (stripos($orgTab[0], "Department") !== false || stripos($orgTab[0], "Service d") !== false) {
										$orgTab[0] = "";
										$orgName = "";
										for($dpt = 0; $dpt < count($orgTab); $dpt++) {
											if ($orgTab[$dpt] != "") {$orgName .= $orgTab[$dpt]. ", ";}
										}
										$orgName = substr($orgName, 0, (strlen($orgName) - 2));
									}
									$keyCO = array_search($orgName, $affModTab);
									$pays = ($keyCO != '') ? $couTab[$keyCO] : '';
									$ror = ($keyCO != '') ? $rorTab[$keyCO]: '';
									//Si UMR ou UPR, pays = France
									if (stripos($orgName, 'UMR') !== false || stripos($orgName, 'UPR') !== false) {$pays = 'FR';}
									//ROR
									if ($ror != '') {
										$chaine .= '            <idno type="ROR">'.$ror.'</idno>'."\r\n";
									}
									$orgName = str_replace(array("~troliv~", "~trolia~"), array(",", "'"), $orgName);
									$chaine .= '            <orgName>'.trim($orgName).'</orgName>'."\r\n";
									if ($pays != "")
									{
										//$keyP = array_search($pays, $countries, true); 
										$chaine .= '            <desc>'."\r\n";
										$chaine .= '              <address>'."\r\n";
										//$chaine .= '                <country key="'.$keyP.'"></country>'."\r\n";
										$chaine .= '                <country key="'.$pays.'"></country>'."\r\n";
										$chaine .= '              </address>'."\r\n";
										$chaine .= '            </desc>'."\r\n";
									}
									$chaine .= '          </org>'."\r\n";
									$indT++;
								}
								$chaine .= '        </listOrg>'."\r\n";
								$chaine .= '      </back>'."\r\n";
							}
							$chaine .= '  </text>'."\r\n";
							$chaine .= '</TEI>';
							fwrite($inF,$chaine);
							fclose($inF);
							$zip->addFile($Fnm);
							$zip->close();
							unlink($Fnm);
						}
						break;
						
						
				}
				$k++;
			}
			echo "</tbody></table>";
		}
		if ($limzot == "non")
		{
			?>
			<a name='Auteurs des références de <?php echo $nomSouBib;?> non trouvées dans HAL'></a><h4>Auteurs des références de <?php echo $nomSouBib;?> non trouvées dans HAL - <a href='#Resultats'><em>Retour aux résultats</em></a></h4>

			<p>Vous pouvez utiliser le logiciel <a href="http://www.treecloud.org">TreeCloud</a> pour afficher une figure
			résumant les auteurs les plus présents dans cette liste d'articles manquants sur HAL, et les sensibiliser
			au dépôt dans cette archive ouverte.</p>
			<ul>
			<?php
			foreach($papers[$key] as $key2 => $data)
			{
				 $formattedAuthors=$data[$colAuthors].', ';
				 $formattedAuthors=preg_replace('#\., #', '|', $formattedAuthors);
				 $formattedAuthors=preg_replace('#, #', '_', $formattedAuthors);
				 $formattedAuthors=preg_replace('# #', '_', $formattedAuthors);
				 $formattedAuthors=preg_replace('#\.#', '_', $formattedAuthors);
				 $formattedAuthors=preg_replace('#-#', '_', $formattedAuthors);
				 $formattedAuthors=preg_replace('#__#', '_', $formattedAuthors);
				 $formattedAuthors=preg_replace('#\|#', ' ', $formattedAuthors);
				 echo "<div style='width: 900px; word-wrap: break-word;'>".$formattedAuthors." de de de de de de de de de de de de de de de de de de de de de de de de de </div><br/>";
			}
			?>
			</ul>
			<?php
		}
	}
}

if (file_exists("./HAL/OverHAL_scopus.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_scopus.bib">Exporter les résultats Scopus pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_wos_csv.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_wos_csv.bib">Exporter les résultats WoS pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_scifin.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_scifin.bib">Exporter les résultats SciFinder pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_zotero.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_zotero.bib">Exporter les résultats Zotero pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_pubmed_xml.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_pubmed_xml.bib">Exporter les résultats PubMed pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_pubmed_txt.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_pubmed_txt.bib">Exporter les résultats PubMed pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_pubmed_csv.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_pubmed_csv.bib">Exporter les résultats PubMed pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_wos_txt.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_wos_txt.bib">Exporter les résultats WoS pour Bib2HAL</a>';
}
if (file_exists("./HAL/OverHAL_openalex.bib"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_openalex.bib">Exporter les résultats OpenAlex pour Bib2HAL</a>';
}

if (file_exists("./HAL/OverHAL_scopus.zip"))
{
	echo '&nbsp;<a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_scopus.zip">Exporter les résultats Scopus au format TEI</a><br />';
}
if (file_exists("./HAL/OverHAL_wos_csv.zip"))
{
	echo '&nbsp;<a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_wos_csv.zip">Exporter les résultats WoS au format TEI</a><br />';
}
if (file_exists("./HAL/OverHAL_scifin.zip"))
{
	echo '&nbsp;<a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_scifin.zip">Exporter les résultats SciFinder au format TEI</a><br />';
}
if (file_exists("./HAL/OverHAL_zotero.zip"))
{
	echo '&nbsp;<a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_zotero.zip">Exporter les résultats Zotero au format TEI</a>';
}
if (file_exists("./HAL/OverHAL_pubmed_xml.zip"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_pubmed_xml.zip">Exporter les résultats Pubmed au format TEI</a>';
}
if (file_exists("./HAL/OverHAL_pubmed_txt.zip"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_pubmed_txt.zip">Exporter les résultats Pubmed au format TEI</a>';
}
if (file_exists("./HAL/OverHAL_pubmed_fcgi.zip"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_pubmed_fcgi.zip">Exporter les résultats Pubmed au format TEI</a>';
}
if (file_exists("./HAL/OverHAL_dimensions.zip"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_dimensions.zip">Exporter les résultats Dimensions au format TEI</a>';
}
if (file_exists("./HAL/OverHAL_wos_txt.zip"))
{
	echo '<br /><a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_wos_txt.zip">Exporter les résultats WoS au format TEI</a>';
}
if (file_exists("./HAL/OverHAL_openalex.zip"))
{
	echo '&nbsp;<a class="btn btn-secondary mt-2" target="_blank" rel="noopener noreferrer" href="./HAL/OverHAL_openalex.zip">Exporter les résultats OpenAlex au format TEI</a>';
}

if (!empty($papers[$key])) {//Affichage des tableaux uniquement si présence de résultats !
	echo '<br /><br />';
	echo '<div class="row align-items-center ml-1">';
	echo '	Chargez votre fichier TEI dans Zip2HAL';
	echo '	<a class="nav-link dropdown-toggle arrow-none" href="Zip2HAL.php" id="topnav-myhal" role="button">';
	echo '		<h2 class="h2 mt-0"><i class="mdi mdi mdi-folder-zip-outline text-primary mr-1"></i></h2>';
	echo '	</a>';
	echo '</div>';
}

echo '<br/ >';
	?>

	<a name='Bilan quantitatif'></a><h4>Bilan quantitatif - <a href='#Resultats'><em>Retour aux résultats</em></a></h4>

	<table class='table table-responsive table-bordered table-centered text-center'>
	<thead class='thead-dark'>
	<?php
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th>";
	foreach ($souBib as $key => $subTab)
	{
		$nomSouBib = $souBib[$key]["Maj"];// Name of the bibliographic source
		if ($_FILES[$key]['error'] != 4) {echo"<th colspan='3'>".$nomSouBib."</th>";}
	}
	echo"</tr>";
	echo "<tr><th>Année</th><th>sur HAL</th>";
	foreach ($souBib as $key => $subTab)
	{
		if ($_FILES[$key]['error'] != 4) {echo"<th>Nb</th><th>non trouvé dans HAL</th><th>% trouvé dans HAL</th>";}
	}
	echo"</tr></thead><tbody>";
	$years = array();
	$yearMin = date('Y', time());
	foreach ($souBib as $key => $subTab)
	{
		if ($_FILES[$key]['error'] != 4) //file exists
		{
			foreach ($nbPerYear[$key] as $key2 => $data)
			{
				if(intval($key2) < $yearMin) {$yearMin = intval($key2);}
			}
		}
	}

	for ($i = $yearMin; $i <= date('Y', time()); $i++)
	{
		array_push($years, $i);
	}
	foreach($years as $year)
	{
		if (isset($nbHalPerYear[$year]))
		{
			echo "<tr><td valign='top'>".$year."</td><td valign='top'>".$nbHalPerYear[$year]."</td>";
		}else{
			echo "<tr><td valign='top'>".$year."</td><td valign='top'>-</td>";
		}
		foreach ($souBib as $key => $subTab)
		{
			if ($_FILES[$key]['error'] != 4) //file exists
			{
				if (isset($nbPerYear[$key][$year])) // there are some articles for the year
				{
					if (isset($nbNotFoundPerYear[$key][$year])) {$nNFPY = $nbNotFoundPerYear[$key][$year];}else{$nNFPY = 0;}
					echo "<td valign='top'>".$nbPerYear[$key][$year]."</td><td valign='top'>".$nNFPY."</td><td valign='top'>".(round(10000*($nbPerYear[$key][$year]-$nNFPY)/($nbPerYear[$key][$year]))/100)."%</td>";
				}else{
					echo"<td valign='top'>-</td><td valign='top'>-</td><td valign='top'>-</td>";
				}
			}
		}
	echo "</tr>";
	}

	?>
	</tbody></table>
<br/><br/>
<a href="OverHAL.php">Retour à l'accueil du site</a>
<br>


                                    </div> <!-- end card-body-->
                                    
                                </div> <!-- end card-->

                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div>
                    <!-- container -->

                </div>
                <!-- content -->
								
								<?php
								include('./Glob_bas.php');
								?>
								
								</div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        
				<button id="scrollBackToTop" class="btn btn-primary"><i class="mdi mdi-24px text-white mdi-chevron-double-up"></i></button>
				<!-- END wrapper -->

        <!-- bundle -->
        <!-- <script src="./assets/js/vendor.min.js"></script> -->
        <script src="./assets/js/app.min.js"></script>

        <!-- third party js -->
        <script src="./assets/js/vendor/Chart.bundle.min.js"></script>
        <!-- third party js ends -->
        <script src="./assets/js/pages/hal-ur1.chartjs.js"></script>
				
				<script>
            (function($) {
                'use strict';
                $('#warning-alert-modal').modal(
                    {'show': true, 'backdrop': 'static'}    
                    
                        );
                $(document).scroll(function() {
                  var y = $(this).scrollTop();
                  if (y > 200) {
                    $('#scrollBackToTop').fadeIn();
                  } else {
                    $('#scrollBackToTop').fadeOut();
                  }
                });
                $('#scrollBackToTop').each(function(){
                    $(this).click(function(){ 
                        $('html,body').animate({ scrollTop: 0 }, 'slow');
                        return false; 
                    });
                });
            })(window.jQuery)
        </script>

    </body>
</html>
