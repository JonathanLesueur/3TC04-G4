# Utilisation de Webpack

- Commencer par installer les dépendences JS : ouvrir l'invite de commandes dans le dossier en cours puis lancer la commande ```npm install```
- Après installation, webpack devrait être utilisable. Pour cela, lancer la commande ```webpack --watch --integration``` ou ```webpack --watch --symfony``` selon l'endroit où les sources compilées doivent être envoyées.

- Il est aussi possible de lancer webpack directement via le fichier ```start-serve.bat```, qui est configuré de base sur le mode ```integration```.

- L'initialisation peut prendre 1 à 2 minutes. Une fois lancé, webpack compilera les sources SCSS et JS du dossier dev automatiquement en CSS et JS dans le dossier ```espace-social/public/dist``` du projet Symfony.

- Chaque modification de fichier SCSS ou JS dans le projet front entraîne une compilation automatique par webpack et donc une mise à jour des fichiers de sortie dans le projet Symfony. (c'est magique !)
