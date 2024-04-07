# MTG Viewer - Exercice

## Rappel

Pour l'ensemble de l'exercice, pensez à:

- Documenter les API avec OpenAPI (Swagger)
- Lintez votre code

De plus, vous devrez notez dans un documents les choix fait en fonction des problématiques rencontrées, la manière d'avoir trouvé la solution (benchmark, etc.) et eventuellement les design pattern utilisés.

## 0. Complétion de l'import

La commande d'import de carte est déjà implémentée, mais n'est pas entierrement fonctionnelle.  
Dans un premier temps, essayer d'importer 10,000 cartes, puis 30,000 cartes.
Si vous rencontrez des lenteurs, prenez le temps de les analyser et de les corriger.

Aide qui peut être utile:

- [Il est possible d'utiliser le symfony profiler avec une commande](https://symfony.com/doc/current/console.html#profiling-commands)
- Pour accéder au profiler: [http://localhost/\_profiler](http://localhost/_profiler) (adapter l'URL si besoin)

## 1. Ajout de logs

- Ajouter des logs pour chaque appel à l'API
- Ajouter des logs pour le début, la fin, la durée et les erreurs de l'import

## 2. Ajouter la recherche de carte

Si vous parcourez le site, vous verez le listing de toutes les cartes, et l'affichage d'une carte en particulier.  
Il y a une page en TODO, qui est la recherche de carte. Créer cette page et les API necessaires.  
Consigne:

- La recherche se fait sur le nom de la carte
- L'API utiliser doit être documentée via OpenAPI (Swagger)
- N'afficher que les 20 premiers résultats
- La recherche se lance à partir de 3 caractères, automatiquement

## 3. Ajouter des filtres

Ajouter un fitlre sur le setCode pour la recherche de carte et le listing de carte.  
Pensez à faire une route qui liste tous les setCode disponible pour les afficher dans un select. (documentation OpenAPI, etc.)

## 4. Ajouter la pagination

Vous avez réussi a importer +30,000 cartes, super ! La page de listing de carte est maintenant très lente.
Ajouter la pagination pour afficher 100 cartes par page.

## 5. Bonus - Enregistrer les Artistes

Dans le fichier CSV, il y a des informations sur l'artiste qui a illustré la carte.  
Ajouter l'artiste dans la base de données au moment de l'import.  
Ajouter les informations de l'artiste dans la page de détail de la carte.  
Ajouter un filtre sur l'artiste dans la recherche de carte / listing des cartes.
