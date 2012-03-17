<?php
setlocale(LC_TIME, 'nl_NL');

#!!!
#Debut Page "modifiche_3.php"
include(NIVO."conf/pays.php");
#Fin Page "modifiche_3.php"


# Page de variables pour texte et illu de tout le site

#	$titre_00 = "JOBSTUDENTEN";
	$titre_00     = "JOBISTEN";
	$titre_new_00 = "Inschrijving";

# Debut mots OUTILs "toutes pages"
	$tool_00 = "";
	$tool_01 = "Gebruiksaanwijzing";
	$tool_02 = "Bevestigen";
	$tool_03 = "Doorgaan";
	$tool_04 = "Nee";
	$tool_05 = "Ja";
	$tool_06 = "Be&euml;indigen";
	$tool_07 = "Teruggaan";
	$tool_08 = "Verzenden";
	$tool_09 = "Afdrukken";
	$tool_10 = "Legende";
	$tool_11 = "Wijzigen";
	$tool_12 = "Toevoegen";
	$tool_13 = "Maken";


	$tool_20 = "Opdracht";
	$tool_21 = "Datum";
	$tool_22 = "Klant";
	$tool_23 = "Locatie";
	$tool_24 = "Afdrukken";
	$tool_25 = "Job";
	$tool_26 = "Referentie";
	$tool_27 = "Week";

	$tool_30 = "Ma";
	$tool_31 = "Di";
	$tool_32 = "Wo";
	$tool_33 = "Do";
	$tool_34 = "Vr";
	$tool_35 = "Za";
	$tool_36 = "Zo";

	$tool_37 = "* Date a laquelle les contrats seront disponibles";
# Fin mots OUTILs "toutes pages"


# Debut Page "menu.php"
	$menu_00 = "Welkom op de beveiligde omgeving van Exception";
	$menu_01 = "Persoonsgegevens";
	$menu_02 = "Uw persoonsgegevens en beschikbaarheid bijwerken";
	$menu_03a = "Fiche is niet beschikbaar";
	$menu_03 = "Uw fiche wordt momenteel verwerkt door een medewerker van Exception&sup2;.  Uw fiche zal weldra opnieuw beschikbaar zijn.";
	$menu_04 = "Fiche bijwerken";
	$menu_05 = "Beschikbaarheid";
	$menu_06 = "Afdrukken";
	$menu_07 = "Werkdocumenten van Exception&sup2; afdrukken";
	$menu_08 = "Contract voor animatie";
	$menu_09 = "Contract voor merchandising";
	$menu_10 = "Contract voor VIP";
	$menu_11 = "Weldra beschikbaar";
	$menu_12 = "Planning voor EAS";
	$menu_13 = "Tewerkstellingattest";
	$menu_14 = "Loonbrieven";
	$menu_15 = "Arbeidsreglement";


# Fin Page "menu.php"

# Debut Page "newmenu.php"
	$menu_new_01 = "Inschrijvingsformulier";
	$menu_new_02 = "Klik op de onderstaande link om toegang te krijgen<br> tot het on-line-inschrijvingsformulier";

	$menu_new_03 = "Welkom";
	$menu_new_04 = "
		<b>Welkom op de beveiligde ruimte van Exception&sup2;.</b>
		<br><br> Zodra uw inschrijving door een verantwoordelijke van Exception&sup2; is bevestigd, krijgt u toegang tot de momenteel uitgegrijsde menu&rsquo;s waarmee u:
		<br><br>
		<ul>
		<li>Uw beschikbaarheid kunt aanduiden, </li>
		<li>Uw resultaten kunt afdrukken, </li>
		<li>actualiteiten en jobaanbiedingen kunt raadplegen, </li>
		<li>etc.</li></ul>";
# Fin Page "newmenu.php"

# Debut Page "v-detail.php" / Mise à jour de la fiche
	$detail_00 = "Fiche bijwerken";
	$detail_01 = "Inleiding";
	$detail_02 = "Contacten";
	$detail_03 = "Algemene informatie";
	$detail_04 = "Sociaal secretariaat";
	$detail_05 = "Foto";
	$detail_06 = "Bevestiging";

	$detail_07 = "";
	$detail_08 = "";
	$detail_09 = "";

	$detail_10 = "Contract voor VIP";

