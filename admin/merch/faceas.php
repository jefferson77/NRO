<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<style type="text/css" media="screen">
	.bloc {
		width: 250px;
		float: left;
		background-color: #ADADAD;
		margin: 5px;
		padding: 5px;
		padding-top :0px;
		text-align: center;
		border: 1px solid #E3E3E3;
	}

	.bloc form {
		width: 100%;
		background-color: #979797;
		text-decoration: none;
		display: block;
		margin: -5px;
		padding: 2px 5px;
	}

	.bloc form:hover {
		background-color: #848697;
	}

	.bloc h3 {
		background-color: #BCBDBF;
		margin-left: -5px;
		margin-right: -5px;
		margin-bottom: 10px;
		margin-top: 0px;
	}

	h2 {
		width: 100%;
		border-bottom: 2px solid #999;
		float: left;
	}
</style>
<div id="centerzonelarge" style="background-color: #D3D3D3;">
<br>
<h1>Facturation EAS</h1>
<?php

	// if ($_SESSION['roger'] == 'user') {
	// 	$clause = "AND a.idagent = ".$_SESSION['idagent'];
	// } else {
	// 	$clause = "";
	// }

	## Recupère tous les merch depuis Janvier 2009
	$listing = $DB->getArray("SELECT
			CASE me.idclient
				WHEN '2929' THEN '1713'
				WHEN '2928' THEN '2651'
				ELSE me.idclient END AS grclient,

				c.societe AS clsociete,
				o.langue AS langue,

			GROUP_CONCAT(DISTINCT me.idclient  SEPARATOR ', ') as clients,
			GROUP_CONCAT(DISTINCT me.facnum  SEPARATOR ', ') as factures,

			COUNT(DISTINCT me.idmerch) as nbrmissions,

			CONCAT(YEAR(me.datem),'-',DATE_FORMAT(me.datem, '%m')) as period,
			CONCAT(YEAR(me.datem),'-',DATE_FORMAT(me.datem, '%m'), '-', CASE me.idclient WHEN '2929' THEN '1713' WHEN '2928' THEN '2651' ELSE me.idclient END) as bloc

		FROM merch me
			LEFT JOIN client c ON me.idclient = c.idclient
			LEFT JOIN cofficer o ON me.idcofficer = o.idcofficer

		WHERE me.genre = 'EAS'
			AND me.datem BETWEEN '2009-01-01' AND '".date("Y-m-t", strtotime("-1 month"))."'

		GROUP BY CONCAT(YEAR(me.datem),'-',DATE_FORMAT(me.datem, '%m'), '-', grclient)

		ORDER BY CONCAT(YEAR(me.datem),DATE_FORMAT(me.datem, '%m')), grclient, me.idshop, me.datem");

	$lastjump = "-";
	foreach ($listing as $row) {
		if ($row['period'] != $lastjump) {
			echo "<h2>".date("Y m", strtotime($row['period']."-01"))."</h2>";
		}

		switch ($row['langue']) {
				case "NL":
					setlocale(LC_TIME, 'nl_NL');
				break;
				case "FR":
				default:
					setlocale(LC_TIME, 'fr_FR');
		}

		echo '<div class="bloc"><h3>'.$row['clsociete'].'</h3>
('.$row['clients'].') <br>
<br>
 <h3>facs</h3>'.$row['factures'].'<br><br>
<form action="'.NIVO.'print/merch/facture/printfaceas.php" method="post" accept-charset="utf-8">
	<input type="hidden" name="titre" value="'.$row['clsociete'].' '.strftime("%B %Y", strtotime($row['period']."-01")).'">
	<input type="hidden" name="bloc" value="'.$row['bloc'].'">
	<input type="hidden" name="clients" value="'.$row['clients'].'">

	<input type="submit" class="btn printer" value="Print">
	 '.$row['nbrmissions'].' missions
</form>
</div>';

		$lastjump = $row['period'];
	}

	// include "factoring1.php";
?>
</div>
