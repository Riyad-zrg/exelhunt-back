# SAE5.01 Exel'Hunt chasse au trésor

## Cadalen Adrien - Desban Nicolas - Lebegue Killyan - Zerrougui Riyad

## Installation / Configuration 

1. Cloner le dépôt GitHub :
   ```bash
   git clone https://iut-info.univ-reims.fr/gitlab/lebe0069/sae5.01_exelhunt.git
    cd sae5.01_exelhunt
    ```

2. Installer les dépendances :
   ```bash
   composer install
   ```
   ne pas accepter le fichier de configuration grumphp lors de l'installation

### Utilisation des Scripts

1. Pour démarrer le serveur de développement, utilisez la commande suivante :
    ```bash
    composer start
    ```
    Cela lancera le serveur sur `http://localhost:8000`.

2. Pour exécuter les tests, utilisez la commande suivante :
    ```bash
    composer test
    ```
    Cela exécutera tous les tests définis dans le projet.
3. Pour exécuter les fixs, utilisez la commande suivante :
    ```bash
    composer fix
    ```
    Cela exécutera tous les fixs définis dans le projet.
4. Pour lancer la base de données, utilisez la commande suivante :
    ```bash
    composer db
    ```
    Cela lancera la base de données et chargera les données.