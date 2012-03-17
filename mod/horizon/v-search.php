<div id="centerzonelarge">
	<form action="?act=generate" method="post" accept-charset="utf-8">
		<table width="80%" cellspacing="1" cellpadding="2" align="center">
			<tr>
				<td>
					<fieldset>
						<legend>Factures</legend>
						<table border="0" align="center">
							<tr>
								<td>A partir du n&deg; : </td>
								<td><input type="text" name="facfromid" value=""></td>
							</tr>
							<tr>
								<td>Jusqu'au n&deg; : </td>
								<td><input type="text" name="factoid" value=""></td>
							</tr>
						</table>
						<br><br>
						<div align="center">
						Entrez les num&eacute;ro de <b>facture</b> &agrave; partir duquel et jusqu'auquel vous souhaitez importer dans Horizon. <br>
						(laissez le deuxi&egrave;me vide pour tout importer &agrave; partir d'un num&eacute;ro)
						</div>
					</fieldset>
				</td>
				<td>
					<fieldset>
						<legend>Notes de Cr&eacute;dit</legend>
						<table border="0" align="center">
							<tr>
								<td>A partir du n&deg; : </td>
								<td><input type="text" name="ncfromid" value=""></td>
							</tr>
							<tr>
								<td>Jusqu'au n&deg; : </td>
								<td><input type="text" name="nctoid" value=""></td>
							</tr>
						</table>
						<br><br>
						<div align="center">
							Entrez les num&eacute;ro de <b>notes de cr&eacute;dit</b> &agrave; partir duquel et jusqu'auquel vous souhaitez importer dans Horizon. <br>
							(laissez le deuxi&egrave;me vide pour tout importer &agrave; partir d'un num&eacute;ro)
						</div>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td align="center" style="padding: 15px" colspan="2"><input type="submit" value="Importer"></td>
			</tr>
		</table>
	</form>
</div>