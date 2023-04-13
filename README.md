# Dynamic Model for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laracraft-tech/laravel-dynamic-model.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-dynamic-model)
[![Tests](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/run-tests.yml)
[![Check & fix styling](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/laracraft-tech/laravel-dynamic-model/actions/workflows/fix-php-code-style-issues.yml)
[![License](https://img.shields.io/packagist/l/laracraft-tech/laravel-dynamic-model.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-dynamic-model)
<!--[![Total Downloads](https://img.shields.io/packagist/dt/laracraft-tech/laravel-dynamic-model.svg?style=flat-square)](https://packagist.org/packages/laracraft-tech/laravel-dynamic-model)-->


Normally, each model in Laravel is written for only one table, and it's not so easy to break this convention.
This is for a good reason - it ensures a well-designed and clean model.
But in very specific cases, you may need to handle multiple tables via a single model.
Here **Laravel Dynamic Model** comes into play!
It provides you with an eloquent model which finally can handle multiple tables
and if you want also multiple database connections!

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

---

``` bash
php artisan make:migration create_foo_table
php artisan make:migration create_bar_table
```

Create migrations for the tables:

``` php
Schema::create('foo', function (Blueprint $table) {
    $table->id();
    $table->string('col1');
    $table->integer('col2');
    $table->timestamps();
});

Schema::create('bar', function (Blueprint $table) {
    $table->date('period')->primary();
    $table->string('col1');
    $table->integer('col2');
    $table->timestamps();
});
```

``` bash
php artisan migrate
```

### Let's create a Dynamic Model

---

If you want to create a **Dynamic Model** then you have to use the **DynamicModelFactory**.
The Factory ensures, that the `table` and optionally the `connection` gets set for your new created model.
Also it checks the schema of your provided table to set the propper values for: `primaryKey`, `keyType`, `incrementing`.
Means, also if you defined your table schema to have a primary key called for instance __period__ with a __date__ type, the Factory will handle it for you.

Note that the default DynamicModel is set to **unguarded**.
If you do not like this or you want your Dynamic Models have some custom functions,
check the section below and create your own Dynamic Model.

``` php
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;

$foo = app(DynamicModelFactory::class)->create(DynamicModel::class, 'foo');

$foo->create([
    'col1' => 'asdf',
    'col2' => 123
]);

$faz = app(DynamicModelFactory::class)->create(DynamicModel::class, 'faz');

$faz->create([
    'period' => '2023-01-01',
    'col1' => 'asdf',
    'col2' => 123
]);

// optionally use another db connection (this one must be defined in your config/database.php file)
$fooOtherDB = app(DynamicModelFactory::class)->create(DynamicModel::class, 'foo', 'mysql2');
$fooOtherDB->create([...]);

dump($foo->first());
dump($faz->first());
dump($fooOtherDB->first());
```

Which gives you:

```
^ LaracraftTech\LaravelDynamicModel\DynamicModel_mysql_foo {#328 ▼
  #connection: "mysql"
  #table: "foo"
  #primaryKey: "id"
  #keyType: "int"
  +incrementing: true
  #attributes: array:5 [▼
    "id" => 1
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2023-03-22 15:34:22"
    "updated_at" => "2023-03-22 15:34:22"
  ]
}

^ LaracraftTech\LaravelDynamicModel\DynamicModel_mysql_faz {#328 ▼
  #connection: "mysql"
  #table: "faz"
  #primaryKey: "period"
  #keyType: "string"
  +incrementing: false
  #attributes: array:5 [▼
    "period" => "2023-01-01"
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2023-03-22 15:34:22"
    "updated_at" => "2023-03-22 15:34:22"
  ]
}

^ LaracraftTech\LaravelDynamicModel\DynamicModel_mysql2_foo {#328 ▼
  #connection: "mysql2"
  #table: "foo"
  #primaryKey: "id"
  #keyType: "int"
  +incrementing: true
  #attributes: array:5 [▼
    "id" => 1
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2023-03-22 15:34:22"
    "updated_at" => "2023-03-22 15:34:22"
  ]
}
```

### Use your own Dynamic Model

---

If you need to add **custom** methods to your Dynamic Model or maybe **guard** it,
you can just create your own Eloquent model and then call it through the **Factory**.
This will create a new Model instance, which is **extended** by your original model.
Make sure your model is implementing the **DynamicModelInterface** or is extended by the **DynamicModel** class.

``` php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelInterface;

// class MyDynamicModel extends Model implements DynamicModelInterface (this would also work)
class MyDynamicModel extends DynamicModel
{
    proteced $guarded = ['id'];

    public function doSomething()
    {
        // do something
    }
}

$foo = app(DynamicModelFactory::class)->create(MyDynamicModel::class, 'foo');

$foo->create([
    'col1' => 'asdf',
    'col2' => 123
]);

$foo->doSomething();

dd($foo->first());
```

Which gives you:

```
^ App\Model\MyDynamicModel_mysql_foo {#328 ▼
  #connection: "mysql"
  #table: "foo"
  #primaryKey: "id"
  #keyType: "int"
  +incrementing: true
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