# Debut Page "v-detail.php" : section 0  / Mise à jour de la fiche : Introduction
	$detail_0_01 = "Op deze ruimte kunt u <b>zich inschrijven in de database van jobstudenten</b> van Exception2.";
	$detail_0_02 = "Op deze ruimte kunt u <b>uw fiche van jobstudent bij Exception&sup2; bijwerken</b>.";
	$detail_0_03 = "Vul de volgende formulieren nauwkeurig in. <b>Wees zo volledig mogelijk!</b> <br><br><span class='error_info'>Opgelet: uw fiche zal slechts worden verzonden als u de inschrijvingsprocedure volledig hebt voltooid! </span><br><br>Bedankt en tot binnenkort!<div align='right'>Het Exception&sup2;-team</div>";
# Fin Page "v-detail.php" : section 0  / Mise à jour de la fiche : Introduction

# Debut Page "v-detail.php" : section 1  / Mise à jour de la fiche : Contacts
	$detail_1_01 = "Geslacht";
	$detail_1_02 = "Naam";
	$detail_1_03 = "Voornaam";
	$detail_1_04 = "Adres";
	$detail_1_05 = "Bus";
	$detail_1_06 = "Postcode";
	$detail_1_07 = "Stad";
	$detail_1_08 = "Land";
	$detail_1_09 = "Tel";
	$detail_1_10 = "Fax";
	$detail_1_11 = "GSM";
	$detail_1_12 = "E-mail";
	$detail_1_13 = "Login voor toegang tot webruimte";
	$detail_1_14 = "Wachtwoord";
	$detail_1_15 = "wachtwoord voor toegang tot webruimte - tussen 4 en 10 tekens";
# Fin Page "v-detail.php" : section 1  / Mise à jour de la fiche : Contacts

# Debut Page "v-detail.php" : section 2  / Mise à jour de la fiche : Infos Générales
	$detail_2_01    = "Uiterlijk";
	$detail_2_02_01 = "European";
	$detail_2_02_02 = "Mediterraans";
	$detail_2_02_03 = "Zwart";
	$detail_2_02_04 = "Aziatisch";
	$detail_2_03    = "Haarkleur";
	$detail_2_04_01 = "Blond";
	$detail_2_04_02 = "Bruin";
	$detail_2_04_03 = "Zwart";
	$detail_2_04_04 = "Kastanjebruin";
	$detail_2_04_05 = "Ros";
	$detail_2_05    = "Haarlengte";
	$detail_2_06_01 = "Lang";
	$detail_2_06_02 = "Halflang";
	$detail_2_06_03 = "Kort";
	$detail_2_06_04 = "Kaalgeschoren";
	$detail_2_07    = "Lengte";
	$detail_2_08    = "Maat van jas";
	$detail_2_09    = "Maat van rok";
	$detail_2_10    = "Schoenmaat";
	$detail_2_11    = "Rijbewijs";
	$detail_2_12    = "Auto";
	$detail_2_13    = "Talen";
	$detail_2_14    = "Borstmaat";
	$detail_2_15    = "Heupmaat";
	$detail_2_16    = "Taillemaat";
	$detail_2_17    = "Rookt";
	$detail_2_18    = "Informaticus";

# Fin Page "v-detail.php" : section 2  / Mise à jour de la fiche : Infos Générales

# Debut Page "v-detail.php" : section 3  / Mise à jour de la fiche : Secrétariat Social
	$detail_3_01    = "Geboortegegevens";
	$detail_3_02    = "Datum";
	$detail_3_03    = "Postcode - Stad";
	$detail_3_04    = "Land";
	$detail_3_05    = "Nationaliteit";
	$detail_3_06    = "Gezinsgegevens";
	$detail_3_07    = "Burgerlijke staat";
	$detail_3_08_01 = "Vrijgezel";
	$detail_3_08_02 = "Gehuwd";
	$detail_3_08_03 = "Weduwe/weduwnaar";
	$detail_3_08_04 = "Uit de echt gescheiden";
	$detail_3_08_05 = "Gescheiden";
	$detail_3_09    = "Huwelijksdatum";
	$detail_3_10    = "Naam echtg.";
	$detail_3_11    = "Geboortedatum echtg.";
	$detail_3_12    = "Beroep echtg.";
	$detail_3_14    = "Personen ten laste";
	$detail_3_15    = "Kinderen ten laste";
	$detail_3_16    = "Documenten";
	$detail_3_17    = "Nr. identiteitskaart";
	$detail_3_18    = "Rijksregisternummer";
	$detail_3_19    = "Betalingswijze";
	$detail_3_20    = "Rekening";
	$detail_3_21    = "Neem contact op met Exception2 (Tel: 02 732 74 40) voor betalingen naar het buitenland.";
