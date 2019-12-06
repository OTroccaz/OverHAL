<?php
if (isset($_GET['css']) && ($_GET['css'] != ""))
{
  $css = $_GET['css'];
}else{
  $css = "./HAL_SCD.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <title>CouvHAL : Comparaison HAL vs Scopus</title>
  <meta name="Description" content="CouvertureHAL : Comparaison HAL vs Scopus">
  <link rel="stylesheet" href="<?php echo($css);?>" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script type="text/javascript" src="./js/overlib.js"></script>
</head>

<body>
<b>Aucune revue trouvée dans Sherpa-Romeo pour ce titre</b>
</body></html>