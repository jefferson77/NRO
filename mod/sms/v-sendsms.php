<?php
## Entete

$phAg = $DB->getOne("SELECT agsm FROM agent WHERE idagent = ".$_SESSION['idagent']);

$phoneAgentTemp = fphone($phAg);

$phoneAgent = $phoneAgentTemp['human'];

if(!empty($_GET['dest']))
{
	$infopeople = $DB->getArray("SELECT p.pprenom, p.pnom, p.gsm, p.idpeople FROM people p WHERE p.idpeople = ".$_GET['dest'][0]);
}
elseif(empty($_POST['dest'])) // pas de tableau d'idpeople créé -> la requête sql est transmise en POST
{
	if(empty($_POST['secteur'])) // on vient de la recherche d'un people dans le menu
	{
		if(!empty($_POST['matos'])) // la recherche vient du matos
		{
			$_POST['query'] = "LEFT JOIN matos m ON m.idpeople = p.idpeople WHERE MONTH(m.dateout) = ".$_POST['month']." AND YEAR(m.dateout) = ".$_POST['year']." AND m.idpeople > 0 GROUP BY idpeople";
		}
		else //on vient du menu, il faut rajouter le where de la requête attention, pas plusieurs fois
		{
			if(!empty($_POST['query']))
			{
				if(substr_count($_POST['query'],"WHERE ")<1)
				{
					$_POST['query'] = "WHERE ".$_POST['query'];

				}
			}
			else
			{
				$infopeople=array();
			}
		}
		if(!isset($infopeople))
		{
			$infopeople = $DB->getArray("SELECT p.pprenom, p.pnom, p.gsm, p.idpeople FROM people p ".$_POST['query']);
		}

	}
	else // on vient des missions
	{
		$infopeople = $DB->getArray("SELECT p.pprenom, p.pnom, p.gsm, p.idpeople FROM people p ".$_POST['query']);
	}
}
else //on a un tableau d'idpeople
{
	if(count($_POST['dest'])>1) // il y a plus qu'un idpeople
	{
		$search = "IN (".implode(",",$_POST['dest']).")";
	}
	else // un seul idpeople dans le tableau
	{
		//ici petit cafouillage, quand il n'y a qu'un people, il peut être à l'index 0 ou 1, selon qu'on ait effacé la personne au dessus ou en dessous dans la liste
		if(!empty($_POST['dest'][0]))
		{
			$search = " = ".$_POST['dest'][0];
		}
		else
		{
			$search = " = ".$_POST['dest'][1];
		}
	}

	$infopeople = $DB->getArray("SELECT pprenom, pnom, gsm, idpeople FROM people WHERE idpeople ".$search);
}
array_unique($infopeople);
?>
jq	<style type="text/css" media="screen">
	#counter0 {display:inline; color:red; font-size:16px;}
</style>
<div id="centerzonelarge">
	<form action="<?php echo NIVO ?>mod/sms/adminsms.php?act=redirect" method="POST" name="sms">
		<input type="hidden" name="query" value="<?php echo $_POST['query']; ?>">

