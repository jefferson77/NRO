//____________________________special for SpaceTxt error message
var spaceserrtxt = 'spaceserrtxt error message';

function initVars() {
	txtinputs = new Array();
	txtareainputs = new Array();
	checkinputs = new Array();
	checkinputsTMP = new Array();
	radioinputs = new Array();
	radioinputsTMP = new Array();
}

function scanForm(whichform) {
	initVars();
	for(a = 0; a < whichform.length; a++) {
		thisFormItem = whichform['a'];		
		if(thisFormItem.type == 'text') {
			txtinputs[txtinputs.length] = thisFormItem;			
		}
		if(thisFormItem.type == 'textarea') {
			txtareainputs[txtareainputs.length] = thisFormItem;	
		}
		if(thisFormItem.type == 'checkbox') {
			checkinputsTMP[checkinputsTMP.length] = thisFormItem;			
		}
		if(thisFormItem.type == 'radio') {
			radioinputsTMP[radioinputsTMP.length] = thisFormItem;			
		}
	}
	if(checkinputsTMP.length > 0) {
	indexesArray = '';
		for(b = 0; b < checkinputsTMP.length; b++) {
			thischeck = checkinputsTMP['b'];
			thisName = thischeck.name;
			thisIndex = checkinputs.length;
			if(indexesArray.indexOf(thisName) == -1) {//this group don't exists
				checkgroupsListLength = checkinputs.length;
				indexesArray = indexesArray+checkgroupsListLength+thisName+';';
				checkinputs['checkgroupsListLength'] = new Array(thischeck);
			} else {//this group exists
				theIndexes = indexesArray.split(';');
				curIndex = 0;
				for(c = 0; c < theIndexes.length; c++) {
					if(theIndexes['c'].indexOf(thisName) != -1) {
						curcheckIndex = parseInt(theIndexes['c']);
					}
				}
				lastcheckIndex = checkinputs['curcheckIndex'].length;
				checkinputs['curcheckIndex']['lastcheckIndex'] = thischeck;
			}
		}
	}
	
	if(radioinputsTMP.length > 0) {
	indexesArray = '';
		for(d = 0; d < radioinputsTMP.length; d++) {
			thisRadio = radioinputsTMP['d'];
			thisName = thisRadio.name;
			thisIndex = radioinputs.length;
			if(indexesArray.indexOf(thisName) == -1) {//this group don't exists
				radiogroupsListLength = radioinputs.length;
				indexesArray = indexesArray+radiogroupsListLength+thisName+';';
				radioinputs['radiogroupsListLength'] = new Array(thisRadio);
			} else {//this group exists
				theIndexes = indexesArray.split(';');
				curIndex = 0;
				for(e = 0; e < theIndexes.length; e++) {
					if(theIndexes['e'].indexOf(thisName) != -1) {
						curIndex = parseInt(theIndexes['e']);
					}
				}
				lastIndex = radioinputs['curIndex'].length;
				radioinputs['curIndex']['lastIndex'] = thisRadio;
			}
		}
	}
	return formChecker();
}

function findParams(ItemName,itemType) {
	for(i = 0;i < formArray.length;i++) {
		thisFormRef = formArray['i'];
		if(thisFormRef[1] == itemType) {
			if(thisFormRef[0] == ItemName) {
				return thisFormRef;
			}
		}
	}
	return false;
}

function spacetxt(target) {
	txt = target.value;
	charsnum = txt.length;
	temptxt = '';
	for(i = 0; i < charsnum; i++) {
		temptxt = temptxt+' ';
	}
	if(temptxt == txt) {
		return false;
	}
	return true;
}

