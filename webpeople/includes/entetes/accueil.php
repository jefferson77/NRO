<STYLE type="text/css">
<!--
/*MENU NEWS*/
div.news_title_date {
	padding-top:5px;
}
div.news_title_date {
	float:right;
	text-align:right;
	margin-right:5px;
	color: #009999;
}
/*CONTENU PAGE ACCUEIL*/ 
span.chapeau{
	position:relative;
	margin-left:50px;
	top:-15px;
}
div.icon_menu{
	width:70%;
	margin:auto;
	background-color:<?php echo $color5a ?>;
	border: medium solid <?php echo $color5c ?>;
}
div.icon_row{
	padding:10px;
}
div.icon{
	width:33%;
	margin:auto;
	text-align:center;
	float:left;
}
span.icontext {
	display:block;
	padding-top:5px;
}
div.icon img, div.icon span  {
  opacity: 0.7;
  filter:alpha(opacity=70);
  }

div.icon:hover img, div.icon:hover span  {
  opacity: 1;
  filter:alpha(opacity=100);  
  }

div.icon:hover span	{
	font-weight: bold;
}
-->
</STYLE>

<?php
$Titre = $titre_00;
if (empty($_GET['page'])) {
$textehaut = $menu_00;
}
?>