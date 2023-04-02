# Dynamic Model for Laravel!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laracraft-tech/laravel-dynamic-model.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-dynamic-model)
[![Tests](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/run-tests.yml)
[![Check & fix styling](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/fix-php-code-style-issues.yml)
[![License](https://img.shields.io/packagist/l/laracraft-tech/laravel-dynamic-model.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-dynamic-model)
<!--[![Total Downloads](https://img.shields.io/packagist/dt/laracraft-tech/laravel-dynamic-model.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-dynamic-model)-->


Normally, each model in Laravel is written for only one table, and it's not so easy to break this convention.
This is for a good reason - it ensures a well-designed and clean model.
But in very specific cases, you may need to handle multiple tables via a single model.
Here **Laravel Dynamic Model** comes into play!
It provides you with an eloquent model which finally can handle multiple database tables!

## Installation

### Dependencies

This package depends on Doctrine/DBAL, so make sure you have it installed.

``` bash
composer require doctrine/dbal
```

### Package

``` bash
composer require laracraft-tech/laravel-dynamic-model
```

## Usage

### Let's create some dummy tables:

``` bash
php artisan make:migration create_foo_table
php artisan make:migration create_faz_table
php artisan make:migration create_bar_table
php artisan make:migration create_baz_table
```

Create migrations for all tables, for example:

``` php
...
Schema::create('foo', function (Blueprint $table) {
    $table->id();
    $table->string('col1');
    $table->integer('col2');
    $table->timestamps();
});
...
```

``` bash
php artisan migrate
```

### Let's use the Dynamic Model:

**Note** that the DynamicModel by default is set to **unguarded**.

``` php
use LaracraftTech\LaravelDynamicModel\DynamicModel;

$foo = App::make(DynamicModel::class, ['table_name' => 'foo']);

$foo->create([
    'col1' => 'asdf',
    'col2' => 123
]);

$faz = App::make(DynamicModel::class, ['table_name' => 'faz']);
$faz->create([...]);

$bar = App::make(DynamicModel::class, ['table_name' => 'bar']);
$bar->create([...]);

$baz = App::make(DynamicModel::class, ['table_name' => 'baz']);
$baz->create([...]);

dd($foo->first());
```

Which gives us:

```
^ LaracraftTech\LaravelDynamicModel\DynamicModel {#328 ▼
  ...
  #attributes: array:5 [▼
    "id" => 1
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2023-03-22 15:34:22"
    "updated_at" => "2023-03-22 15:34:22"
  ]
  ...
}
```

### Use your own dynamic model

If you need to add methods to a dynamic model,
you can just create your own Eloquent model and **extend** it by the **DynamicModel**
or if the model is already extended by another model, you can just use the **DynamicModelBinding** trait.

If you use the trait, make sure your model implements the **DynamicModelInterface**.
Also make sure to resolve these models through the **DynamicModelFactory**!

``` php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use LaracraftTech\LaravelDynamicModel\DynamicModelBinding;
use LaracraftTech\LaravelDynamicModel\DynamicModelInterface;

# option 1: use extends
class MyDynamicModel extends DynamicModel
{
    public function doSomething()
    {
        // do something
    }
}

# option 2: use the trait
class MyDynamicModel extends SomeBaseModel implements DynamicModelInterface
{
    use DynamicModelBinding;

    public function doSomething()
    {
        // do something
    }
}

$foo = app(DynamicModelFactory::class)->create(MyDynamicModel::class, 'foo')

$foo->create([
    'col1' => 'asdf',
    'col2' => 123
]);

dd($foo->first());
```

Which gives us:

```
^ App\Model\MyDynamicModel {#328 ▼
  ...
  #attributes: array:5 [▼
    "id" => 1
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2023-03-22 15:34:22"
    "updated_at" => "2023-03-22 15:34:22"
  ]
  ...
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Zacharias Creutznacher](https://github.com/laracraft-tech)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
