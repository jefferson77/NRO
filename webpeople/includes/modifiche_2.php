<?php
$detail = new db('', '', 'webneuro');
$detail->inline("SELECT * FROM `webpeople` WHERE `idpeople` = $_SESSION[idpeople]");
$infos = mysql_fetch_array($detail->result);

if ($_POST['confirmfiche'] == '1') {

	####vérification serveur du formulaire envoyé dans modifiche_1####

	if ( (empty ($_POST['sexe']))
		|| (empty ($_POST['pnom']))
		|| (empty ($_POST['pprenom']))
		|| (empty ($_POST['adresse1']))
		|| (empty ($_POST['zipcode']))
		|| (empty ($_POST['city']))
		|| (empty ($_POST['pays1']))
		|| (empty ($_POST['gsm']))
		|| (empty ($_POST['email']))
		|| (empty ($_POST['webpass']))
		|| (empty ($_POST['webpass2'])) ) {

		die($emodifiche1_01);

	} else {


		///////traitement des données///////
		$_POST['pnom'] = trim($_POST['pnom']);
		$_POST['pprenom'] = ucfirst(trim($_POST['pprenom']));
		$_POST['adresse1'] = trim($_POST['adresse1']);
		$_POST['zipcode'] = trim($_POST['zipcode']);
		$_POST['city'] = ucfirst(trim($_POST['city']));
		$_POST['gsm'] = trim($_POST['gsm']);
		$_POST['email'] = trim($_POST['email']);
		$_POST['webpass'] = trim($_POST['webpass']);
		$_POST['webpass2'] = trim($_POST['webpass2']);

		///1. champs obligatoires
		//vérifie que le champs GSM ne contient que des chiffres
		$_POST['gsm']=str_replace(' ','',$_POST['gsm']); //suppression des espace
		if(!is_numeric($_POST['gsm'])) {
			die($emodifiche1_02);
		}
		//vérifie la syntaxe de l'adresse mail
		$mailcheck='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#';
		if(!preg_match($mailcheck,$_POST['email'])){
			die($emodifiche1_03);
		}
		//concordance des passwords
		if ($_POST['webpass'] != $_POST['webpass2']) {
			die($emodifiche1_04);
		}

		///2. champs optionnels

		if ($_POST['num1']) {
			$_POST['num1'] = trim($_POST['num1']);
		}
		if ($_POST['bte1']) {
			$_POST['bte1'] = trim($_POST['bte1']);
		}

		//adresse2
		if (empty($_POST['adresse2'])) {
			$_POST['zipcode2'] = '';
			$_POST['city2'] = '';
			$_POST['num2'] = '';
			$_POST['bte2'] = '';
			$_POST['pays2'] = '';
		} else {
			$_POST['adresse2'] = trim($_POST['adresse2']);
			$_POST['zipcode2'] = trim($_POST['zipcode2']);
			$_POST['city2'] = ucfirst(trim($_POST['city2']));
			if ($_POST['num2']) {
				$_POST['num2'] = trim($_POST['num2']);
			}
			if ($_POST['bte2']) {
				$_POST['bte2'] = trim($_POST['bte2']);
			}
		}

		if ($_POST['tel']) {
			$_POST['tel']=str_replace(' ','',$_POST['tel']); //suppression des espace
			if(!ctype_digit($_POST['tel'])) {
				die($emodifiche1_02);
			}
		}
		if ($_POST['fax']) {
			$_POST['fax']=str_replace(' ','',$_POST['fax']); //suppression des espace
			if(!ctype_digit($_POST['fax'])) {
				die($emodifiche1_02);
			}
		}

		$sql = new db('', '', 'webneuro');
		$requete = "UPDATE webpeople SET sexe='".$_POST['sexe']."', pnom='".$_POST['pnom']."', pprenom='".$_POST['pprenom']."',adresse1='".$_POST['adresse1']."',num1='".$_POST['num1']."',bte1='".$_POST['bte1']."',cp1='".$_POST['zipcode']."',ville1='".$_POST['city']."',pays1='".$_POST['pays1']."',adresse2='".$_POST['adresse2']."',num2='".$_POST['num2']."',bte2='".$_POST['bte2']."',cp2='".$_POST['zipcode2']."',ville2='".$_POST['city2']."',pays2='".$_POST['pays2']."',datemodifweb=NOW(),tel='".$_POST['tel']."',fax='".$_POST['fax']."',gsm='".$_POST['gsm']."',email='".$_POST['email']."',webpass='".$_POST['webpass']."' $optfields WHERE idpeople='".$infos['idpeople']."'";
		$sql->inline($requete);

	} //fin du else du début de page
}

