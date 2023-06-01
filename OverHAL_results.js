/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Scripts pour les résultats obtenus - Scripts for the results obtained
 */
 
//titreN = titre normalisé
//titreO = titre original
function majMailsM(adr,titreN,doi,typ,file,lang,labo,titreO) {
  alert('Mail envoyé à ' + adr);
  $.post("OverHAL_mails_maj.php", {
    qui: adr,
    quoi1: titreN,
    quoi2: doi,
    type: typ,
    fic: file,
    lang: lang,
		labo: labo,
		titre: titreO
  });
  document.getElementById(titreN+'M').innerHTML = "<b>OK</b>";
}

function majMailsP(adr,titreN,doi,typ,file,lang,labo,titreO) {
  alert('Mail envoyé à ' + adr);
  $.post("OverHAL_mails_maj.php", {
    qui: adr,
    quoi1: titreN,
    quoi2: doi,
    type: typ,
    fic: file,
    lang: lang,
		labo: labo,
		titre: titreO
  });
  document.getElementById(titreN+'P').innerHTML = "<b>OK</b>";
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
