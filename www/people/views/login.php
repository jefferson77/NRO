<?php
## Message du haut
if 		(!empty($message['red'])) 		$messagehaut = '<font style="color: #6D0001">'.$message['red'].'</font>';
elseif 	(!empty($message['green']))		$messagehaut = '<font style="color: #0099CC">'.$message['green'].'</font>';
elseif 	($_REQUEST['p'] == 'register')	$messagehaut = $lg['registerPeople'];
else 									$messagehaut = $lg['accesEspaceSecu'];

?>
<div class="logbloc">
	<img src="<?php echo Conf::read('WebSiteURL') ?>illus/login.jpg" width="131" height="155" alt="Login" style="float: left; margin-right: 15px;">
	<div id="name" class="greentitre"><?php echo $lg['Bienvenue'] ?></div>
	<p><?php echo $messagehaut ?></p>
	<div id="logscreen" style="clear:both;">
		<?php if ($_REQUEST['p'] == 'sendpass'): ?>
			<form action="?p=mailpass" method="post" accept-charset="utf-8">
				<?php echo $lg['sendMe'] ?><br>
				<label for="email"><?php echo $lg['email'] ?></label><input type="text" name="email" size="25" value=""><br>
				<?php echo $lg['mailInDb'] ?><br>
				<input type="submit" class="btn send-button" >
			</form>
		<?php elseif($_REQUEST['p'] == 'register'): ?>
			<form action="?p=newpeople" method="post" accept-charset="utf-8">
				<label for="pnom"><?php echo $lg['nom'] ?></label><input type="text" name="pnom" value=""><br>
				<label for="pprenom"><?php echo $lg['prenom'] ?></label><input type="text" name="pprenom" value=""><br>
				<label for="email"><?php echo $lg['email'] ?></label><input type="text" name="email" value=""><br>
				<div align="center">
					<input type="submit" class="btn login-button">
				</div>
			</form>
		<?php else: ?>
			<form action="?p=log" method="post" accept-charset="utf-8">
				<label for="email"><?php echo $lg['email'] ?></label><input type="text" name="email" value=""><br>
				<label for="pass"><?php echo $lg['password'] ?></label><input type="password" name="pass" value=""><br>
				<div align="center">
					<input type="submit" class="btn login-button">
				</div>
			</form>
		<?php endif ?>
	</div>
	<p><?php if ($_REQUEST['p'] != 'register') echo $lg['pasInscrit'].'<a href="?p=register"> '.$lg['clicIci'].'</a>'; ?></p>
	<p><?php if ($_REQUEST['p'] == 'register') echo $lg['dejaInscrit'].'<a href="?p=login"> '.$lg['clicIci'].'</a>'; ?></p>
	<p><?php if ($_REQUEST['p'] != 'sendpass') echo $lg['oubliePass'].'<a href="?p=sendpass"> '.$lg['clicIci'].'</a>'; ?></p>
	<span><?php echo $lg['plusRenseignement'] ?></span>
	<div class="phone"><?php echo $lg['phoneNumber'] ?></div>
</div>