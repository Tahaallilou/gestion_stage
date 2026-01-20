Documentation explicative – Projet de gestion des stages

 Présentation générale du projet
Le projet Gestion des stages est une application web développée avec Laravel et MySQL, permettant de gérer l’ensemble du cycle de vie des stages :
•	Inscription et authentification des utilisateurs
•	Gestion des rôles (Étudiant, Entreprise, Encadrant, Administrateur)
•	Publication des offres de stage
•	Candidature aux offres
•	Validation ou refus des candidatures
•	Suivi des stages
•	Évaluations et rapports
•	Logs système
•	Automatisation via procédures, triggers et curseurs MySQL
________________________________________
Mise en place du projet Laravel
Création du projet
composer create-project laravel/laravel gestion-stages
=>Initialise un nouveau projet Laravel avec toute l’architecture de base.
Lancement du serveur
php artisan serve
________________________________________
Configuration de la base de données
Création de la base MySQL
CREATE DATABASE gestion_stages 



Configuration du fichier .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stages_2
DB_USERNAME=root
DB_PASSWORD=
=>connectionà la base de données MySQL.
________________________________________
Migrations et structure de la base de données
Création des tables 
php artisan migrate
=>migration 
________________________________________
Authentification et gestion des rôles
Authentification
L’authentification Laravel :
•	Inscription
•	Connexion
•	Sessions
•	Sécurité de base
Gestion des rôles
Rôles définis :
•	Étudiant
•	Entreprise
•	Encadrant
•	Administrateur

Migration :
php artisan make:migration add_role_to_users_table --table=users
Ajout Du role dans la table users pour gérer les autorisations et l’accès aux fonctionnalités.
Création des tables métier
Les tables suivantes représentent le cœur fonctionnel de l’application :
Table	Rôle
users	Stocke les informations de connexion, les rôles (étudiant, entreprise, encadrant, admin) et le statut is_active
entreprises	Informations des entreprises
offres_stage	Stocke les informations de connexion, les rôles (étudiant, entreprise, encadrant, admin) et le statut is_active
candidatures	Fait le lien entre l'étudiant et l'offre avec le statut (en attente, acceptée, refusée).
stages	Gère le suivi du stage (en cours, terminé), les dates et les liens avec l'encadrant.
evaluations	Stocke les notes et commentaires (entreprise et pédagogique).
rapports	Pour la gestion des fichiers de rapports déposés
logs_systeme	Pour la traçabilité des actions effectuées sur la plateforme.

Chaque table est créée via une migration Laravel :
php artisan make:migration create_xxx_table
php artisan migrate
Contraintes métier
•	Un étudiant ne peut pas postuler deux fois à la même offre
•	Un étudiant ne peut pas faire plusieurs stages simultanément
•	Une offre ne peut pas dépasser son nombre de places
•	L’encadrant et l’entreprise ne peuvent pas évalue  sans avoir de rapport de stage .




Fonctions stockées MySQL (logique avancée)
1. nb_stages_etudiant : Compte le nombre de stages d'un étudiant.
2. nb_places_restantes : Calcule les places disponibles sur une offre.
3.verifier_eligibiliter_etudiant : vérifie si l'étudiant peut postuler (pas de double stage). 
4. calcul_note_finale : Calcule la moyenne automatique du stage.
Procédures stockées MySQL
 1. Postuler à une offre (postuler_offre) :
•	Vérifie l’éligibilité
•	Vérifie les places disponibles
•	Empêche les doubles candidatures
•	Crée la candidature avec statut en attente
Transaction MySQL ( pour garantir l’intégrité des données) :
•	START TRANSACTION
•	COMMIT
•	ROLLBACK en cas d’erreur
2. Accepter une candidature(accepter_candidature) :
•	Change le statut de la candidature passe en en « accepte »
•	Déclenche automatiquement la création du stage apres la verification de la disponibilite de l’encadrant  ayant la meme filiere du stage

