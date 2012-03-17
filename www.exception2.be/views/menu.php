	<div id="content">
		<div class="col" style="width: 280px;">
			<div class="titre"><?php echo $t['login']; ?></div>
			<div class="logs cadre10">
				<a href="<?php echo $DataSiteURL ?>people/"><img src="illus/logpeople.png" width="108" height="75" alt="Logpeople"><?php echo $t['people']; ?></a><br/><br/><br/>
				<?php echo $t['inscrivez_vous']; ?>
				<div class="insc cadre10"><a href="<?php echo $DataSiteURL ?>people/"><?php echo $t['inscris_toi']; ?></a></div>
			</div>
			<div class="logs cadre10"><a href="<?php echo $DataSiteURL ?>client/"><img src="illus/logclient.png" width="110" height="75" alt="Logclient"><?php echo $t['client']; ?></a><br/><br/><br/><?php echo $t['recuperez_vos_rap']; ?></div>
		</div>
		<div class="col" style="width: 490px;">
			<div class="titre"><?php echo $t['news']; ?></div>
			<?php readfile($DataSiteURL.'news/webnews.php?l='.$_GET['l']); ?>
		</div>
		<div class="col" style="width: 140px;">
			<a href="index.php?l=<?php echo $_GET['l'] ?>&p=video" style="color: #FFF;text-decoration: none;">
				<img src="illus/logotrends.png" alt="Logotrends">
				<br>
				<br>
				<div class="logs cadre10" style="margin: 0px;text-align: justify;"><?php echo $t['exception_laureate']; ?></div>
			</a>
		</div>
	</div>