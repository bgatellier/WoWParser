

// Vérifie si l'extension du fichier est valide, et soumet le formulaire si c'est le cas.
// Paramètres:
//	* id_champ :	ID du champ de type FILE
function soumetFormulaire( id_champ )
{
	if( verifExtensionFichier(id_champ) )
	{
		document.getElementById( 'buttons_patchnote' ).style.display = 'none';
		document.getElementById( 'loading_bar' ).style.display = 'block';
		
		// Soumission du formulaire
		document.getElementById( 'form_patchnote' ).submit();
	}
	else
	{
		alert('Seuls les fichiers possédant une extension .txt sont acceptés');
		document.getElementById( id_champ ).value = '';
		document.getElementById( id_champ ).focus();
	}
}

// Vérifie l'extension d'un fichier
// Paramètres:
//	* id_champ : champ de type file contenant le nom de l'image
// Retour :
//	* extension_valide : indique si l'extension du fichier est valide ou pas
function verifExtensionFichier( id_champ )
{
	// Tableau contenant les extensions de fichier attendues
	var extensions_autorisees = new Array('txt');
	
	// On récupère la valeur du champ de type file
	var nom_fichier = document.getElementById( id_champ ).value;
	
	// Position du dernier caractère '.' dans la chaîne (retourne -1 si le caractère n'existe pas)
	var indice_pt = nom_fichier.lastIndexOf('.');
	
	var extension_valide = false;
	
	if( indice_pt>=0 )
	{
		// Extraction puis convertion en minuscule de l'extension du fichier
		var extension_fichier = nom_fichier.substring( indice_pt+1 );
		extension_fichier = extension_fichier.toLowerCase();
		
		var indice_tab = 0;
		
		// Tant que l'extension n'est pas valide et que l'indice est inférieur à la taille du tableau
		while( !extension_valide && indice_tab<extensions_autorisees.length )
		{
			// Si l'extension du fichier correspond à l'extension lue dans le tableau
			if( extension_fichier == extensions_autorisees[indice_tab] )
				extension_valide = true;	// L'extension du fichier est valide
			
			indice_tab++;
		}
	}
	
	return extension_valide;
}

// Masque un élément DIV et en affiche un autre
// Paramètres:
//	* div_to_hide :	ID du DIV à masquer
//	* div_to_show :	ID du DIV à afficher
function switchDiv( div_to_hide, div_to_show)
{
	document.getElementById(div_to_hide).style.display = 'none';
	document.getElementById(div_to_show).style.display = 'block';
}

// Permet d'accéder au patch souhaité
// Paramètres:
//	* obj_select_patch :	objet représentant le menu déroulant listant les patchs
//	* php_script_name :	nom du script PHP courant (donné par $_SESSION['PHP_SELF'])
function goToPatch( obj_select_patch, php_script_name ){
	var patch_number = obj_select_patch.options[obj_select_patch.selectedIndex].value;
	
	switchDiv('changelog', 'patchnote');
	window.location.replace(php_script_name+'#'+patch_number);
}