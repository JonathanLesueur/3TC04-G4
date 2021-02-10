# ESU - Espace Social Universitaire

Projet tutoré d'étudiants en Licence Professionnelle à l'Université de Limoges.

- Le dossier ```back``` contient les sources du projet fonctionnant avec le framework PHP Symfony 5.
- Le dossier ```front``` contient les sources statiques utilisées sur le projet : fichiers CSS, SASS, JS, Fonts, Icônes.

Pour la partie front, le module bundler Webpack est utilisé pour la compilation des fichiers SCSS et la minification du code CSS produit, il en va de même pour les fichiers JS.
Webpack a été configuré selon deux modes de développement : ```integration``` et ```symfony```. 

- Le premier permet de sortir les sources compilées vers un dossier intégration, utilisé pour l'intégration pure de la maquette réalisée.
- Le second permet de sortir les sources compilées vers le dossier public de symfony, dans le but d'associer l'intégration réalisée aux vues Twig.


## Membres du groupe

**Développement :**

- BLONDIN Marylou
- DOUBRE Killian
- LESUEUR Jonathan

**Gestion de projet :**

- GOMBAULD Melanie
- SAGET Jean-Claude
- SIMON David