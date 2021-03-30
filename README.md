# NOTICE D'UTILISATION DE MAGENTO 2.4

# Table des matières

- [Configuration Système](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#1configuration-syst%C3%A8me)
- [Elastic Search](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#2installationconfiguration-elasticsearch)
- [Installation Magento](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#3installation-magento-24)
- [Test de l'installation](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#4test-de-linstallation)
- [Changement de mode](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#5changer-de-mode)
- [Installation d'un thème](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#6installation-dun-th%C3%A8me)
- [Personnalisation d'un thème](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#7personnalisation-dun-th%C3%A8me)
- [Afficher les indications d'un template](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#8afficher-les-indications-de-templates)
- [Ajouter une extension existante](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#9ajouter-une-extension-existante)
- [Créer un module personnalisé](https://github.com/christophe-bernard-tribe/magento-module/new/main?readme=1#10cr%C3%A9er-un-module-personnalis%C3%A9)

**Adaptation du tutoriel en ligne :**
[https://digitalstartup.co.uk/t/how-to-install-magento-2-4-and-build-a-web-server/1607](https://digitalstartup.co.uk/t/how-to-install-magento-2-4-and-build-a-web-server/1607)

## 1.Configuration Système

### a.Installation/Configuration Firewall

`sudo apt install ufw` <br>
`sudo ufw enable`

### b.Installation/configuration Apache

`sudo apt update` <br>
`sudo apt install apache2 -y` <br>
`sudo service apache2 start` <br>
`sudo nano /etc/apache2/sites-available/000-default.conf` <br><br>
Ajouter entre les balises **<Virtual Host :80>** : <br>
`<Directory "/var/www/html">` <br>
&nbsp; <space> `AllowOverride All` <br>
`</Directory>` <br>

### c.Installation de Php 7.4

### d.Installation/Configuration MYSQL / MariaDB

### e.Installation/Configuration PHPMyAdmin

### f.Création d’une base de données pour l’installation

### g.Installation/Configuration SMTP

## 2.Installation/Configuration Elasticsearch

## 3.Installation Magento 2.4

### a.Création d’un utilisateur

### b.Permissions utilisateur

### c.Install Composer 1.X (2.X non supporté)

### d.Download Magento

### e.Installation Magento

### f.Mise à jour de la limite mémoire

### g.Installation des tâches cron

## 4.Test de l’installation

### a.Désactiver l’authentification 2 facteurs pour se connecter à l’interface admin (dev)

## 5.Changer de mode

## 6.Installation d’un thème

## 7.Personnalisation d’un thème

## 8.Afficher les indications de templates

## 9.Ajouter une extension existante

### a.Choix de l’extension

### b.Installation de l’extension

## 10.Créer un module personnalisé

### b.Création de l’architecture

### c.Déclaration du module

### d.Création d’une table en BDD pour le module

### e.Enregistrement du module

### f.Création d’un model

### g.Création d’un menu admin

### h.Création d’une route

### i.Création des Controller

### j.Création des layouts

### k.Création d’un Block

### l.Création d’un Template

### m.Modification de l’icône du menu admin créé

### n.Résultat visuel
