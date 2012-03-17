<?php
setlocale(LC_TIME, 'fr_FR');

include(NIVO."conf/nationalites.php");
include(NIVO."conf/pays.php");

# Page de variables pour texte et illu de tout le site
    $titre_00 = 'JOBISTES';
    $titre_new_00 = 'Inscription';

# Debut mots OUTILs "toutes pages"
    $tool_00 = "";
    $tool_01 = "Mode d&rsquo;Emploi";
    $tool_02 = "Valider";
    $tool_03 = "Continuer";
    $tool_04 = "Non";
    $tool_05 = "Oui";
    $tool_06 = "Terminer";
    $tool_07 = "Retour";
    $tool_08 = "Envoyer";
    $tool_09 = "Impression";
    $tool_10 = "L&eacute;gende";
    $tool_11 = "Modifier";
    $tool_12 = "Ajouter";
    $tool_13 = "Cr&eacute;er";


    $tool_20 = "Mission";
    $tool_21 = "Date";
    $tool_22 = "Client";
    $tool_23 = "Lieux";
    $tool_24 = "Impression";
    $tool_25 = "Job";
    $tool_26 = "R&eacute;f&eacute;rence";
    $tool_27 = "Semaine";

    $tool_30 = "Lu";
    $tool_31 = "Ma";
    $tool_32 = "Me";
    $tool_33 = "Je";
    $tool_34 = "Ve";
    $tool_35 = "Sa";
    $tool_36 = "Di";

    $tool_37 = "* Date a laquelle les contrats seront disponibles";

# Fin mots OUTILs "toutes pages"


# Debut Page "menudefaut.php"
    $menu_00 = "Bienvenue dans l&rsquo;espace s&eacute;curis&eacute; d&rsquo;Exception";
    $menu_01 = "Informations Personnelles";
    $menu_02 = "Mise &agrave; jour de vos informations personnelles et de vos disponibilit&eacute;s";
    $menu_03a = "Fiche indisponible";
    $menu_03 = "Votre fiche est actuellement en cours de traitement par un responsable d&rsquo;Exception&sup2;.  Elle sera de nouveau disponible d&rsquo;ici peu.";
    $menu_04 = "Mise &agrave; jour de la fiche";
    $menu_05 = "Disponibilit&eacute;";
    $menu_06 = "Impressions";
    $menu_07 = "Impression des documents de travail d&rsquo;Exception&sup2;";
    $menu_08 = "Contrat Animation";
    $menu_09 = "Contrat Merchandising";
    $menu_10 = "Contrat VIP";
    $menu_11 = "Bient&ocirc;t disponible";
    $menu_12 = "Planning pour EAS";
    $menu_13 = "Attestation de travail";
    $menu_14 = "Fiches de Salaire";
    $menu_15 = "R&egrave;glement de travail";

# Fin Page "menudefaut.php"

#Début Vocabulaire général
    $genvoc1a = "oui";
    $genvoc1b = "non";

#Fin Vocabulaire général


#Debut Page "modifiche_menu.php"
    $modiFmenu_00 = "Mise &agrave; jour de la fiche";
    $modiFmenu_01 = "Introduction";
        $modiFmenu_01a = "Cet espace va vous permettre de <b>vous inscrire dans la base de donn&eacute;es de jobistes</b> d&rsquo;Exception2.";
        $modiFmenu_01b = "Cet espace va vous permettre de <b>mettre &agrave; jour votre fiche jobistes</b> Exception&sup2;.";
        $modiFmenu_01c = "Veuillez remplir les formulaires suivants avec un maximum d&rsquo;attention. <b>Soyez aussi complet que possible !</b><br><br><span class='error_info'>Attention: votre fiche ne sera envoy&eacute;e que si vous terminez compl&egrave;tement le processus d&rsquo;inscription !</span><br><br>Merci et &agrave; bient&ocirc;t !<div align='right'>L&rsquo;&eacute;quipe Exception&sup2</div>";
    $modiFmenu_02 = "Contacts";
    $modiFmenu_03 = "Infos G&eacute;n&eacute;rales";
    $modiFmenu_04 = "Secr&eacute;tariat Social";
    $modiFmenu_05 = "Photo";
    $modiFmenu_06 = "Validation";
#Fin Page "modifiche_menu.php"