<table border="1" cellspacing="5" cellpadding="5" width="100%" height="100%">
	<tr>
		<td rowspan="2" width ="350">
			<h2>Destinataires : <?php echo count($infopeople); ?> Personnes </h2>
			<?php if(!empty($infopeople))
			{ ?>
			<table class="sortable-onload-0 paginate-20 rowstyle-alt no-arrow" width="340" align="center">
				<thead>
					<tr>
						<th class="sortable-text">People</th>
						<th class="sortable-numeric">GSM</th>
						<th></th>

					</tr>
				</thead>
				<tbody>
					<?php
					foreach($infopeople as $row)
					{
						$gsms = fphone($row['gsm']);
						if(($gsms['err'] == 'OK') and ($gsms['type']=='gsm'))
						{

							echo "<tr>";
								echo "<td>";
									echo '<input type="hidden" name="dest[]" value="'.$row['idpeople'].'">';
									echo $row['pprenom']." ".$row['pnom'];
									echo "</td>";
									echo "<td>";
									echo $gsms['human'];
									echo "</td>";
									echo"<td>";
									echo '<input type="submit" class="btn delete" name="rmidpeople" value="'.$row['idpeople'].'">';
								echo "</td>";
							echo "</tr>";
				 	}
					}
					?>

				</tbody>
			</table>
			<?php }
			else
			{
				$_POST['disable'] = "disable";
				echo "Pas de people sélectionnés";
			} ?>
			<br>
			<input type="hidden" name="idpeople" value="" id="idpeople">
			<input type="text" name="people" value="" id="pname" title="Entrez le début d'un nom ou un codepeople" style="text-align:center;">
			<input type="submit" class="btn add" value="idpeople" name="addidpeople">
		</td>
		<td>
			<h2> Votre message </h2>
			<table border="1">
					<textarea name="text" rows="8" cols="40" onkeyup='updatePreviewMessage()' id="limited"><?php echo $_POST['text']; ?></textarea>
					<span class="counter"></span>
					<br>
					<?php
					if(!empty($_POST['idagent']))
					{
						$idagent = $_POST['idagent'];
					}
					else
					{
						$idagent =  $_SESSION['idagent'];
					}
					$nomagent = $DB->getOne("SELECT CONCAT(prenom,' ', nom) FROM agent WHERE idagent = ".$idagent);

					?>
					<input type="hidden" name="idagent" value="<?php echo $idagent; ?>" id="idagent">
					<input type="text" name="nomagent" value="<?php echo $nomagent; ?>" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;">

				<?php

				 ?>
			</table>
			<input type="submit" value="Envoyer" name="send" <?php if($_POST['disable']=="disable") echo "DISABLED"; ?>>

		</td>
	</tr>
	<tr>
		<td id='previewMessage'>

			hello !

			<!-- <h2>Statistiques</h2> -->

			<?php
			// if(empty($_POST['session_id']))
			// 			{
			// 			$data = array("api_id"		=>	"3123166",
			// 						  "user"		=>	"Exception2",
			// 						  "password"  	=>	"Exception01"
			// 						);
			//
			// 			$ch = curl_init();
			// 			## requête googlmaps
			// 			curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/http/auth');
			// 			curl_setopt($ch, CURLOPT_POST, 1);
			// 			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			// 			curl_setopt($ch, CURLOPT_HEADER, 0);
			// 			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
			// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 			$string = curl_exec($ch);
			// 			curl_close($ch);
			// 			#enlever les 4 premiers caractères pour avoir la session id, qui va permettre de récupérer le crédit sms restant
			// 			$sessid = substr($string,4);
			//
			//
			//echo $sessid."<BR>";

			?>
				<!-- <input type="hidden" name="session_id" value="<?php //echo $sessid; ?>"> -->
			<?php
			// }
			// 		else
			// 		{
				?><!-- <input type="hidden" name="session_id" value="<?php //echo $_POST['session_id']; ?>"> --><?php
				// $sessid = $_POST['session_id'];
				// 	}
				// 	$data = array("session_id"		=>	"$sessid");
				//
				// 	$ch = curl_init();
				// 	## requête googlmaps
				// 	curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/http/getbalance');
				// 	curl_setopt($ch, CURLOPT_POST, 1);
				// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				// 	curl_setopt($ch, CURLOPT_HEADER, 0);
				// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
				// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// 	$string = curl_exec($ch);
				// 	curl_close($ch);
				// 	#enlever les 8 premiers caractères pour avoir le crédit sms restant
				//
				// 	$balance = substr($string,8)/1.5;
				// 	echo $balance." messages restants.";
			?>

		</td>
	</tr>