3. Refuser une candidature ( refuser_candidature ) :
•	Met à jour le statut en "refusée".
•	Journalise l’action
4. Clôturer un stage ( cloturer_stage ) :
•	Termine officiellement un stage
Triggers MySQL (automatisation)
Les triggers permettent d’exécuter automatiquement des actions après certains événements 
Trigger		Fonction
trg_log_candidature		Enregistre une trace lors d'une nouvelle postulation.
trg_log_statut_candidature		Suit les changements d'état (en attente/accepté).
trg_apres_evaluation		Déclenche la mise à jour de la note finale après insertion.
trg_blocage_double_stage		Empêche un étudiant d'avoir deux stages actifs.
trg_log_evaluation		Historise les notes déposées.
trg_log_stage		Journalise la création automatique du stage.
trg_desactiver_offre		Désactive l'offre quand le quota de places est atteint.
trg_protege_offre_suppression		Empêche de supprimer une offre si des stagiaires y sont liés.

Curseurs MySQL
Les curseurs servent à traiter des données automatiquement :
1. cursor_cloture_stages : Clôture des stages arrivés à date de fin.
2. cursor_cloture_avec_notes : Calcule les notes de tous les stages terminés.
3. cursor_desactiver_offres : Désactive toutes les offres expirées.
4. cursor_audit_systeme : Analyse les logs pour générer des rapports de sécurité.




Section : Sécurité du Système
1. Sécurité de l'Infrastructure (Laravel & Middleware)
L'application repose sur le framework Laravel, qui intègre nativement des protections contre les failles Web majeures:
•	Authentification Robuste : Utilisation de Laravel Breeze pour une gestion sécurisée des sessions et du hachage des mots de passe (Bcrypt).
•	Protection CSRF (Cross-Site Request Forgery) : Chaque requête POST est protégée par un jeton unique pour empêcher les soumissions de formulaires malveillantes.
•	Prévention des Injections SQL : Utilisation de l'ORM Eloquent et de requêtes préparées qui neutralisent automatiquement les tentatives d'injection dans les entrées utilisateur.
•	Contrôle d'Accès basé sur les Rôles (RBAC) : Mise en place de Middlewares qui vérifient le rôle de l'utilisateur (Étudiant, Entreprise, Encadrant, Admin) avant d'autoriser l'accès à une route spécifique.
2. Sécurité Active : Suspension de Compte
Pour répondre au besoin de "sécuriser la gestion", j'ai implémenté un système de bannissement immédiat :
•	Attribut is_active : Un champ booléen ajouté à la table users permettant à l'administrateur de désactiver un accès en un clic.
•	Vérification en temps réel : Une fonction SQL is_compte_active ou un Middleware Laravel vérifie ce statut à chaque action de l'utilisateur pour l'expulser immédiatement s'il est suspendu.
3. Intégrité et Sécurité au niveau MySQL (Le "Cœur" de la donnée)
La logique métier est déléguée à MySQL pour garantir que les données restent cohérentes, même en cas de bug dans le code PHP:
•	Verrouillage par Triggers : Le trigger trg_blocage_double_stage empêche physiquement l'insertion d'un stage si les règles métiers ne sont pas respectées (ex: double stage interdit).
•	Transactions MySQL : Les opérations complexes (comme la création automatique d'un stage après acceptation d'une candidature) sont enveloppées dans des Transactions. Cela garantit que soit tout est enregistré, soit rien ne l'est, évitant ainsi les données corrompues.
•	Contraintes de suppression : Le trigger trg_protege_offre_suppression interdit la suppression d'une offre si des données liées (candidatures/stages) existent, protégeant ainsi l'intégrité référentielle.
4. Traçabilité et Audit
La transparence est assurée par un journal de bord complet:
•	Table logs_systeme : Enregistre les actions critiques effectuées sur la plateforme.
•	Historisation automatique : Des triggers de "Log" (ex: trg_log_candidature, trg_log_evaluation) enregistrent l'auteur et la date de chaque modification importante pour permettre un audit a posteriori.