#Debut Page "modifiche_1.php"
    $modifiche1_01  = "Sexe";
    $modifiche1_01a = "M";
    $modifiche1_01b = "F";
    $modifiche1_02  = "Nom";
    $modifiche1_03  = "Pr&eacute;nom";
    $modifiche1_04  = "Adresse";
    $modifiche1_05  = "Bte";
    $modifiche1_06  = "CP";
    $modifiche1_07  = "Ville";
    $modifiche1_08  = "Pays";
    $modifiche1_08a = "Belgique";
    $modifiche1_08b = "France";
    $modifiche1_08c = "Luxembourg";
    $modifiche1_09  = "Tel";
    $modifiche1_10  = "Fax";
    $modifiche1_11  = "GSM";
    $modifiche1_12  = "Email";
    $modifiche1_12a = "(Vous servira de login pour l'espace web)";
    $modifiche1_13  = "Mot de passe";
    $modifiche1_13a = "(mot de passe de l'espace web - entre 4 et 10 caract&egrave;res)";
    $modifiche1_14  = "Re-Mot de passe";

#Fin Page "modifiche_1.php"

#Debut Page "modifiche_2.php"
    $emodifiche1_01 = "Toutes les informations nécessaires nécessaires n'ont pas été entrées";
    $emodifiche1_02 = "Les champs TEL/GSM/FAX ne peuvent contenir que des chiffres";
    $emodifiche1_03 = "L'adresse email entrée semble incorrecte";
    $emodifiche1_04 = "Les mots de passe ne concordent pas";
    //
    $modifiche2_01  = "Physionomie";
    $modifiche2_01a = "Asiatique";
    $modifiche2_01b = "Black";
    $modifiche2_01c = "Hispanique";
    $modifiche2_01d = "Méditéranéen";
    $modifiche2_01e = "Nord-africain";
    $modifiche2_01f = "Occidental";
    $modifiche2_01g = "Orientale";
    $modifiche2_01h = "Slave";
    $modifiche2_02  = "Couleur cheveux";
    $modifiche2_02a = "blond";
    $modifiche2_02b = "brun";
    $modifiche2_02c = "chatain";
    $modifiche2_02d = "noir";
    $modifiche2_02e = "roux";
    $modifiche2_03  = "Longueur cheveux";
    $modifiche2_03a = "ras&eacute;";
    $modifiche2_03b = "court";
    $modifiche2_03c = "mi-long";
    $modifiche2_03d = "long";
    $modifiche2_04  = "Taille";
    $modifiche2_05  = "Taille veste";
    $modifiche2_06a = "Taille jupe";
    $modifiche2_06b = "Taille pantalon";
    $modifiche2_07  = "Pointure";
    $modifiche2_08  = "Tour de poitrine";
    $modifiche2_09  = "Tour de taille";
    $modifiche2_10  = "Tour de hanche";
    $modifiche2_12  = "Permis";
    $modifiche2_13  = "Voiture";
    $modifiche2_14  = "Fume";
    $modifiche2_15  = "Informatique";

#Debut Page "modifiche_3.php"
    $emodifiche2_01 = "Les champs TAILLE et POINTURE ne peuvent contenir que des chiffres";
    $emodifiche2_02 = "Les champs TOUR DE POITRINE, TOUR DE TAILLE et TOUR DE HANCHE ne peuvent contenir que des chiffres";
    //
    $modifiche3_01  = "Infos relatives &agrave; la Naissance";
    $modifiche3_02  = "Date de naissance";
    $modifiche3_03  = "Localit&eacute;";
    $modifiche3_04  = "Pays";
    $modifiche3_05  = "Nationalit&eacute;";
    $modifiche3_06  = "Situation";
    $modifiche3_07  = "Etat civil";
    $modifiche3_07a = "C&eacute;libataire";
    $modifiche3_07b = "Mari&eacute;";
    $modifiche3_07c = "Veuf";
    $modifiche3_07d = "Divorc&eacute;";
    $modifiche3_07e = "S&eacute;par&eacute";
    $modifiche3_08  = "Date Mariage";
    $modifiche3_09  = "Nom Conjoint";
    $modifiche3_10  = "Naissance Conj.";
    $modifiche3_11  = "Job Conj.";
    $jobconjoint = array(
                    '0' => 'Inconnue',
                    '1' => 'Ouvrier',
                    '2' => 'Gens De Maison',
                    '3' => 'Employe',
                    '4' => 'Independant',
                    '5' => 'Ouvrier Des Mines',
                    '6' => 'Marin',
                    '7' => 'Travailleur Des Services Publics',
                    '8' => 'Autre',
                    '9' => 'Sans',
    );

    $modifiche3_12 = "Personnes &agrave; charge";
    $modifiche3_13 = "Enfants &agrave; charge";

    $modifiche3_14 = "Documents";
    $modifiche3_15 = "N&deg; Carte d&rsquo;identit&eacute;";
    $modifiche3_16 = "N&deg; Reg national";
    $modifiche3_17 = "Paiement";
    $modifiche3_18 = "Compte";
    $modifiche3_19 = "Pour des payements &agrave; l'&eacute;tranger, merci de contacter Exception2 (Tel : 02 732 74 40)";
