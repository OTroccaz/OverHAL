<?php
/*
 * OverHAL - Convertissez vos imports éditeurs en TEI - Convert your publisher imports to TEI
 *
 * Copyright (C) 2023 Olivier Troccaz (olivier.troccaz@cnrs.fr) and Laurent Jonchère (laurent.jonchere@univ-rennes.fr)
 * Released under the terms and conditions of the GNU General Public License (https://www.gnu.org/licenses/gpl-3.0.txt)
 *
 * Remplacement d'abréviations dans les affiliations par leurs équivalents complets - Replacement of abbreviations in affiliations with their full equivalents
 */
 
$search = array(
"Inst Technol",
"Ctr Hosp Univ",
"Ctr Hosp",
"Hosp",
"French Agcy Food Environm and Occupat Hlth and Safety",
"Fac Med",
"Fac Pharm",
"Hop",
"Inst Pasteur",
"Sci Po",
"Natl Inst Demog Studies",
"Ctr",
"Univ Hosp",
"Hop Pontchaillou",
"Canc",
"Sante Publ France",
"Inst Univ",
"Clin",
"Assistance Publ",
"Inst Publ Hlth",
"Ecole Hautes Etud Sante Publ",
"Inst Rech Dev",
"French Inst Publ Hlth Surveillance",
"Adv Sch Publ Hlth EHESP",
"Res Ctr",
"Coll",
"Natl",
"Acad Sci",
"Ecole Natl Super Chim Rennes",
"Natl Inst Mat Sci",
"Chem Phys",
"Ecole Metiers Environm",
"Rech and Innovat",
"Key Lab",
"Technol",
"Fed"
);

$replace = array(
"Institute of technology",
"CHU",
"CHU",
"CHU",
"ANSES",
"Faculté de médecine",
"Faculté de pharmacie",
"Hôpital",
"Insitut Pasteur",
"Science politique",
"INED",
"Centre",
"CHU",
"CHU Rennes",
"Cancer",
"Santé Publique France",
"Institut Universitaire",
"Clinique",
"Assistance Publique",
"Institute of Public Health",
"EHESP",
"IRD",
"InVS",
"ESHEP",
"Research Center",
"College",
"National",
"Academy of Sciences",
"ENSCR",
"NIMS",
"Chemical Physics",
"Ecole des Métiers de l'Environnement",
"Recherche and Innovation",
"Key Laboratory",
"Technology",
"Federal"
);
?>