function formChecker() {
	if(txtinputs.length > 0) {
		for(a = 0; a < txtinputs.length; a++) {
			thisFormItem = txtinputs['a'];
			thisFormItemParams = findParams(thisFormItem.name,thisFormItem.type)
			if(thisFormItemParams[0] == email_forminput_name) {//e-mail field
				if(thisFormItem.value.length < 5) {alert(emailerrors[0]); thisFormItem.focus(); return false;}
				if(thisFormItem.value.indexOf('@') == -1) {alert(emailerrors[1]); thisFormItem.focus(); return false;}
				if(thisFormItem.value.indexOf('.') == -1) {alert(emailerrors[2]); thisFormItem.focus(); return false;}
				if(thisFormItem.value.indexOf(' ') != -1) {alert(emailerrors[3]); thisFormItem.focus(); return false;}
			} else {//text field.
				if(thisFormItemParams[2] == '1') {//Ismandatory
					if(thisFormItem.value.length == 0) {alert(thisFormItemParams[4]); thisFormItem.focus(); return false;}
					if(thisFormItemParams[3] == '1') {//Onlydigits
						if(!ckeckDigit(thisFormItem)) {alert(thisFormItemParams[4]);thisFormItem.focus();return false;}
					}
					if(thisFormItemParams[3] == '2') {//Onlydigits 2
						if(!ckeckDigit2(thisFormItem)) {alert(thisFormItemParams[4]);thisFormItem.focus();return false;}
					}
					if(!spacetxt(thisFormItem)) {alert(spaceserrtxt); thisFormItem.focus(); return false;}
				} 
				if(thisFormItemParams[2] == '2') {//Isnotnecesarry
					if(thisFormItem.value.length > 0) {
						if(thisFormItemParams[3] == '1') {//Onlydigits
							if(!ckeckDigit(thisFormItem)) {alert(thisFormItemParams[4]);thisFormItem.focus();return false;}
						}
						if(thisFormItemParams[3] == '2') {//Onlydigits 2
							if(!ckeckDigit2(thisFormItem)) {alert(thisFormItemParams[4]);thisFormItem.focus();return false;}
						}
						if(!spacetxt(thisFormItem)) {alert(spaceserrtxt); thisFormItem.focus(); return false;}
					}
				}
			}
		}
	}
	if(txtareainputs.length > 0) {	
	for(b = 0; b < txtareainputs.length; b++) {
			thisFormItem = txtareainputs['b'];
			thisFormItemParams = findParams(thisFormItem.name,thisFormItem.type)
			if(thisFormItemParams[2] == '1') {
				if(thisFormItem.value.length == 0) {alert(thisFormItemParams[4]);thisFormItem.focus();return false;}
				if(!spacetxt(thisFormItem)) {alert(spaceserrtxt); thisFormItem.focus(); return false;}
			}
		}
	}
	if(checkinputs.length > 0) {
		for(c = 0; c < checkinputs.length; c++) {
		checkeds = 0;
			thischeckGroup = checkinputs['c'];
			thisFormItemParams = findParams(thischeckGroup[0].name,thischeckGroup[0].type);
			for(d = 0; d < thischeckGroup.length; d++) {
				thisFormItem = thischeckGroup['d'];
				if(thisFormItem.checked) {checkeds = checkeds+1;}
			}
			if(thisFormItemParams[2] == '1') {
				if(checkeds == 0) {alert(thisFormItemParams[4]);return false;}
			}
		}
	}
	if(radioinputs.length > 0) {
		for(e = 0; e < radioinputs.length; e++) {
		checkeds = 0;
			thisRadioGroup = radioinputs['e'];
			thisFormItemParams = findParams(thisRadioGroup[0].name,thisRadioGroup[0].type)
			for(f = 0; f < thisRadioGroup.length; f++) {
				thisFormItem = thisRadioGroup['f'];
				if(thisFormItem.checked) {checkeds = checkeds+1;}
			}
			if(thisFormItemParams[2] == '1') {
				if(checkeds == 0) {alert(thisFormItemParams[4]);return false;}
			}
		}
	}
	return true;
}

function ckeckDigit(item) { //Onlydigits 1
	thisNumber = item.value;
	for(i = 0; i < thisNumber.length; i++) {
		thisDigit = thisNumber.charAt(i);
		if(digitsAllowed.indexOf(thisDigit) == -1) {return false;}
	}
	return true;
}
function ckeckDigit2(item) { //Onlydigits 2
	thisNumber = item.value;
	for(i = 0; i < thisNumber.length; i++) {
		thisDigit = thisNumber.charAt(i);
		if(digitsAllowed2.indexOf(thisDigit) == -1) {return false;}
	}
	return true;
}