####nouveau formulaire####
?>
<p></p>
<div class="formdiv">
<form id="modifiche_2" action="<?php echo $_SERVER['PHP_SELF']?>?page=modifiche&step=3" method="post">
		<table cellspacing="0" class="formulaire">
			<tr>
				<td class="lignename"><?php echo $modifiche2_01 ?></td>
				<td>
					<?php
					$physios = array(
						'asiatique' => $modifiche2_01a,
						'black' => $modifiche2_01b,
						'hispanique' => $modifiche2_01c,
						'mediterraneen' => $modifiche2_01d,
						'nordafricain' => $modifiche2_01e,
						'occidental' => $modifiche2_01f,
						'orientale' => $modifiche2_01g,
						'slave' => $modifiche2_01h
					);
					echo createSelectList('physio',$physios,$infos['physio'],'--');
					?>
				</td>
			</tr>
		</table>
		<br>
		<table cellspacing="0" class="formulaire">
			<tr class="ligne2">
				<td class="lignename"><?php echo $modifiche2_02 ?></td>
				<td>
					<?php
					$ccheveux = array(
						'blond' => $modifiche2_02a,
						'brun' => $modifiche2_02b,
						'chatain' => $modifiche2_02c,
						'noir' => $modifiche2_02d,
						'roux' => $modifiche2_02e
					);
					echo createSelectList('ccheveux',$ccheveux,$infos['ccheveux'],'--');
					?>
				</td>
			</tr>
			<tr>
				<td class="lignename"><?php echo $modifiche2_03 ?></td>
				<td>
					<?php
					$lcheveux = array(
						'rase' => $modifiche2_03a,
						'court' => $modifiche2_03b,
						'mi-long' => $modifiche2_03c,
						'long' => $modifiche2_03d
					);
					echo createSelectList('lcheveux',$lcheveux,$infos['lcheveux'],'--');
					?>
				</td>
			</tr>
		</table>
		<br>
		<table cellspacing="0" class="formulaire">
			<tr class="ligne2">
				<td class="lignename"><?php echo $modifiche2_04 ?></td>
				<td>
					<input class="validate-digits" type="text" size="3" name="taille" id="taille" value="<?php echo $infos['taille']; ?>"> cm
				</td>
			</tr>
			<tr>
				<td class="lignename"><?php echo $modifiche2_05 ?></td>
				<td>
					<?php
					if ($infos['sexe'] == 'F') {
						$tailles = array(
							'34' => '34',
							'36' => '36',
							'38' => '38',
							'40' => '40',
							'42' => '42',
							'44' => '44',
							'46' => '46',
							'48' => '48',
							'50' => '50',
							'52' => '52',
							'54' => '54',
							'56' => '56'
						);
					} else {
						$tailles = array(
							'S' => 'S',
							'M' => 'M',
							'L' => 'L',
							'X' => 'X',
							'XL' => 'XL'
						);
					}
					echo createSelectList('tveste',$tailles,$infos['tveste'],'--');
					?>
				</td>
			</tr>
			<tr class="ligne2">
				<td class="lignename">
				<?php
				if ($infos['sexe'] == 'F') {
					echo $modifiche2_06a;
				} else {
					echo $modifiche2_06b;
				}
				?></td>
				<td>
					<?php
					echo createSelectList('tjupe',$tailles,$infos['tjupe'],'--');
					?>
				</td>
			</tr>
			<tr>
				<td class="lignename"><?php echo $modifiche2_07 ?></td>
				<td>
					<input class="validate-digits" type="text" size="3" name="pointure"  value="<?php echo $infos['pointure']; ?>">
				</td>
			</tr>
			<?php if ($infos['sexe'] == 'F') {?>
				<tr class="ligne2">
					<td class="lignename"><?php echo $modifiche2_08 ?></td>
					<td>
						<input class="validate-digits" type="text" size="3" name="menspoi" value="<?php echo $infos['menspoi']; ?>">
					</td>
				</tr>
				<tr>
					<td class="lignename"><?php echo $modifiche2_09 ?></td>
					<td>
						<input class="validate-digits" type="text" size="3" name="menstai" value="<?php echo $infos['menstai']; ?>">
					</td>
				</tr>
				<tr class="ligne2">
					<td class="lignename"><?php echo $modifiche2_10 ?></td>
					<td>
						<input class="validate-digits" type="text" size="3" name="menshan" value="<?php echo $infos['menshan']; ?>">
					</td>
				</tr>
			<?php } ?>
		</table>
		<br>
		<table cellspacing="0" class="formulaire">
			<tr>
				<td class="lignename"><?php echo $modifiche2_12 ?></td>
				<td>
					<?php
					$permis = array(
						'non' => $genvoc1b,
						'A' => 'A',
						'B' => 'B',
						'C' => 'C',
						'D' => 'D',
						'E' => 'E'
					);
					echo createRadioList('permis',$permis,$infos['permis']);
					?>
				</td>
			</tr>
			<tr class="ligne2">
				<td class="lignename"><?php echo $modifiche2_13 ?></td>
				<td>
					<?php
					$affneg = array(
						'oui' => $genvoc1a,
						'non' => $genvoc1b
					);
					echo createRadioList('voiture',$affneg,$infos['voiture']);
					?>
				</td>
			</tr>
		</table>
		<br>
		<table cellspacing="0" class="formulaire">
			<tr>
				<td class="lignename"><?php echo $modifiche2_14 ?></td>
				<td>
					<?php
					echo createRadioList('fume',$affneg,$infos['fume']);
					?>
				</td>
			</tr>
		</table>
		<br>
		<table cellspacing="0" class="formulaire">
			<tr class="ligne2">
				<td class="lignename"><?php echo $modifiche2_15 ?></td>
				<td>
						<?php
						$conninformatiq = array(
							'0' => 'Word',
							'1' => 'Excel',
							'2' => 'Powerpoint',
							'3' => 'Internet'
						);
							echo createNumericCheckboxList('conninformatiq',$conninformatiq,$infos['conninformatiq']);
						?>
				</td>
			</tr>
		</table>
	<input type="hidden" name="confirmfiche" value="1">
	<div style="text-align:right;padding-right:25px;padding-top:5px">
		<a href="?page=modifiche&step=0"><img src="../../web/illus/formback.png" width="70" height="38"></a>&nbsp;
		<input type="submit" name="submit" class="btn formok">
		<a href="<?php echo $_SERVER['PHP_SELF']?>?page=modifiche&step=3"><img src="../../web/illus/formskip.png"></a>
	</div>
	</form>
</div>
					<script type="text/javascript">
						function formCallback(result, form) {
							window.status = "valiation callback for form '" + form.id + "': result = " + result;
						}

						var valid = new Validation('modifiche_2', {immediate : true, onFormValidate : formCallback});
					</script>