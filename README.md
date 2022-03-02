
## Projet OCR - Bilemo
par Mickaël MOLEY

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/984280855ab04c708e381d86989d8c36)](https://www.codacy.com/gh/MickaelMoley/ocr-7-bilemo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MickaelMoley/ocr-7-bilemo&amp;utm_campaign=Badge_Grade)

## Configuration de JWT

JWT a besoin deux fichiers **private.pem** et **public.pem** pour fonctionner.
Par défaut, les références vers ces deux fichiers se trouvent dans le fichier **.env** à la racine.

Vous devez ajouter votre phrase secrète ici :

    JWT_PASSPHRASE=bilemo-ocr
 Remplacer **bilemo-ocr** par votre propre phrase secrète.
 
 Enfin, on peut générer ces deux fichiers de deux manières.

### Générer les clés avec OpenSSL

Pour pouvoir générer ces deux clés avec OpenSSL.

Il faut taper les commandes suivants :
PS : Vous devez être dans le répertoire de votre projet pour les générer.

    openssl genrsa -out config/jwt/private.pem -aes256 4096
   Cette commande va servir à générer la clé privée. Lorsque vous allez lancer cette commande, cela aura pour effet de générer une clé, il vous sera demander de fournir une phrase secrète (PASS PHRASE).
   **Utilisez la phrase secrète que vous avez défini dans le fichier .env** 
   
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
Cette commande va servir à générer la clé public à partir de la clé privée. Il vous sera demander d'entrer la phrase secrète pour pouvoir le générer.   **Utilisez la phrase secrète que vous avez défini dans le fichier .env** 
   
### Générer les clés avec la commande Symfony

Avec le bundle `lexik-authenticator-bundle`, on peut générer les clés.
NB: Ce bundle a été installé par **Composer** (si vous avez lancer la commande `composer install`)
Il suffit d'entrer cette commande pour pouvoir les générer.

    php bin/console lexik:jwt:generate-keypair
 Si une configuration existe déjà, vous devez peut-être forcer la nouvelle configuration en ajoutant `--overwrite` après la commande. Cela aura pour effet d'écraser les anciennes clés par les nouvelles.

# Installation du projet

## Cloner le projet
Lancer cette commande depuis un terminal afin d'installer les sources du projet en local :


    git@github.com:MickaelMoley/ocr-7-bilemo.git

## Installer les dépendances du projet
Lancer la commande suivante pour installer les dépendances du projet :

    composer install

**Voir la configuration de JWT avant de tester l'API.  Vous ne pourrez pas effectuer des requêtes si les clés n'ont pas été générées.**

## Jeu de données
Le projet contient des données de test.
Pour démarrer le projet avec un jeu de donnée de test, lancer simplement cette commande :

    bin/console doctrine:fixtures:load


## Découvert de l'API
Pour pouvoir tester l'API, vous devez accéder à la route suivante :

Route : localhost/**api/doc**

Pour commencer, lancer le serveur via la commande **symfony**

    symfony serve
ou
en exécutant la commande suivante :

    cd public && php -S localhost:8000

Enfin, accéder à l'URL suivante : https://localhost:8000/index.php/api/doc
Vous pourrez tester l'API via la documentation.

