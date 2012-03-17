<SCRIPT language="Javascript">
<!-- 
function toggleDisabled(obj)
{
	obj.disabled = !(obj.disabled);
	var z = (obj.disabled) ? 'disabled' : 'enabled';
}


function toggleCityStateEnabled() {
	var city = document.getElementById("city"+ whichZip);
	toggleDisabled(city);
	
	var citySpinner = document.getElementById("citySpinner"+ whichZip);

	citySpinner.style.display = city.disabled ? "inline" : "none";

}

function zipCodeChanged(zipCodeInput, whichZip) {
		var srcEl = document.createElement('script');
		srcEl.language="Javascript";
		srcEl.type="text/javascript";
		srcEl.src="<?php echo NIVO ?>includes/ajaxzipcheck.php?whichZip="+ whichZip +"&zip=" + zipCodeInput.value;
		document.body.appendChild(srcEl);
		toggleCityStateEnabled(whichZip);
}
// -->
</SCRIPT> 