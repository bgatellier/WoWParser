<?php
	session_start();
	
	include("inc/fonctions_chaines.inc.php");
	include("inc/fonctions_dates.inc.php");
	include("inc/parser.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Parser du patchnote de World of Warcraft</title>
	<link rel="stylesheet" href="css/parser.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/changelog.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/fonctions.js"></script>
</head>
<body>

<form id="form_patchnote" action="" method="post" enctype="multipart/form-data">
	<p>S&eacute;lectionnez le patchnote de <acronym title="World of Warcraft">WoW</acronym> (situ&eacute; par d&eacute;faut dans le dossier d'installation du jeu, et nomm&eacute; <em>Patch.txt</em>) :</p>
	<p id="buttons_patchnote">
		<input type="file" name="file_patchnote" id="file_patchnote" />
		<input type="button" name="button_patchnote" value="Ouvrir le fichier" onclick="soumetFormulaire('file_patchnote');" />
	</p>
	<p id="loading_bar">
		Analyse du fichier en cours...<br />
		<object type="application/x-shockwave-flash" data="swf/loading_bar.swf" width="70" height="10">
			  <param name="movie" value="swf/loading_bar.swf" />
			  <param name="quality" value="high" />
		</object>
	</p>
</form>

<?php
	$file_loaded = is_uploaded_file($_FILES["file_patchnote"]["tmp_name"]);
	
	if( $file_loaded ){
		
		// Ouverture du fichier en lecture
		$patchnote = fopen($_FILES["file_patchnote"]["tmp_name"], "r");
		
		// Tableau des caractères spéciaux
		$caracteres_bruts = array("&", "<", ">");
		// Tableau de leurs équivalents
		$caracteres_codes = array("&amp;", "&lt;", "&gt;");
		
		// Nombre de lignes lues
		$nb_lignes = 0;
		// Nombre de lignes du titre lues
		$nb_lignes_titre = 0;
		// Nombre de patchs rencontrés
		$nb_patchs = -1;
		
		// Liste des patchs. Chaque case du tableau est un taleau  associatif
		// contenant  le titre et le descriptif du patch
		$patchs = array();
		
		// Liste des versions des patchs
		$versions = array();
		
		// Indique si la ligne lue est un titre ou pas.
		$titre = false;
		// Indique si une balise <ul> est ouverte ou pas.
		$ul_ouvert = false;
		// Indique si une balise <li> est ouverte ou pas.
		$li_ouvert = false;
		// Indique si une balise <p> est ouverte ou pas.
		$p_ouvert = false;
		
		while( !feof($patchnote) ){
			// Lecture de la ligne
			$ligne = trim( fgets($patchnote, 255) );
			$nb_lignes ++;
			
			// Supression des 3 premiers caractères du fichier encodé en UTF-8
			// Raison : ????
			if( $nb_lignes==1 ){
				$ligne = substr($ligne, 3);
			}
				
			// On remplace les caractères spéciaux par leurs équivalents
			$ligne = str_replace($caracteres_bruts, $caracteres_codes, $ligne);
			
			// On récupère les deux premiers caractères de la ligne
			$debut = substr($ligne, 0, 2);
			
			if( $debut=='--' ){
				$titre = true;
				$nb_lignes_titre ++;
				
				switch( $nb_lignes_titre ){
					case '1':
						if( $ul_ouvert ){
							$patchs[$nb_patchs]["descriptif"] .= "</li>\n</ul>\n";
							$li_ouvert = false;
							$ul_ouvert = false;
						}
						
						// On indique qu'on vient de trouver un patch 
						$nb_patchs ++;
					break;
					
					case '3':
						$nb_lignes_titre = 0;
						$titre = false;
					break;
				}
			}
			elseif( $debut=='* '||$debut=='- ' ){
				if( $titre  ){
					$nb_lignes_titre ++;
					$patchs[$nb_patchs]["titre"] = substr($ligne, 2);
					$versions[$nb_patchs] = extraitNumeroVersion( $patchs[$nb_patchs]["titre"] );
					$patchs[$nb_patchs]["titre"] = remplaceDateImplementation( $patchs[$nb_patchs]["titre"] );
				}
				else{
					if( !$ul_ouvert ){
						$patchs[$nb_patchs]["descriptif"] .= "\n<ul>\n";
						$ul_ouvert = true;
					}
					
					if( $li_ouvert ){
						$patchs[$nb_patchs]["descriptif"] .= "</li>\n";
					}
					
					$patchs[$nb_patchs]["descriptif"] .= "<li>".substr($ligne, 2);
						
					$li_ouvert = true;
				}
			}
			elseif( empty($debut) ){	// Saut de ligne
				if( $ul_ouvert ){
					$patchs[$nb_patchs]["descriptif"] .= "</li>\n</ul>\n";
					$li_ouvert = false;
					$ul_ouvert = false;
				}
			}
			else{
				$patchs[$nb_patchs]["descriptif"] .= " ".$ligne;
			}
		}
		
		$_SESSION['patchs'] = $patchs;
		$_SESSION['versions'] = $versions;
	}
	
	if( isset($_SESSION['patchs'])&&!empty($_SESSION['patchs']) ){
		$patchs = $_SESSION['patchs'];
		$versions = $_SESSION['versions'];
	}
?>
<ul id="menu">
<?php
	if( $file_loaded||(isset($_SESSION['patchs'])&&!empty($_SESSION['patchs'])) ){
?>
	<li>
		<a href="#" onclick="goToPatch(document.getElementById('select_patch'), '<?php echo $_SESSION['PHP_SELF'];?>');">Acc&eacute;der au patch</a> 
		<select id="select_patch" name="select_patch" onchange="goToPatch(this, '<?php echo $_SESSION['PHP_SELF'];?>');">
<?php	
			// Liste des versions
			foreach( $versions as $cle => $contenu ){
?>
			<option value="patch_<?php echo $contenu;?>"><?php echo $contenu;?></option>
<?php
			}
?>
		</select>
	</li>
<?php
	}
?>
	<li>
		<a href="#" onclick="javascript:switchDiv('patchnote', 'changelog');">Changelog</a>
	</li>
</ul>

<div id="patch">
<?php
	if( $file_loaded||(isset($_SESSION['patchs'])&&!empty($_SESSION['patchs'])) ){
?>
	<div id="patchnote">
<?php
		// Affichage des données
		$nb_patchs = count($patchs);
		for( $current_patch=0 ; $current_patch<$nb_patchs ; $current_patch++ ){
?>
		<div class="patch">
			<h4 id="patch_<?php echo $versions[$current_patch];?>"><?php echo $patchs[$current_patch]["titre"];?></h4>
			<?php echo activeURLs( $patchs[$current_patch]["descriptif"] );?>
		</div>
<?php
		}
?>
	</div>
<?php
	}
?>
	

	<div id="changelog"<?php if( $file_loaded||(isset($_SESSION['patchs'])&&!empty($_SESSION['patchs'])) ){?> style="display: none;"<?php }?>>
<?php
	require("inc/changelog.inc.php");
?>
	</div>
</div>

</body>
</html>
