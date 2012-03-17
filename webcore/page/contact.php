<?php
switch ($_GET['section']){
	### SECTION VIP
	case "vip" :
		switch ($_GET['page']){
		### page contact
			case "contact" :
			default:
?>			
						<h1><?php echo $titre; ?></h1>
						<table width="100%" border="0" cellpadding="0">
							<tr>
								<td colspan="2" align="center">
									<b>Vincent Michotte</b><br>
									<?php echo $contact_direction_vip; ?><br>
									02 / 739.63.90<br>
									0475 / 54.43.20<br>
									<a href="mailto:vincent@exception2.be">vincent@exception2.be</a>
									<br><br>
								</td>
							</tr>
							<tr>
								<td width="50%" valign="top" align="center">
									<b>Rebekka Spelt</b>
									<br>
									<?php echo $contact_planning; ?><br>
									02 / 739.63.92<br>
									0476 / 21.45.84<br>
									<a href="mailto:rebekka@exception2.be">rebekka@exception2.be</a>
									<br><br>
								</td>
								<td colspan="2" align="center">
									<b>Yvan Sterckx</b>
									<br>
									<?php echo $contact_planning; ?><br>
									02 / 739.63.99<br>
									0473 / 80.80.87<br>
									<a href="mailto:yvan@exception2.be">yvan@exception2.be</a>
									<br><br>
								</td>
							</tr>
							<tr>
								<td valign="top" align="center">
									<b>Annabel Claix</b>
									<br>
									<?php echo $contact_planning; ?><br>
									02 / 739.63.94<br>
									0473 / 86.71.60<br>
									<a href="mailto:annabel@exception2.be">annabel@exception2.be</a>
									<br><br>
								</td>
							</tr>
							<tr>
								<td valign="top" align="center">
									<b>Marie-Christine Maes</b>
									<br>
									<?php echo $contact_planning; ?><br>
									0473 / 80.80.87<br>
									<a href="mailto:marie@exception2.be">marie@exception2.be</a>
									<br><br>
								</td>
							</tr>
						  <tr>
							<td width='50%' valign='top' align='center'>
								 <b>Genevi&egrave;ve Lannoo</b><br>
								  <?php echo "Foires et Salon - Beurzen" ?><br>
								  02 / 732.74.40<br>
								  0475 / 34.34.50<br>
								  <a href='mailto:genevieve@exception2.be'>genevieve@exception2.be</a>   
							</td>
						  </tr>
						</table>
<?php
			break;
		}
	break;
	#/## SECTION VIP

	### SECTION animation-------------------------------------------------------------------------------------------------------------------
	case "animation" :
		switch ($_GET['page']){
		### page contact
			case "contact" :
			default:
?>			
						<h1><?php echo $titre; ?></h1>
						<table width='100%' border='0' cellpadding='0'>
						 <tr>
							<td colspan='2' align='center'>
								<b>V&eacute;ronique Vandamme</b><br>
								<?php echo $contact_direction_com; ?><br>
								02 / 739.63.95<br>
								0475 / 41.74.53<br>
								<a href='mailto:veronique@exception2.be'>veronique@exception2.be</a>
								<br><br><br>
							</td>
							<td colspan='2' align='center'>
								<b>Ga&euml;lle Denis</b><br>
								<?php echo $contact_planning; ?><br>
								02 / 739.63.93<br>
								0475 / 34.34.50<br>
								<a href='mailto:gaelle@exception2.be'>gaelle@exception2.be</a>
								<br><br><br>
							</td>
						  </tr>
						  <tr>
							<td width='50%' valign='top' align='center'>
								 <b>Fabienne Nicaise</b><br>
								  <?php echo $contact_planning; ?><br>
								  02 / 739.63.97<br>
								  0477 / 20.64.80<br>
								  <a href='mailto:fabienne@exception2.be'>fabienne@exception2.be</a>   
							</td>
						  </tr>
						</table>
<?php
			break;
		}
	break;
	#/## SECTION animation
	
	### SECTION merchandising----------------------------------------------------------------------------------------------------------
	case "merchandising" :
		switch ($_GET['page']){
		### page contact
			case "contact" :
			default:
?>			
						<h1><?php echo $titre; ?></h1>
 
<table width='100%' border='0' cellpadding='0'>
	<tr>
		<td  align='center'>
			<b>V&eacute;ronique Vandamme</b><br>
<?php echo $contact_direction_com; ?><br>
			02 / 739.63.95<br>
			0475 / 41.74.53<br>
			<a href='mailto:veronique@exception2.be'>veronique@exception2.be</a> 
			<br><br><br>
		</td>
		<td  align='center'>
			<b>Ga&euml;lle Denis</b><br>
			<?php echo $contact_planning; ?><br>
			02 / 739.63.93<br>
			0475 / 34.34.50<br>
			<a href='mailto:gaelle@exception2.be'>gaelle@exception2.be</a> 
			<br><br><br>
		</td>
	</tr>
	<tr>
		<td valign='top' align='center'>
			<b>Mich&egrave;le Eulaerts</b><br>
			<?php echo $contact_planning; ?><br>
			02 / 739.63.98<br>
			0477 / 94.61.23<br>
			<a href='mailto:michele@exception2.be'>michele@exception2.be</a> 
		</td>
		<td valign='top' align='center'>
			<b>Marlies Neven</b><br>
			Rack Assistance<br>
			--<br>
			--<br>
			<a href='mailto:marlies@exception2.be'>marlies@exception2.be</a> 
		</td>
	</tr>
</table>
<?php
			break;
		}
	break;

	### SECTION ACCUEIL--------------------------------------------------------------------------------------------------------------------------------
	case "accueil" :
	default:
		switch ($_GET['page']){
		### page contact
			default:
			case "contact" :
?>			
				<h1><?php echo $titre; ?></h1>
				<h3><?php echo $contact_plus_info; ?></h3><br>
					<table width="100%" border="0" cellpadding="0">
						<tr>
						  <td>
							<a href="page.php?section=accueil&page=contact&nom=Lannoo&lang=<?php echo $lang;?>">Fran&ccedil;oise Lannoo</a>
					<?php if ($_GET['nom'] == 'Lannoo') {echo ' <Font color="#444444"> &lt;&lt; </Font>';} ?>
								<br>&nbsp;
						  </td>
						  <td></td>
						  <td></td>
						</tr>
						<tr>
							<td width="33%"><img src="../images/rouge.gif" width="5" height="5"> VIP<br><br></td>
							<td width="33%"><img src="../images/vert.gif" width="5" height="5"> ANIMATION<br><br></td>
							<td><img src="../images/orange.gif" width="5" height="5"> MERCHANDISING<br><br></td>
						</tr>
						<tr valign="top">
							<td>
								<a href="page.php?section=accueil&page=contact&nom=Michotte&lang=<?php echo $lang;?>">Vincent Michotte</a>
								<?php if ($_GET['nom'] == 'Michotte') {echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Spelt&lang=<?php echo $lang;?>">Rebekka Spelt</a>
								<?php if ($_GET['nom'] == 'Spelt') { echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Sterckx&lang=<?php echo $lang;?>">Yvan  Sterckx</a>
								<?php if ($_GET['nom'] == 'Sterckx') {echo ' <Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Claix&lang=<?php echo $lang;?>">Annabel Claix</a> 
								<?php if ($_GET['nom'] == 'Claix') { echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Maes&lang=<?php echo $lang;?>">Marie-Christine Maes</a> 
								<?php if ($_GET['nom'] == 'Maes') { echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Gene&lang=<?php echo $lang;?>">Genevi&egrave;ve Lannoo</a>
								<?php if ($_GET['nom'] == 'Gene') {echo ' <Font color="#444444"> &lt;&lt; </Font>'; } ?>
							</td>
							<td>
								<a href="page.php?section=accueil&page=contact&nom=Vandamme1&lang=<?php echo $lang;?>">V&eacute;ronique Vandamme</a>
								<?php if ($_GET['nom'] == 'Vandamme1') {echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Denis1&lang=<?php echo $lang;?>">Ga&euml;lle Denis</a>
								<?php if ($_GET['nom'] == 'Denis1') { echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Nicaise&lang=<?php echo $lang;?>">Fabienne Nicaise</a>
								<?php if ($_GET['nom'] == 'Nicaise') { echo '<Font color="#444444"> &lt;&lt; </Font>'; } ?>
								<br>
							</td>
							<td>
								<a href="page.php?section=accueil&page=contact&nom=Vandamme2&lang=<?php echo $lang;?>">V&eacute;ronique Vandamme</a>
								<?php	if ($_GET['nom'] == 'Vandamme2') {echo '<Font color="#444444"> &lt;&lt; </Font>';} ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Denis2&lang=<?php echo $lang;?>">Ga&euml;lle Denis</a>
								<?php if ($_GET['nom'] == 'Denis2') { echo '<font color="#444444"> &lt;&lt; </font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Eulaerts&lang=<?php echo $lang;?>">Mich&egrave;le Eulaerts</a>
								<?php if ($_GET['nom'] == 'Eulaerts') { echo '<font color="#444444"> &lt;&lt; </font>'; } ?>
								<br>
								<a href="page.php?section=accueil&page=contact&nom=Neven&lang=<?php echo $lang;?>">Marlies Neven</a>
								<?php if ($_GET['nom'] == 'Neven') { echo '<font color="#444444"> &lt;&lt; </font>'; } ?>
							</td>
						</tr>
					</table>
					<br>
					<table width="90%" border="0" cellpadding="0">
						<tr valign="top">
							<td colspan="3" align="center" valign="middle">
				<?php
				switch ($_GET['nom']){
					case "Vandamme1" :
					case "Vandamme2" :
				?>
							<b>V&eacute;ronique Vandamme</b><br><br>
							<?php echo $contact_direction_com; ?><br>
							02 / 739.63.91<br>
							0475 / 41.74.53<br>
							<a href='mailto:veronique@exception2.be'>veronique@exception2.be</a><br><br>
				<?php
					break;
					case "Spelt" :
						$photo = "rebekka";
				?>
							<b>Rebekka Spelt</b>
							<br><br>
							<?php echo $contact_planning; ?><br>
							02 / 739.63.92<br>
							0476 / 21.45.84<br>
							<a href='mailto:rebekka@exception2.be'>rebekka@exception2.be</a><br><br>
				<?php
					break;
					case "Lannoo" :
				?>
							<b>Fran&ccedil;oise Lannoo</b><br><br>
							<?php echo $contact_direction; ?><br>
							02 / 739.63.96<br>
							<a href="mailto:francoise@exception2.be">francoise@exception2.be</a><br><br>
				<?php
					break;
					case "Sterckx" :
						$photo = "yvan";
				?>
							<b>Yvan Sterckx</b>
							<br><br>
							<?php echo $contact_planning; ?><br>
							02 / 739.63.99<br>
							0473 / 80.80.87<br>
							<a href="mailto:yvan@exception2.be">yvan@exception2.be</a>
				<?php
					break;
					case "Gene" :
						$photo = "genevieve";
				?>
							<b>Genevi&egrave;ve Lannoo</b>
							<br><br>
							<?php echo "Foires et Salon - Beurzen" ?><br>
							02 / 732.74.40<br>
							0475 / 34.34.50<br>
							<a href="mailto:genevieve@exception2.be">genevieve@exception2.be</a>
				<?php
					break;
					case "Michotte" :
						$photo = "vincent";
				?>
							<b>Vincent Michotte</b><br><br>
							<?php echo $contact_direction_vip; ?><br>
							02 / 739.63.90<br>
							0475 / 544.320<br>
							<a href="mailto:vincent@exception2.be">vincent@exception2.be</a>
				<?php
					break;
					case "Denis1" :
					case "Denis2" :
						$photo = "gaelle";
				?>
							<b>Ga&euml;lle Denis</b><br>
							<?php echo $contact_planning; ?><br>
							02 / 739.63.93<br>
							0475 / 34.34.50<br>
							<a href='mailto:gaelle@exception2.be'>gaelle@exception2.be</a>
				<?php
					break;
					case "Nicaise" :
						$photo = "fabienne";
				?>
							 <b>Fabienne Nicaise</b><br>
							  <?php echo $contact_planning; ?><br>
							  02 / 739.63.97<br>
							  0477 / 20.64.80<br>
							  <a href='mailto:fabienne@exception2.be'>fabienne@exception2.be</a>   
				<?php
					break;
					case "Eulaerts" :
						$photo = "michele";
				?>
								<b>Mich&egrave;le Eulaerts</b><br>
								<?php echo $contact_planning; ?><br>
								02 / 739.63.98<br>
								0477 / 94.61.23<br>
								<a href='mailto:michele@exception2.be'>michele@exception2.be</a> 
				<?php
					break;
					case "Neven" :
						$photo = "";
				?>
								<b>Marlies Neven</b><br>
								Rack Assistance<br>
								--<br>
								--<br>
								<a href='mailto:marlies@exception2.be'>marlies@exception2.be</a> 
				<?php
					break;
					case "Claix" :
						$photo = "annabel";
				?>
						<b>Annabel Claix</b>
						<br>
						<?php echo $contact_planning; ?><br>
						02 / 739.63.94<br>
						0473 / 86.71.60<br>
						<a href="mailto:annabel@exception2.be">annabel@exception2.be</a><br>
				<?php
					break;
					case "Maes" :
						$photo = "mariechristine";
				?>
						<b>Marie-Christine Maes</b>
						<br>
						<?php echo $contact_planning; ?><br>
						0473 / 80.80.87<br>
						<a href="mailto:marie@exception2.be">marie@exception2.be</a><br>
				<?php
					break;
				} ?>
							</td>
				<?php 
				if (!empty($photo)) {
					echo '<td width="130">';
					echo '<img src="/images/photosperso/'.$photo.'.jpg" alt="" width="127" height="189">';
					echo '</td>';
				}
				?>
						</tr>
					</table>
<?php
break;
		}
	break;
}
?>