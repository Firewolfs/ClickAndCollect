# ClickAndCollect
Site Click &amp; Collect Symfony LP METINET
Groupe : OVEJERO Daniel - LEGUILLIER Christopher

## Initialisation du projet
Installer les bundles
```
composer install
```

Initialiser le .env
- Pour la base de données
```
DB_USER=
DB_PASSWORD=
DB_HOST=
DB_PORT=
DB_NAME=
DATABASE_URL="mysql://${DB_USER}:${DB_PASSWORD}@${DB_HOST}:${DB_PORT}/${DB_NAME}?serverVersion=5.7"
```
- Pour l'envoi de mail
```
MAILER_SMTP=
MAILER_SMTP_PORT=
MAILER_SMTP_ENCRYPTION=
MAILER_USER=
MAILER_PASSWORD=
MAILER_URL=smtp://${MAILER_SMTP}:${MAILER_SMTP_PORT}?encryption=${MAILER_SMTP_ENCRYPTION}&auth_mode=login&username=${MAILER_USER}&password=${MAILER_PASSWORD}
```

Créer la Base de Données
```
php bin/console doctrine:database:create
```
Effectuer les migrations
```
php bin/console doctrine:migration:migrate
```
