<fieldset>
	<legend>Infos de recherche</legend>
	<form action="<?php echo '?act=select&idvip='.$idvip.'&etape=listepeople&idvipjob='.$idvipjob.'&sel=people&from='.$_GET['from'];?>" method="post">
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
			<tr>
				<td valign="top">
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
						<tr>
							<td>Nom</td>
							<td><input type="text" size="20" name="pnom" value=""></td>
						</tr>
						<tr>
							<td>Pr&eacute;nom</td>
							<td><input type="text" size="20" name="pprenom" value=""></td>
						</tr>
						<tr>
							<td>code (registre) </td>
							<td><input type="text" size="5" name="codepeople" value=""></td>
						</tr>
						<tr>
							<td>Sexe</td>
							<td>
								<?php 
								echo '<input type="radio" name="sexe" value="x" '; if ($_GET['sexe'] == 'x') { echo 'checked';} echo'> X';
								echo '<input type="radio" name="sexe" value="f" '; if ($_GET['sexe'] == 'f') { echo 'checked';} echo'> F';
								echo '<input type="radio" name="sexe" value="m" '; if ($_GET['sexe'] == 'm') { echo 'checked';} echo'> M';
								?>
							</td>
						</tr>
						<tr>
							<td>Sous-traitance :</td>
							<td>
								<input type="radio" name="indep" value="n" checked> Internes
								<input type="radio" name="indep" value="y"> Sous-trait&eacute;
								<input type="radio" name="indep" value="t"> Tous
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Be</td>
							<td>
								<input type="radio" name="beaute" value="1"> 1
								<input type="radio" name="beaute" value="2"> 2
								<input type="radio" name="beaute" value="3"> 3
								<input type="radio" name="beaute" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>Ch</td>
							<td>
								<input type="radio" name="charme" value="1"> 1
								<input type="radio" name="charme" value="2"> 2
								<input type="radio" name="charme" value="3"> 3
								<input type="radio" name="charme" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>Dy</td>
							<td>
								<input type="radio" name="dynamisme" value="1"> 1
								<input type="radio" name="dynamisme" value="2"> 2
								<input type="radio" name="dynamisme" value="3"> 3
								<input type="radio" name="dynamisme" value="4"> 4
							</td>
						</tr>
					</table>
				</td>
				<td valign="top">
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Fr</td>
							<td>
								<input type="radio" name="lfr" value="0"> 0
								<input type="radio" name="lfr" value="1"> 1
								<input type="radio" name="lfr" value="2"> 2
								<input type="radio" name="lfr" value="3"> 3
								<input type="radio" name="lfr" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>NL</td>
							<td>
								<input type="radio" name="lnl" value="0"> 0
								<input type="radio" name="lnl" value="1"> 1
								<input type="radio" name="lnl" value="2"> 2
								<input type="radio" name="lnl" value="3"> 3
								<input type="radio" name="lnl" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>En</td>
							<td>
								<input type="radio" name="len" value="0"> 0
								<input type="radio" name="len" value="1"> 1
								<input type="radio" name="len" value="2"> 2
								<input type="radio" name="len" value="3"> 3
								<input type="radio" name="len" value="4"> 4
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Du</td>
							<td>
								<input type="radio" name="ldu" value="0"> 0
								<input type="radio" name="ldu" value="1"> 1
								<input type="radio" name="ldu" value="2"> 2
								<input type="radio" name="ldu" value="3"> 3
								<input type="radio" name="ldu" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>It</td>
							<td>
								<input type="radio" name="lit" value="0"> 0
								<input type="radio" name="lit" value="1"> 1
								<input type="radio" name="lit" value="2"> 2
								<input type="radio" name="lit" value="3"> 3
								<input type="radio" name="lit" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>Sp</td>
							<td>
								<input type="radio" name="lsp" value="0"> 0
								<input type="radio" name="lsp" value="1"> 1
								<input type="radio" name="lsp" value="2"> 2
								<input type="radio" name="lsp" value="3"> 3
								<input type="radio" name="lsp" value="4"> 4
							</td>
						</tr>
						<tr>
							<td>Age</td>
							<td><input type="text" size="10" name="age" value=""></td>
						</tr>
					</table>
				</td>
				<td valign="top">
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
						<tr>
							<td>CP</td>
							<td><input type="text" size="10" name="cp1a" value=""> à <input type="text" size="10" name="cp1b" value=""></td>
						</tr>
						<tr>
							<td>Ville</td>
							<td><input type="text" size="20" name="ville1" value=""></td>
						</tr>
						<tr>
							<td>Rayon (km)</td>
							<td><input type="text" name="rayon" size="5"></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Note</td>
							<td><input type="text" size="20" name="notegenerale" value=""></td>
						</tr>
						<tr>
							<td>Secteur</td>
							<td>
								<input type="checkbox" name="categorie[0]" value="1"> Anim &nbsp; 
								<input type="checkbox" name="categorie[1]" value="1"> Merch &nbsp; 
								<input type="checkbox" name="categorie[2]" value="1" checked> Hotes &nbsp; 
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Taille</td>
							<td>
								<input type="text" size="3" name="taillea" value=""> cm &nbsp; &agrave; &nbsp;
								<input type="text" size="3" name="tailleb" value=""> cm
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td>Cheveux</td>
							<td>
						<?php 
						$coulcheveux = array(
							'blond' => 'Blond',
							'brun' => 'Brun',
							'noir' => 'Noir',
							'chatain' => 'Chatain',
							'roux' => 'Roux'
						);

							echo '<select name="ccheveux"><option label="" selected></option>';
			
							foreach ($coulcheveux as $key => $value) {
								echo '<option value="'.$key.'"';	
								echo '>'.$value.'</option>';
							}
							
							echo '</select>';
						?>
							</td>
						</tr>
						<tr>
							<td>Physionomie</td>
							<td>
						<?php 
						
						$physios = array(
							'occidental' => 'Occidental',
							'slave' => 'Slave',
							'asiatique' => 'Asiatique',
							'orientale' => 'Orientale',
							'black' => 'Black',
							'nordafricain' => 'Nord-africain',
							'hispanique' => 'Hispanique',
							'mediterraneen' => 'M&eacute;dit&eacute;rran&eacute;en'
						);

						
							echo '<select name="physio"><option label="" selected></option>';
			
							foreach ($physios as $key => $value) {
								echo '<option value="'.$key.'"';	
								echo '>'.$value.'</option>';
							}
							
							echo '</select>';
						?>
							</td>
						</tr>
						<tr>
							<td>Permis</td>
							<td>
								<input type="radio" name="permis" value="non"> Non
								<input type="radio" name="permis" value="A"> A
								<input type="radio" name="permis" value="B"> B
								<input type="radio" name="permis" value="C"> C
								<input type="radio" name="permis" value="D"> D
								<input type="radio" name="permis" value="E"> E
							</td>
						</tr>
						<tr>
							<td>Voiture</td>
							<td>
								<input type="radio" name="voiture" value="oui"> Oui
								<input type="radio" name="voiture" value="non"> Non
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
			<tr>
				<td align="left" width="50%"><input type="reset" name="reset" value="Reset"></td>
				<td><input type="submit" name="Rechercher" value="Rechercher"></td>
			</tr>
		</table>
	</form>
</fieldset>
