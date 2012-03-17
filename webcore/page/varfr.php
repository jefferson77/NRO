<?php
# Page de variables pour texte et illu de tout le site
switch ($_GET['section']){
	### SECTION webpeople
	case "webpeople" :
		$texte1 = 'Vous allez p&eacute;n&eacute;trer dans l&rsquo;espace s&eacute;curis&eacute; de la soci&eacute;t&eacute; Exception&sup2;';
		$texte2 = 'Veuillez introduire votre <b>login</b> et votre <b>mot de passe</b>';
		$texte3 = 'Espace jobiste s&eacute;curis&eacute;';
		$texte4 = 'Login ( Email )';
		$texte5 = 'Mot de passe';
		$texte6 = 'Entrer';
		$texte7 = 'email inconnu';
		$texte8 = 'Envoyez mon mot de passe &agrave; l&rsquo;adresse email suivante';
		$texte9 = 'L&rsquo;adresse email de votre fiche chez Exception&sup2;';
		$texte10 = 'Envoyez moi mon mot de passe';
		$texte11 = 'Un email vous a &eacute;t&eacute; envoy&eacute; sur';
		$texte12 = 'Cliquez ici';
		$texte13 = 'Pour tous renseignements compl&eacute;mentaires, <br>n&rsquo;h&eacute;sitez pas &agrave; prendre contact avec un responsable d&rsquo;Exception&sup2;.';
		$texte14 = 'Entrer';
		$texte15 = 'Veuillez introduire votre <b>Nom</b> votre <b>Pr&eacute;nom</b> et votre <b>Email</b>';
		$texte16 = 'Nom';
		$texte17 = 'Pr&eacute;nom';
		$texte18 = 'Oubli&eacute; votre mot de passe ?';
		$texte19 = "Tu n'es pas encore inscrit(e) ? Cliques ici";
		$texte20 = "Tu es d&eacute;j&agrave; inscrit(e) ? Cliques ici";

	break;
	#/## SECTION webpeople


	### SECTION webclient
	case "webclient" :
		$texte1 = 'Vous allez p&eacute;n&eacute;trer dans l&rsquo;espace s&eacute;curis&eacute; de la soci&eacute;t&eacute; Exception&sup2;';
		$texte2 = 'Veuillez introduire votre <b>ID client</b>, <b>login</b> et votre <b>mot de passe</b>';
		$texte3 = 'Espace Client s&eacute;curis&eacute;';
		$texte4 = 'ID Client :';
		$texte5 = 'Envoyez mon mot de passe &agrave; l&rsquo;adresse email suivante :';
		$texte6 = '(L&rsquo;adresse email de votre fiche chez Exception&sup2;)';
		$texte7 = 'Envoyez moi mon mot de passe';
		$texte8 = 'Login ( Email ) :';
		$texte9 = 'Mot de passe :';
		$texte10 = 'Entrer';
		$texte11 = 'Un email vous a &eacute;t&eacute; envoy&eacute; sur';
		$texte12 = 'Cliquez ici';
		$texte13 = 'Pour tous renseignements compl&eacute;mentaires, <br>n&rsquo;h&eacute;sitez pas &agrave; prendre contact avec un responsable d&rsquo;Exception&sup2;.';
		$texte18 = 'Oubli&eacute; votre mot de passe ?';
		$texte19 = 'Veuillez introduire votre <b>login</b> et votre <b>mot de passe</b>';
		$texte20 = "Veuillez remplir tous les champs du formulaire, merci.";

		$texte30 = 'Soci&eacute;t&eacute;';
		$texte31 = 'Qualit&eacute;';
		$texte32 = 'Mr';
		$texte33 = 'Mme';
		$texte34 = 'Mlle';
		$texte35 = 'Nom';
		$texte36 = 'Pr&eacute;nom';
		$texte37 = '';
		$texte38 = 'Entrer';
		$texte39 = 'Entrer';
		$texte40 = 'Entrer';

	break;
	#/## SECTION webclient

	### SECTION VIP
	case "vip" :
		switch ($_GET['page']){
		### page Nos services
			case "nosservices" :
				$titre = "NOS SERVICES";
				$texte1 = '
					Exception&sup2; VIP accorde beaucoup d&rsquo;importance &agrave; votre accompagnement! 
					<br><br>Fort de son expertise acquise au fil des ann&eacute;es, notre d&eacute;partement VIP peut vous mettre &agrave; disposition <b><i>des &eacute;quipes qualifi&eacute;es</i></b> pour des activit&eacute;s telles que : <br>
					<br>
					<ul>
					<li> l&rsquo;accueil VIP, la registration &amp; le badging lors d&rsquo;&eacute;v&eacute;nements, de salons ou de congr&egrave;s
					<li> l&rsquo;event support (bar, vestiaire, voituriers, chauffeurs,&hellip;)
					<li> les actions promotionnelles, les d&eacute;gustations ou le sampling
					<li> les street actions
					<li> l&rsquo;encadrement
					<li> les night actions
					</ul>
					<br>
					Chaque &eacute;v&eacute;nement est coordonn&eacute; en fonction de <i><b>votre image de marque et de vos standards de qualit&eacute;.</i></b><br>
				';
			break;
		### page projet
			case "projet" :
				#$titre = "PROJETS EN COURS";
				$texte1 = "
					<table>
						<tr>
							<th class='bleu' colspan='2'>
								<li>Salon de l&rsquo;automobile :&nbsp; (du 22/01/04 au 29/01/04)
							</th>
						</tr>
						<tr>
							<td width='25'><br></td>
							<td>
								Impression des badges <br>
								Accueil du stand Nissan, mise &agrave; disposition de 20 h&ocirc;tesses.<br>
								Accueil du stand Citroen, gestion de l&rsquo;offre de cadeaux promotionnels.<br>
								<br>
							</td>
						</tr>
					</table>
				";
			break;
		### page news
			case "news" :
				$titre = "NEWS";
				$toinclude="news.php";
			break;
		### page tenues
			case "tenues" :
				$titre = "TENUES";
				$texte1 = '
						Nos uniformes sont &agrave; votre disposition ; classiques ou conceptuels.<br><br>
						Ils sont le symbole du service VIP, tout en &eacute;tant toujours l&#8217;image de marque de votre soci&eacute;t&eacute;.
						';	
				$texte2 = 'Tailleur Gris';
				$texte3 = 'Tailleur Noir - Chemisier Mauve';
				$texte4 = 'Tailleur Noir - Chemisier Fushia';
				$texte5 = 'Tailleur Noir - Chemisier Emeraude';
				$texte6 = 'Tailleur Bleu';
				$texte7 = 'Tailleur Rouge';
				$texte8 = 'Costume';
				$texte9 = 'Chemises';
			break;
		### page contact
			case "contact" :



			break;
		### page présentation
			case "presentation" :
			default:
				$titre = "PRESENTATION";
				$texte1 = '
								Notre d&eacute;partement VIP vous propose ses services pour tous vos &eacute;v&eacute;nements, pour toutes vos actions n&eacute;cessitant du personnel jeune, dynamique et enthousiaste.
							<br><br>
								<b><i>Ils seront les ambassadeurs de votre soci&eacute;t&eacute;, de vos marques.</b></i>
							<br><br>
								A cette fin, la s&eacute;lection de nos h&ocirc;tes et h&ocirc;tesses se fait avec le plus grand soin, sur base de leurs connaissances en langues, leurs aptitudes, leur sens de l&rsquo;initiative&hellip; 
							<br><br>
								Cette s&eacute;lection rigoureuse, en fonction de vos crit&egrave;res propres, est le travail de <a href="../structure/index1.php?section=vip&page=contact&lang=fr" target="site">quatre v&eacute;ritables passionn&eacute;s </a> au sein de notre agence. Ils veilleront à ce que chaque &eacute;quipe, mise au service des clients, soit form&eacute;e de personnes accueillantes, souriantes, cr&eacute;atives et dot&eacute;es d&rsquo;un r&eacute;el dynamisme. 
				';
			break;
		}
	break;
	#/## SECTION VIP

	### SECTION animation-------------------------------------------------------------------------------------------------------------------
	case "animation" :
		switch ($_GET['page']){
		### page possibilites
			case "possibilites" :
				$titre = "NOS SERVICES";
				$texte1 = '
								Fort de son expertise, le d&eacute;partement Animation peut mettre &agrave; votre
								disposition des &eacute;quipes jeunes et/ou exp&eacute;riment&eacute;es et cela
								pour des actions du type :
							<br><br>
								- d&eacute;gustation<br>
								- sampling<br>
								- action promo de vente<br>
								- description technique de produit<br>
								- concours
							<br><br>
								Nos animateurs et animatrices de par une pr&eacute;sence r&eacute;currente 
								dans les points de vente garantissent une synergie et une bonne collaboration
								avec les responsables de rayons.<br>
';
			break;
		### page projet
			case "projet" :
				$titre = "PROJETS EN COURS";
				$texte1 = "projets en cours";
			break;
		### page news
			case "news" :
				$titre = "NEWS";
			break;
		### page tenues


			case "tenues" :
				$titre = "TENUES";
				$texte1 = "tenues";
			break;
		### page materiel
			case "materiel" :
				$titre = "MATERIEL";
				$texte1 = "En fonction de vos besoins, nous pouvons mettre &agrave; votre disposition le
				mat&eacute;riel n&eacute;cessaire au bon d&eacute;roulement de vos animations:
				<p>-	Stands, fours, taques, ...<br>
				  -	Consommables tels que serviettes, nappages, cure dents, cuill&egrave;res &#8230;.<br>
				  -	Uniformes";
			break;
		### page contact
			case "contact" :


			break;
		### page philo
			case "philo" :
			default:
				$titre = "PRESENTATION";
				$texte1 = '
							Pour vos <b><i>animations</b></i> ou <b><i>promotions de vente</b></i>, notre d&eacute;partement peut mettre &agrave; votre
							disposition des <b><i>professionnels aguerris</b></i> aux techniques de vente pour qui l&#8217;organisation
							de <b><i>d&eacute;gustation</b></i> et la mise en avant de produits n&#8217;ont plus aucun
							secret.

						<br><br>
							S&eacute;lectionn&eacute;s en fonction de leurs qualit&eacute;s et de vos besoins,
							nos animateurs et animatrices seront les ambassadeurs de votre marque.<br>
							En toute transparence, ils vous communiqueront dans leur rapport d&#8217;animation
							en plus des r&eacute;sultats de vente et de d&eacute;gustations, commentaires
							et r&eacute;actions des consommateurs et des responsables de point de vente
							afin que vous puissiez avoir une vue bien concr&egrave;te de la situation sur
							le terrain.
						<br><br>
							Le r&ocirc;le de <a href="../structure/index1.php?section=animation&page=contact&lang=fr" target="site">notre &eacute;quipe administrative</a>, outre la s&eacute;lection
							du personnel &agrave; mettre &agrave; votre disposition, est de tout mettre
							en &#339;uvre pour que ce dernier puisse accomplir leur mission dans les
							meilleures conditions possibles.
						<br><br>
							D&egrave;s le mardi, munis de votre login, <b><i>vous pourrez consulter en ligne les informations
							contenues dans les rapports r&eacute;dig&eacute;s par nos animateurs </b></i> et ce gr&acirc;ce &agrave; notre outil informatique performant.<br>
						';
						#### Texte modifié	
							#D&egrave;s les animations du week-end termin&eacute;es, notre &eacute;quipe
							#r&eacute;coltera et synth&eacute;tisera les informations contenues dans les
							#<b><i>rapports</b></i> envoy&eacute;s par le people pr&eacute;sent sur le terrain.<br>
						# / ### Texte modifié	
			break;
		}
	break;
	#/## SECTION animation
	
	### SECTION merchandising----------------------------------------------------------------------------------------------------------
	case "merchandising" :
		switch ($_GET['page']){
		### page Nos services
			case "nosservices" :
				$titre = "NOS SERVICES";

				$texte1 = "Notre d&eacute;partement Merchandising peut mettre &agrave; votre disposition
					du personnel ou des &eacute;quipes pour des missions telles que :
					<p>-	Prise de commande<br>
					  -	Rack jobbing<br>
					  -	Full filling<br>

					  -	R&eacute;alisation de blitz, de t&ecirc;tes de banc<br>
					  -	Store check<br>
					  -	S&eacute;curisation<br>
					  -	Price audit<br>
					  -	Placement de PLV<br>
					  -	Outsourcing (force de vente, &#8230;)<br>
					  - &#8230;.";
			break;
		### page projet
			case "projet" :
				$titre = "PROJETS EN COURS";
				$texte1 = "projets en cours";
			break;
		### page news
			case "news" :
				$titre = "NEWS";
				$toinclude="news.php";
			break;
		### page tenues
			case "tenues" :
				$titre = "TENUES";
				$texte1 = "tenues";
			break;
		### page materiel
			case "materiel" :
				$titre = "MATERIEL";
				$texte1= "materiel";
			break;
		### page contact
			case "contact" :



			break;
		### page présentation
			case "presentation" :
			default:
				$titre = "PRESENTATION";
				$texte1 = 
							'Que ce soit pour la &laquo; grande distribution &raquo; ou pour le &laquo; commerce de
							d&eacute;tail &raquo;, notre d&eacute;partement Merchandising g&egrave;re
							et organise le planning de <b><i>v&eacute;ritables sp&eacute;cialistes</b></i> de la mise en
							avant des produits tant au niveau de l&#8217;approvisionnement que de l&#8217;am&eacute;nagement.
						<br><br>
							Outre ces t&acirc;ches traditionnelles, nous avons &eacute;tendu notre expertise
							dans le domaine des missions se rapportant au contr&ocirc;le en point de vente
							; nous pensons ici &agrave; des <b><i>missions de store checking</b></i>, de <b><i>price auditing</b></i>
							ou de <b><i>s&eacute;curisation.</b></i>
						<br><br>
							Enfin, ce d&eacute;partement pourra &eacute;galement r&eacute;pondre &agrave; votre
							attente en vous fournissant pour des missions &agrave; moyen et long terme
							de <b><i>v&eacute;ritables &eacute;quipes de promoteurs</b></i> qui seront votre force de
							vente.
						<br><br>
							La gestion de toutes ces activit&eacute;s est assur&eacute;e par notre <a href="../structure/index1.php?section=merchandising&page=contact&lang=fr" target="site">&eacute;quipe
							interne</a> gr&acirc;ce &agrave; un outil informatique adapt&eacute; sur mesure
							au besoin.<br>
							Un <b><i>rapport d&#8217;activit&eacute;</b></i>, &eacute;tabli en fonction de vos besoins, 
							est consultable en ligne en toute confidentialit&eacute;.
						';
			
			break;
		}
	break;

	### SECTION Plan + staff
	case "left" :
		switch ($_GET['page']){
		### page plan 1 petit
			case "plan1" :
				$titre = "PLAN";
				$texte1= '<div align="center">
							<a href="page.php?section=left&page=plan2&lang=fr" target="main" class="minilien">Zoom Out</a><br><br>
							<img src="../images/carte-in.gif" width="427" height="354"><br>
							<br>
							195 Avenue de la Chasse &nbsp; B-1040 Bruxelles ( Etterbeek )<br><br>
							</div>
					';
			break;
		### page plan 1 petit
			case "plan2" :
				$titre = "PLAN";
				$texte1= '<div align="center">
							<a href="page.php?section=left&page=plan1&lang=fr" target="main" class="minilien">Zoom In</a><br><br>
							<img src="../images/carte-out.gif" width="427" height="354"><br>
							<br>
							195 Avenue de la Chasse &nbsp; B-1040 Bruxelles ( Etterbeek )<br><br>
							</div>
					';
			break;
		### page staff
			case "staff" :
				$titre = "STAFF";
				$texte1= "Staff organigramme";
			break;
		}
	break;


	
	### SECTION ACCUEIL--------------------------------------------------------------------------------------------------------------------------------
	case "accueil" :
	default:
		switch ($_GET['page']){
		### page contact
			case "contact" :



			break;
		### page ACCUEIL / ACCUEIL
			case "presentation" :
			default:
				$titre = "PRESENTATION";
				$texte1 = '
					Bienvenue sur le site d&rsquo;Exception&sup2;<br>
					<br>
					Etablie depuis 1987, l&rsquo;agence Exception&sup2;, longtemps sp&eacute;cialis&eacute;e en h&ocirc;tesses et promoboys VIP, n&rsquo;a cess&eacute; de grandir afin de r&eacute;pondre de la mani&egrave;re la plus professionnelle aux demandes du march&eacute;.<br>
					<br>
					Aujourd&rsquo;hui, 10 <a href="../structure/index1.php?section=accueil&page=contact&lang='.$lang.'" target="site">sp&eacute;cialistes</a> r&eacute;partis dans 3 d&eacute;partements, arm&eacute;s d&rsquo;un outil informatique &agrave; la pointe de la technologie, sont pr&eacute;sents pour r&eacute;pondre efficacement &agrave; toutes vos demandes de personnel que ce soit pour du <a href="../structure/index1.php?section=vip&page=presentation&lang='.$lang.'" target="site">VIP</a>, des <a href="../structure/index1.php?section=animation&page=presentation&lang='.$lang.'" target="site">animations</a> de vente, du <a href="../structure/index1.php?section=merchandising&page=presentation&lang='.$lang.'" target="site">merchandising</a> ou de l&rsquo;<b>outsourcing</b>.<br>
					<br>
					Mais ces professionnels du &laquo; People&rsquo;s Management &raquo; ne seraient rien sans le vaste fichier de personnel que nous mettons &agrave; votre disposition.<br>
					<br>
					Disponibilit&eacute;, professionnalisme, ponctualit&eacute; et bonne humeur sont les marques de notre agence et du personnel travaillant pour nous, pour vous.<br>
				';
			break;
		}
	break;
}


if ($_GET['page'] == "contact" ) {
	$titre = "CONTACT";
	$contact_direction = "Direction";
	$contact_direction_vip = "Direction du d&eacute;partement VIP";
	$contact_planning = "Planning";
	$contact_direction_com = "Direction &amp; Commerciale";
	$contact_Social = "Social Secretary";
	$contact_info = "Informations de contact";
	$contact_plus_info = "Pour plus d&rsquo;informations, cliquez sur le nom de la personne";
}

if (($_GET['page']) or ($_GET['page'])) {
	switch ($_GET['page']){

		### page Espace Clients
			case "espaceclients" :
				$titre = "ESPACE CLIENTS";
				$texte1 = '
							<li>
								<b><i>Vous n&#8217;&ecirc;tes pas encore client</b></i> chez Exception&sup2; et le contenu de notre site a &eacute;veill&eacute; un certain int&eacute;r&ecirc;t de votre part ?<br>
								N&#8217;h&eacute;sitez pas, &agrave; l&#8217;aide du document que vous trouverez en cliquant <a href="clientnew.php?lang='.$lang.'&l='.$lang.'" target="_blank">ici</a>, &agrave; nous faire parvenir votre demande d&#8217;offre &agrave; laquelle nous nous ferons fort de r&eacute;pondre dans les 24h00.
							<br><br><br>
							<li>
								<b><i>Vous &ecirc;tes client</b></i>, un simple <a href="client.php?lang='.$lang.'&l='.$lang.'" target="_blank">click</a> vous conduira &agrave; notre espace s&eacute;curis&eacute. 
								<br><br>
								Dans ce dernier, nous vous proposons un <i><b>acc&egrave;s direct &agrave; diverses informations exclusivement relatives &agrave; votre soci&eacute;t&eacute;.</i></b><br>
								Une de nos factures ou offres s&#8217;est &eacute;gar&eacute;e, pas de probl&egrave;me, un historique des commandes, offres et comptabilit&eacute; sont disponibles et vous permettent de r&eacute;-imprimer le document manquant.							
								<br><br>
								Vous pourrez y consulter &eacute;galement, d&egrave;s le mardi matin, les r&eacute;sultats complets et rapports des animations du week-end et bien d&#8217;autres choses.
							<br><br><br>
							<li>
								<b><i>Vous &ecirc;tes client mais n&#8217;avez pas encore de login</b></i>, pas de probl&egrave;me, remplissez le <a href="clientpass.php?lang='.$lang.'&l='.$lang.'" target="_blank">formulaire ad&eacute;quat</a> et ce dernier vous parviendra sous peu. 
						';
			break;
		### page Espace Jobistes
			case "espacejobistes" :
				$titre = "ESPACE JOBISTES";
				$texte1 = 
							'<li>
								Vous &ecirc;tes dynamique, travailleur, ponctuel, extraverti &#8230;, vous souhaitez rejoindre nos &eacute;quipes d&#8217;h&ocirc;tesses ou de promoboys VIP ?<br><br>
								Super ! Il vous suffit de remplir le <a href="peoplenew.php?lang='.$lang.'&l='.$lang.'" target="_blank">formulaire</a> et de nous le retourner.<br><br>
								Nous vous conseillons toutefois de passer nous voir d&egrave;s que possible &agrave; l&#8217;agence car, comme vous le savez, dans le monde de la communication, rien ne remplace le &laquo; relationnel &raquo;.
							<br><br><br>
							<li>
								Vous &ecirc;tes d&eacute;j&agrave; inscrit. <a href="people.php?lang='.$lang.'&l='.$lang.'" target="_blank">Cet espace</a> vous permettra de nous communiquer vos <i><b>disponibilit&eacute;s</i></b>, d&#8217;apporter d&#8217;&eacute;ventuelles modifications &agrave; votre fiche &ldquo;personnel&rdquo; individuelle, <i><b>d&#8217;imprimer votre prochain contrat</i></b>, de consulter nos <i><b>offres de travail</i></b> &#8230;
							';
			break;
	}
}	
?>