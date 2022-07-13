function majReqHAL() {
  //var reqHAL = document.getElementById("reqHAL").value;
  var team = document.getElementById("team").value;
  var year1 = document.getElementById("year1").value;
  var year2 = document.getElementById("year2").value;
  var iann = year1;
  var year = "";
  while (iann <= year2) {
    if (iann == year1) {year += "&fq=(";}else{year += "%20OR%20";}
    year += 'producedDateY_i:"'+iann+'"';
    iann++;
  }
  year += ")";
  var txtint = "";
  var txtintplus = "";
  if(document.getElementById("txtint").checked == true){
    txtint = "%20AND%20(submitType_s:file%20OR%20arxivId_s:?*)";
    txtintplus = ",arxivId_s";
  }
  if(document.getElementById("aparai").checked == true){
    var aparai = "";
  }else{
    var aparai = "%20AND%20NOT%20inPress_bool:%22true%22";
  }
  var hal = "https://api.archives-ouvertes.fr/search/?q=collCode_s:";
  hal += '\"'+team+'\"';
  //hal += "&fq=";
  hal += year;
  hal += txtint;
  hal += aparai;
  hal += "&rows=10000&fl=docType_s,docid,halId_s,authFullName_s,title_s,subTitle_s,journalTitle_s,volume_s,issue_s,page_s,producedDateY_i,proceedings_s,files_s,label_s,citationFull_s,bookTitle_s,doiId_s,conferenceStartDateY_i,publisherLink_s,seeAlso_s";
  hal += txtintplus;
  document.getElementById("reqHAL").value = hal;
  
  //alert(reqHAL);
}
