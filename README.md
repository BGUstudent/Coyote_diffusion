# Coyote_diffusion

Il s'agit d'une application web dédiée à un usage professtionnel pour une entreprise de distribution de journaux.
Les administrateurs définissent clients et leurs tournées, puis les attribuent aux livreurs. 
Les livreurs disposent alors d'une feuille de route pour leur livraison et transmette des données à chaque point livré.

-- FEATURES --

-Login/logout
	
	-Si un utilisateur se connecte, accès à l'nterface de livraison uniquement
	
-Interface de livraison:
	
	-marquer chaque points atteint et fournir des informations sur le déroulé de la livraison
	
	-Copier rapidement les adresses pour usage externe

--ADMIN FEATURES--

-Assigner les tournées:

	-Recherche par client
	
	-Définir une date
	
	-Attribuer un livreur à la tournée (deux livreurs si tournée "binôme")
	
	-Récapitulatif des tournées déjà atribuées
-CRUD Clients

-CRUD Tournées

-CRUD Users

-Gestion des points de livraison :

	-Selection par tournée
	
	-Modification de l'ordre par "drag&drop"
	
	-Outils de différenciation "tournée prévue" vs "tournée effectuée"
	
	-Edition/suppression des point en temps réel
	
	-Ajout de points de livraison
-Monitoring :

	-Outils de suivi détaillé des distributions en cours
	
	-Edition et lecture de rapport de distribution (PDF et Excel)
	
	-Reset des tournées, pour préparer la prochaine

-Statistiques des points de livraison en BDD, filtrage par catégorie et par code_postal/ville

