<?php
/*
include("./normalize.php");
*/
function testFic($fileUrl)
{
    $headers = @get_headers($fileUrl);
    if (preg_match("|200|", $headers[0])) {
      // file exists
      //print "exists\n";
      return true;
    } else {
      // file doesn't exists
      //print "does not exist\n";
      return false;
    }
}

function testOALic($url, $vol, $iss, $pag, $dat, $pdfCR, &$evd, &$titLic, &$typLic, &$compNC, &$compND, &$compSA, &$urlPDF) {
  $context = stream_context_create(array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "User-Agent: Mozilla/5.0 (Windows NT 6.0; rv:8.0) Gecko/20100101 Firefox/8.0\r\n" .
              "Cookie: foo=bar\r\n"
  )
  ));
  
  $oa = "";
  $evd = "restricted";//by default ?
  $titLic = "";//'title' license field from oadoi
  $tipLic = "";//'title' license field from oadoi
  $compNC = "";//complementary information on the license from oadoi
  $compND = "";//complementary information on the license from oadoi
  $compSA = "";//complementary information on the license from oadoi
  $titPDF = "";//PDF file name
  $urlPDF = "";//PDF file URL
  $dat = 0;
  $vol = 0;
  $iss = 0;
  $pag = 0;

  
  //some variables
  $doi = str_replace("https://api.oadoi.org/v2/", "", $url);
  $entDOITab = explode("/", $doi);
  $entDOI = $entDOITab[0];
  $finURL = str_replace($entDOI."/", "", $doi);
  if ($entDOI == "10.3917") {$finURL = str_replace(".", "_", strtoupper($finURL));}
  if ($entDOI == "10.1159") {$finURL = str_replace("000", "", strtoupper($finURL));}
  $titPDF = normalize($finURL);
  //$titPDF = $halID;
  $numSuf = preg_replace("/[^0-9]/", "", $finURL);//We keep only the number
  //echo $numSuf.'<br>';

