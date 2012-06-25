<?php
	// Retourne le numéro de version du patch
	// Paramètre :
	//	$_titre : titre du patch
	// Retour:
	//	$numero_version : numéro de la version du patch (chaîne de caractères)
	function extraitNumeroVersion( $_titre )
	{
		// Morcellement du titre du patch selon le caractère espace
		$tab = explode(" ", $_titre);
		$nb_words = count($tab);
		
		for( $i=0 ; $i<$nb_words ; $i++ )
		{
			// Si la case du tableau débute par un chiffre, on vient de trouver le numéro de version du patch
			if( ereg('^[0-9]', $tab[$i]) )
				$numero_version = $tab[$i];
		}
		
		return $numero_version;
	}
	
	// Remplace la date d'implémentation du patch
	// Paramètre :
	//	$_titre : titre du patch
	// Retour:
	//	$_titre : titre du patch dont la date d'implémentation a été formatée selon le modèle : JJ mois YYYY
	function remplaceDateImplementation( $_titre )
	{
		// Position de la dernière parenthèse fermante
		$pos_derniere_parenthese_fermante = strrpos($_titre, ')' );
		
		if( $pos_derniere_parenthese_fermante!==false )
		{
			// Extraction des tous les caractères situés avant la dernière parenthèse fermante
			$chaine_tronquee = substr( $_titre, 0, $pos_derniere_parenthese_fermante );
			
			// Position de la dernière parenthèse ouvrante dans la chaîne précédente
			$pos_derniere_parenthese_ouvrante = strrpos( $chaine_tronquee, '(' );
			
			// Récupération de la date d'implémentation (non formatée)
			// La date d'implémentation peut se trouver sous 2 formes : YYYY-MM--DD ou DD/MM/YYYY
			$date_implementation = substr( $chaine_tronquee, $pos_derniere_parenthese_ouvrante+1, $pos_derniere_parenthese_fermante-$pos_derniere_parenthese_ouvrante );
			
			// Morcellement de la date d'implémentation le caractère -
			$tab = explode('-', $date_implementation );
			
			// Si le premier morceau est identique à la date d'implémentation non morcellée
			if( $tab[0]==$date_implementation )
				$tab = explode('/', $date_implementation );	// Morcellement de la date d'implémentation le caractère /
				
			if( strlen($tab[0])==4 )
			{
				// L'année se trouve dans la première case du tableau, le format de la date est donc YYYY MM DD
				$date_implementation = $tab[2];
				$date_implementation.= ' '.utf8_encode( getMoisfrancais($tab[1]) ).' ';
				$date_implementation.= $tab[0];
			}
			else
			{
				// L'année se trouve dans la première case du tableau, le format de la date est donc DD MM YYYY
				$date_implementation = $tab[0];
				$date_implementation.= ' '.utf8_encode( getMoisfrancais($tab[1]) ).' ';
				$date_implementation.= $tab[2];
			}
			
			// Recomposition du titre
			$_titre = substr( $_titre, 0, $pos_derniere_parenthese_ouvrante);
			$_titre.= '('.$date_implementation.')';
			
			return $_titre;
		}
		else
			return $_titre;
	}
?>