</table>
</form>
</div>
<script type="text/javascript">
var nomAgent = document.getElementById("nomagent").value.split(' ')[0];
var phoneAgent = "<?php echo $phoneAgent; ?>";
function updatePreviewMessage()
{
	document.getElementById("previewMessage").innerHTML = document.getElementById("limited").value+" "+nomAgent+" "+phoneAgent;
}
updatePreviewMessage();


/*
 * pass '-1' as speed if you don't want the char-deletion effect. (don't just put 0)
 * Example: jQuery("Textarea").textlimit('span.counter',256)
 *
 * $Version: 2009.07.25 +r2
 * Copyright (c) 2009 Yair Even-Or
 * vsync.design@gmail.com
*/
(function(jQuery) {
	jQuery.fn.textlimit=function(counter_el, thelimit, speed) {
		var charDelSpeed = speed || 15;
		var toggleCharDel = speed != -1;
		var toggleTrim = true;
		var that = this[0];
		var isCtrl = false;
		updateCounter();

		function updateCounter(){
			if(typeof that == "object")
				jQuery(counter_el).text(thelimit - that.value.length+" caractères restants");
		};

		this.keydown (function(e){
			if(e.which == 17) isCtrl = true;
			var ctrl_a = (e.which == 65 && isCtrl == true) ? true : false; // detect and allow CTRL + A selects all.
			var ctrl_v = (e.which == 86 && isCtrl == true) ? true : false; // detect and allow CTRL + V paste.
			// 8 is 'backspace' and 46 is 'delete'
			if( this.value.length >= thelimit && e.which != '8' && e.which != '46' && ctrl_a == false && ctrl_v == false)
				e.preventDefault();
		})
		.keyup (function(e){
			updateCounter();
			if(e.which == 17)
				isCtrl=false;

			if( this.value.length >= thelimit && toggleTrim ){
				if(toggleCharDel){
					// first, trim the text a bit so the char trimming won't take forever
					// Also check if there are more than 10 extra chars, then trim. just in case.
					if ( (this.value.length - thelimit) > 10 )
						that.value = that.value.substr(0,thelimit+100);
					var init = setInterval
						(
							function(){
								if( that.value.length <= thelimit ){
									init = clearInterval(init); updateCounter()
								}
								else{
									// deleting extra chars (one by one)
									that.value = that.value.substring(0,that.value.length-1); jQuery(counter_el).text('Trimming... '+(thelimit - that.value.length));
								}
							} ,charDelSpeed
						);
				}
				else this.value = that.value.substr(0,thelimit);
			}
		});

	};
})(jQuery);

	function formatResult(row) {return row[0];}
	function formatItem(row) {return "("+row[2]+") "+row[1];}

	function agentFormatResult(row) {return row[0];}
	function agentFormatItem(row) {return row[1];}

	$(document).ready(function(){
		// fastsearch nom du people
		$("input#pname").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/peoplebyname.php?mode=full", {
			inputClass: 'autocomp',
			formatItem: formatItem,
			formatResult: formatResult
		});

		$("input#pname").result(function(data, row) {
			if (data) document.sms.idpeople.value = row[0];
			if (data) document.sms.people.value = row[1];
		});

		$("input#nomagent").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/agent.php", {
			inputClass: 'autocomp',
			width: 250,
			minChars: 2,
			formatItem: agentFormatItem,
			formatResult: agentFormatResult,
			delay: 200
		});

		$("input#nomagent").result(function(data, row) {
			if (data) document.sms.idagent.value = row[0];
			if (data) document.sms.nomagent.value = row[1];
		});

		// limiteur et compteur du textarea sms
		var nomAgent = document.getElementById("nomagent").value;
		var splitted = nomAgent.split(" ");
		var nomAgent = splitted[0];
		var length = nomAgent.length;
		$("#limited").textlimit('span.counter',160-15-length); /* all textarea's with an attribut maxlength will get a textlimiter */
	});
</script>
