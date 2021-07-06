<?php
//authentification CAS ou autre ?
if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'ecobio') !== false) {
  include('./_connexion.php');
}else{
  require_once('./CAS_connect.php');
	$HAL_USER = phpCAS::getUser();
	$HAL_QUOI = "OverHAL";
	if($HAL_USER != "jonchere" && $HAL_USER != "otroccaz") {include('./Stats_listes_HALUR1.php');}
}

// récupération de l'adresse IP du client (on cherche d'abord à savoir s'il est derrière un proxy)
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
}else {
  $ip = $_SERVER['REMOTE_ADDR'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>OverHAL - HAL - UR1</title>
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
	<script src="OverHAL.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<!-- third party js end -->
	
	<!-- bundle -->
	<script src="./assets/js/vendor.min.js"></script>
	<script src="./assets/js/app.min.js"></script>

	<!-- third party js -->
	<!-- <script src="./assets/js/vendor/Chart.bundle.min.js"></script> -->
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
                                                <li class="breadcrumb-item"><a href="index.php"><i class="uil-home-alt"></i> Accueil HALUR1</a></li>
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
																					OverHAL permet d'une part de générer un fichier de publications TEI HAL pour Zip2HAL à partir d'un fichier source, d'autre part d'envoyer des mails aux auteurs pour récupération du manuscrit auteur. Conçu à partir d'un script de <a target="_blank" rel="noopener noreferrer" href="http://igm.univ-mlv.fr/~gambette/ExtractionHAL/CouvertureHAL/">Philippe Gambette</a> (CouvertureHAL), il a été créé par Olivier Troccaz (conception-développement) et Laurent Jonchère (conception).																					
                                        </p>
																				
																				<p class="mb-4">
                                            Contacts : <a target='_blank' rel='noopener noreferrer' href="https://openaccess.univ-rennes1.fr/interlocuteurs/laurent-jonchere">Laurent Jonchère</a> (Université de Rennes 1) / <a target='_blank' rel='noopener noreferrer' href="https://openaccess.univ-rennes1.fr/interlocuteurs/olivier-troccaz">Olivier Troccaz</a> (CNRS CReAAH/OSUR).
                                        </p>

                                    </div> <!-- end card-body-->
                                    
                                </div> <!-- end card-->

                            </div> <!-- end col -->
                            <div class="col-lg-6 col-xl-4 d-flex">
                                <div class="card shadow-lg w-100">
                                    <div class="card-body">
                                        <h5 class="badge badge-primary badge-pill">Mode d'emploi</h5>
                                        <div class=" mb-2">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <a target="_blank" rel="noopener noreferrer" href="https://halur1.univ-rennes1.fr/Manuel-OverHAL.pdf"><i class="mdi mdi-file-pdf-box-outline mr-1"></i> Télécharger le manuel</a>
                                                </li>
                                               
                                            </ul> 
                                        </div>
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
                                        
																				<form enctype="multipart/form-data" action="OverHAL_results.php" method="post" accept-charset="UTF-8">
																				<input type="hidden" name="MAX_FILE_SIZE" value="900000">
																				
                                        <h5 class="badge badge-primary badge-pill">Paramétrage</h5>
																				
																				<div class="row mb-3">
                                            <div class="col-sm-12">
                                                <div class="border border-dark rounded p-2 mb-2">
                                                    <div class='h4 text-uppercase text-secondary mb-3'>Étape 1 : Charger le fichier</div>

                                                    <div class="border p-2 small text-primary mb-2">
                                                        Envoyez les fichiers résultat (900 Ko maximum, voir ci-dessus le "mode d'emploi") :
                                                    </div>
                                                
                                                    <div class="row">
                                                        <div class="form-group col-sm-4">
                                                            <label for="wos_csv" class="badge badge-secondary-lighten">Web of Science (CSV) :</label>
                                                            <input class="form-control mb-1" id="wos_csv" name="wos_csv" type="file">

                                                            <label for="scopus" class="badge badge-secondary-lighten">Scopus (CSV) :</label>
                                                            <input class="form-control mb-1" id="scopus" name="scopus" type="file">
																														
																														<label for="pubmed_txt" class="badge badge-secondary-lighten">Pubmed (TXT) :</label>
                                                            <input class="form-control mb-1" id="pubmed_txt" name="pubmed_txt" type="file">
																														
                                                        </div>

                                                        <div class="form-group col-sm-4">

                                                            <label for="zotero" class="badge badge-secondary-lighten">Zotero (CSV) :</label>
                                                            <input class="form-control mb-1" id="zotero" name="zotero" type="file">

                                                            <label for="scifin" class="badge badge-secondary-lighten">SciFinder (CSV) :</label>
                                                            <input class="form-control mb-1" id="scifin" name="scifin" type="file">
																														
																														<label for="pubmed_fcgi" class="badge badge-secondary-lighten">Pubmed (FCGI) :</label>
                                                            <input class="form-control mb-1" id="pubmed_fcgi" name="pubmed_fcgi" type="file">
																														
																														<span><a target="_blank" rel="noopener noreferrer" href="OverHAL_FCGI_construct_import.php"> Construire un fichier FCGI à partir d'une liste de PMID</a>, puis l'envoyer à OverHAL avec le formulaire ci-dessus.</span>
                                                            
                                                        </div>

                                                        <div class="form-group col-sm-4">

                                                            <label for="pubmed_csv" class="badge badge-secondary-lighten">Pubmed (CSV) : </label>
                                                            <input class="form-control mb-1" id="pubmed_csv" name="pubmed_csv" type="file">

                                                            <label for="pubmed_xml" class="badge badge-secondary-lighten">Pubmed (XML) : </label>
                                                            <input class="form-control mb-1" id="pubmed_xml" name="pubmed_xml" type="file">

																														<?php
																														include("./Glob_IP_list.php");
																														if (in_array($ip, $IP_aut)) {
																															echo '<label for="dimensions" class="badge badge-secondary-lighten">Dimensions (CSV) :</label>';
																															echo '<input class="form-control mb-1" id="dimensions" name="dimensions" type="file" /><br/>';
																														}
																														?>
                                                        </div>
                                                    </div> <!-- .row -->

                                                    <div class="row border-top pt-3">
                                                        <div class="col-sm-12">
                                                            <div class="mb-2 small">
                                                                <span class="badge badge-primary badge-pill"><i class="mdi mdi-flash-alert mdi-18px"></i></span>
                                                            Expérimental (enregistrer des alertes mail en html) :</div>
                                                           
                                                        </div>
                                                        
                                                    </div> <!-- .row -->

                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <label for="wos_csv" class="badge badge-secondary-lighten">Web of Science (HTML) :</label>
                                                            <input class="form-control mb-1" id="wos_html" name="wos_html" type="file">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="wos_csv" class="badge badge-secondary-lighten">Pubmed (HTML) : </label>
                                                    <input class="form-control mb-1" id="pubmed_html" name="pubmed_html" type="file">
                                                        </div>
                                                        
                                                    </div> <!-- .row -->

                                                </div> <!-- .border -->

                                            </div> <!-- .col-sm-12 -->
                                        </div> <!-- .row -->

																				<div class="row mb-3">
                                            <div class="col-sm-12">
                                                <div class="border border-dark rounded p-2 mb-2">
                                                    <div class='h4 text-uppercase text-secondary mb-3'>Étape 2 : Construire la requête HAL</div>

                                                    <div class="form-group row mb-1">
                                                        <label for="reqHAL" class="col-12 col-md-2 col-form-label font-weight-bold">
                                                        Requête libre
                                                        </label>
																												<?php
																												$reqHAL = "https://api.archives-ouvertes.fr/search/?q=collCode_s:\"IRSET\"&fq=(producedDateY_i:\"".date('Y', time())."\")&rows=10000&fl=docType_s,docid,halId_s,authFullName_s,title_s,subTitle_s,journalTitle_s,volume_s,issue_s,page_s,producedDateY_i,proceedings_s,files_s,label_s,citationFull_s,bookTitle_s,doiId_s,conferenceStartDateY_i,publisherLink_s,seeAlso_s";
																												?>                                                        
                                                        <div class="col-12 col-md-10">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <button type="button" tabindex="0" class="btn btn-info" data-html="true" data-toggle="popover" data-trigger="focus" title="" data-content="consultez l'API de HAL <a href='https://api.archives-ouvertes.fr/docs/search'>https://api.archives-ouvertes.fr/docs/search</a>" data-original-title="">
                                                                    <i class="mdi mdi-comment-question text-white"></i>
                                                                    </button>
                                                                </div>
                                                                <input type="text" id="reqHAL" name="hal" class="form-control"  value='<?php echo $reqHAL;?>'>
                                                           
                                                        </div>
                                                    
                                                </div>
                                            </div> <!-- .form-group -->

                                            <div class="form-group row mb-1">
                                                <div class="col-12">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
																												<input type="checkbox" checked class="custom-control-input" name="limzot" id="limzot" value="ok">
                                                        <label class="custom-control-label" for="limzot">Limiter l'affichage des résultats aux seules références non trouvées dans HAL</label>
                                                    </div>
                                                </div>
                                            </div> <!-- .form-group -->


                                            <div class="form-group row mb-1">
                                                <div class="col-12">
                                                    <h3 class="d-inline-block border-bottom border-primary text-primary">OU</h3>
                                                </div>
                                            </div> <!-- .form-group -->

                                            <div class="form-group row mb-3">
                                                <label for="team" class="col-12 col-md-2 col-form-label font-weight-bold">
                                                Code collection HAL
                                                </label>
                                                
                                                <div class="col-12 col-md-10">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button type="button" tabindex="0" class="btn btn-info" data-html="true" data-toggle="popover" data-trigger="focus" title="" data-content='Code visible dans l&apos;URL d&apos;une collection.
                                            Exemple : IPR-MOL est le code de la collection http://hal.archives-ouvertes.fr/ <span class="font-weight-bold">IPR-PMOL</span> de l&apos;équipe Physique moléculaire de l&apos;unité IPR UMR CNRS 6251' data-original-title="">
                                                            <i class="mdi mdi-comment-question text-white"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" id="team" name="team" class="form-control"  value="IRSET" onchange="majReqHAL();">
                                                    <a class="ml-2 small" target="_blank" rel="noopener noreferrer" href="https://hal-univ-rennes1.archives-ouvertes.fr/page/codes-collections">Trouver le code<br>de mon équipe / labo</a>
                                                    </div>

                                                    
                                                </div>
                                            </div> <!-- .form-group -->
																						
																						<div class="form-group row mb-1">
																							<div class="form-group col-sm-2">
																								<label for="year1">Période : Depuis</label>
																								<select id="year1" class="custom-select" name="year1" onchange="majReqHAL();">
																								<?php
																								$moisactuel = date('n', time());
																								if ($moisactuel >= 10) {$i = date('Y', time())+1;}else{$i = date('Y', time());}
																								while ($i >= date('Y', time()) - 30) {
																									if (isset($year1) && $year1 == $i) {$txt = "selected";}else{$txt = "";}
																									echo '<option value='.$i.' '.$txt.'>'.$i.'</option>' ;
																									$i--;
																								}
																								?>
																								</select>
																							</div>
																							<div class="form-group col-sm-2">
																								<label for="year2">Jusqu'à</label>
																								<select id="year2" class="custom-select" name="year2" onchange="majReqHAL();">
																								<?php
																								$moisactuel = date('n', time());
																								if ($moisactuel >= 10) {$i = date('Y', time())+1;}else{$i = date('Y', time());}
																								while ($i >= date('Y', time()) - 30) {
																									if (isset($year2) && $year2 == $i) {$txt = "selected";}else{$txt = "";}
																									echo '<option value='.$i.' '.$txt.'>'.$i.'</option>';
																									$i--;
																								}
																								?>
																								</select>
																							</div>
                                            </div><!-- .form-group -->

                                            <div class="form-group row mb-2">
                                                <div class="col-12">
                                                    <div class="custom-control custom-checkbox">
																												<input type="checkbox" checked class="custom-control-input" name="aparai" id="aparai" value="ok" onchange="majReqHAL();">
                                                        <label class="custom-control-label" for="aparai">Inclure les articles "À paraître"</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
																												<input type="checkbox" class="custom-control-input" name="txtint" id="txtint" value="ok" onchange="majReqHAL();">
                                                        <label class="custom-control-label" for="txtint">Requête uniquement sur le texte intégral (dépôt HAL ou lien arxiv ou lien Pubmed Central)</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
																												<input type="checkbox" checked class="custom-control-input" name="desactSR" value="oui" id="desactSR">
                                                        <label class="custom-control-label" for="desactSR">Désactiver les recherches Sherpa/RoMEO</label>
                                                    </div>
																										<?php
																										include("./Glob_IP_list.php");
																										if (in_array($ip, $IP_aut)) {
																											echo '<div class="custom-control custom-checkbox">';
																											echo '  <input type="checkbox" class="custom-control-input" name="actMailsM" value="oui" id="actMailsM">';
                                                      echo '  <label class="custom-control-label" for="actMailsM">Activer les procédures d\'envoi de mails (demande du manuscrit à l\'auteur = M)</label>';
																											echo '</div>';
																											
																											echo '<div class="custom-control custom-checkbox">';
																											echo '  <input type="checkbox" class="custom-control-input" name="actMailsP" value="oui" id="actMailsP">';
                                                      echo '  <label class="custom-control-label" for="actMailsP">Activer les procédures d\'envoi de mails (autorisation de dépôt du post-print = P)</label>';
																											echo '</div>';
																										}else{
																											echo('<br/>La fonctionnalité d\'envoi d\'emails ne s\'affiche que pour les utilisateurs autorisés (adresse IP). Voir le mode d\'emploi / installation<br /><br />');
																										}
																										?>
																										
                                                </div>
                                            </div> <!-- .form-group -->

                                            <div class="form-group row mb-1">
                                                <div class="col-12">
                                                    <div class="custom-control custom-checkbox">
																												<input type="checkbox" class="custom-control-input" name="bibtex" value="oui" id="bibtex">
                                                        <label class="custom-control-label" for="bibtex">Générer un bibtex de publications en français, à partir d'un fichier Zotero, avec éventuellement,</label>
                                                    </div>
                                                    
                                                </div>
                                            </div> <!-- .form-group -->

                                            <div class="form-group row mb-2">
                                                <label for="keywords" class="col-md-3 offset-md-1 col-form-label font-weight-bold p-0">
                                                ces mots-clés <span class="small">(séparés par "; ")</span> :
                                                </label>
                                                
                                                <div class="col-12 col-md-6">
                                                    <input type="text" class="form-control" id="keywords" name="keywords" size="40">
                                                </div>

                                            </div> <!-- .form-group -->

                                            <div class="form-group row mb-2">
                                                <label for="author" class="col-md-3 offset-md-1 col-form-label font-weight-bold p-0">
                                                cet auteur <span class="small">("Mortier, Renaud", par exemple)</span> :
                                                </label>
                                                
                                                <div class="col-12 col-md-6">
                                                    <input type="text" class="form-control" id="author" name="author" size="40">
                                                </div>

                                            </div> <!-- .form-group -->

                                            <div class="form-group row mb-1">
                                                <label for="joker" class="col-md-2 col-form-label font-weight-bold">
                                                URL Joker :
                                                </label>
                                                
                                                <div class="col-12 col-md-10">
                                                    <input type="text" class="form-control" id="joker" name="joker" size="40">
                                                </div>

                                            </div> <!-- .form-group -->

                                            


                                        </div> <!-- .border -->


                                        <div class="form-group row mt-4">
                                                <div class="col-12 justify-content-center d-flex">
																										<input type="hidden" name="ip" value="<?php echo $ip; ?>">
                                                    <input type="submit" class="btn btn-md btn-primary btn-lg" value="Valider" name="soumis">
                                                </div>
                                            </div>

                                    </div> <!-- .col-sm-12 -->
                                </div> <!-- .row -->
																
																				</form>
																

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
        <!-- <script src="./assets/js/vendor/Chart.bundle.min.js"></script> -->
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