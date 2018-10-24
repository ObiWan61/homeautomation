<?php
/* index6.php

Mise en automatique

*/

if (isset($_GET["msg"]))			// pour afficher un message de confirmation
	$message=$_GET["msg"];
else
	$message="&nbsp;";

// Fonctions Mise en Page
// Insere le texte $t dans une colonne en centrant/gras/Italique/Big si $mode contient c/g/i/b */
function td($t="", $mode="") {
	if ($t=="")							$t="&nbsp;";
	if (strpos($mode,"g") !== false)	$t="<b>".$t."</b>";			// Si on demande du gras, on insère dans <B>
	if (strpos($mode,"b") !== false)	$t="<big>".$t."</big>";		// en BIG
	if (strpos($mode,"i") !== false)	$t="<i>".$t."</i>";			// en Italique
	if (strpos($mode,"c") !== false)	$mode=" align='center'";	// Pour centrer éventuellement
	else								$mode="";
	return "<td$mode>$t</td>\n";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home Automation</title>
	<meta NAME='description' CONTENT='Home Automation Scripts'>
	<meta HTTP-EQUIV='Expires' CONTENT='now'>
	<meta NAME='Content-Language' CONTENT='fr'>
	<meta name='author' content='Olivier' >
	<meta NAME='Copyright' CONTENT='OBCD Occagnes'>
	<meta http-equiv='pragma' content='no-cache'>
	<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
	<link rel='stylesheet' type='text/css' href='homeAutomation.css'>
</head>
<body>
<div id='titre'>
	<table align='center' border=0 width=100%>
		<tr height=150 align='center' valign='middle'>
			<td width=20%><img src='zwave.png' width=232 height=112 name='zwave' alt='Z-Wave'></td>
			<td><h1>La Maison est Automatique !!</h1></td>
			<td width=20%><img src='Raspberry-Pi-Logo.jpg' width=150 height=150 name='raspi' alt='Raspberry-Pi'></td>
		</tr>
	</table>
</div>
<div id='fond'>
	<table width=90% border='1'>
		<caption>
			<?php echo "$message<br><br>"; ?>
		</caption>
		<colgroup>
    		<col span='1' style='background-color:#B8C7D3' />
        	<col span='4' style='background-color: #CCCCCC' />
    	</colgroup>
	<thead><tr>
		<th>VOLETS</th>
		<th width=20%>Salle &agrave; Manger</th>
		<th width=20%>Travers</th>
		<th width=20%>Salon</th>
		<th width=20%><g>TOUS</g></th>
	</tr></thead>
	<tbody>
<?php
	$actions=array("Ouvre" => 255, "Milieu" => 40, "Et&eacute;" => 13, "Ferme" =>0);
	$titreactions=["Ouverture","Moiti&eacute;","Et&eacute;","Fermeture"];
	$volets =array("SàM" => 3, "Travers" => 2, "Salon" => 4);
	$i=0;
	foreach($actions as $a => $niv) {
		echo "<tr>".td($titreactions[$i++],"g");
		foreach($volets as $v => $num) echo td("<a href=macros.php?com=ZwaveOne&dev=$num&val=$niv>$a</a>","c");
		echo td("<a href=macros.php?com=ZwaveAll&val=$niv>$a</a>","cg");
		echo "</tr>";
	}
/*	
	echo "<tr bgcolor=#F0E8FF><td colspan=5>&nbsp;</td>";
	echo "</tr><tr>";

	echo "<td colspan=3>Lampes Terrasse</td>";
	echo td("<a href=macros.php?com=ZwaveOne5=$num&val=255>Allume</a>");
	echo td("<a href=macros.php?com=ZwaveOne&dev=5&val=0>Eteint</a>");
	echo "</tr>";
*/
?>
	</tbody></table>
	<br><br><br>
	<form action='macros.php' method='post'>
	<table width=75% border=1>
	<colgroup>
    	<col span='1' width='200' style='background-color:#B8C7D3' />
        <col span='3' width='150' style='background-color: #CCCCCC' />
    </colgroup>
	<thead>
		<tr><th colspan=4>Fonctionnement en Automatique</th></tr>
	</thead><tbody>
	<tr>
		<td><b>Ouverture</b>
		<input name='FormOuvMod' type='hidden' value='0'></td>
		<td>
<?php
		if (file_exists("OuvAuto.txt")) {
			$oa="checked";
			$om="";
		} else {
			$oa="";
			$om="checked";
		}
		echo "			<input type='radio' name='OuvAuto' value=2 $om onchange='SiChange(1)'>Manuel\n";
		echo "			<input type='radio' name='OuvAuto' value=1 $oa onchange='SiChange(1)'>Automatique\n";
?>
		</td>
		<td>Heure :&nbsp;<input type='text' name='OuvHeure' size=5 maxlength=5 value='8:00'></td>
		<td rowspan=2>
			<input name='modeauto' type='hidden' value='1'>
			<button name='Valider' type='submit' disabled>Valider</button></td>
	</tr><tr>
		<td><b>Fermeture</b>
		<input name='FormFermMod' type='hidden' value='0'></td>
		<td>
<?php
		if (file_exists("FerAuto.txt")) {
			$fa="checked";
			$fm="";
		} else {
			$fa="";
			$fm="checked";
		}
		echo "			<input onchange='SiChange(2)' type='radio' name='FerAuto' value=0 $fm>Manuel\n";
		echo "			<input onchange='SiChange(2)' type='radio' name='FerAuto' value=1 $fa>Automatique\n";
?>
		</td>
		<td>Heure :&nbsp;<input type='text' name='FerHeure' size=5 maxlength=5 value='20:00'></td>
	</tr>
	<script type="text/javascript">
		function SiChange(m) {
			document.getElementByName("Valider").prop("disabled",false);
		}
	</script>	
	<tr>
<?php	
	echo td("Horaires<br>Lever/Coucher de Soleil","cg");
	echo td("Conventionnel<br>Civil<br>Nautique<br>Astronautique")."<td>";
	// Horaires de lever/coucher de soleil -> éventuellement automatique un jour ?
	// Occagnes, Orne, France
	$lat = 48.775335;   		// Nord
	$long = -0.069207;  		// Ouest
	$offset = 1+date("I");		// Fuseau horaire GMT + decalage si heure été
	$zenith=[90+50/60,96,102,108];	// Décalages conventionnel / Civiles / Nautiques / Astronomiques
	foreach($zenith as $a)
		echo date_sunrise(time(), SUNFUNCS_RET_STRING, $lat, $long, $a, $offset)."<br>";
	echo "</td><td>";
	foreach($zenith as $a)
		echo date_sunset(time(), SUNFUNCS_RET_STRING, $lat, $long, $a, $offset)."<br>";
?>
	</td></tr>
	</tbody></table></form>
	</div>
	<br><br><br><br>
	<div id='findepage'><center>	
		<b>OBCD</b> - <b>O</b>livier <b>B</b>IETZER <b>C</b>onseil & <b>D</b>atabase Management<br>
		Tel: +33 (0)7 81 71 99 22 - skype: obietzer - Email: olivier&#64;bietzer.org
	</center></div>
</body>
</html>