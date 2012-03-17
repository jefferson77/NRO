<?php
# Page de variables pour texte et illu de tout le site
switch ($_GET['section']){
	### SECTION VIP
	case "vip" :
		switch ($_GET['page']){
		### page Nos services
			case "nosservices" :
				$titre = "OUR SERVICES";
				$texte1 = '
					Exception&sup2; VIP places much emphasis on supporting your events!
					<br><br>Thanks to expertise acquired over the years, our VIP department can put <b><i>qualified teams</i></b> at your disposal for activities such as: <br>
					<br>
					<ul>
					<li> VIP greetings, registration and badge distribution during events, trade shows or congresses
					<li> event support (bar, checkroom, valets, drivers, etc.)
					<li> promotional activities, taste tests or samplings
					<li> street actions
					<li> supervision
					<li> night actions
					</ul>
					<br>
					Each event is co-ordinated according to <i><b>your brand image and your quality standards.</i></b><br>
				';
			break;
		### page projet
			case "projet" :
				$titre = "CURRENT PROJECTS";
				$texte1 = "Projets en cours";
			break;
		### page news
			case "news" :
				$titre = "NEWS";
				$toinclude="news.php";
			break;
		### page tenues
			case "tenues" :
				$titre = "UNIFORMS";
				$texte1 = '
						We have both classic and conceptual uniforms at your disposal.<br>
						They are the symbol of the VIP service, all while representing the brand image of your company.
						';	
				$texte2 = 'Tailleur Gris';
				$texte3 = 'Black suit - Purple shirt';
				$texte4 = 'Black suit - Fuchsia shirt';
				$texte5 = 'Black suit - Emerald shirt';
				$texte6 = 'Blue suit';
				$texte7 = 'Tailleur Rouge';
				$texte8 = 'Suit';
				$texte9 = 'Shirts';
			break;
		### page contact
			case "contact" :


			break;
		### page présentation
			case "presentation" :
			default:
				$titre = "PRESENTATION";
				$texte1 = '
								Our VIP department provides you with services for all your events and activities requiring young, dynamic and enthusiastic personnel.
							<br><br>
								<b><i>They will become the ambassadors of your company and your brands.</b></i>
							<br><br>
								To this end, the selection of our hosts and hostesses is done with the utmost care and on the basis of their knowledge in languages, skills, sense of initiative, etc. 
							<br><br>
								This rigorous selection according to your own criteria is the work of <a href="../structure/index1.php?section=vip&page=contact&lang=en" target="site">four truly passionate people</a> at the heart of our agency. They make sure that each team put to the service of clients is made up of warm, smiling, creative and energetic people. 
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
				$titre = "OUR SERVICES";
				$texte1 = '
								Using its expertise, the demonstrations department can provide you with young and/or experienced teams for events such as:
							<br><br>
								- taste tests<br>
								- samplings<br>
								- sales promotion events<br>
								- technical product descriptions<br>
								- contests
							<br><br>
								The recurring presence of our presenters at points of sales guarantees a synergy and an excellent collaboration with those responsible for stocking.<br>
';
			break;
		### page projet
			case "projet" :
				$titre = "CURRENT PROJECTS";
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
				$titre = "MATERIAL";
				$texte1 = "According to your needs, we can provide you with the necessary materials for the proper working of your demonstrations:
					<p>-Stands, ovens, burners, etc.<br>
					  -	Consumables such as napkins, tablecloths, tooth picks, spoons, etc.<br>
					  -	Uniforms

				";
			break;
		### page contact
			case "contact" :


			break;
		### page philo
			case "philo" :
			default:
				$titre = "PRESENTATION";
				$texte1 = '

							For your <b><i>demonstrations</b></i> and <b><i>sales promotions</b></i>, our department provides sales-driven, <b><i>proven professionals</b></i> who can organise <b><i>taste tests</b></i> and present products without any problems.
							<br><br>
							Selected according to their qualities and your needs, our presenters will become the ambassadors of your brand.
							<br>
							In all transparency, they will give you the results of the sales and taste tests in their demonstration report as well as the comments and responses of the consumers and of those responsible for the point of sales, providing you will a concrete view of the on-site situation.
							<br><br>
							Besides selecting personnel for you, the role of <a href="../structure/index1.php?section=animation&page=contact&lang=en" target="site">our administrative team</a>, is to do everything possible so that the personnel can accomplish their assignment under the best possible conditions.
						<br><br>
							Every Tuesday and by using your login name, <b><i>you will be able to consult on-line the information contained in the reports written up by our presenters</b></i> thanks to our powerful IT tool.<br>
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
				$titre = "OUR SERVICES";
				$texte1 = "Our merchandising department can have personnel or teams at your disposal for assignments such as:
					<p>-	Order taking<br>
					  -	Rack jobbing<br>
					  -	Fulfilment<br>
					  -	Blitzes, end aisles displays<br>
					  -	Store checks<br>
					  -	Securing<br>
					  -	Price audits<br>
					  -	POS placements<br>
					  -	Outsourcing (sales force, etc.)<br>
					  - And more
					";
			break;
		### page projet
			case "projet" :
				$titre = "CURRENT PROJECTS";
				$texte1 = "Current projects";
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
							'Whether it is for major distribution or retail, our merchandising department manages and organises the planning of true<b><i>product placement specialists</b></i> for supplies as well as settings.
						<br><br>
							Besides these traditional tasks, we have extended our expertise in the field of assignments related to point of sales monitoring, such as <b><i>store checking assignments</b></i>, de <b><i>price auditing</b></i>
							and <b><i>securing.</b></i>
						<br><br>
							Finally, this department will also be able to meet your expectations by providing you with <b><i>real teams of promoters</b></i> who will become your sales force for mid-term and long-term assignments.
						<br><br>
							All these activities are managed by our <a href="../structure/index1.php?section=merchandising&page=contact&lang=en" target="site">internal team</a> using an IT tool adapted to your needs.<br>
							An <b><i>activity report</b></i>, established according to your needs, can be consulted on line and in all confidentiality.
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
							<a href="page.php?section=left&page=plan2&lang=en" target="main" class="minilien">Zoom Out</a><br><br>
							<img src="../images/carte-in.gif" width="427" height="354"><br>
							<br>
							195 Avenue de la Chasse &nbsp; B-1040 Brussels ( Etterbeek )<br><br>
							</div>
					';
			break;
		### page plan 1 petit
			case "plan2" :
				$titre = "PLAN";
				$texte1= '<div align="center">
							<a href="page.php?section=left&page=plan1&lang=en" target="main" class="minilien">Zoom In</a><br><br>
							<img src="../images/carte-out.gif" width="427" height="354"><br>
							<br>
							195 Avenue de la Chasse &nbsp; B-1040 Brussels ( Etterbeek )<br><br>
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
					Welcome to the Exception&sup2; site<br>
					<br>
					Established in 1987, the Exception&sup2; agency, experienced and specialised in VIP hostesses and promoboys is continuously growing in order to cater to the demands of the market in the most professional way possible.<br>
					<br>
					Today, 10 <a href="../structure/index1.php?section=accueil&page=contact&lang='.$lang.'" target="site">specialists</a> in three departments, equipped with a state-of-the-art IT tool are available to efficiently fulfil all your personnel requests, be it for <a href="../structure/index1.php?section=vip&page=presentation&lang='.$lang.'" target="site">VIP</a>, <a href="../structure/index1.php?section=animation&page=presentation&lang='.$lang.'" target="site">demonstrations</a>, <a href="../structure/index1.php?section=merchandising&page=presentation&lang='.$lang.'" target="site">merchandising</a> or <b>outsourcing</b>.<br>
					<br>
					These people management professionals could not do their job without the vast file of personnel they are able to put at your disposal.<br>
					<br>
					Availability, professionalism, punctuality and good humour are the trademarks of our agency as well as for the personnel working for us and for you.<br>
				';
			break;
		}
	break;
}

