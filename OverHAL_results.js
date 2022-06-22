function majMailsM(adr,titre,doi,typ,file,lang,labo) {
  alert('Mail envoyé à ' + adr);
  $.post("OverHAL_mails_maj.php", {
    qui: adr,
    quoi1: titre,
    quoi2: doi,
    type: typ,
    fic: file,
    lang: lang,
		labo: labo
  });
  document.getElementById(titre+'M').innerHTML = "<b>OK</b>";
}

function majMailsP(adr,titre,doi,typ,file,lang,labo) {
  alert('Mail envoyé à ' + adr);
  $.post("OverHAL_mails_maj.php", {
    qui: adr,
    quoi1: titre,
    quoi2: doi,
    type: typ,
    fic: file,
    lang: lang,
		labo: labo
  });
  document.getElementById(titre+'P').innerHTML = "<b>OK</b>";
}

function mailto(fic,adr,sub,mes) {
  //alert(fic);
  $.post("OverHAL_mailto.php", {
    fich: fic,
		qui: adr,
    quoi: sub,
		mess: mes
  });
}
 
function errAdr(adr) {
  alert('L\'envoi du mail a échoué car au moins une adresse est erronée : ' + adr);
}
