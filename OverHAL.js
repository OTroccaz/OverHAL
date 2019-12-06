function majReqHAL() {
  //reqHAL = document.getElementById("reqHAL").value;
  team = document.getElementById("team").value;
  year1 = document.getElementById("year1").value;
  year2 = document.getElementById("year2").value;
  iann = year1;
  year = "";
  while (iann <= year2) {
    if (iann == year1) {year += "&fq=(";}else{year += "%20OR%20";}
    year += 'producedDateY_i:"'+iann+'"';
    iann++;
  }
  year += ")";
  txtint = "";
  txtintplus = "";
  if(document.getElementById("txtint").checked == true){
    txtint = "%20AND%20(submitType_s:file%20OR%20arxivId_s:?*%20OR%20pubmedcentralId_s:?*)";
    txtintplus = ",arxivId_s,pubmedcentralId_s";
  }
  aparai = "";
  if(document.getElementById("aparai").checked == true){
    aparai = "";
  }else{
    aparai = "%20AND%20NOT%20inPress_bool:%22true%22";
  }
  hal = "https://api.archives-ouvertes.fr/search/?q=collCode_s:";
  hal += '\"'+team+'\"';
  //hal += "&fq=";
  hal += year;
  hal += txtint;
  hal += aparai;
  hal += "&rows=10000&fl=docType_s,docid,halId_s,authFullName_s,title_s,subTitle_s,journalTitle_s,volume_s,issue_s,page_s,producedDateY_i,proceedings_s,files_s,label_s,citationFull_s,bookTitle_s,doiId_s,conferenceStartDateY_i";
  hal += txtintplus;
  document.getElementById("reqHAL").value = hal;
  
  //alert(reqHAL);
}
