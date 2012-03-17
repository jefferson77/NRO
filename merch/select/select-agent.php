<div id="centerzonelarge">
<?php
$classe = 'standard' ;

### Première étape : formulaire de recherche de Agents
?>
<h1 align="center">Recherche des Agents</h1>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=agent';?>" method="post">
	<input type="hidden" name="etape" value="listeagent"> 
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr>
			<td>
				Nom de l&rsquo;Agent
			</td>
			<td>
				<input type="text" size="20" name="searchagent" value="">
			</td>
		</tr>
	</table>
	<input type="submit" name="Rechercher" value="Rechercher"> 
</form>
</div>