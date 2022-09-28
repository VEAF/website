### 1.11.2
* FIX erreur 500 sur les profils dont les stats sont à zéro

### 1.11.1
* FIX profil - regroupement par période incorrect
* FIX CVE guzzle + twig

### 1.11.0
* ADD serveurs - indicateur contrôleurs ATC/GCI par serveur
* ADD calendrier - reservation du serveur pour la mission
* ADD calendrier - ATO complet, gestion des participations et invités
* FIX calendrier - erreur 500 si le client n'est pas connecté
* FIX fontawesome - version 5 -> 6
* FIX CVE - symfony 4.4.37

### 1.10.1
* FIX calendrier - l'administrateur ne peut pas modifier un événement
* FIX calendrier - inscription fermée lors de la copie d'un événement

### 1.10.0
* ADD profil - spération des modules par époque
* ADD roster - spération des avions par époque
* ADD roster - affichage des caractéristiques rôles et systèmes du module
* ADD calendrier - copie d'un évenement
* ADD mission-maker - filtrer les avions par époque
* ADD map - ajout de l'affichage de la carte + bullseye (WIP)
* ADD cms - visibilité des éléments du menu selon le profil
* ADD cms - restriction d'accès aux pages en fonction des profils
* ADD admin - gestion des rôles des modules (CAS, SEAD, etc...)
* ADD admin - gestion des systèmes des modules
* FIX calendrier - les membres pouvaient modifier les événements des autres

### 1.9.4
* ADD admin - inscription désactivable par le sysadmin

### 1.9.3
* ADD calendrier - l'organisateur peut bloquer les inscriptions
* FIX roster - décompte des cadets prêts

### 1.9.2
* ADD identification des cadets prêts à devenir membre
* FIX calendrier - désactivation du correcteur d'orthographe dans l'éditeur
* FIX calendrier - défaut d'affichage top menu quand pas d'événements
* FIX admin - alerte sur les variants des modules DCS non associés à un module

### 1.9.1
* FIX wind was inverted on server view

### 1.9.0
* ADD public - afficher l'état des serveurs (perun)
* ADD public - afficher le temps écoulé en mission (perun)
* ADD public - afficher la météo en cours sur le serveur (perun)
* ADD calendrier - vérification de la date de fin
* ADD admin - afficher le nombre de jours depuis la dernière connexion (cadets zombies)

### 1.8.1
* FIX stats slmod - fix import données incorrectes

### 1.8.0
* ADD teamspeak - affiche le nombre de clients connectés au serveur Teamspeak
* ADD recrutement - export des (cadets) zombies
* ADD calendrier - bouton pour marquer tous les événements comme lu
* ADD calendrier - choix de l'appareil limité aux restrictions de l'événement
* UPD gestion des sessions via REDIS
* FIX CVE symfony

### 1.7.3
* UPD perun - mise à jour compatiblité perun 0.12.0

### 1.7.2
* ADD calendrier - editeur markdown avec support images drag&drop
* ADD système de miniature dynamique pour les images (en markdown par exemple)
* ADD calendrier - affichage de "mes prochains événements"

### 1.7.1
* FIX calendrier - peut-être absent toujours affiché
* FIX calendrier - choix du module pour l'événement - anomalie dans l'enregistrement

### 1.7.0
* ADD choix du module lors de la participation à un événément
* ADD multisite - theme 51EG + messages adaptés
* ADD admin - afficher le lien de reset de mot de passe
* ADD sécurité - le lien de reset du mot de passe expire au bout de 24h
* FIX mission-maker bouton recherche lance l'export après un premier export
* FIX admin - gestion du drapeau présentation dans la fiche utilisateur

### 1.6.1
* ADD profil - gestion de mon pseudo forum et discord
* ADD calendrier - affichage nom du jour et temps relatif de l'événement
* ADD messages d'erreur custom - intégrés au site
* ADD mission maker - nouveau module pour aider à la construction de missions

### 1.6.0
* ADD nouveau module: recrutement
* ADD profil - ajout d'une phase de sélection: cadet ou invité
* ADD profil - cadet - statut du programme d'intégration
* ADD recruteur - notification des cadets sans présentation de l'association
* ADD recruteur et membres - interface de suivi du cadet, suivi des vols période d'essai

### 1.5.0
* ADD public - statistiques détaillées d'un joueur
* ADD public - satistiques fréquentation serveurs - filtre par type de profil sur la heatmap
* ADD public - satistiques fréquentation serveurs - navigation de jour en jour
* FIX public - erreur dans le décompte des événements non lus
* FIX public - erreur dans l'ordre des éléments du menu

