<?php
###génère les options d'une liste déroulante
###PARAMS :
####$l_name (NAME de l'élément SELECT)
####$l_array (variable d'où sont extraites les options)
####$l_sqldatas (champs de référence dans la base SQL pour l'auto select)
####[OPTIONNEL] $firstblank (si rempli, ajoute une première option sans valeur)
####[OPTIONNEL] $selfvalue (si pas empty; valeur de l'option=l'option)
####[OPTIONNEL] id
####[OPTIONNEL] $class
####[OPTIONNEL] $l_size
function createSelectList($l_name,$l_array,$l_sqldatas,$firstblank='',$selfvalue='',$id='',$class='',$l_size='1') {

	$opt = '';
	if (!empty($id)) {
		$opt .=' id="'.$id.'"';
	}
	if (!empty($class)) {
		$opt .=' class="'.$class.'"';
	}

	$list = '<select name="'.$l_name.'"'.$opt.' size="'.$l_size.'">';
	if (!empty($firstblank)) {
		$list .='<option value="">'.$firstblank.'</option>';
	}

	$list .= '<optgroup label="-">';

	foreach ($l_array as $key => $value) {
		if ($value == "--") {
			$list .= '</optgroup><optgroup label="-">';

		} else {
			if (!empty($selfvalue)) {
				$list .= '<option value="'.$value.'"';
			} else {
				$list .= '<option value="'.$key.'"';
			}
			if ((!empty($l_sqldatas)) && ($l_sqldatas == $key)) {
				$list .=' selected';
			}
			$list .= '>'.$value.'</option>';
		}
	}

	$list .= '</optgroup>';
	$list .= '</select> ';
//
	return $list;
}

function createRadioList($l_name,$l_array,$l_sqldatas) {

	$list = '';
	foreach ($l_array as $key => $value) {
		$list .= '<input type="radio" name="'.$l_name.'" value="'.$key.'"';
		if ($l_sqldatas == $key) {
			$list .=' checked';
		}
		$list .= '> '.$value.' ';
	}
//
	return $list;
}

function createCheckboxList($l_name,$l_array,$l_sqldatas) {

	$list = '';
	foreach ($l_array as $key => $value) {
		$list .= '<input type="checkbox" name="'.$l_name.'[]" value="'.$key.'"';
		if (strchr($l_sqldatas, $key)) {
			$list .=' checked';
		}
		$list .= '> '.$value.' ';
	}
//
	return $list;
}

function createNumericCheckboxList($l_name,$l_array,$l_sqldatas) {

	$list = '';
	foreach ($l_array as $key => $value) {
		$list .= '<input type="checkbox" name="'.$l_name.'['.$key.']" value="1"';
		if ((strlen($l_sqldatas) >= $key) && $l_sqldatas{$key} == '1') {
			$list .=' checked';
		}
		$list .= '> '.$value.' ';
	}
//
	return $list;
}

//retourne un array contenant une fourchette de nombres
function getFork ($nbre_start,$nbre_end) {
	$fork = array();
	for ($i=$nbre_start; $i<=$nbre_end; $i++) {
		$fork[$i] = $i;
	}
	return($fork);
}

?>