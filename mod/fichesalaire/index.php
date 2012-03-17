<?php
define('NIVO', '../../');

# Entete de page
$Titre = 'Fiches Salaires';
$Style = 'admin';

include NIVO."includes/entete.php" ;

## Classes
include_once NIVO.'print/dispatch/dispatch_functions.php';

## Models
include "m-fichesalaire.php";
include "m-281.10.php";

switch (@$_REQUEST['act']) {
	#### Ajout d'un mois de fiches de salaires ####################################################
	case 'addPDF':
		if(isset($_FILES['newfile'])) {
			if ($_POST['ftype'] == 'fiche') {
				$fs = new FicheSalaire($_POST['annee'], $_POST['mois']);
				$fs->uploadFile('newfile');
				$fs->splitPDF();
				$fs->batchIdentify($fs->SplitPath);
				$notify[] = $fs->nbrfiche.' fiches de salaires ajoutées';
				if (isEmptyDir($fs->SplitPath)) rmdir($fs->SplitPath);
			}

			if ($_POST['ftype'] == '281.10') {
				$fs = new doc28110($_POST['annee']);
				$fs->uploadFile('newfile');
				$fs->splitPDF();
				$fs->batchIdentify($fs->SplitPath);
				$notify[] = $fs->nbrfiche.' fiches de salaires ajoutées';
				if (isEmptyDir($fs->SplitPath)) rmdir($fs->SplitPath);
			}
		}
	case 'showErrors':
		include 'v-errors.php';
		break;
	#### Envoi des fiches de salaires ##############################################################
	case 'sendForm':
		include 'v-sendForm.php';
		break;
	case 'send':
		include 'v-sendResult.php';
		break;
	#### formulaire d'ajout des fiches de salaires #################################################
	case 'addform':
	default:
		include 'v-addForm.php';
		break;
}

?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=showErrors"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Erreurs</a></td>
			<td class="on"><a href="?act=addform"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Ajouter</a></td>
			<td class="on"><a href="?act=sendForm"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Envoyer</a></td>
		</tr>
	</table>
</div>
<?php include NIVO."includes/pied.php"; ?>