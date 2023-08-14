# Welcome to the cinema club!
[![PHP 8.1](https://img.shields.io/badge/php-8.1-%23777BB4?style=for-the-badge&logo=php&logoColor=black">)](https://www.php.net/releases/8_1_0.php)
[![MariaDB 10.3](https://img.shields.io/badge/MariaDB-10.3-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.com/kb/en/mariadb-10332-release-notes/)
[![Nginx 1.17](https://img.shields.io/badge/nginx-1.17-%23009639.svg?style=for-the-badge&logo=nginx&logoColor=white)](https://nginx.org/en/CHANGES-1.18)
[![Symfony 6.1](https://img.shields.io/badge/symfony-6.1-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white)](https://symfony.com/doc/6.1)
[![Yarn](https://img.shields.io/badge/yarn-%232C8EBB.svg?style=for-the-badge&logo=yarn&logoColor=white)](https://www.npmjs.com/package/yarn/v/1.22.5)

#### How to init:
```angular2html
make init

cd project && docker-compose run --rm php-cli bash 

symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

#### Admin panel
```angular2html
path: /admin

login: cinema-club@admin.info
password: super-password
```
