
# WolfAPI

This package converts your Laravel application to an API; it bundles by default all the necessary features to work as an API service.

## How it works

This package provides a set of useful classes and magical utilities to convert your application
into a REST API, providing support by default for Laravel Modules, JWT authentication, user roles and permissions,
custom error handling, and a guaranteed response in JSON.

It's highly recommended that you use Laravel Modules to scaffold your application in order to maintain
a properly organized structure, and allowing you to manage your API easily.

## Installation

You can install the package via composer:

```bash
composer require ignitionwolf/wolf-api
```

## Usage

### Models

You can create your model using ```php artisan module:make-model [name] [module]```.

### Controllers, routes, and handle requests


## Package implementations

### Laravel Scout

This package supports 2 drivers by default: [PostgreSQL](https://github.com/pmatseykanets/laravel-scout-postgres) and [Elastic Cache](https://github.com/babenkoivan/elastic-scout-driver).
The configuration will be detected automatically by WolfAPI and the filtering strategies will be chosen accordingly.

There is also two new commands to handle entities indices per module.
```
php artisan module:scout:flush [model name / "*" for all] [module / "*" for all]
php artisan module:scout:import [model name / "*" for all] [module / "*" for all]
```

### Laravel Socialite

This package uses Socialite to interact with the social media providers to obtain data from an OAuth token, it is expected that you obtain this token in the front end and then pass it to the `api/auth/social` route. 

Besides that, you need to add a `registration_source NOT NULL DEFAULT 'email'` column in your users table to determine if the user registered via social media or regular e-mail registration.   

### Laravel Modules

### Bouncer

### JWT

## Testing

``` bash
composer test
```