### 1.4.0
* ADD public - calendrier - notifications des nouveaux événéments
* ADD public - calendrier - meta données pour le partage d'événement
* ADD admin - gestion dynamique du menu
* ADD admin - module de redirection par url courtes (url shortener)
* FIX public - amélioration des notifications calendrier et joueurs connectés

### 1.3.3
* FIX public - erreur javascript select2 sur la page d'accueil
* FIX public - url permanente vers le discord VEAF
* FIX admin - informations de debug/stats pour l'import des données SLMOD

### 1.3.2
* ADD admin - liste des utilisateurs - tri par date d'inscription
* ADD profil - alerte si aucun simulateur n'est configuré sur mon profil
* ADD profil - édition de mon profil: choix du simulateur et pseudo

### 1.3.1
* FIX public - affichage des joueurs connectés incorrect
* FIX public - erreur 500 sur calendrier si non connecté
* FIX admin - bug sur les select2

### 1.3.0
* ADD ajout d'un module calendrier
* ADD gestion des restrictions des cartes pour les participants
* ADD gestion des participants à l'événement
* ADD statistiques de fréquentation de l'ensemble des serveurs
* ADD ajout du type de pilote sur la vue serveur
* ADD lien direct vers le profil public depuis la page 'mon profil'
* FIX bug sur la heatmap

### 1.2.1
* ADD admin - gestion d'un nom court et d'un nom long dans les modules
* FIX profil - limite les noms des modules trop longs dans les stats

### 1.2.0
* ADD public - normalisation des icônes des joueurs connectés
* ADD public - ajout du nombre de joueurs connectés sur le top menu
* ADD public - utilise le code et nom du serveur dans les stats
* ADD public - affichage du nombre de pilotes dans le roster
* ADD profil - gestion du niveau (skill) sur les modules spéciaux
* ADD profil - ajout des stats globales heures + modules favoris
* ADD admin - gestion des variants des modules (stats SLMOD)
* ADD admin - gestion des serveurs DCS
* ADD admin - gestion des utilisateurs importés depuis SLMOD
* FIX mise en page sur smartphone
* FIX public - légende incorrecte sur la heatmap d'un serveur

### 1.1.2
* FIX session + ordre des heures sur la heatmap

### 1.1.1
* FIX bug dans la sélection de la fréquentation heatmap

### 1.1.0
* ADD public - graph de fréquentation du serveur DCS sur 24h
* ADD public - ajout du graph heatmap de fréquentation du serveur sur 2 semaines

### 1.0.3
* FIX public - format des url du module CMS

### 1.0.2
* FIX public - liste des modules spéciaux dans le roster
* FIX admin - erreur lors de la saisie d'un code de module trop long
* FIX admin - problème sur l'ajout et sur le changement de routes

### 1.0.1
* ADD public - ajout du statut du pilote dans le roster
* ADD public - lien vers le profil de l'utilisateur dans la page bureau
* ADD public - modules spéciaux sur la page d'accueil + roster pilotes
* FIX public - icône du président
* FIX public - pseudo perun dans la liste des utilisateurs
* FIX admin - erreur lors de l'ajout d'un nouveau module
* FIX admin - surbrillance dans le menu de gauche pour les modules

### 1.0.0
* ADD public - page d'accueil
* ADD public - liste des serveurs DCS de l'association (perun)
* ADD public - liste des joueurs connectés aux serveurs DCS (perun)
* ADD public - page "le bureau" gérée en dynamique depuis les statuts des membres du bureau
* ADD public - affichage des pages dynamiques (CMS)
* ADD roster - affichage des pilotes par groupe et par module (dynamique)
* ADD admin - interface d'admnistration
* ADD admin - administration des joueurs DCS (perun)
* ADD admin - administration des utilisateurs du site
* ADD admin - interface d'admnistration des pages (CMS)
* ADD admin - interface d'admnistration des fichiers (images)
* ADD utilisateur - inscription (+ recaptcha)
* ADD utilisateur - mot de passe perdu (+ recaptcha), validation par email
* ADD utilisateur - interface de connexion / déconnexion
* ADD utilisateur - mon profil - gestion de mes modules
* ADD import des stats SLMOD temps total par variante de module
* ADD dev - script de mise à jour (semi) automatique: ./scripts/upgrade.sh
* UPD normalisation du lien utilisateur/joueur DCS perun