#Fin Page "modifiche_3.php"

#Début Page "modifiche_4.php"
$emodifiche3_01 = "En tant que personne mariée, vous devez indiquer les informations relatives à votre conjoint.";
$emodifiche3_02 = "Les champs PERSONNES A CHARGE et ENFANTS A CHARGE ne peuvent contenir que des chiffres";
#Début Page "modifiche_4.php"

# Debut Page "newmenu.php"
    $menu_new_01 = "Formulaire<br>d&rsquo;inscription";
    $menu_new_02 = "Cliquez sur le lien ci-dessus pour acc&eacute;der &agrave;<br>la fiche d&rsquo;inscription Online.";

    $menu_new_03 = "Bienvenue";
    $menu_new_04 = "<b>Bienvenue dans l&rsquo;espace s&eacute;curis&eacute; de la soci&eacute;t&eacute; Exception&sup2;.</b><br><br>Une fois votre inscription valid&eacute;e par un coordinateur d&rsquo;Exception&sup2;, vous aurez acc&egrave;s aux diff&eacute;rents menus gris&eacute;s actuellement.<br><br>Vous pourrez alors:<br><ul><li>Mettre &agrave; jour votre fiche</li><li>Indiquer vos disponibilit&eacute;s</li><li>Imprimer vos contrats</li><li>Consulter les news et les offres d&rsquo;emplois</li><li>etc...</li></ul>";
# Fin Page "newmenu.php"

# Debut Page "v-detail.php" / Mise à jour de la fiche
    $detail_00 = "Mise &agrave; jour de la fiche";
    $detail_01 = "Introduction";
    $detail_02 = "Contacts";
    $detail_03 = "Infos G&eacute;n&eacute;rales";
    $detail_04 = "Secr&eacute;tariat Social";
    $detail_05 = "Photo";
    $detail_06 = "Validation";

    $detail_07 = "";
    $detail_08 = "";
    $detail_09 = "";

    $detail_10 = "Contrat VIP";

# Debut Page "v-detail.php" : section 0  / Mise à jour de la fiche : Introduction
    $detail_0_01 = "Cet espace va vous permettre de <b>vous inscrire dans la base de donn&eacute;es de jobistes</b> d&rsquo;Exception2.";
    $detail_0_02 = "Cet espace va vous permettre de <b>mettre &agrave; jour votre fiche jobistes</b> Exception&sup2;.";
    $detail_0_03 = "Veuillez remplir les formulaires suivants avec un maximum d&rsquo;attention. <b>Soyez aussi complet que possible !</b><br><br><span class='error_info'>Attention: votre fiche ne sera envoy&eacute;e que si vous terminez compl&egrave;tement le processus d&rsquo;inscription !</span><br><br>Merci et &agrave; bient&ocirc;t !<div align='right'>L&rsquo;&eacute;quipe Exception&sup2</div>";
# Fin Page "v-detail.php" : section 0  / Mise à jour de la fiche : Introduction

# Debut Page "v-detail.php" : section 1  / Mise à jour de la fiche : Contacts
    $detail_1_01  = "Sexe";
    $detail_1_02  = "Nom";
    $detail_1_03  = "Pr&eacute;nom";
    $detail_1_04  = "Adresse";
    $detail_1_04a = "Adresse L&eacute;gale<br>(Pour les contrats par exemple)";
    $detail_1_04b = "Adresse effective<br>(Point de d&eacute;part de vos missions)";
    $detail_1_05  = "Bte";
    $detail_1_06  = "CP";
    $detail_1_07  = "Ville";
    $detail_1_08  = "Pays";
    $detail_1_09  = "Tel";
    $detail_1_10  = "Fax";
    $detail_1_11  = "GSM";
    $detail_1_12  = "Email";
    $detail_1_13  = "login de l'espace web";
    $detail_1_14  = "Web password";
    $detail_1_15  = "mot de passe de l'espace web - entre 4 et 10 caract&egrave;res";
