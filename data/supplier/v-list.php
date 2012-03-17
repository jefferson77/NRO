<?php $suppliers = $DB->getArray("SELECT * FROM `supplier` WHERE ".((!empty($_SESSION['supplierquid']))?$_SESSION['supplierquid']:'1')." ORDER BY idsupplier DESC LIMIT 100"); ?>
<div id="centerzonelarge">
	<fieldset>
		<legend>Recherche des suppliers :</legend>
		<table class="sortable-onload-1 rowstyle-alt paginate-20 no-arrow" border="0" width="90%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th class="sortable-numeric">ID</th>
					<th class="sortable-text">Soci&eacute;t&eacute;</th>
					<th class="sortable-text">Activit&eacute;</th>
					<th class="sortable-text">Notes</th>
					<th class="sortable-text">Tel</th>
					<th class="sortable-text">Fax</th>
					<th class="sortable-text">Email</th>
					<th class="sortable-numeric">Etat</th>
				</tr>
			</thead>
			<tbody>
		<?php
		foreach ($suppliers as $row)
		{
			echo '<tr ondblclick="location.href=\''.$_SERVER['PHP_SELF'].'?act=show&idsupplier='.$row['idsupplier'].'\'">
					<td>'.$row['idsupplier'].'</td>
					<td>'.$row['societe'].'</td>
					<td>'.$row['fonction'].'</td>
					<td>'.$row['notes'].'</td>
					<td>'.$row['tel'].'</td>
					<td>'.$row['fax'].'</td>
					<td>'.$row['email'].'</td>
					<td>'.(($row['etat'] == 5)?'<font color="green"> In':'<font color="red"> Out').'</font></td>
				</tr>';
		} ?>
			</tbody>	
		</table>
	</fieldset>
</div>
