<?php
define('NIVO', '');

if (!isset($_REQUEST['act'])) $_REQUEST['act'] = '';

$Titre     = 'Menu';
$PhraseBas = 'Menu Principal';

include NIVO."includes/entete.php" ;

### masquer les liens où les résultats sont nuls ?
$mask_noresult_links = 1;

switch ($_REQUEST['act']) {
	case 'inline':
		include NIVO.'menu/v-inline.php';
	break;

	default:
		include NIVO.'menu/v-usermenu.php';
	break;
}

?>
<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=inline&link=mail"><img src="<?php echo STATIK ?>nro/illus/icons48/mail.png" alt="ajouter" width="32" height="32" border="0"><br>Mail</a></td>
			<td class="on"><a href="?act=inline&link=cal"><img src="<?php echo STATIK ?>nro/illus/icons48/ical.png" alt="ajouter" width="32" height="32" border="0"><br>Calendriers</a></td>		</tr>
	</table>
</div>
<?php include NIVO."includes/pied.php"; ?>