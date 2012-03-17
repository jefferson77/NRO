<?php
# =============
# = Variables =
# =============
$pathannexe =   Conf::read('Env.root').'media/annexe/anim/';
$pathgaleries = Conf::read('Env.root').'media/galerie/anim/';

# =============
# = Fonctions =
# =============
/**
 * Remplace '-%%' l'ancien séparateur du champ 'shopselection' par '|'
 *
 * @return string avec le nouveau séparateur
 * @author Nico
 **/
function cleanShopSelection($shopselection)
{
	global $DB;
	
	if (strstr($shopselection, '-%%')) {
		$parts = explode('-%%', $shopselection);
		$parts = array_filter($parts);
		$newshopselection = '|'.implode('|', $parts).'|';
	} else {
		$newshopselection = $shopselection;
	}

	if ($newshopselection != $shopselection) $DB->inline("UPDATE animjob SET shopselection = '".$newshopselection."' WHERE idanimjob = ".$_REQUEST['idanimjob']." LIMIT 1");
	
	return $newshopselection;
}

/**
 * Filtre les fichiers qui appartiennent au job donné dans le dossier des annexes
 *
 * @return bool
 * @author Nico
 **/
function isannex($name) {
	return strchr($name, 'anim-'.$_REQUEST['idanimjob'].'-');
}

/**
 * Filtre les photos d'un job et retourne les fichiers photos valides (pas les thumbs)
 *
 * @return bool
 * @author Nico
 **/
function isphoto($name) {
	return (($name != '.') and ($name != '..') and ($name{0} != 't'))?true:false;
}

?>