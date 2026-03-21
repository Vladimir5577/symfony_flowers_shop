Install Symfony.
$ composer create-project symfony/skeleton:"8.0.*" my_project_directory
$ mv my_project_directory/* .
$ mv my_project_directory/.* .
$ rm -R my_project_directory

$ composer require webapp

// ======================================
    Security

$ composer require symfony/security-bundle
$ php bin/console make:user
then add needed fields


// =======================================
    Admin panel

$ php bin/console make:security:form-login





$ composer require easycorp/easyadmin-bundle


composer require liip/imagine-bundle
  composer require vich/uploader-bundle
  composer require doctrine/doctrine-fixtures-bundle


// =======================================

php bin/console make:migration
php bin/console doctrine:migrations:migrate

    Load fixtures:
$ php bin/console doctrine:fixtures:load


// ========================================
    Create admin
$   3. Админка — защищена access_control: { path: ^/admin, roles: ROLE_ADMIN }. API (/api/) — без авторизации. Создание админа:
    php bin/console cache:clear
    php bin/console app:create-admin admin 1234

// =========================================

    Run Dbgate
$ docker compose -f docker-compose.dbgate.yml up
