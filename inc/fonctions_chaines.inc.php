<?php
	/////////////////////////////////////////////////////////////////////////////////////////////
	//	Bastien Gatellier
	//	v1.2	le fichier est désormais encodé en UTF-8 (sans BOM)
	//	v1.1.1	modification de la la fonction activeURLs( $_chaine ) afin de prendre en compte les adresse débutant par www.
	//	v1.1	ajout de la fonction activeURLs( $_chaine )
	//	v1.0	Première version du fichier.
	//		Fonctions présentes : 
	//			getExtensionFichier( $_nom_fichier )
	//			supprimeAccents( $_chaine )
	//			tronqueChaine( $_chaine, $_caracteres_max )
	//
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	// Récupère l'extension d'un nom de fichier ou false si le nom ne contient pas d'extension
	// Paramètre :
	//	$_fichier : nom du fichier
	// Retour :
	//	Extension du fichier ou false si le nom de fichier ne comporte pas d'extension
	function getExtensionFichier( $_nom_fichier )
	{
		//Convertion de la chaîne en minuscule
		$_nom_fichier = strtolower($_nom_fichier);
		
		//Position du dernier '.' dans la chaîne
		$pos = strrpos( $_nom_fichier, '.' );
		
		//S'il y a un "." dans la chaîne
		if( $pos!==false )
			return substr( $_nom_fichier, $pos+1 );	//Extraction de l'extension de fichier
		else
			return false;
	}
	
	
	
	// Supprime les accents d'une chaîne de caractères
	// Paramètre :
	//	$_chaine : chaîne de caractères à traiter
	// Retour :
	//	$_chaine : chaîne de départ dépourvues de majuscules et d'accents
	function supprimeAccents( $_chaine )
	{
		//Tableau des caractères accentués à remplacer dans la chaine
		$char_accent = array('à', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'î', 'ï', 'ô', 'ö', 'û', 'ù', 'ü', 'ÿ');
		
		//Tableau des caractères remplacants les accentués
		$char_unaccent = array('a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'y');
		
		//Remplacement dans la chaine $chaine des caractères accentués ($char_accent)
		//par ceux non-accentués ($char_unaccent)
		$_chaine = str_replace($char_accent, $char_unaccent, $_chaine);
		
		return $_chaine;
	}
	
	
	
	// Tronque une chaîne de caractères en évitant de couper le dernier mot en deux.
	// Paramètres :
	//	$_chaine : chaîne de caractères  à tronquer
	//	$_caracteres_max : nombre de caractères maximum dans le texte tronqué.
	// Retour :
	//	$trunc : chaîne tronquée
	function tronqueChaine( $_chaine, $_caracteres_max )
	{
		if( $_caracteres_max<strlen($_chaine) ){
			// On tronque la chaîne
			$trunc = substr( $_chaine, 0, $_caracteres_max );
			
			// Dans la chaîne tronquée, on récupère la position du dernier espace
			$position_dernier_espace = strrpos( $trunc, ' ');
			
			// On tronque la chaîne déjà tronquée jusqu'au dernier caractère espace
			$trunc = substr( $trunc, 0, $position_dernier_espace );
		}
		else
			$trunc = $_chaine;
		
		return $trunc;
	}
	
	
	
	// Active les URL d'une chaîne de caractères (rajoute la balise a et le lien href)
	// Les URL sont définis comme étant les chaînes inintérompues de caractères débutant par http:// ou www.
	// les URLs débutant par www. auront l'attribut href du lien précédé des http:// afin que le lien pointe bien sur le site
	// Les caractères marquants la fin d'une URL sont : ' ', '\n', '. ' et '</'
	// Paramètres :
	//	$_chaine : chaîne de caractères
	// Retour :
	//	$_chaine : chaîne dont les liens ont été activés
	function activeURLs( $_chaine )
	{
		$http = 'http://';
		$www = 'www.';
		
		// Liste des caractères marquant la fin d'une URL
		$liste_caracteres_fin_URL = array("\n", " ", ". ", "</", ".</");
		
		// Chaîne qui sera retournée par la fonction
		$chaine_liens_actifs = '';
		
		// Chaîne contenant les caractères qui restent à lire
		// Au fur et à mesure de la lecture d'URL dans la chaîne principale, sa longueur diminuera
		$_chaine_raccourcie = $_chaine;
		
		// Position de premier http:// dans la chaîne
		$pos_http = strpos($_chaine_raccourcie, $http);
		
		// Position du premier www. dans la chaîne
		$pos_www = strpos($_chaine_raccourcie, $www);
				
		while( $pos_http!==false || $pos_www!==false ){
			if( $pos_http===false ){
				$pos_http = strlen( $_chaine_raccourcie );
			}
				
			if( $pos_www===false ){
				$pos_www = strlen( $_chaine_raccourcie );
			}
			
			// La position retenue est celle la moins éloignée du début de la chaîne
			$caractere_debut = min( $pos_http, $pos_www );
			
			// Ajout des caractères ne contenant pas le lien à la chaîne qui sera renvoyée par la fonction
			$chaine_liens_actifs.= substr($_chaine_raccourcie, 0, $caractere_debut);
			
			// On tronque la chaîne à partir du caractère h de la chaîne http://
			$_chaine_raccourcie = substr($_chaine_raccourcie, $caractere_debut);
			
			// Liste des positions des caractères marquant la fin d'une URL
			$liste_positions = array();
			
			foreach( $liste_caracteres_fin_URL as $indice => $valeur ){
				// On récupère la position du caractère marquer la fin de l'URL dans la chaîne
				${'pos'.$indice} = strpos( $_chaine_raccourcie, $valeur);
				
				// Si le caractère n'existe pas dans la chaîne, sa position sera la longueur de la chaîne,
				// afin d'éviter que la fonction min de retourne rien
				if( ${'pos'.$indice}===false ){
					${'pos'.$indice} = strlen( $_chaine_raccourcie );
				}
				
				// On ajoute la position à la liste
				$liste_positions.= ${'pos'.$indice};
				
				// On ajoute un caractère de séparation après la position
				// si le caractère marquant la fin de l'URL n'est pas le dernier de la liste
				if( $indice<count($liste_caracteres_fin_URL)-1 ){
					$liste_positions.= ',';
				}
			}
			
			// Position du premier caractère marquant la fin d'une URL rencontré
			$pos_fin_url = min( explode(',', $liste_positions) );
			
			// Lien qui sera affiché à l'écran
			$lien_visible = substr( $_chaine_raccourcie, 0, $pos_fin_url );
			
			// Lien qui sera contenu dans l'attribut href de la balise <a>
			$lien_href = $lien_visible;
			
			// Si le premier caractère du lien du href est un w, alors on ajoute les http:// devant l'adresse
			// afin que cette dernière pointe convenablement vers le site.
			if( substr($lien_href, 0, 1)=='w' ){
				$lien_href = 'http://'.$lien_href;
			}
			
			// On ajoute le lien à la chaîne qui sera retournée
			$chaine_liens_actifs.= '<a href="'.$lien_href.'">'.$lien_visible.'</a>';
			
			// On enlève de la chaîne l'adresse que l'on vient de trouver
			$_chaine_raccourcie = substr($_chaine_raccourcie, $pos_fin_url);
			
			// Position du premier http:// dans la nouvelle chaîne
			$pos_http = strpos($_chaine_raccourcie, $http);
			
			// Position du premier www. dans la nouvelle chaîne
			$pos_www = strpos($_chaine_raccourcie, $www);
		}
		
		// Ajout des caractères ne contenant pas le lien à la chaîne qui sera renvoyée par la fonction
		$chaine_liens_actifs.= $_chaine_raccourcie;
		
		return $chaine_liens_actifs;
	}
?>