# Fin Page "v-detail.php" : section 3  / Mise à jour de la fiche : Secrétariat Social

# Debut Page "v-detail.php" : section 4  / Mise à jour de la fiche : Photo
	$detail_4_01 = '
				1. U dient te beschikken over een degelijke pasfoto in elektronisch formaat.
				<br>Deze foto moet in .jpg of .jpeg-formaat zijn met een minimumafmeting van 300 pixels x 300 pixels en een maximum van 800 pixel x 600 pixel.
				<br>Elke foto met een grote boven de 250 kb (0,25 Mb) zal vernietigd worden.
				<br><br>Zoek deze foto met behulp van de knop <input type="file" name="exemple" size="1"> (in de linkerruimte).
				<br><br>
				3. Selecteer het document door op de naam van het bestand te klikken. <br><br>
				4. Klik op de knop <input type="submit" value="Verzenden">. <br><br>
				5. Ga naar de tweede foto als u er nog een wilt verzenden <br><br>
				6. Als u tevreden bent met de foto(s), klikt u op de knop Bevestigen om de selectieprocedure voor de foto te be&euml;indigen.';
	$detail_4_02 = "De foto die u wilt verzenden moet in .jpg of .jpeg-formaat zijn.";
	$detail_4_03 = "De verzonden foto is te klein, <br>kies een andere foto.";
	$detail_4_03a = "De verzonden foto is te groot, <br>kies een andere foto.";
	$detail_4_04 = "De foto is te klein.";
	$detail_4_04a = "De foto is te groot.";
	$detail_4_05 = "Rekening";
	$detail_4_06 = "Oude";
	$detail_4_07 = "Nieuwe";
	$detail_4_08 = "Idee";
	$detail_4_09 = "Als je al een foto in Foto 1 hebt, gerbruik maar Foto 2 !";
	$detail_4_10 = "Opgelet ! Uw inschrijving is niet geldig zonder de twee fotos !";
	$detail_4_11 = "U moet een profielfoto en een portretfoto downloaden op uw fiche.(foto dat U zou gebruiken voor een cv)";
	$detail_4_12 = "Persoonlijke foto";
	$detail_4_13 = "Foto 1 : portretfoto";
	$detail_4_14 = "Foto 2 : staande  profielfoto";
	$detail_4_15 = "Er is nog geen foto 1 in uw profiel.";
	$detail_4_16 = "Er is nog geen foto 2 in uw profiel.";
	$detail_4_17 = "Nieuwe foto 1";
	$detail_4_18 = "Nieuwe foto 2";
	$detail_4_19 = "Progressie :";

# Fin Page "v-detail.php" : section 4  / Mise à jour de la fiche : Photo

# Debut Page "v-detail.php" : section 5  / Mise à jour de la fiche : Validation
	$detail_5_01 = "<b>Welkom bij Exception2 !</b><br> <br> Uw fiche zal slechts worden verzonden als u de inschrijvingsprocedure volledig hebt voltooid en op &quot;Be&euml;indigen&quot; hebt geklikt.<br> Wij vragen u om 48u te wachten, om toegang te krijgen tot u persoonlijke fiche op onze site.<br> <br> Op donderdag geven wij een infossesie op ons kantoor, dit kan om 11u stipt of om 14u stipt, deze infossesie is verplicht.<br> <br> Exception Team.";
	$detail_5_02 = "Bedankt voor het bijwerken van uw fiche van jobstudent bij Exception2. <br>Opgelet: uw fiche zal slechts worden verzonden als u de inschrijvingsprocedure volledig hebt voltooid en op Be&euml;indigen hebt geklikt.";
	$detail_5_03 = "Tot binnenkort, <br><br>het Exception&sup2;-team";
	$detail_5_04 = "Ik heb kennis genomen met het ";
	$detail_5_05 = "Veuillez accepter le règlement de travail pour cloturer votre inscription";

# Fin Page "v-detail.php" : section 5  / Mise à jour de la fiche : Validation

# Fin Page "v-detail.php" / Mise à jour de la fiche

