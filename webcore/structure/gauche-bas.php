<?php
$lang=$_GET['lang'];
if ($lang == 'fr') 
{
	$txt1 = '195 Av. de la Chasse';
	$txt2 = 'Bruxelles';
}
if ($lang == 'nl') 
{
	$txt1 = 'Jachtlaan 195';
	$txt2 = 'Brussel';
}
if ($lang == 'en') 
{
	$txt1 = '195 Av. de la Chasse';
	$txt2 = 'Brussels';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link href="../classe/page.css" rel="stylesheet" type="text/css">
</head>

<body class="basgauche">
<table width="144" height="152" cellpadding="0">
  <tr>
    <td width="30" rowspan="3">&nbsp;</td>
    <td height="15" colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td height="101" colspan="2"><div align="center"><font size="1" face="Helvetica,Arial,sans-serif"><strong><font color="#000000" size="2">Exception2</font></strong><br></b><br>
        <font color="#000000"><?php echo $txt1; ?><br>
        B-1040 <?php echo $txt2; ?><br>
        Tel : 02 732.74.40<br>
        Fax : 02 732.79.38</font><br>
      <a href="mailto:info@exception2.be"> info@exception2.be</a></font></div></td>
  </tr>
  <tr>
    <td width="51" height="23"><div align="right"><a href="../page/page.php?section=left&page=plan1&lang=<?php echo $lang; ?>" target="main" class="minilien"><img src="../images/plan-<?php echo $lang;?>.gif" width="36" height="14" border="0"></a></div></td>
    <td width="53"><div align="left"><a class="minilien" href="../structure/index1.php?page=contact&lang=<?php echo $lang; ?>" target="site"><img src="../images/staff.gif" width="36" height="14" border="0"></a></div></td>
  </tr>
</table>
</body>
</html>