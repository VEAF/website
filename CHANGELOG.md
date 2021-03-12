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
