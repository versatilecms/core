# Versatile

Laravel Admin & BREAD System (Browse, Read, Edit, Add, & Delete), supporting Laravel 5.4, 5.5 and 5.6!

Disclaimer (pt_BR)
==========
Este repositório é uma versão modificada do pacote [tcg/voyager](https://github.com/the-control-group/voyager). Diversas mudanças foram realizadas para uma melhor integração com as demais libs do projeto [Versatile](https://github.com/versatilecms).

## Algumas das principais mudanças:
- Atualização da interface do painel
- Separação do módulo de páginas
- Separação do módulo de posts
- Separação dos componentes de listas
- Separação da classe de LogViewer
- `Filters` customizados
- `Actions` customizados
- Recuperação de senha
- Registro de usuários
- Atualização dos componentes de forms
- Padronização dos `BreadsSeeders`
- Implementação do pacote [versatilelibs/laravel-searchable](https://github.com/versatilelibs/laravel-searchable)

<hr>

## Installation

### 1. Require the Package

After creating your new Laravel application you can include the Voyager package with the following command: 

```bash
composer require versatilecms/core
```

### 2. Add the DB Credentials & APP_URL

Next make sure to create a new database and add your database credentials to your .env file:

```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

You will also want to update your website URL inside of the `APP_URL` variable inside the .env file:

```
APP_URL=http://localhost:8000
```

> Only if you are on Laravel 5.4 will you need to [Add the Service Provider.](https://versatile.readme.io/docs/adding-the-service-provider)

### 3. Run The Installer

Lastly, we can install versatile. You can do this either with or without dummy data.
The dummy data will include 1 admin account (if no users already exists), 1 demo page, 4 demo posts, 2 categories and 7 settings.

To install Voyager without dummy simply run

```bash
php artisan versatile:install
```

If you prefer installing it with dummy run

```bash
php artisan versatile:install --with-dummy
```

> Troubleshooting: **Specified key was too long error**. If you see this error message you have an outdated version of MySQL, use the following solution: https://laravel-news.com/laravel-5-4-key-too-long-error

And we're all good to go!

Start up a local development server with `php artisan serve` And, visit [http://localhost:8000/admin](http://localhost:8000/admin).

## Creating an Admin User

If you did go ahead with the dummy data, a user should have been created for you with the following login credentials:

>**email:** `admin@admin.com`   
>**password:** `password`

NOTE: Please note that a dummy user is **only** created if there are no current users in your database.

If you did not go with the dummy user, you may wish to assign admin privileges to an existing user.
This can easily be done by running this command:

```bash
php artisan versatile:admin your@email.com
```

If you did not install the dummy data and you wish to create a new admin user you can pass the `--create` flag, like so:

```bash
php artisan versatile:admin your@email.com --create
```

And you will be prompted for the user's name and password.