# Fin Page "v-detail.php" : section 1  / Mise à jour de la fiche : Contacts

# Debut Page "v-detail.php" : section 2  / Mise à jour de la fiche : Infos Générales
    $detail_2_01    = "Physionomie";
    $detail_2_02_01 = "europ&eacute;en";
    $detail_2_02_02 = "m&eacute;diterrann&eacute;en";
    $detail_2_02_03 = "black";
    $detail_2_02_04 = "asiatique";
    $detail_2_03    = "Couleur Cheveux";
    $detail_2_04_01 = "blond";
    $detail_2_04_02 = "brun";
    $detail_2_04_03 = "noir";
    $detail_2_04_04 = "chatain";
    $detail_2_04_05 = "roux";
    $detail_2_05    = "Longueur cheveux";
    $detail_2_06_01 = "long";
    $detail_2_06_02 = "mi-long";
    $detail_2_06_03 = "court";
    $detail_2_06_04 = "ras&eacute;";
    $detail_2_07    = "Taille";
    $detail_2_08    = "Taille Veste";
    $detail_2_09    = "Taille Jupe";
    $detail_2_10    = "Pointure";
    $detail_2_11    = "Permis";
    $detail_2_12    = "Voiture";
    $detail_2_13    = "Langues";
    $detail_2_14    = "Tour de poitrine";
    $detail_2_15    = "Tour de hanche";
    $detail_2_16    = "Tour de taille";
    $detail_2_17    = "Fume";
    $detail_2_18    = "Informatique";

# Fin Page "v-detail.php" : section 2  / Mise à jour de la fiche : Infos Générales

# Debut Page "v-detail.php" : section 3  / Mise à jour de la fiche : Secrétariat Social
    $detail_3_01    = "Infos relatives &agrave; la Naissance";
    $detail_3_02    = "Date";
    $detail_3_03    = "CP - Localit&eacute;";
    $detail_3_04    = "Pays";
    $detail_3_05    = "Nationalit&eacute;";
    $detail_3_06    = "Infos relatives &agrave; la Famille";
    $detail_3_07    = "Etat civil";
    $detail_3_08_01 = "C&eacute;libataire";
    $detail_3_08_02 = "Mari&eacute;";
    $detail_3_08_03 = "Veuf";
    $detail_3_08_04 = "Divorc&eacute;";
    $detail_3_08_05 = "S&eacute;par&eacute;";
    $detail_3_09    = "Date Mariage";
    $detail_3_10    = "Nom Conjoint";
    $detail_3_11    = "Naissance Conj.";
    $detail_3_12    = "Job Conj.";
    $detail_3_14    = "Personnes &agrave; charge";
    $detail_3_15    = "Enfants &agrave; charge";
    $detail_3_16    = "Documents";
    $detail_3_17    = "N&deg; Carte d&rsquo;identit&eacute;";
    $detail_3_18    = "N&deg; Reg national";
    $detail_3_19    = "Payement";
    $detail_3_20    = "Compte";
    $detail_3_21    = "Pour des payements &agrave; l'&eacute;tranger, merci de contacter Exception2 (Tel : 02 732 74 40)";
# Fin Page "v-detail.php" : section 3  / Mise à jour de la fiche : Secrétariat Social