# Debut Page "contratanim.php" + "contratmerch.php" + "contratvip.php" + 'easmerch.php"
	$contrat_00 = "Welkom op de beveiligde omgeving van Exception";
	$contrat_02 = 'Cliquez sur (<img src="'.STATIK.'illus/minipdf.gif" width="16" height="16">) pour afficher la contrat.<br> Utilisez ensuite la fonction IMPRIMER de votre navigateur.';
	$contrat_03 = 'Er wordt een bestand in .pdf-formaat (<img src="'.STATIK.'illus/minipdf.gif" width="16" height="16">) gemaakt dat die geselecteerde contract bevat.';
	$contrat_08 = ".pdf-bestand, standaard afdrukformaat.";
	$contrat_09 = "Uw contracten zijn steeds twee dagen voor de te leveren prestatie online beschikbaar.";

	$contrat_anim_00 = "Contracten afdrukken: Animatiesector";
	$contrat_anim_01 = "Lijst van contracten voor animatie";

	$contrat_merch_00 = "Contracten afdrukken: Merchandisingsector";
	$contrat_merch_01 = "Lijst van contracten voor merchandising";

	$contrat_vip_00 = "Contracten afdrukken: VIP-sector";
	$contrat_vip_01 = "Lijst van contracten voor VIP";

	$eas_merch_00 = "EAS Planning afdrukken: Merchandisingsector";
	$eas_merch_01 = "Lijst van EAS Planning voor merchandising";

# Fin Page "contratanim.php" + "contratmerch.php" + "contratvip.php" + 'easmerch.php"

# Debut Page "dispo.php"
	$dispo_00 = "Beschikbaarheid";
	$dispo_01 = "U kunt uw beschikbaarheid voor de gekozen maand wijzigen.";
	$dispo_02 = "Om uw beschikbaarheid voor een bijkomende maand in te voeren.";
	$dispo_03 = "U kunt uw beschikbaarheid slechts ".@$nbrmois." maanden op voorhand invoeren.";
	$dispo_04 = "Kruis hieronder de periodes aan waarin uw beschikbaar bent.";
	$dispo_05 = "In deze ruimte kunt u ons uw beschikbaarheid bekendmaken.";
	$dispo_06 = "Gebruik de 'Double-Clic' om een volledige dag te selecteren";
# Fin Page "dispo.php"

# Debut Page "attest.php"
	$attest_00 = "gedekte periode van dit werkattest";
	$attest_01 = "van";
	$attest_02 = "tot";
	$attest_03 = "de begindatums moeten begrepen zijn tussen ";
	$attest_04 = "bv";
	$attest_05 = " en ";
	$attest_06 = "Al de datums buiten deze periode worden ontweken";
# Fin Page "attest.php"

# Debut Page "281.10.php"
	$f281_01 = "Inkomen";
	$f281_02 = "Uw fiche 281.10 heb je nodig om uw belastingsbrief in te vullen.";
# Fin Page "281.php"

# Debut Page "ficheSalaire.php"
	$fichesalaire_01 = "Loonbrief ";

# Debut Page ANIM "sales.php"
	$sales_00 = "Verkooprapport";
	$sales_01 = "Mission";
	$sales_02 = "Week";
	$sales_03 = "Datum";
	$sales_04 = "Opmerking Jobiste";
	$sales_05 = "Nota winkel";
	$sales_06 = "Andere animaties";
	$sales_07 = "Ja";
	$sales_08 = "Neen";
	$sales_09 = "Naar Exception&sup2; doorsturen";

	$sales_prod_01 = "Produkt";
	$sales_prod_02 = "Stock begin";
	$sales_prod_03 = "Stock einde";
	$sales_prod_04 = "Verkoop";
	$sales_prod_05 = "Ruptuur";
	$sales_prod_06 = "Degustatie";


	$sales_menu_01 = "Consultatie";
	$sales_menu_02 = "Uw verkoopsrapport is consulteerbaar vanaf de datum van de aktie tot de dinsdag volgend &nbsp;13:00 uur.";
	$sales_menu_03 = "Intikken: : opdrachten (km en uren)";
	$sales_menu_04 = "Tik al uw informaties over van uw origineel rapport en valideer daarna.";
	$sales_menu_05 = "Intikken : produkten";
	$sales_menu_06 = "Tik al uw verkoopsgegevens in , valideer en tik de volgende gegevens in, valideer na elk produkt.";
	$sales_menu_07 = "Controle";
	$sales_menu_08 = "kijk goed na wat je ingetikt hebt en valideer definitief om naar Exception&sup2; door te sturen..";
	$sales_menu_09 = "Envoyer chez Exception";
	$sales_menu_10 = "Indien jullie zeker zijn dat alles kompleet is, druk dan op &ldquo;".$sales_09."&rdquo;";
	$sales_menu_11 = "Hartelijk dank !";


# Fin Page ANIM "sales.php"

?>