$url .= "?email=yourmailadress";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'SCD (https://halur1.univ-rennes1.fr)');
  curl_setopt($ch, CURLOPT_USERAGENT, 'PROXY (http://siproxy.univ-rennes1.fr)');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $json = curl_exec($ch);
  //echo $json;
  curl_close($ch);
  $parsed_json = json_decode($json);
  var_dump($parsed_json);

  if (isset($parsed_json->{'HTTP_status_code'}))//invalid DOI?
  {
    //echo 'Invalid DOI?';
  }else{
    if ($parsed_json->{'is_oa'} !== false)//is_oa true
    {
      //some tests on the license type
      if (isset($parsed_json->{'best_oa_location'}->{'license'}))
      {
        if (substr($parsed_json->{'best_oa_location'}->{'license'}, 0, 2) == "cc")//type licence oa
        {
          $oa = "ok";
          if (strpos($parsed_json->{'best_oa_location'}->{'license'}, "nc") !== false) {$compNC = "ok";}
          if (strpos($parsed_json->{'best_oa_location'}->{'license'}, "nd") !== false) {$compND = "ok";}
          if (strpos($parsed_json->{'best_oa_location'}->{'license'}, "sa") !== false) {$compSA = "ok";}
          if ($parsed_json->{'best_oa_location'}->{'evidence'} !== false)
          {
            $evdCont = $parsed_json->{'best_oa_location'}->{'evidence'};
            if (stripos($evdCont, "oa") !== false) {$evd = "greenPublisher";}
            if (stripos($evdCont, "open") !== false) {$evd = "greenPublisher";}
            if (stripos($evdCont, "hybrid") !== false) {$evd = "publisherPaid";}
          }
        }
      }else{//license = null
        if (isset($parsed_json->{'journal_issns'}))
        {
          $issn = $parsed_json->{'journal_issns'};
          $doajUrl = "https://doaj.org/api/v1/search/journals/issn%3A".$issn;
          //echo $url.' - '.$doajUrl;
          $doaj = curl_init();
          curl_setopt($doaj, CURLOPT_URL, $doajUrl);
          curl_setopt($doaj, CURLOPT_HEADER, 0);
          curl_setopt($doaj, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($doaj, CURLOPT_USERAGENT, 'SCD (https://halur1.univ-rennes1.fr)');
          curl_setopt($doaj, CURLOPT_USERAGENT, 'PROXY (http://siproxy.univ-rennes1.fr)');
          curl_setopt($doaj, CURLOPT_SSL_VERIFYPEER, false);
          $doajJson = curl_exec($doaj);
          //echo $doajJson;
          curl_close($doaj);
          $doajParsed_json = json_decode($doajJson);
          //var_dump($doajParsed_json);
          if ($doajParsed_json->{'total'} != 0)//oa review
          {
            //var_dump($doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'});
            if($doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'}[0]->{'open_access'})
            {
              $oa = "ok";
              $titLic = $doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'}[0]->{'title'};
              $tipLic = $doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'}[0]->{'type'};
              $compNC = $doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'}[0]->{'NC'};
              $compND = $doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'}[0]->{'ND'};
              $compSA = $doajParsed_json->{'results'}[0]->{'bibjson'}->{'license'}[0]->{'SA'};
            }
          }
        }
      }
    }
  }
  //echo $urlPDF.'<br>';
  if ($oa == "ok")
  {
    if ($pdfCR != "")//1st method > URL PDF file found with crossRef via CrosHAL.php
    {
      //is the URL valid ?
      /*
      $timeout = 10;
      $ch = curl_init(urldecode($pdfCR));
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      if (preg_match('`^https://`i', $url))
      {
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      }
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_exec($ch);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      if ($http_code == "200") {$urlPDF = urldecode($pdfCR);}
      */
      $urlPDF = urldecode($pdfCR);
    }else{
      //2nd method to find the PDF
      include "./oaDOI_entDOI_urlPDF.php";
      //var_dump($entURL);
      //echo $entDOI. '<br>';
      $keyURL = array_search($entDOI, $entURL);
      $keyURL = str_replace("/DuplicateKey", "", $keyURL);//Problem with duplicate keys
      //echo $keyURL.'<br>';
      if ($keyURL != "")
      {
        $urlPDF = $keyURL;
        $urlPDF = str_replace("NUMERO-SUFFIXE", $numSuf, $urlPDF);
        $urlPDF = str_replace("DOI", $doi, $urlPDF);
        $urlPDF = str_replace("PREFIXE", $entDOI, $urlPDF);
        $urlPDF = str_replace("SUFFIXE", $finURL, $urlPDF);
        $urlPDF = str_replace("ANNEE", $dat, $urlPDF);
        $urlPDF = str_replace("/vVOL/", "/v".$vol."/", $urlPDF);
        $urlPDF = str_replace("/VOL/", "/".$vol."/", $urlPDF);
        $urlPDF = str_replace("/nNUM/", "/n".$iss."/", $urlPDF);
        $urlPDF = str_replace("/NUM/", "/".$iss."/", $urlPDF);
        $urlPDF = str_replace("STARTPAGE", $pag, $urlPDF);
        if ($entDOI == "10.1117") {$urlPDF = str_replace("D.O.I", "DOI", $urlPDF);}
        //echo ($urlPDF.'<br>');
      }
    }
    //echo ($urlPDF.'<br>');
    if ($urlPDF != "")//PDF URL found with the 1st or 2nd method
    {
      /*
      if (!copy($urlPDF, './PDF/'.$halID.'.pdf', $context))
      //if (!file_put_contents('./PDF/'.$titPDF.'.pdf', file_get_contents($urlPDF)))
      {
        //echo('Unable to copy file');
        $titPDF = "";
      }else{
        //echo (mime_content_type('./PDF/'.$titPDF.'.pdf').'<br>');
        if(mime_content_type('./PDF/'.$halID.'.pdf') != "application/pdf")
        {//deleting the file
          unlink('./PDF/'.$halID.'.pdf');
          $titPDF = "";
        }else{
          $titPDF = $halID;
        }
      }
      */
      //$titPDF = $halID;
    }else{//3rd method to find the PDF
      $pdf = $parsed_json->{'oa_locations'};
      //var_dump($pdf);
      foreach ($pdf as $elt)
      {
        //echo 'toto : '.$elt->{'url_for_pdf'}.'<br>';
        if (isset($elt->{'url_for_pdf'}) && $elt->{'url_for_pdf'} != "" && isset($elt->{'version'}))
        {
          /*
          //$evd = $elt->{'evidence'};
          //echo $lic.' - '.$evd;
          if (!copy($elt->{'url_for_pdf'}, './PDF/'.$halID.'.pdf', $context))
          //if (!testFic($elt->{'url_for_pdf'}))
          {
            //Unable to copy file
            $titPDF = "";
          }else{
            //echo $elt->{'url_for_pdf'};
            //echo (mime_content_type('./PDF/'.$titPDF.'.pdf').'<br>');
            if(mime_content_type('./PDF/'.$halID.'.pdf') == "application/pdf")
            {
              $titPDF = $halID;
              break;
            }else{//deleting the file
              unlink('./PDF/'.$halID.'.pdf');
              $titPDF = "";
            }
          }
          */
          $urlPDF = $elt->{'url_for_pdf'};
          //$titPDF = $halID;
        }else{
          //$titPDF = "";
        }
      }
    }
  }else{
    //$titPDF = "";
  }
  //echo $titPDF;
}
?>