# Debut Page "v-detail.php" : section 4  / Mise à jour de la fiche : Photo
    $detail_4_01 = '
                1. Vous devez disposer d&rsquo;une photo personnelle de qualit&eacute; en format &eacute;lectronique. Cette photo doit &ecirc;tre au format &ldquo;.jpg&rdquo;, &ldquo;.jpeg&rdquo;, &ldquo;.gif&rdquo;, .&ldquo;png&rdquo; et &ldquo;.bmp&rdquo;.<br><br>
                2. Recherchez cette photo &agrave; l&rsquo;aide du bouton <input type="file" name="exemple" size="1" DISABLED>.<br><br>
                3. S&eacute;lectionnez la photo choisie en cliquant sur le nom du fichier.<br><br>
                4. Proc&eacute;dez de la m&ecirc;me fa&ccedil;on si vous voulez envoyer une seconde photo.<br><br>
                5. Si la (les) photo vous convient cliquez sur le bouton <input type="submit" name="act" value="Valider" DISABLED>  pour terminer le processus de s&eacute;lection de photo.';
    $detail_4_02 = "La photo envoy&eacute;e doit &ecirc;tre au format jpg ou jpeg.";
    $detail_4_03 = "La photo envoy&eacute;e est trop petite,<br> merci d&rsquo;en choisir une autre";
    $detail_4_03a = "La photo envoy&eacute;e est trop grande,<br> merci d&rsquo;en choisir une autre";
    $detail_4_04 = "est une taille insuffisante.";
    $detail_4_04a = "est une taille trop importante.";
    $detail_4_05 = "Compte";
    $detail_4_06 = "Ancienne";
    $detail_4_07 = "Nouvelle";
    $detail_4_08 = "Astuce";
    $detail_4_09 = "Si vous disposez d&eacute;j&agrave; d&rsquo;une photo dans Photo 1, utilisez la Photo 2 !";
    $detail_4_10 = "Attention : votre inscription ne sera validée que si vous y joignez une ou deux photos !";
    $detail_4_11 = "Merci d'envoyer une photo de profil et une photo portrait. (ex : photo que vous utilisez pour vos cv)";
    $detail_4_12 = "Photos personnelles";
    $detail_4_13 = "Ancienne Photo 1 portrait";
    $detail_4_14 = "Ancienne Photo 2 de face debout";
    $detail_4_15 = "il n'y a pas encore de photo 1 dans votre profil.";
    $detail_4_16 = "il n'y a pas encore de photo 2 dans votre profil.";
    $detail_4_17 = "Nouvelle photo 1 portrait";
    $detail_4_18 = "Nouvelle photo 2 de face debout";
    $detail_4_19 = "Progression :";


# Fin Page "v-detail.php" : section 4  / Mise à jour de la fiche : Photo

# Debut Page "v-detail.php" : section 5  / Mise à jour de la fiche : Validation
    $detail_5_01 = "<b>Bienvenue chez Exception2 !</b><br> <br> Attention: votre fiche ne sera valid&eacute;e que si vous terminez le processus d&rsquo;inscription en cliquant sur &ldquo;Terminer&rdquo;.<br> Merci d&#x27;attendre 48h avant de pouvoir acc&eacute;der &agrave; votre fiche personnelle.<br> <br> Afin de finaliser compl&egrave;tement votre inscription, nous organisons &agrave; l&#x27;agence 2 s&eacute;ances d&#x27;information le jeudi, soit &agrave; 11h tapante, soit &agrave; 14h tapante.<br> <br> L&#x27;&eacute;quipe Exception.";
    $detail_5_02 = "Merci d&rsquo;avoir mis &agrave; jour votre fiche jobistes Exception2<br>Attention: votre fiche ne sera envoy&eacute;e que si vous terminez compl&egrave;tement le processus d&rsquo;inscription en cliquant sur &ldquo;Terminer&rdquo;";
    $detail_5_03 = "A tr&egrave;s bient&ocirc;t,<br><br>L&rsquo;&eacute;quipe Exception&sup2;.";
    $detail_5_04 = "J'ai lu et j'accepte le ";
    $detail_5_05 = "Veuillez accepter le règlement de travail pour clôturer votre inscription";

# Fin Page "v-detail.php" : section 5  / Mise à jour de la fiche : Validation

# Fin Page "v-detail.php" / Mise à jour de la fiche

# Debut Page "contratanim.php" + "contratmerch.php" + "contratvip.php" + 'easmerch.php"
    $contrat_00 = "Bienvenu dans l&rsquo;espace s&eacute;curis&eacute; d&rsquo;Exception";
    $contrat_02 = 'Cliquez sur (<img src="'.STATIK.'illus/minipdf.gif" width="16" height="16">) pour afficher la contrat.<br> Utilisez ensuite la fonction IMPRIMER de votre navigateur.';
    $contrat_03 = 'Un fichier en format PDF est cr&eacute;&eacute;, il contient le contrats s&eacute;l&eacute;ctionn&eacute;.';
    $contrat_08 = "Fichier PDF, format standard de document imprimable.";
    $contrat_09 = "Vos contrats sont disponibles &lsquo;En ligne&rsquo; deux jours avant la prestation.";

    $contrat_anim_00 = "Impression des contrats : Secteur Animation";
    $contrat_anim_01 = "Listing des contrats en Animations";

    $contrat_merch_00 = "Impression des contrats : Secteur Merchandising";
    $contrat_merch_01 = "Listing des contrats en Merchandising";

    $contrat_vip_00 = "Impression des contrats : Secteur Vip";
    $contrat_vip_01 = "Listing des contrats en VIPs";

    $eas_merch_00 = "Impression des Planning EAS : Secteur Merchandising";
    $eas_merch_01 = "Listing des Planning EAS en Merchandising";
