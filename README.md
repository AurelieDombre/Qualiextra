# Projet-22-Qualiextra


Qualiextra a pour but de vendre des expériences personnalisées aux épicuriens.

Expériences hôtelières (nuitée + activité) ou expériences gustatives (menu à l’aveugle, dégustation de spiritueux) etc à des particuliers (B to C).
Ces packages seront accessibles 100% en ligne aux clients depuis une application web.
La société référence aussi une sélection d’établissements qui ne proposent pas    d’expériences afin de proposer des lieux atypiques aux visiteurs de la plateforme.

![qualiextra-home](https://user-images.githubusercontent.com/99244644/187074745-33022f5b-e2db-41e9-ab3d-28291a74dc87.png)



## Installation

Afin d'installer le projet, nous vous proposons de suivre le processus suivant :

1. Installation du composer :

`composer install`

Cette commande permet d'installer, les bundles en fonction de ce que nous avons dans le fichier composer.json.

2. Création du lien avec la base de donnée (BDD)

Dans votre outils de gestion de base de donnée (adminer, phpmyadmin,..) créer un utilisateur.

Dupliquer le fichier env.test et le renommer en env.local

Vérifier votre version de mysql ou mariadb en éxécutant cette commande : `mysql -V`

Insérer cette ligne : 
`DATABASE_URL="mysql://identifiant:motdepasse@127.0.0.1:3306/qualiextra?serverVersion=mariadb-10.3.25&charset=utf8mb4'

Changer l'identifiant et le mot de passe avec les vôtres de la chaîne de connection .

3. Exécuter cette commande dans le terminal qui permet de créer la base de donnée
    `bin/console doctrine:database:create`

4. Afin de générer les table excuter ces commandes dans le terminal :
   `bin/console make:migration`
puis `bin/console doctrine:migration:migrate`

Votre BDD est prête.

## Programmer la commande de suppression

Il faut dans un premier temps configurer l'automatisation de la commande via le terminal.

`crontab -e`

```
no crontab for student - using an empty one

Select an editor.  To change later, run 'select-editor'.
  1. /bin/nano        <---- easiest
  2. /usr/bin/vim.basic
  3. /usr/bin/vim.tiny
  4. /usr/bin/code
  5. /bin/ed

Choose 1-5 [1]: 1
crontab: installing new crontab


# minute (m), hour (h), day of month (dom), month (mon),
# and day of week (dow) or use '*' in these fields (for 'any').
# 
# Notice that tasks will be started based on the cron's system
# daemon's notion of time and timezones.
# 
# Output of the crontab jobs (including errors) is sent through
# email to the user the crontab file belongs to (unless redirected).
# 
# For example, you can run a backup of all your user accounts
# at 5 a.m every week with:
# 0 5 * * 1 tar -zcf /var/backups/home.tgz /home/
# 
# For more information see the manual pages of crontab(5) and cron(8)
# 
# minute heure  utilisateur/bin/php chemin/vers/le/dosser/bin/console  command

0 12 * * * /usr/bin/php /var/www/html/index.html/Apotheose/projet-22-qualiextra/bin/console app:package:expired
```

Pour enregistrer CTRL +O et ENTREE

Pour quitter CTRL+x


Le projet est okay


