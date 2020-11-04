<?php
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
	
<form enctype="multipart/form-data" action="OverHAL_FCGI_construct_export.php" method="post" accept-charset="UTF-8">
<p class="form-inline">
<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
<label for="fcgi_csv">FCGI (CSV)</label> : <input class="form-control" id="fcgi_csv" style="height: 25px; font-size: 90%; padding: 0px;" name="fcgi_csv" type="file" />
(<a href="./OverHAL_fcgi_modele.csv">voir un fichier modèle</a>)
<br/>
<input type="submit" class="form-control btn btn-md btn-primary" value="Envoyer">
</form>