if ($_GET['page'] == "contact" ) {
	$titre = "CONTACT";
	$contact_direction = "Management";
	$contact_direction_vip = "VIP department manager";
	$contact_planning = "Planning";
	$contact_direction_com = "Management &amp; Sales";
	$contact_Social = "Social Secretary";
	$contact_info = "Contact Informations";
	$contact_plus_info = "For more information, click on the person&#8217;s name";
}


if (($_GET['page']) or ($_GET['page'])) {
	switch ($_GET['page']){


		### page Espace Clients
			case "espaceclients" :
				$titre = "CLIENT AREA";
				$texte1 = '
							<li>
								<b><i>If you are not yet an Exception&sup2; client</b></i> but the content of our site has caught your attention, please use the document you will find by
								<a href="clientnew.php?lang='.$lang.'" target="_blank">clicking here</a> to send us your request for quotation, which we will gladly answer within 24 hours
							<br><br><br>
							<li>
								<b><i>If you are a client</b></i>, a simple <a href="client.php?lang='.$lang.'" target="_blank">click</a> will bring your to our secured area.
								<br><br>
								The latter offers you<i><b>a direct access to various information exclusively related to your company.</i></b><br>
								If one of our invoices or quotations has been misplaced, an order history, quotations and accounting are indeed available to allow you to reprint the missing document.							
								<br><br>
								Every Tuesday morning you will also be able to consult the complete results and reports of the demonstrations of the previous weekend and much more.
							<br><br><br>
							<li>
								<b><i> If you are a client but do not yet have a login name</b></i>,	 just fill out <a href="clientpass.php?lang='.$lang.'" target="_blank">this form</a> and it will be sent to you shortly.
						';
			break;
		### page Espace Jobistes
			case "espacejobistes" :
				$titre = "WORKER AREA";
				$texte1 = 
							'<li>
								You are dynamic, hard-working, punctual, extroverted and would like to join our teams of VIP hostesses or promoboys?<br><br>
								Great! Just fill out <a href="peoplenew.php?lang='.$lang.'" target="_blank">the form</a> and return it to us.<br><br>
								We highly advise you to come by the agency as soon as possible, since as you know in the world of communication, nothing replaces meeting face-to-face.
							<br><br><br>
							<li>
								If you are already registered, <a href="people.php?lang='.$lang.'" target="_blank">this area</a>  will allow you to tell us of your <i><b>availability</i></b>, make any modifications to your personal file <i><b>print out your next contract</i></b>, consult our <i><b>job offers</i></b> and more.
							';
			break;
	}
}	

?>