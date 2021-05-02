# WolfAPI

This is meant to ease the friction when developing restful APIs with Laravel, by simply installing this package you
will be able to serve an API service without much hassle.

## How it works

This package focuses on handling boilerplate when creating a REST API, most of the magic occurs in the `CRUDController`
which is in charge of handling create, read, update, delete, and list requests for a specific model. This follows
Laravel conventions, so you will also need to create a FormRequest for each action which will handle validation
and authorization. Of course, this package provides a set of commands to help with that.

In the background, this uses Responder package for building the JSON responses, and a custom exception handler which
intercepts thrown exceptions and outputs them as JSON accordingly.

## Installation

You can install the package via composer:

``` bash
$ composer require ignitionwolf/wolf-api
$ php artisan clear-compiled
```

## Usage

### Create an API resource

You can create the CRUD controller file, form requests, model, and route definitions by running this command:

``` bash
$ php artisan make:crud {name}
```

You can also run each make command individually and define the routes manually like you usually do with Laravel.

### Extending the controller

When you create a controller with ```bash php artisan make:crud``` or ```php artisan make:controller --api``` you will
be able to see a class like this:

```php
class DummyController extends CRUDController
{
    /**
     * Points to the model to be handled in the controller.
     *
     * @var string
     */
    protected string $model = \App\Models\Dummy:class;

    /**
     * List of sortable attributes for the list endpoint.
     *
     * @var array
     */
    protected array $allowedSorts = [];

    /**
     * List of filterable attributes for the list endpoint.
     *
     * @var array
     */
    protected array $allowedFilters = [];
}
```

The ```$allowedSorts``` and ```$allowedFilters``` are used for listing purposes, this uses
[spatie's laravel-query-builder](https://github.com/spatie/laravel-query-builder) internally to generate the data
for the ``` GET /api/model ``` route.


Behind scenes, the ```CRUDController``` grabs validated data from the FormRequest and uses it to fill the model data accordingly.
There isn't more than that, therefore if you need to extend the functionality of the controller in order to implement
business logic or more complex actions then you can use pre and post hooks for a specific action, for example:

```php
public function onPreUpdate(Request $request, Model $model)
{
    $model->times_updated ++;
}
```

You can check out the [WithHooks trait](./src/Concerns/WithHooks.php) in order to see which hooks you can use.

It's also worthy to note that you're not restricted to using hooks to extend a model's functionality for specific actions
but that you can also use Laravel events and listeners, or the Automap pattern.

#### Automap

There are some repetitive actions when creating a model. For example, let's say you want to keep record on who created
certain entities.

```bash
$ php artisan make:automap CreatedByAttribute
```

```php
# file: app/Models/Dummy.php
class Dummy extends Model
{
    use \IgnitionWolf\API\Automap\Automapable;
    
    protected $map = [
        'created_by' => \App\Automap\CreatedByAttribute::class
    ];
}
```

```php
# file: app/Automap/CreatedByAttribute.php
class CreatedByAttribute implements AutomapInterface
{
    public function map(Model $entity, string $attribute)
    {
        // This will assign created_by to the current authenticated user.
        $entity->${attribute} = auth()->user();
    }
}
```

## Alternatively, dingo

Another known solution is [dingoapi](https://github.com/dingo/api). Contrary to dingoapi, WolfAPI tries to stay ignorant
about implementation details and business logic, for example, authentication or authorization/permission systems. You're
free to develop your API however you want, with a small-to-none learning curve as long as you're comfortable
with Laravel principles and best practices, while WolfAPI removes the overhead of writing and maintaining boilerplate code.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please send an e-mail to mauricio@ignitionwolf.com in order to report security vulnerabilities.

## Credits

- [IgnitionWolf](https://github.com/IgnitionWolf)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
