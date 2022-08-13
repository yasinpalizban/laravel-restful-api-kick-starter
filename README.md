# Laravel 9 Modular RestFull Api Kick Starter

Laravel restful api kick start is amazing  project, very power full  scaffolding to develop any application without being worry about how project is getting bigger and bigger. 

### Instructions

- Run Migrations

```
php artisan module:migrate Auth
php artisan module:migrate Common
```

- Run seeders

```
php artisan module:make-seed GroupSeeder Auth
php artisan module:make-seed UsersSeeder Auth
php artisan module:make-seed UsersGroupsSeeder Auth
php artisan module:make-seed PermissionSeeder Auth
php artisan module:make-seed UsersPermissionSeeder Auth
php artisan module:make-seed GroupsPermissionSeeder Auth
```



### Run Application

```
php artisan serve

```

### Extra Information

You can sign in via Email Or Phone Or Username </br>

- Default user email admin@admin.com
- Default user phone 0918000
- Default user username admin
- Default user password pass


#### Packages

- [laravel](https://laravel.com/)
- [php-jwt](https://github.com/firebase/php-jwt)
- [recaptcha](https://github.com/google/recaptcha)
- [pusher-php-server](https://github.com/pusher/pusher-http-php)
- [laravel-modules](https://github.com/nWidart/laravel-modules)

#### Last Words

The code has been tested on live and localhost too, you don't worry about it. :-)
