<?php
$detail2 = new db('', '', 'webneuro');
$detail2->inline("SELECT * FROM `webpeople` WHERE `idpeople` = '".$_SESSION['idpeople']."'");
$infos2 = mysql_fetch_array($detail2->result);
?>

<p></p>
<div class="formdiv">
<form id="modifiche_1" action="<?php echo $_SERVER['PHP_SELF']?>?page=modifiche&step=2" method="post">
	<table cellspacing="0" class="formulaire">
      <tr>
        <td class="lignename"><?php echo $modifiche1_01;?></td>
        <td>
			<?php
			// pour la validation : appliquer la classe à la dernière option et encadrer l'ensemble des options dans un div.
			if ($infos2['sexe'] == 'F') {
				$radiosexF = 'checked="checked"';
			} elseif ($infos2['sexe'] == 'M') {
				$radiosexM = 'checked="checked"';
			} echo'
			<div><input type="radio" name="sexe" value="F" '.$radiosexF.'/>'.$modifiche1_01b.'
			<input class="validate-one-required" id="sexem" type="radio" name="sexe" value="M" '.$radiosexM.'/>'.$modifiche1_01a.'</div></td>';
			?>
      </tr>
      <tr class="ligne2">
        <td class="lignename"><?php echo $modifiche1_02;?></td>
        <td>
          <input class="required" type="text" size="20"  id="pnom" name="pnom" value="<?php echo $infos2['pnom']?>" />
        </td>
      </tr>
      <tr>
        <td class="lignename"><?php echo $modifiche1_03;?></td>
        <td>
          <input class="required" id="pprenom" type="text" size="20"  name="pprenom" value="<?php echo $infos2['pprenom']?>" />
        </td>
      </tr>
      <tr class="ligne2">
        <td class="lignename"><?php echo $modifiche1_04;?></td>
        <td>
          <input  class="required" id="adresse1" type="text" size="40"  name="adresse1" value="<?php echo $infos2['adresse1']?>" />
          N&deg;<input class="validate-alphanum" id="num1" type="text" size="5"  name="num1" value="<?php echo $infos2['num1']?>" />
          <?php echo $modifiche1_05;?><input class="validate-alphanum" id="bte1" type="text" size="5"  name="bte1" value="<?php echo $infos2['bte1']?>" /><br>
		<?php echo $modifiche1_06;?>
				  <input class="required-zipcode" id="zipcode" name="zipcode" type="text" onChange="zipCodeChanged(this,'');" size="6" maxlength="10" value="<?php echo $infos2['cp1']?>"/>
				  <?php echo $modifiche1_07;?>
				<span id="ajaxlist"><input class="required" name="city" type="text" id="city" size="12" value="<?php echo $infos2['ville1']?>"/>
				<img src="<?php echo NIVO ?>web/illus/snake_transparent.gif" style="display:none;" id="citySpinner"></span>
				  <?php echo $modifiche1_08;?>
		  <select  name="pays1" size="1">
			<?php if ($infos2['pays1'] == 'BE') {
				$pays1opt = 'selected="selected"';
			} elseif ($infos2['pays1'] == 'FR') {
				$pays2opt = 'selected="selected"';
			} elseif ($infos2['pays1'] == 'LU') {
				$pays3opt = 'selected="selected"';
			}
			echo'<option value="BE" '.$pays1opt.'>'.$modifiche1_08a.'</option>
			<option value="FR" '.$pays2opt.'>'.$modifiche1_08b.'</option>
			<option value="LU" '.$pays3opt.'>'.$modifiche1_08c.'</option>';
			?>
		  </select>
        </td>
      </tr>
      <tr>
        <td class="lignename"><?php echo $modifiche1_04;?> 2</td>
        <td>
          <input  id="adresse2" type="text" size="40"  name="adresse2" value="<?php echo $infos2['adresse2']?>" />
          N&deg;<input class="validate-alphanum" id="num2" type="text" size="5"  name="num2" value="<?php echo $infos2['num2']?>" />
          <?php echo $modifiche1_05;?><input class="validate-alphanum" id="bte2" type="text" size="5"  name="bte2" value="<?php echo $infos2['bte2']?>" /><br>
		<?php echo $modifiche1_06;?>
				  <input class="validate-zipcode" id="zipcode2" name="zipcode2" type="text" onChange="zipCodeChanged(this,'2');" size="6" maxlength="10" value="<?php echo $infos2['cp2']?>"/>
				  <?php echo $modifiche1_07;?>
				<span id="ajaxlist2"><input name="city2" type="text" id="city2" size="12" value="<?php echo $infos2['ville2']?>"/>
				<img src="<?php echo NIVO ?>web/illus/snake_transparent.gif" style="display:none;" id="citySpinner2"></span>
				  <?php echo $modifiche1_08;?>
		  <select  name="pays2" size="1">
			<?php if ($infos2['pays2'] == 'BE') {
				$pays1opt = 'selected="selected"';
			} elseif ($infos2['pays2'] == 'FR') {
				$pays2opt = 'selected="selected"';
			} elseif ($infos2['pays2'] == 'LU') {
				$pays3opt = 'selected="selected"';
			}
			echo'<option value="BE" '.$pays1opt.'>'.$modifiche1_08a.'</option>
			<option value="FR" '.$pays2opt.'>'.$modifiche1_08b.'</option>
			<option value="LU" '.$pays3opt.'>'.$modifiche1_08c.'</option>';
			?>
		  </select>
        </td>
      </tr>
      <tr class="ligne2">
        <td class="lignename"><?php echo $modifiche1_09;?></td>
        <td>
          <input class="validate-digits" id="tel" type="text" size="20"  name="tel" value="<?php echo $infos2['tel']?>" />
        </td>
      </tr>
      <tr>
        <td class="lignename"><?php echo $modifiche1_10;?></td>
        <td>
          <input class="validate-digits" id="fax" name="fax" value="<?php echo $infos2['fax']?>" />
        </td>
      </tr>
      <tr class="ligne2">
        <td class="lignename"><?php echo $modifiche1_11;?></td>
        <td>
          <input class="required-digits" id="gsm" type="text" size="20"  name="gsm" value="<?php echo $infos2['gsm']?>" />
        </td>
      </tr>
      <tr>
        <td class="lignename"><?php echo $modifiche1_12;?></td>
        <td>
          <input class="required-email" type="text" size="20"  id="email" name="email" value="<?php echo $infos2['email']?>" />
        <img src="<?php echo STATIK ?>illus/bullet_error.png" width="16" height="16" /><?php echo $modifiche1_12a;?></td>
      </tr>
      <tr class="ligne2">
        <td class="lignename"><?php echo $modifiche1_13;?></td>
        <td>
          <input class="required-password" type="password" size="20" id="webpass" name="webpass" value="<?php echo $infos2['webpass']?>" />
          <img src="<?php echo STATIK ?>illus/bullet_error.png" alt="" width="16" height="16" /><?php echo $modifiche1_13a;?></td>
      </tr>
      <tr>
        <td class="lignename"><?php echo $modifiche1_14;?></td>
        <td>
          <input class="required-password-confirm" type="password" size="20"  id="webpass2" name="webpass2" value="<?php echo $infos2['webpass']?>" />
        </td>
      </tr>
    </table>
	<div style="text-align:right;padding-right:25px;padding-top:5px">
		<?php if ($step == 1) {
			echo'<a href="?page=modifiche"><img src="../../web/illus/formback.png" width="70" height="38"></a>&nbsp;';
		}
		?>
	<input type="submit" class="btn formok" name="submit"></div>
	<input type="hidden" name="confirmfiche" value="1">
</form>
</div>
					<script type="text/javascript">
						function formCallback(result, form) {
							window.status = "valiation callback for form '" + form.id + "': result = " + result;
						}

						var valid = new Validation('modifiche_1', {immediate : true, onFormValidate : formCallback});
					</script>