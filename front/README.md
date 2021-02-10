# Utilisation de Webpack

- Commencer par installer les dépendences JS : ouvrir l'invite de commandes dans le dossier en cours puis lancer la commande ```npm install```
- Après installation, webpack devrait être utilisable. Pour cela, lancer la commande ```webpack --watch```

- L'initialisation peut prendre 1 à 2 minutes. Une fois lancé, webpack compilera les sources SCSS et JS du dossier dev automatiquement en CSS et JS dans le dossier ```espace-social/public/dist``` du projet Symfony.

- Chaque modification de fichier SCSS ou JS dans le projet front entraîne une compilation automatique par webpack et donc une mise à jour des fichiers de sortie dans le projet Symfony. (c'est magique !)

- Le fichier start-serve.bat permet de lancer directement le serveur webpack sans avoir à l'écrire manuellement.