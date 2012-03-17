<style type="text/css" media="screen">
	table.rowstyle-alt td {
		height: 18px;
	}
</style>
<?php
## skip
$lister = 25;
$from   = (isset($_GET['skip']) && $_GET['skip'] > 0) ? $_GET['skip'] : 0;
$skip   = $from + $lister;
$rwd    = $from - $lister;

## get datas
$tablesalaires = $_SESSION['table'];

$peoples = $DB->getArray("SELECT s.idpeople, p.pnom, p.pprenom, p.codepeople, COUNT(s.date) AS nbjours, p.err
					FROM grps.$tablesalaires s
						LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
					GROUP BY s.idpeople
					ORDER BY p.codepeople
					LIMIT $from, $lister");

$nbrpeople = $DB->getOne("SELECT COUNT(DISTINCT idpeople) FROM grps.$tablesalaires");

## Left Menu
include 'v-leftmenu.php';

?>

<div id="infozone">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top">
			<h1><?php echo nomtable($tablesalaires);?></h1>
				<table class="rowstyle-alt" border="0" cellspacing="1" cellpadding="0" align="center" width="85%">
					<thead>
						<tr align="center">
							<th align="left">Registre</th>
							<th align="left">Nom</th>
							<th style="width: 100px;">Days</th>
							<th style="width: 60px;">ID</th>
							<th style="width: 60px;"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($peoples as $row) {
								$color = ($row['err'] == 'Y')?' style="background-color: #FFCC00;"':'';
							echo '<tr'.$color.' ondblclick="location.href=\'?act=showPeople&idpeople='.$row['idpeople'].'&skip='.$from.'\'">
								<td>'.$row['codepeople'].'</td>
								<td>'.$row['pprenom'].' '.$row['pnom'].'</td>
								<td style="text-align: center;">'.$row['nbjours'].'</td>
								<td style="color: #AAA;">'.$row['idpeople'].'</td>
								<td align="center"><a href="?act=delPeople&idpeople='.$row['idpeople'].'&skip='.$from.'"><font color="#CC0000">Suppr</font></a></td>
							</tr>';
						} ?>
					</tbody>
					<tfoot>
						<tr style="background-color: #A5B9C8;">
							<th colspan="2">
							<?php
								if ($from > 0) { ?><a class="blanc" href="?skip=<?php echo $rwd;?>"><img src="<?php echo STATIK ?>illus/NAVprv.gif" alt="NAVprv.gif" width="13" height="15" border="0" align="left"></a><?php }
								echo '&nbsp;&nbsp;'.$from;?> &agrave; <?php if ($skip > $nbrpeople) {echo $nbrpeople;} else {echo $skip;} ?> sur <?php echo $nbrpeople.'&nbsp;&nbsp;';
								if ($skip < $nbrpeople) { ?><a class="blanc" href="?skip=<?php echo $skip;?>"><img src="<?php echo STATIK ?>illus/NAVnxt.gif" alt="NAVnxt.gif" width="13" height="15" border="0" align="right"></a><?php } ?>
							</th>
							<th colspan="3">
								<?php
									$max = ceil($nbrpeople / $lister);

									for ($i = 1; $i <= $max; $i++) {
										$sk = ($i - 1) * $lister;
										if ($sk == $from) {
											echo '<img src="'.STATIK.'illus/NAVon.gif" alt="NAVoff.gif" width="12" height="15" border="0">';
										} else {
											echo '<a class="level2" href="?skip='.$sk.'"><img src="'.STATIK.'illus/NAVoff.gif" alt="NAVoff.gif" width="12" height="15" border="0"></a>';
										}
									}
								?>
							</th>
						</tr>
					</tfoot>
				</table>
			</td>
			<td width="100" valign="top" align="center">
				<fieldset>
					<legend>Ajouter un people</legend>
					<form action="?act=addPeople" method="post">
						<input type="text" name="numreg" size="5">
						<input type="submit" name="sub" value="Ajouter"><br>
						Entrez le num&eacute;ro de registre du people a ajouter aux paiements
					</form>
				</fieldset>
			</td>
		</tr>
	</table>
</div>
<div id="infobouton">
</div>