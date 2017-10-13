<?php
// Fonction du contrôleur ctrl3.php : traiter la demande de changement de mot de passe
// Ecrit le 13/10/2017 par Pierre

if (!isset ($_Post["txtNouveauMdp"]) && !isset ($_POST["txtConfirmation"]))
{   // si les données n'ont pas été postées, c'est le premier appel du formulaire
    // on affiche alors la vue sans message d'erreur
	$nouveauMdp ='';
	$confirmationMdp='';
	$afficherMdp = 'off';
	$message='';
	$typeMessage='';       // 2 valeurs possibles : 'information' ou 'avertissement'
	include_once('vue3.php');
}
else
{   // récupération des données postées
	if (empty($_POST["txtNouveauMdp"]) == true) $nouveauMdp="";
	else $nouveauMdp = $_POST["txtNouveauMdp"];
	
	if (empty($_POST["txtConfirmation"]) == true ) $confirmationMdp ="";
	else $confirmationMdp = $_POST["txtConfirmation"];
	
	if (empty ($_POST["caseAfficherMdp"]) == true) $afficherMdp = 'off';
	else $afficherMdp=$_POST["caseAfficherMdp"];
	
	// utilisation d'une expression régulière pour vérifier la force du mot de passe
	$EXPRESSION = "#^(.*[0-9].*[a-z].*[A-Z].*|.*[0-9].*[A-Z].*[a-z].*|.*[a-z].*[A-Z].*[0-9].*|.*[a-z].*[0-9].*[A-Z].*|.*[A-Z].*[0-9].*[a-z].*|.*[A-Z].*[a-z].*[0-9].*)$#";
	if (preg_match($EXPRESSION, $nouveauMdp) == false || strlen($nouveauMdp) < 8)
	{   // si le mot de passe n'est pas assez fort, réaffichage de la vue avec un message explicatif
		$message = "Le mot de passe doit comporter au moins 8 caractères, dont au moins une lettre minuscule, une lettre majuscule et un chiffre !";
		$typeMessage='avertissement';
		include_once ('vue3.php');
	}
	else 
	{
		if($nouveauMdp != $confirmationMdp)
		{   // si les 2 saisies sont différentes, réaffichage de la vue avec un message explicatif
			$message="Le nouveau mot de passe et sa confirmation sont différents ! ";
			$typeMessage = 'avertissement';
			include_once ('vue3.php');
		}
		else 
		{   // envoi d'un mail à l'utilisateur avec son nouveau mot de passe
			$sujet = "Modification de votre mot de passe";
			$message="Votre mot de passe a été modifié. \n\n";
			$message .="Votre nouveau mot de passe est : ".$nouveauMdp;
			$adresseEmetteur = "delasalle.sio.eleves@gmail.com";
			$adresseDestinataire ="delasalle.sio.delaunay.pierre@gmail.com";
		      
			// utilisation d'une expression régulière pour vérifier si c'est une adresse Gmail
			if (preg_match("#^.+@gmail.com$#", $adresseDestinataire) == true)
			{   // on commence par enlever les points ds l'adresse gmail car ils ne sont pas pris en compte
				$adresseDestinataire=str_replace(".","",$adresseDestinataire);
				// puis on remet le point de "@gmail.com"
				$adresseDestinataire=str_replace("@gmailcom", "@gmail.com", $adresseDestinataire);
			}
			// envoi du mail avec la fct mail de PHP
			$ok=mail($adresseDestinataire, $sujet, $message, "From :".$adresseEmetteur);
		
			if ($ok)
			{
				$message = "Enregistrement effectué.<br>Vous allez recevoir un mail de confirmation. ";
				$typeMessage="information";
			}
			else 
			{
				$message="Enregistrement effectué.<br>L'envoi de mail de confirmation a rencontré un problème. ";
				$typeMessage="avertissement";
			}
			include_once ('vue3.php');
		}
	}
}
?>