# NOTICE D'UTILISATION DE MAGENTO 2.4

# Table des matières

- [Configuration Système](#_Toc68005382)
- [Elastic Search](#_Toc68005383)
- [Installation Magento](#_Toc68005384)
- [Test de l'installation](#_Toc68005385)
- [Changement de mode](#_Toc680053826)
- [Installation d'un thème](#_Toc68005387)
- [Personnalisation d'un thème](#_Toc68005388)
- [Afficher les indications d'un template](#_Toc68005389)
- [Ajouter une extension existante](#_Toc68005310)
- [Créer un module personnalisé](#_Toc68005311)

**Adaptation du tutoriel en ligne :**
[https://digitalstartup.co.uk/t/how-to-install-magento-2-4-and-build-a-web-server/1607](https://digitalstartup.co.uk/t/how-to-install-magento-2-4-and-build-a-web-server/1607)

## <a id="_Toc68005382"></a>1. Configuration Système

### a.Installation/Configuration Firewall

`sudo apt install ufw`<br>
`sudo ufw enable`

### b.Installation/configuration Apache

`sudo apt update`<br>
`sudo apt install apache2 -y`<br>
`sudo service apache2 start`<br>
`sudo nano /etc/apache2/sites-available/000-default.conf`

**Ajouter entre les balises <em>`<Virtual Host :80>`</em> :**

```xml
<Directory "/var/www/html">
    AllowOverride All
</Directory>
```

**Vérification :**

`sudo apache2ctl configtest`

**Activer rewrite mode d’Apache :**

`sudo a2enmod rewrite`

**Redémarrer Apache :**

`sudo systemctl restart apache2`

**Autoriser Apache à travers le Firewall :**

`sudo ufw allow 'Apache Full'`

### c.Installation de Php 7.4

```bash
sudo apt install php7.4 libapache2-mod-php7.4 php7.4-mysql php7.4-soap php7.4-bcmath php7.4-xml php7.4-mbstring php7.4-gd php7.4-common php7.4-cli php7.4-curl php7.4-intl php7.4-zip zip unzip -y
```

**Modifier la priorité des fichiers php, en déplaçant index.php en début de liste dans le fichier :**

`sudo nano /etc/apache2/mods-enabled/dir.conf`

**Configuration php.ini :**

`sudo nano /etc/php/7.4/apache2/php.ini`

Rechercher avec `Ctrl + W` :

- `memory_limit` et modifier la valeur `128M` en `4G`
- `date.timezone` et ajouter `Europe/Paris`

Redémarrer Apache :

`sudo systemctl restart apache2`

### d.Installation/Configuration MYSQL / MariaDB

**⚠️Attention, ne pas installer une version de MariaDB > 10.5⚠️**

**Vérifier si Mysql est installé :**

`sudo service mysql status`

Si non, l’activer

`sudo service mysql start`

**Vérifier que MySql fonctionne :**

```mysql
sudo mysql \-\-user=root

DROP USER 'root'@'localhost';

CREATE USER 'root'@'localhost' IDENTIFIED BY 'Pass123\*';

GRANT ALL PRIVILEGES ON \*\.\* TO 'root'@'localhost' WITH GRANT OPTION;

exit;
```

### e.Installation/Configuration PHPMyAdmin

`sudo apt install phpmyadmin php7.4-mbstring php7.4-gettext -y`

![image](https://user-images.githubusercontent.com/45293401/112999460-4336e300-916f-11eb-8d46-c4fb44c651f6.png)

**⚠️Choisir No⚠️**

**Activer l’extension mysqli :**

`sudo phpenmod mysqli`

**Redémarrer Apache :**

`sudo systemctl restart apache2`

Vérifier le bon fonctionnement d’Apache sur le navigateur à l’adresse : `[http://localhost/phpmyadmin](http://localhost/phpmyadmin)`

Si l’adresse n’est pas accessible, il est possible que PHPMyAdmin ne soit pas installé dans le bon dossier :

`sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin`

### f.Création d’une base de données pour l’installation

Dans le navigateur, se rendre à : `[http://localhost/phpmyadmin](http://localhost/phpmyadmin)`

Se connecter avec les identifiants créés plus haut

- User : root
- Password : Pass123\*

Clic sur le menu **User Accounts / Add user account**

![image](https://user-images.githubusercontent.com/45293401/113000433-1f27d180-9170-11eb-9829-d0706ddce20e.png)

**Remplir les champs :**

- User name : `magento-master`<br>
- Host name : `Local – localhost`<br>
- Password : `Pass123*`<br>
- Re-type :<br>
- Create database with same name and grant all privileges<br>

![image](https://user-images.githubusercontent.com/45293401/113000688-5b5b3200-9170-11eb-829a-4507452dcf3b.png)

### g.Installation/Configuration SMTP

`sudo apt install mailutils -y`<br>
`sudo apt install postfix -y`<br>

**Editer le fichier <em>`main.cf`</em> :**

`sudo nano /etc/postfix/main.cf`

Rechercher avec `Ctrl + W` :

`inet_interfaces` et modifier la valeur `all` en `loopback-only`

**Redémarrer Postfix :**

`sudo systemctl restart postfix`

## <a id="_Toc68005383"></a>2. Installation/Configuration Elasticsearch

**Installation des packages Java :**

sudo apt install default-jdk
java --version

**Import de ElasticSearch :**

```bash
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.6.0-amd64.deb
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.6.0-amd64.deb.sha512
```

**File integrity check :**

`shasum -a 512 -c elasticsearch-7.6.0-amd64.deb.sha512`

**Maj des packages et install :**

```bash
sudo dpkg -i elasticsearch-7.6.0-amd64.deb
sudo /bin/systemctl daemon-reload
```

**Ajout du service au démarrage :**

`sudo /bin/systemctl enable elasticsearch.service`

**Démarrer le service :**

`sudo systemctl start elasticsearch`

**Check service :**

`curl -X GET "localhost:9200/"`

## <a id="_Toc68005384"></a>3. Installation Magento 2.4

### a.Création d’un utilisateur

```bash
sudo adduser magento
sudo usermod -g www-data magento
```

### b.Permissions utilisateur

`sudo chown -R magento:www-data /var/www/html/`

### c.Install Composer 1.X (⚠️2.X non supporté⚠️)

```bash
wget https://github.com/composer/composer/releases/download/1.10.20/composer.phar
sudo curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

**Si composer est déjà installé, effectuer un downgrade :**

`composer self-update 1.10.16`

**Vérifier la version de composer :**

`composer --version`

### d.Download Magento

**Naviguer vers le dossier web :**

`cd /var/www/html`

**Changer d’utilisateur :**

`su magento`

**Créer un dossier (il faut qu’il soit vide pour composer) :**

`mkdir magento`

**Récupérer les access key :** [https://marketplace.magento.com/customer/accessKeys/?](https://marketplace.magento.com/customer/accessKeys/?)

- Username = Public Key: 33c2cab27b4fa2f12eecd1e701e15bd2
- Password = Private Key: ed4195733b996ecf1528cfa58c70aac2

**Télécharger Magento vers le dossier /var/www/html/magento :**

`composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition=2.4.0 ./magento/`

**Modifier les permissions de pré-installation :**

```bash
cd magento
find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} + && find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} + && chown -R :www-data . && chmod u+x bin/magento
```

### e.Installation Magento

**Exécution du script dans le dossier magento :**

```bash
bin/magento setup:install \
--base-url=http://127.0.0.1/magento \
--db-host=localhost \
--db-name=magento-master \
--db-user=magento-master \
--db-password=Pass123* \
--admin-firstname=admin \
--admin-lastname=admin \
--admin-email=admin@admin.com \
--admin-user=admin \
--admin-password=admin123 \
--language=fr_FR \
--currency=EUR \
--timezone=Europe/Paris \
--use-rewrites=1
```

A la fin de l’installation, récupérer le path pour l’interface admin (ex : /admin_i9wrqn)

### f.Mise à jour de la limite mémoire

`nano .htaccess`

Rechercher avec `Ctrl + W`

`memory_limit` et modifier la valeur `756MB` en `2G`

### g.Installation des tâches cron

`bin/magento cron:install`

## <a id="_Toc68005385"></a>4. Test de l’installation

**Boutique :**

- Dans le navigateur, se rendre à l'adresse : `[http://localhost/magento](http://localhost/magento)`

Panneau d’administration :

&nbsp;&nbsp;&nbsp;&nbsp;Dans le navigateur, se rendre à : `http://localhost/magento/{path_admin}`

**Désactiver l’authentification 2 facteurs pour se connecter à l’interface admin (dev) :**

```bash
bin/magento module:disable Magento_TwoFactorAuth
bin/magento cache:flush
```

## <a id="_Toc68005386"></a>5. Changer de mode

- Vérifier le mode actif : `bin/magento deploy:mode:show`

- Developper Mode : `bin/magento deploy:mode:set developer`

- Production Mode : `bin/magento deploy:mode:set production`

- Nettoyer le cache après chaque changement de mode : `bin/magento cache:clean`

## <a id="_Toc68005387"></a>6. Installation d’un thème

- Télécharger un thème (ex : [https://www.hiddentechies.com/bizkick-responsive-theme-magento-2.html](https://www.hiddentechies.com/bizkick-responsive-theme-magento-2.html))
- Copier et extraire l’archive (dossiers `/app` et `/pub`) dans le dossier `<Magento root dir>/` :

`unzip<nom_de_l_archive> <destination>`

- Exécuter les commandes :

```bash
bin/magento setup:upgrade
bin/magento setup:static-content:deploy
```

- Dans la partie administration, choisir l’onglet **Content / Configuration**
- Editer la partie **Store View**
- Dans la section **Applied Theme**, choisir le thème dans la liste déroulante
- Rafraîchir le cache

## <a id="_Toc68005388"></a>7. Personnalisation d’un thème

- Structure d’un thème :

[https://devdocs.magento.com/guides/v2.3/frontend-dev-guide/themes/theme-structure.html](https://devdocs.magento.com/guides/v2.3/frontend-dev-guide/themes/theme-structure.html)

- Création d’un thème enfant :

[https://magecomp.com/blog/how-to-customize-themes-styles-in-magento/](https://magecomp.com/blog/how-to-customize-themes-styles-in-magento/)

## <a id="_Toc68005389"></a>8. Afficher les indications de templates

- Basculer en mode Developper
- Exécuter les commandes :

```bash
bin/magento dev:template-hints:enable
bin/magento cache:clean config full_page
```

- Ajouter le paramètre ?templatehints=magento dans l’url pour afficher les indications

## <a id="_Toc68005310"></a>9. Ajouter une extension existante

Le gestionnaire d’extension n’est plus disponible depuis la version 2.4 de Magento, toutes les extensions doivent être installées en ligne de commande.

![image](https://user-images.githubusercontent.com/45293401/113004232-c22e1a80-9173-11eb-80aa-838975416ec3.png)

### a.Choix de l’extension

Lien du store : [https://marketplace.magento.com/extensions.html](https://marketplace.magento.com/extensions.html)

Après avoir acheté une extension (gratuite ou payante), se rendre sur son historique de commande [https://marketplace.magento.com/downloadable/customer/products/](https://marketplace.magento.com/downloadable/customer/products/) et choisir l’extension à installer.

Clic sur **Technical details**, puis récupérer le **nom du composant** et la **version**.

![image](https://user-images.githubusercontent.com/45293401/113006033-4765ff00-9175-11eb-815d-0ad35ff80a27.png)

Ex :

- Nom du composant : sendinblue/module-sendinblue **`<extension-name>`**
- Version : 2.1.4 **`<version>`**

### b.Installation de l’extension

**L’installation d’une extension s’exécute avec la commande :**

`composer require <extension-name>:<version> --no-update.`

**Dans le dossier `Magento root dir/` exécuter les commandes :**

```bash
composer require sendinblue/module-sendinblue:2.1.4 --no-update
composer update
```

**Vérification de l’ensemble des modules :**

`bin/magento module:status`

On récupère ensuite le module SendinBlue dans la partie **List of disabled modules** : `Sendinblue_Sendinblue`

**Activation du module et compilation de l’application :**

```bash
bin/magento module:enable Sendinblue_Sendinblue
bin/magento setup:upgrade
bin/magento setup:di:compile
```

**Vérifier ensuite si l’extension est correctement installée :**

`bin/magento module:status Sendinblue_Sendinblue`

On retrouve l’extension dans le panneau d’administration, onglet Content :

![image](https://user-images.githubusercontent.com/45293401/113010529-33240100-9179-11eb-94e1-79daaa99b4ba.png)

## <a id="_Toc68005311"></a>10. Créer un module personnalisé

Sources :

- [https://www.mageplaza.com/magento-2-module-development/](https://www.mageplaza.com/magento-2-module-development/)
- [https://devdocs.magento.com/videos/fundamentals/create-a-new-module/](https://devdocs.magento.com/videos/fundamentals/create-a-new-module/)

Chaque module de Magento est composé de deux parties : **Vendor + Module**.

Les bibliothèques (**Vendor**) peuvent contenir plusieurs modules (**Module**).

### b.Création de l’architecture

Dans le dossier `<Magento root dir>/app/code/`, créer un dossier **Vendor** (ex : Ubista) et un sous dossier **Module** (ex : Ubista) : `magento/app/code/Ubista/Ubista`.

⚠️Attention à bien respecter la casse (PascalCase) pour les noms des **Vendors** et des **Modules**.⚠️

### c.Déclaration du module

Pour être installé par composer, il faut ajouter le fichier **composer.json** à la racine du module avec le contenu :

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/composer.json](https://github.com/christophe-bernard-tribe/magento-module/blob/main/composer.json)

```yaml
{
  "name": "ubista/ubista",
  "description": "Custom module for Magento 2.4",
  "type": "magento2-module",
  "version": "1.0.0",
  "support": { "docs": "" },
  "license": ["proprietary"],
  "require": { "php": ">=7.1.0" },
  "autoload":
    { "files": ["registration.php"], "psr-4": { "Ubista\\Ubista\\": "" } },
}
```

Afin que Magento puisse trouver et utiliser le module, il faut le déclarer dans un répertoire `etc` avec un fichier **module.xml**.

Exemple de chemin complet : `magento/app/code/Ubista/Ubista/etc/module.xml`

Contenu du fichier module.xml :

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/etc/module.xml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/etc/module.xml)

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="Ubista_Ubista" setup_version="1.0.0"></module>
</config>
```

Les balises **`<sequence></sequence>`** font références aux dépendances liées au module (optionnel).

### d.Création d’une table en BDD pour le module

Créer un dossier **Setup** (respecter la casse) dans le dossier du module `magento/app/code/Ubista/Ubista/Setup`

Créer un fichier **InstallSchema.php** qui exécutera la fonction **install()** lors de l’installation du module.

Exemple de chemin complet : `magento/app/code/Ubista/Ubista/Setup/InstallSchema.php`

Ce fichier décrit le schéma (structure) de la table.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Setup/InstallSchema.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Setup/InstallSchema.php)

Créer ensuite un fichier **InstallData.php** qui comprendra les données à insérer dans la table.

Exemple de chemin complet : `magento/app/code/Ubista/Ubista/Setup/InstallData.php`

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Setup/InstallData.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Setup/InstallData.php)

### e.Enregistrement du module

Dans le dossier du module, ajouter un fichier **registration.php**.

Exemple de chemin complet : `magento/app/code/Ubista/Ubista/registration.php`

Contenu du fichier **registration.php** :

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/registration.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/registration.php)

```php
<?php
    \Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    Ubista_Ubista,
    __DIR__
);
```

**Activer le module avec :**

```bash
php bin/magento module:enable Ubista_Ubista
php bin/magento setup:upgrade
chmod 777 -R var/
```

Vérifier si le module est activé : `bin/magento module:status`

Vérifier également si la table **`custom_module_param`** est bien présente en base avec les commandes :

- Connexion mysqli : `mysql -u root -p magento-master`
- Vérification table : `SHOW TABLES LIKE '% ubista_parameters %';`

Si la table n’est pas présente, supprimer l’entrée **Ubista_Ubista** dans la table **setup_module** et exécuter la commande `php bin/magento setup:upgrade`

Une fois le module installé, une erreur devrait s’afficher sur le front :

![image](https://user-images.githubusercontent.com/45293401/113015183-92841000-917d-11eb-8471-7ea72aab0b71.png)

**Exécuter les commandes :**

```bash
bin/magento setup:upgrade
bin/magento setup:static-content:deploy
```

Si les feuilles de styles ne sont plus prises en compte après l’activation du module, exécuter les commandes :

```bash
php bin/magento deploy:mode:set developer && php bin/magento cache:clean
php bin/magento cache:flush
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

### f.Création d’un model

Création d’un fichier **Param.php** (Param est le nom de notre modèle dans ce cas) dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Model`.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Model/Param.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Model/Param.php)

Création d’un fichier **Param.php** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Model/ResourceModel`

Ce fichier de ressource permet d’interagir avec la base de données.

Dans la fonction `_init`, le paramètre **`ubista_parameters`** correspond au nom de la table et le paramètre **`id`** correspond à la clé primaire (voir fichier InstallSchema.php)

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Model/ResourceModel/Param.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Model/ResourceModel/Param.php)

Création d’un fichier **Collection.php** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Model/ResourceModel/Param`.

Dans la fonction `_init`, le paramètre **`Ubista\Ubista\Model\Param`** correspond au nom de la classe du model et le paramètre **`Ubista\Ubista\Model\ResourceModel\Param`** correspond au nom de la classe de la ressource.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Model/ResourceModel/Param/Collection.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Model/ResourceModel/Param/Collection.php)

### g.Création d’un menu admin

Création d’un fichier **menu.xml** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/etc/adminhtml/menu.xml`.

Pour l’ajout d’un menu, il faut respecter le format **{Vendor_ModuleName}::{menu_description}**

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/etc/adminhtml/menu.xml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/etc/adminhtml/menu.xml)

Lien pour le détail des attributs : [https://www.mageplaza.com/magento-2-module-development/create-admin-menu-magento-2.html](https://www.mageplaza.com/magento-2-module-development/create-admin-menu-magento-2.html)

### h.Création d’une route

Il est nécessaire de créer un fichier **routes.xml** dans le dossier `etc/frontend/`.

Exemple de chemin complet : `magento/app/code/Ubista/Ubista/etc/adminhtml/routes.xml`

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/etc/adminhtml/routes.xml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/etc/adminhtml/routes.xml)

La route sera alors : `http://localhost/ubista/*`

### i.Création des Controller

- **Index.php :**

Création d’un fichier **Index.php** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Controller/Tribe/Index.php`.

La route complète sera : `http://localhost/magento/ubista/tribe/index`

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Controller/Adminhtml/Tribe/Index.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Controller/Adminhtml/Tribe/Index.php)

- **ApiController.php :**

Création d’un fichier **ApiController.php** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Controller/Tribe/ApiController.php`.

Le controller **Post**, étendra de celui-ci qui effectue les vérifications CSRF.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Controller/Adminhtml/Tribe/ApiController.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Controller/Adminhtml/Tribe/ApiController.php)

- **Post.php :**

Création d’un fichier **Post.php** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Controller/Tribe/Post.php`.

Il est utilisé pour la soumission du formulaire sur la partie Admin du module pour sauvegarder les préférences.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Controller/Adminhtml/Tribe/Post.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Controller/Adminhtml/Tribe/Post.php)

### j.Création des layouts

- **Partie Admin :**

Création d’un fichier **ubista_tribe_index.xml** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/view/adminhtml/layout/ubista_tribe_index.xml`.

Format du nom de fichier : **{NomModule_NomControlleur_ActionController}**

Dans ce fichier, nous définissons le **Block** et le **Template** de la page.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/adminhtml/layout/ubista_tribe_index.xml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/adminhtml/layout/ubista_tribe_index.xml)

- **Partie FrontEnd :**

Création d’un **catalog_product_view.xml** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/view/frontend/layout/catalog_product_view.xml`.

L’attribut **cacheable="false"** du block permet de visualiser les modifications du paramètre côté Admin en temps réel.

Dans ce fichier, nous définissons le **Block** et le **Template** de la page

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/frontend/layout/catalog_product_view.xml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/frontend/layout/catalog_product_view.xml)

### k.Création d’un Block

Création d’un fichier **Param.php** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/Block/Param.php`.

Le block permet de récupérer les données dans la partie phtml (front & back) en appelant **$block->mafonction()**.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/Block/Param.php](https://github.com/christophe-bernard-tribe/magento-module/blob/main/Block/Param.php)

### l.Création d’un Template

- **Partie Admin :**

Création d’un fichier **tribe.phtml** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/view/adminhtml/templates/tribe.phtml`.

Ici, on récupère en BDD la valeur du paramètre créé afin d’afficher la valeur dans un switch, formulaire avec action sur la route **ubista/tribe/post** (controller Post, fonction execute()).

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/adminhtml/templates/tribe.phtml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/adminhtml/templates/tribe.phtml)

- **Partie FrontEnd :**

Création d’un fichier **attribute.phtml** dans le dossier (respecter la casse) : `magento/app/code/Ubista/Ubista/view/frontend/templates/attribute.phtml`.

Ici, on vérifie si le param sur la partie Admin est actif et en fonction de la catégorie du produit, on affiche ou non une Icone.

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/frontend/templates/attribute.phtml](https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/frontend/templates/attribute.phtml)

### m.Modification de l’icône du menu admin créé

Création d’une police (font) personnalisée à partir d’images svg sur le site [https://glyphter.com/](https://glyphter.com/).

Télécharger l’archive et extraire dans le dossier : `magento/app/code/Ubista/Ubista/view/adminhtml/web/fonts`. Les 4 fichiers correspondant à la police (**_.eot, .svg, .ttf, .woff_**) en prennant soin de les renommer du nom de la police afin de les faire correspondre au fichier **css**.

Dans le dossier `magento/app/code/Ubista/Ubista/view/adminhtml/web/css/source`, créer un fichier **\_module.less** et y copier le contenu du fichier **css** de la police. Ajouter le style suivant ou **.item-ubistamenu** correspondant à l’ID du menu crée dans le fichier `magento/app/code/Ubista/Ubista/etc/adminhtml/menu.xml`

[https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/adminhtml/web/css/source/\_module.less](https://github.com/christophe-bernard-tribe/magento-module/blob/main/view/adminhtml/web/css/source/_module.less)

Source : [https://store.magenest.com/blog/set-custom-icon-menu-backend-magento-2/](https://store.magenest.com/blog/set-custom-icon-menu-backend-magento-2/)

**Pour prendre en compte les modifications effectuées sur le module, exécuter les commandes pour le mettre à jour :**

`rm -rf var/view_preprocessed/* pub/static/*` **_(uniquement pour les modifications des fichiers compilés CSS, JS, …)_**

```bash
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

### n.Résultat visuel

- **Partie Admin :**

![image](https://user-images.githubusercontent.com/45293401/113100617-eb928900-91fb-11eb-8ad7-98a4b28bf3be.png)

- **Partie FrontEnd :**

![image](https://user-images.githubusercontent.com/45293401/113100677-fd742c00-91fb-11eb-8a66-4990b0efc07a.png)
