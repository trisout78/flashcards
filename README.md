# Flashcards Interactives

Bienvenue sur le projet **Flashcards Interactives**! Cette application web permet de créer, partager et utiliser des flashcards pour un apprentissage efficace. 

## Fonctionnalités

- **Créer des Flashcards** : Ajoutez des flashcards en entrant des termes et leurs définitions.
- **Partage de Flashcards** : Une fois les flashcards créées, un lien unique est généré pour les partager.
- **Utiliser les Flashcards** : Affichez vos flashcards une par une et révisez vos connaissances.
- **Testez vos connaissances** : Entraînez-vous en vérifiant vos réponses à l'aide de l'application.

## Technologies Utilisées

- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP, SQLite

## Installation

Suivez ces étapes pour configurer le projet en local :

1. Clonez le dépôt :
    ```sh
    git clone https://github.com/trisout78/flashcards.git
    ```
2. Accédez au répertoire du projet :
    ```sh
    cd flashcards
    ```
3. Lancez un serveur PHP intégré :
    ```sh
    php -S localhost:8000
    ```

4. Accédez à l'application via :
    ```sh
    http://localhost:8000/index.php
    ```

## Utilisation

### Créer des Flashcards

1. Sur la page d'accueil (`index.php`), entrez vos flashcards au format `terme = définition`, une par ligne.
2. Cliquez sur "Enregistrer". Un lien sera généré pour accéder à vos flashcards.

### Afficher et Réviser des Flashcards

1. Utilisez le lien généré pour accéder à vos flashcards (`flashcard.php?id=<id>`).
2. Naviguez entre les flashcards à l'aide des boutons "Précédent" et "Suivant".
3. Mélangez les flashcards, changez le sens d'affichage, et activez l'affichage aléatoire du terme ou de la définition.

### Tester vos connaissances

1. Utilisez le lien généré pour accéder à la page de test (`test.php?id=<id>`).
2. Entrez vos réponses dans le champ prévu et validez pour voir si elles sont correctes.
3. Naviguez entre les flashcards et mélangez-les pour un test complet.

## Structure du Projet

- `index.php` : Page principale pour créer des flashcards.
- `flashcard.php` : Page pour afficher et réviser les flashcards.
- `test.php` : Page pour tester vos connaissances avec les flashcards.
- `styleindex.css` : Fichier de style pour `index.php`.
- `style.css` : Fichier de style commun pour `flashcard.php` et `test.php`.
- `flashcards.db` : Base de données SQLite pour stocker les flashcards.

## Contribution

Les contributions sont les bienvenues ! Si vous avez des suggestions ou des améliorations, n'hésitez pas à ouvrir une issue ou à soumettre une pull request.

## Licence

Ce projet est sous licence GNU GPLv3. Voir le fichier [GNU General Public License v3.0](LICENSE) pour plus de détails.

## Auteur

Made with ❤️ by Trisout (Tristan)

---

Merci d'avoir utilisé **Flashcards Interactives**! Bon apprentissage !
