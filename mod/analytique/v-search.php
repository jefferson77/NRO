<div id="centerzonelarge">
	<div align="center">
		<form action="?act=display" method="post" accept-charset="utf-8">
			<h2>Période de la recherche : </h2>
			<p>Du <input type="text" name="datein" value="<?php echo date("1/1/Y", strtotime("-1 month")) ?>"></p>
			<p>Au <input type="text" name="dateout" value="<?php echo date("t/m/Y", strtotime("-1 month")) ?>"></p>
			<p>Client <input type="text" name="idclient" value=""></p>
			<p><input type="submit" value="Recherche"></p>
		</form>
	</div>
		
	<p>Affiche, pour la période demandée, un tableau triable contenant :</p>
	<ul>
		<li>La répartition des postes comptables</li>
		<li>Le total de ces postes</li>
		<li>Le nombre d'heures prestées (les forfaits sont comptés comme 8 Heures)</li>
		<li>Le tarif moyen (montant du poste prestations / nombre d'heures)</li>
	</ul>
	<p>Les résultats sont affichés en €</p>
	<p>Pour incorporer les données dans un tableau excel, il suffit de couper-coller.</p>
	<p>Les clients suivants sont forcés en EAS</p>
	<ul>
		<li>(1713) Carrefour EAS</li>
		<li>(2316) Champion Mestdagh</li>
		<li>(2346) EAS - CEVA</li>
		<li>(2651) GB EAS</li>
		<li>(2928) GB EAS</li>
		<li>(2929) Carrefour EAS</li>
		<li>(2931) BRUGGE RETAIL NV</li>
		<li>(2933) Carrefour Kuringen</li>
	</ul>
	
	<h2>Fait : 97%</h2>
	<ul>
		<li>secteur VIP</li>
		<li>secteur ANIM</li>
		<li>secteur MERCH</li>
		<li>secteur EAS</li>
		<li>Factures Manuelles + vérifier que la répartition des potses</li>
		<li>Facturation Forfaitaire</li>
		<li>Déduction des notes de crédit (montants et heures)</li>
	</ul>
	
	<h2>A faire :</h2>
	<ul>
		<li>Regrouper les colonnes de frais</li>
	</ul>
	
</div>