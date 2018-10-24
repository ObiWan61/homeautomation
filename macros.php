<?php 	

// Fonctions Z-Wave
function BasicSet($dev,$val) {	/* Envoie le signal Basic.Set(va) au device dev */
	$zwave = "http://192.168.0.5:8083/ZWaveAPI/Run/";								
	$address = $zwave."devices[$dev].instances[0].Basic.Set($val)";
	file_get_contents($address); 
}

// Cree un fichier VoletAutoOn ou VoletAutoOff pour memoriser le choix
function create_file_voletauto($m) {
	if (strpos("xOnOff",$m)>0) {
		$handle=fopen("VoletAuto$m.txt","w");
		fwrite($handle,$m);
		fclose($handle);
	}
}

if (isset($_POST["modeauto"])) {													// on arrive via le formulaire de choix heure auto
	if ($_POST["modeauto"]<>1)		die();											// ça veut dire arrivée bizarre...
	header("Location: index.php?msg=Vu%20Mode%20Auto");								// on revient sur la page principale
}

if (isset($_GET["com"]))	$com = $_GET["com"]; 
else						die();

if (isset($com)) {
	switch ($com) {
	case "PHPINFO" :					// petite faille de sécurité non ??
		phpinfo();
		break;
	case "CRFILE_VOLETAUTO" :
		$m=$_GET["mode"];
		create__file_voletauto($m);
		header("Location: index.php?msg=Fichier%20en%20place");
		break;
	case "ZwaveOne" :
		if (isset($_GET["dev"]) AND isset($_GET["val"])) {
			$dev=$_GET["dev"];
			$val=$_GET["val"];
			BasicSet($dev,$val);
		}
		header("Location: index.php?msg=Commande%20%Z-Wave%20envoyée");	
		break;
	case "ZwaveAll" :
		if (isset($_GET["val"])) {
			$val=$_GET["val"];
			$volets=array(2, 3, 4);
			foreach ($volets as $v)	BasicSet($v,$val);
		}
		header("Location: index.php?msg=Commande%globale%envoyée");	
		break;	
	}
}
