<?php include_once(NIVO.'js/ajaxzipcodejs.php');?>
<script src="<?php echo STATIK ?>js/scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="<?php echo STATIK ?>js/scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
 <script type="text/javascript">
function showSelectDiv(valeur){
	if (valeur == 2)  {
		document.getElementById('moreetatcivil').style.display = 'block';
	} else {
		document.getElementById('moreetatcivil').style.display = 'none';
	}
}
</script>