# Fin Page "contratanim.php" + "contratmerch.php" + "contratvip.php" + 'easmerch.php"

# Debut Page "dispo.php"
    $dispo_00 = "Disponibilit&eacute;";
    $dispo_01 = "Vous permet de changer les disponibilit&eacute;s pour le mois choisi.";
    $dispo_02 = "Pour entrer un vos disponibilit&eacute;s pour un mois suppl&eacute;mentaire.";
    $dispo_03 = 'Vous ne pouvez entrer des disponibilit&eacute;s que '.@$nbrmois.' mois &agrave; l&rsquo;avance';
    $dispo_04 = "Cochez ci-dessous vos p&eacute;riodes de disponibilit&eacute;s";
    $dispo_05 = "Cet espace va vous permettre de nous transmettre vos disponibilit&eacute;s.";
    $dispo_06 = "Utilisez un 'Double-Clic' pour sélectionner le jour entier.";
# Fin Page "dispo.php"

# Debut Page "attest.php"
    $attest_00 = "P&eacute;riode couverte par l'attestation de travail";
    $attest_01 = "Du";
    $attest_02 = "Au";
    $attest_03 = "Les dates entr&eacute;es doivent etre comprises entre<br> le";
    $attest_04 = "ex";
    $attest_05 = "et le ";
    $attest_06 = "Toutes les dates en dehors de cette p&eacute;riode seront ignor&eacute;es";
# Fin Page "attest.php"

# Debut Page "281.10.php"
    $f281_01 = "Revenus";
    $f281_02 = "Cette fiche vous est n&eacute;cessaire pour remplir votre d&eacute;claration d'imp&ocirc;ts 2009";

# Debut Page "ficheSalaire.php"
    $fichesalaire_01 = "Fiche Salaire pour le mois de ";


# Debut Page ANIM "sales.php"
    $sales_00 = "Rapport de ventes";
    $sales_01 = "Mission";
    $sales_02 = "Semaine";
    $sales_03 = "Date";
    $sales_04 = "Notes Jobiste";
    $sales_05 = "Note magasin";
    $sales_06 = "Autres animations";
    $sales_07 = "Oui";
    $sales_08 = "Non";
    $sales_09 = "Envoyer chez Exception&sup2;";

    $sales_prod_01 = "Produit";
    $sales_prod_02 = "Stock D&eacute;but";
    $sales_prod_03 = "Stock Fin";
    $sales_prod_04 = "Ventes";
    $sales_prod_05 = "Rupture";
    $sales_prod_06 = "D&eacute;gustation";

    $sales_menu_01 = "Accessibilit&eacute;";
    $sales_menu_02 = "Les rapports de ventes sont accessibles &agrave; partir du jour de la prestation jusqu'au mardi de la semaine suivante &agrave; 13H00.";
    $sales_menu_03 = "Encodage : Mission";
    $sales_menu_04 = "Encodez les informations de la mission tel que not&eacute; sur votre contrat et validez.";
    $sales_menu_05 = "Encodage : Produits";
    $sales_menu_06 = "Encodez les informations d'un produit tel que not&eacute; sur votre rapport papier, validez et passez au produit suivant.";
    $sales_menu_07 = "V&eacute;rification";
    $sales_menu_08 = "V&eacute;rifiez attentivement les informations avant de cl&ocirc;turer et d&rsquo;envoyer votre rapport &agrave; Exception&sup2;.";
    $sales_menu_09 = "Envoyer chez Exception";
    $sales_menu_10 = "Lorsque vous &ecirc;tes certain(e) que le rapport est complet, il ne vous reste plus qu'&agrave; appuyer sur &ldquo;".$sales_09."&rdquo;.";
    $sales_menu_11 = "Merci !";


# Fin Page ANIM "sales.php"

?>