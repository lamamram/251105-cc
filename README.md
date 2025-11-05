# Scenario PHP/CodeIgniter4

## prérequis

php           : 8.3
php extensions: ./php.ini dans exemple
```ini
extension=intl
extension=mbstring
extension=sqlite3
extension=curl
```
> php_dir\ext\php_sqlite3.dll must be in the PATH
> copier php_sqlite3 dans php_dir qui est dans le PATH

db            : sqlite3
deps          : ./composer.json => en particulier `phpunit, php-cs-fixer, codeigniter4/shield`

## installation

1. copier le dossier `project` dans le dossier `workdir`
2. déplacer le `.gitignore` du dossier `project` dans le dossier `workdir`
3. déplacer le dossier `issues` du dossier `project` dans le dossier `workdir`

## lancement 

> si php et composer installés avec les prérequis

```bash
cd project
composer install
php spark serve --host project.lan --port 8080
```

## BONUS: construire une image docker custom pour le pipeline

1. dans la VM: dans le dossier `workdir`: `vagrant ssh`
2. dans le dossier `/vagrant/project/php-cicd`
3. `docker login gitlab.lan.fr:5050 -u root -p R00tt00R`
4. `docker build -t gitlab.lan.fr:5050/formation/dev/php-cicd:8.3 . --push`

> voir l'image dans le project -> Deploy -> Container Registry
