<?php
	/////////////////////////////////////////////////////////////////////////////////////////////
	//	Bastien Gatellier
	//	v1.0 :	Première version du fichier.
	//		Fonctions présentes : 
	//			getMoisFrancais( $_num_mois )
	//			getNumMois( $_nom_mois )
	//
	//	Certaines fonctions nécessitent les fonctions définies dans le fichier fonctions_chaines.inc.php
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	// Renvoie le nom du mois en français ou false si le mois n'existe pas
	// Paramètre :
	// 	$_num_mois : numéro du mois (1 à 12)
	// Retour :
	//	Nom du mois en français
	function getMoisFrancais( $_num_mois ){
		switch( $_num_mois ){
			case 1:		return 'janvier';			break;
			case 2:		return 'f&eacute;vrier';	break;
			case 3:		return 'mars';				break;
			case 4:		return 'avril';				break;
			case 5:		return 'mai';				break;
			case 6:		return 'juin';				break;
			case 7:		return 'juillet';			break;
			case 8:		return 'ao&ucirc;t';		break;
			case 9:		return 'septembre';			break;
			case 10:	return 'octobre';			break;
			case 11:	return 'novembre';			break;
			case 12:	return 'd&eacute;cembre';	break;
			default:	return false;				break;
		}
	}
	
	
	
	// Renvoie le numéro du mois en fonction de son nom français ou false si le mois n'existe pas
	// Paramètre :
	//	$_nom_mois : nom du mois en français
	// Retour :
	//	Numéro du mois (1 à 12)
	function getNumMois( $_nom_mois ){
		// Suppression des accents de la chaîne
		$_nom_mois = supprimeAccents( $_nom_mois );
		
		// Convertion des caractères de la chaîne en minuscules
		$_nom_mois = strtolower( $_nom_mois );
		
	 	switch( $_nom_mois ){
			case 'janvier':		return 1;		break;
			case 'fevrier':		return 2;		break;
			case 'mars':		return 3;		break;
			case 'avril':		return 4;		break;
			case 'mai':			return 5;		break;
			case 'juin':		return 6;		break;
			case 'juillet':		return 7;		break;
			case 'aout':		return 8;		break;
			case 'septembre':	return 9;		break;
			case 'octobre':		return 10;		break;
			case 'novembre':	return 11;		break;
			case 'decembre':	return 12;		break;
			default:			return false;	break;
		}
	}
?>