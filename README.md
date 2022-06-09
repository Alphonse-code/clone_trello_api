1. Install application dependencies:

```bash
composer install
```

2. Configure parameters in the `config/packages/doctrine.yaml` and `.env` files according to your needs.
3. Generate secret keys for JWT (a pass phrase should be match the `JWT_PASSPHRASE` parameter from your `.env` file):

```bash
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

4. Create the database:

```bash
php bin/console doctrine:database:create
```

5. Create the schema migration and migrate it:

```bash
php bin/console make:migrations
php bin/console doctrine:migrations:migrate
```
