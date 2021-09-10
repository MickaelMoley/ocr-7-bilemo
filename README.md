# ocr-7-bilemo

# Configuration de JWT

JWT a besoin deux fichiers **private.pem** et **public.pem** pour fonctionner.
Par défaut, les références vers ces deux fichiers se trouvent dans le fichier **.env** à la racine.

Vous devez ajouter votre phrase secrète ici :

 
    JWT_PASSPHRASE=bilemo-ocr
 Remplacer **bilemo-ocr** par votre propre phrase secrète.
 
 Enfin, on peut générer ces deux fichiers de deux manières.

## Générer les clés avec OpenSSL

Pour pouvoir générer ces deux clés avec OpenSSL.

Il faut taper les commandes suivants :
PS : Vous devez être dans le répertoire de votre projet pour les générer.

    openssl genrsa -out config/jwt/private.pem -aes256 4096
   Cette commande va servir à générer la clé privée. Lorsque vous allez lancer cette commande, cela aura pour effet de générer une clé, il vous sera demander de fournir une phrase secrète (PASS PHRASE).
   **Utilisez la phrase secrète que vous avez défini dans le fichier .env** 
   

    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
Cette commande va servir à générer la clé public à partir de la clé privée. Il vous sera demander d'entrer la phrase secrète pour pouvoir le générer.   **Utilisez la phrase secrète que vous avez défini dans le fichier .env** 
   



## Générer les clés avec la commande Symfony

Avec le bundle `lexik-authenticator-bundle`, on peut générer les clés.
NB: Ce bundle a été installé par **Composer** (si vous avez lancer la commande `composer install`)
Il suffit d'entrer cette commande pour pouvoir les générer.

    php bin/console lexik:jwt:generate-keypair
 Si une configuration existe déjà, vous devez peut-être forcer la nouvelle configuration en ajoutant `--overwrite` après la commande. Cela aura pour effet d'écraser les anciennes clés par les nouvelles.

 
