<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Procédure OpenAlex - OpenAlex procedure
 */
 
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "./HAL_SCD.css";
}
?>
<link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
<link href="bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" language="Javascript" src="OverHAL.js"></script>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
<form action="OverHAL_OpenAlex_construct_export.php" name="OpenAlex" method="POST" accept-charset="UTF-8">
<p class="form-inline">
<!-- https://api.openalex.org/works?filter=institutions.id:I4210090783,from_publication_date:2022-01-01,to_publication_date:2023-01-01&mailto=laurent.jonchere@univ-rennes.fr&page=1&per-page=200 -->
<label for="openalex_url">OpenAlex (URL sans critère(s) & de pagination)</label> : <input class="form-control" id="openalex_url" style="width:600px; height: 25px; font-size: 90%; padding: 0px;" name="openalex_url" type="text" />
(<a target="_blank" href="https://api.openalex.org/works?filter=institutions.id:I4210090783|I4210115835,type:article,type_crossref:!posted-content,best_oa_location.source.id:!https://openalex.org/S4306402512,primary_location.source.type:!repository,from_publication_date:2022-01-01,to_publication_date:2022-12-31&mailto=laurent.jonchere@univ-rennes.fr">voir une URL modèle</a>)
<br/>
<input type="submit" class="form-control btn btn-md btn-primary" value="Envoyer">
</form>
