<?php
define('NIVO', '../');

include NIVO."includes/ifentete.php" ;

$dates = $DB->getRow("SELECT MAX(vipdate) AS max, MIN(vipdate) AS min FROM vipmission WHERE idvipjob = ".$_REQUEST['idvipjob']);

if (!empty($_POST['min'])) $dates['min'] = fdatebk($_POST['min']);
if (!empty($_POST['max'])) $dates['max'] = fdatebk($_POST['max']);

?>
	<style type="text/css" media="screen">
		.maillist {
			border: 2px solid #FF5F00;
			background-color: #FDF9C4;
			font-family: Courier, sans-serif;
			color: #000;
			width: 80%;
			font-size: 14px;
			margin: 20px;
			padding: 10px;
		}
	</style>
	<div align="center">
		<form action="?act=dates" method="post" accept-charset="utf-8">
			<input type="hidden" name="idvipjob" value="<?php echo $_REQUEST['idvipjob'] ?>">
			De :<input type="text" name="min" value="<?php echo fdate($dates['min']) ?>">
			A :<input type="text" name="max" value="<?php echo fdate($dates['max']) ?>">
			<input type="submit" value="Rechercher">
		</form>
		<div class="maillist" align="center">
	<?php
	        $emails = $DB->getColumn("SELECT 
					DISTINCT(p.email) 
				FROM vipmission v 
					LEFT JOIN people p ON p.idpeople = v.idpeople 
				WHERE v.idvipjob = ".$_REQUEST['idvipjob']."
					AND p.email LIKE '%@%'
					AND v.vipdate BETWEEN '".$dates['min']."' AND '".$dates['max']."'
				ORDER BY p.email
				");
			
			echo implode(", ", $emails); ?>
		</div>
		Copiez-collez ces adresses dans le champ A: de votre programme mail
	</div>
<?php		   
include NIVO."includes/ifpied.php";
?>