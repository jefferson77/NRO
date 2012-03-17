<?php
# Page de variables pour texte et illu de tout le site
switch ($_GET['section']){
### SECTION webpeople			
	case "webpeople" :			
		$texte1 = "U krijgt toegang tot de beveiligde ruimte van Exception&sup2;";
		$texte2 = "Gelieve uw login en paswoord in te voeren";
		$texte3 = "Beveiligde ruimte voor jobstudenten";
		$texte4 = "Login (E-mail)";
		$texte5 = "Paswoord";
		$texte6 = "Start";
		$texte7 = "onbekend e-mailadres";
		$texte8 = "Stuur mijn paswoord naar het volgende e-mailadres";
		$texte9 = "Het e-mailadres van uw fiche bij Exception&sup2;";
		$texte10 = "Stuur me mijn paswoord	";
		$texte11 = "Er werd u een e-mail verzonden over	";
		$texte12 = "Klik hier";
		$texte13 = "Aarzel niet om contact op te nemen met een verantwoordelijke van Exception&sup2;<br> voor bijkomende informatie.";
		$texte14 = "Start";
		$texte15 = 'Gelieve uw <b>Naam</b>, <b>Voornaam</b> en <b>Email</b> in te voeren';
		$texte16 = 'Naam';
		$texte17 = 'Voornaam';
		$texte18 = 'Paswoord vergeten ?';
		$texte19 = "Je bent nog niet ingeschreven? Klik hier";
		$texte20 = "Je bent reeds ingeschreven? Klik hier";
	
	break;			
	#/## SECTION webpeople			

	### SECTION webclient
	case "webclient" :
		$texte1 = 'U komt terecht op de beveiligde webruimte van Exception&sup2;';
		$texte2 = 'Gelieve uw <b>klant-ID</b>, <b>login</b> en <b>paswoord</b> in te voeren';
		$texte3 = 'Beveiligde ruimte voor klanten';
		$texte4 = 'Klant-ID :';
		$texte5 = 'Stuur mijn paswoord naar het volgende e-mailadres :';
		$texte6 = '(Het e-mailadres van uw fiche bij Exception&sup2;)';
		$texte7 = 'Stuur me mijn paswoord	';
		$texte8 = 'Login (e-mail) :';
		$texte9 = 'Paswoord :';
		$texte10 = 'Start';
		$texte11 = "Er werd u een e-mail verzonden over	";
		$texte12 = "Klik hier";
		$texte13 = "Aarzel niet om contact op te nemen met een verantwoordelijke van Exception&sup2;<br> voor bijkomende informatie.";
		$texte18 = 'Paswoord vergeten ?';
		$texte19 = "Gelieve uw login en paswoord in te voeren";
		$texte20 = "Gelieve alle velden van het formulier in te vullen.";
		
		$texte30 = 'Bedrijf';
		$texte31 = 'Aanspreektitel';
		$texte32 = 'Meneer';
		$texte33 = 'Mevrouw';
		$texte34 = 'Juffrouw';
		$texte35 = 'Naam';

		$texte36 = 'Voornaam';

	break;
	#/## SECTION webclient


	### SECTION VIP
	case "vip" :
		switch ($_GET['page']){
		### page Nos services
			case "nosservices" :
				$titre = "ONZE DIENSTEN";
				$texte1 = '
					Uw ondersteuning is van groot belang voor Exception&sup2; VIP!
					<br><br>Gezegend met een jarenlange ervaring stelt onze VIP-afdeling u een aantal <b><i>hooggekwalificeerde teams</i></b> ter beschikking voor een hele reeks activiteiten zoals:  <br>
					<br>
					<ul>
					<li> VIP-onthaal, registratie &amp; badging tijdens evenementen, feesten of congressen
					<li> eventondersteuning (bar, vestiaire, transporteurs, chauffeurs,&hellip;)
					<li> promotieacties, degustaties of sampling
					<li> street actions
					<li> omkadering
					<li> night actions
					</ul>
					<br>
					Ieder evenement wordt geco&ouml;rdineerd in functie van <i><b>uw bedrijfsimago en volgens uw kwaliteitsnormen.</i></b><br>
				';
			break;
		### page projet
			case "projet" :
				$titre = "LOPENDE PROJECTEN";
				$texte1 = "Projets en cours";
			break;
		### page news
			case "news" :
				$titre = "NEWS";
				$toinclude="news.php";
			break;
		### page tenues
			case "tenues" :
				$titre = "KLEDING";
				$texte1 = '
						Onze uniformen liggen voor u klaar; zowel klassiek als conceptueel.<br>
						Ze zijn het watermerk van de VIP-dienst en de dragers van uw bedrijfsimago.
						';	
				$texte2 = 'Grijs mantelpak';
				$texte3 = 'Zwart mantelpak - Lila blouse';
				$texte4 = 'Zwart mantelpak - Fuchsia blouse';
				$texte5 = 'Zwart mantelpak - Smaragdgroene blouse';
				$texte6 = 'Blauw mantelpak';
				$texte7 = 'Tailleur Rouge';
				$texte8 = 'Kostuum';
				$texte9 = 'Hemden';
			break;
		### page contact
			case "contact" :


			break;
		### page présentation
			case "presentation" :
			default:
				$titre = "VOORSTELLING";
				$texte1 = '
								Onze VIP-afdeling biedt haar diensten aan voor de organisatie van al uw evenementen en alle acties waarvoor u jonge, dynamische en enthousiaste medewerkers zoekt.
							<br><br>
								<b><i>Zij zijn de ambassadeur van uw bedrijf, van uw merk.</b></i>
							<br><br>
								Wij selecteren onze hosts en hostessen met de grootste zorg door hun talenkennis, vaardigheden, zin voor initiatief, e.d. uitvoerig te testen.
							<br><br>
								Deze strenge selectie volgens de door u zelf bepaalde criteria gebeurt door <a href="../structure/index1.php?section=vip&page=contact&lang=nl" target="site">vier enthousiaste medewerkers</a> binnen ons agentschap. Zij zorgen ervoor dat ieder team dat ter beschikking staat van klanten uit openhartige, glimlachende, creatieve en dynamische medewerkers bestaat.
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
				$titre = "ONZE DIENSTEN";
				$texte1 = '
								Geruggesteund door haar ervaring kan onze afdeling Animatie u een team van jonge en/of ervaren mensen ter beschikking stellen voor verschillende acties:
							<br><br>
								- degustaties<br>
								- sampling<br>
								- verkooppromoties<br>
								- productvoorstellingen<br>
								- wedstrijden
							<br><br>
								Als vaste waarde in verkooppunten garanderen onze animatoren en animatrices een synergie en vlotte samenwerking met de afdelingsverantwoordelijken.<br>
';
			break;
		### page projet
			case "projet" :
				$titre = "LOPENDE PROJECTEN";
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
				$titre = "MATERIALEN";
				$texte1 = "Wij leveren alles wat u nodig heeft om uw animaties vlot te laten verlopen.
				<p>-	Standen, ovens, kookplaten,... <br>
				  -	Verbruiksgoederen zoals servetten, tafellinnen, tandenstokers, bestek, ...<br>
				  -	Uniformen";
			break;
		### page contact
			case "contact" :


			break;
		### page philo
			case "philo" :
			default:
				$titre = "VOORSTELLING";
				$texte1 = '
							Voor uw <b><i>animaties</b></i> en <b><i>verkooppromoties</b></i> stellen wij u <b><i>professionals</b></i>  ter beschikking die door en door vertrouwd zijn met verkoopstechnieken en voor wie het organiseren van <b><i>degustaties</b></i> en productvoorstellingen al lang geen geheimen meer inhoudt.
						<br><br>
							Geselecteerd voor hun kwaliteiten en uw behoeften nemen onze animatoren en animatrices de rol van ambassadeur van uw bedrijfslogo op zich.<br>
							Zij zullen u in alle openheid in hun animatierapport de resultaten meedelen van de productvoorstelling en degustaties, maar ook de commentaren en reacties van consumenten en verkoopsverantwoordelijken vermelden, zodat u steeds het overzicht behoudt op het terrein.
						<br><br>
							Naast het selecteren van de geschikte kandidaten stelt <a href="../structure/index1.php?section=animation&page=contact&lang=nl" target="site">ons administratief team</a> alles in het werk om ervoor te zorgen dat de geselecteerde kandidaten hun taak in optimale omstandigheden kunnen volbrengen.
						<br><br>
							Dankzij een krachtige internetapplicatie <b><i>kunt u iedere dinsdag met uw login op het internet de informatie uit de rapporten van onze animatoren raadplegen.</b></i> <br>
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
				$titre = "ONZE DIENSTEN";
				$texte1 = "Onze afdeling Merchandising kan u personeel en teams ter beschikking stellen voor een brede waaier van opdrachten zoals:
					<p>-	Opnemen van bestellingen<br>
					  -	Rack jobbing<br>
					  -	Full filling<br>
					  -	Organiseren van blitzpromoties, gondelhoofden<br>
					  -	Store check<br>
					  -	Beveiliging<br>
					  -	Price audit<br>
					  -	Plaatsing van PLV-materiaal<br>
					  -	Outsourcing (verkopersteam, &#8230;)<br>
					  - &#8230;.";
			break;
		### page projet
			case "projet" :
				$titre = "LOPENDE PROJECTEN";
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
				$titre = "VOORSTELLING";
				$texte1 = 
							'Of het nu om &laquo; groothandel &raquo; of &laquo; kleinhandel &raquo;gaat, onze afdeling Merchandising organiseert en beheert de planning van onze <b><i>topspecialisten</b></i> van de productlancering zowel op het niveau van de bevoorrading als voor de inrichting.
						<br><br>
							Naast die traditionele taken hebben wij onze expertise op het vlak van controle op verkooppunten verder uitgebreid. We hebben het hier over <b><i>store checking</b></i>, de <b><i>price auditing</b></i>
							en <b><i>beveiliging.</b></i>
						<br><br>
							Deze afdeling kan uw verwachtingen verder inlossen door u voor middellange en langetermijnopdrachten een <b><i>team van promotiespecialisten</b></i> ter beschikking te stellen die uw verkoopteam zullen bemannen.
						<br><br>
							Met behulp van een softwareprogramma op uw maat neemt ons <a href="../structure/index1.php?section=merchandising&page=contact&lang=nl" target="site">team van interne medewerkers</a> het beheer van die verschillende activiteiten voor zijn rekening.<br>
							Op het internet kunt u in alle vertrouwelijkheid een <b><i>activiteitenverslag</b></i> raadplegen dat naar uw behoeften en wensen wordt opgemaakt.
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
							<a href="page.php?section=left&page=plan2&lang=nl" target="main" class="minilien">Zoom Out</a><br><br>
							<img src="../images/carte-in.gif" width="427" height="354"><br>
							<br>
							Jachtlaan 195 &nbsp; B-1040 Brussel ( Etterbeek )<br><br>
							</div>
					';
			break;
		### page plan 1 petit
			case "plan2" :
				$titre = "PLAN";
				$texte1= '<div align="center">
							<a href="page.php?section=left&page=plan1&lang=nl" target="main" class="minilien">Zoom In</a><br><br>
							<img src="../images/carte-out.gif" width="427" height="354"><br>
							<br>
							Jachtlaan 195 &nbsp; B-1040 Brussel ( Etterbeek )<br><br>
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
				$titre = "VOORSTELLING";
				$texte1 = '
					Welkom op de site van Exception&sup2;<br>



					<br>
					Exception&sup2; legt zich al jaren toe op het uitzenden van VIP-hostessen en promoboys. Het bedrijf werd opgericht in 1987 en is sindsdien blijven groeien om op de meest professionele manier op de marktvraag te kunnen inspelen.<br>
					<br>
					Vandaag kunt u een beroep doen op 10 <a href="../structure/index1.php?section=accueil&page=contact&lang='.$lang.'" target="site">specialisten</a> uit 3 verschillende afdelingen die op de meest effici&euml;nte manier en met een hooggeavanceerd softwareprogramma een pasklare oplossing zullen vinden voor alle individuele vragen over <a href="../structure/index1.php?section=vip&page=presentation&lang='.$lang.'" target="site">VIP</a>, <a href="../structure/index1.php?section=animation&page=presentation&lang='.$lang.'" target="site">verkoopsanimaties</a>, <a href="../structure/index1.php?section=merchandising&page=presentation&lang='.$lang.'" target="site">merchandising</a> en <b>outsourcing</b>.<br>
					<br>
					Deze specialisten op het gebied van &laquo; People&rsquo;s Management &raquo; zouden echter nergens zijn zonder de hulp van de mensen uit ons vast personeelsbestand die wij u ter beschikking stellen.<br>
					<br>
					Beschikbaarheid, professionalisme, stiptheid en een goed humeur zijn de handelsmerken van ons agentschap en van het personeel dat voor ons werkt... en voor &uacute;!<br>
				';
			break;
		}
	break;
}


if ($_GET['page'] == "contact" ) {
	$titre = "CONTACTEER";
	$contact_direction = "Directie";
	$contact_direction_vip = "Directie van de VIP-afdeling";
	$contact_planning = "Planning";
	$contact_direction_com = "Directie &amp; Commerci&euml;le";
	$contact_Social = "Social Secretary";
	$contact_info = "Informatie";
	$contact_plus_info = "Voor meer informatie, klikt u op de naam van de desbetreffende persoon.";
}


if (($_GET['page']) or ($_GET['page'])) {
	switch ($_GET['page']){

		### page Espace Clients
			case "espaceclients" :
				$titre = "KLANTENZONE";
				$texte1 = '
							<li>
								<b><i>U bent nog geen klant</b></i>  van Exception&sup2; , maar u heeft wel belangstelling voor de inhoud van onze website?<br>
								Aarzel dan niet om ons aan de hand van <a href="clientnew.php?lang='.$lang.'&l='.$lang.'" target="_blank">dit formulier</a> een offerteaanvraag toe te sturen. Wij zullen u binnen 24 uur contacteren! 
							<br><br><br>
							<li>
								<b><i>U bent reeds klant</b></i>, <a href="client.php?lang='.$lang.'&l='.$lang.'" target="_blank">klik</a> dan hier om toegang te krijgen tot onze beveiligde ruimte. 
								<br><br>
								Als klant heeft u rechtstreeks toegang tot een <i><b>rijke bron van informatie die relevant is voor uw bedrijf.</i></b><br>
								Een van uw facturen is zoek geraakt, geen nood, een historiek van bestellingen, offertes en facturen is steeds beschikbaar zodat u het ontbrekende document moeiteloos kunt terugvinden en afdrukken. 							
								<br><br>
								U vindt er iedere dinsdagochtend ook de complete resultaten en rapporten van de weekendanimatie en een hoop andere dingen.
							<br><br><br>
							<li>
								<b><i>U bent klant, maar u heeft nog geen login</b></i>, geen nood, vul gewoon het <a href="clientpass.php?lang='.$lang.'&l='.$lang.'" target="_blank">gepaste formulier</a> in en u krijgt uw login weldra toegestuurd. 
						';
			break;
		### page Espace Jobistes
			case "espacejobistes" :
				$titre = "JOBISTENZONE";
				$texte1 = 
							'<li>
								U bent dynamisch, een werker, punctueel, open... en u wilt ons VIP-team van hostessen en promoboys komen versterken?<br><br>
								Fantastisch! Dan hoeft u alleen maar het <a href="peoplenew.php?lang='.$lang.'&l='.$lang.'" target="_blank">formulier</a> in te vullen en te verzenden.<br><br>
								Wij raden u wel aan om zo snel mogelijk eens tot bij ons te komen, want zoals u weet kan niets in onze communicatiemaatschappij het &laquo;persoonlijk contact&raquo; vervangen.
							<br><br><br>
							<li>
								 U bent al ingeschreven. Op <a href="people.php?lang='.$lang.'&l='.$lang.'" target="_blank">deze pagina</a> kunt u ingeven wanneer u <i><b>beschikbaar bent</i></b>, wijzigingen aanbrengen in uw profiel, <i><b>uw volgende contract afdrukken</i></b>, <i><b>onze jobaanbiedingen raadplegen</i></b> &#8230;
							';
			break;
	}

}	
?>