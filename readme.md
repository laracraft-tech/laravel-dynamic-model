# Laravel Dynamic Model

<p align="left">
<!--<a href="https://packagist.org/packages/sairahcaz/laravel-dynamic-model"><img src="https://img.shields.io/packagist/dt/sairahcaz/laravel-dynamic-model" alt="Total Downloads"></a>-->
<a href="https://packagist.org/packages/sairahcaz/laravel-dynamic-model"><img src="https://img.shields.io/packagist/v/sairahcaz/laravel-dynamic-model" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/sairahcaz/laravel-dynamic-model"><img src="https://img.shields.io/packagist/l/sairahcaz/laravel-dynamic-model" alt="License"></a>
</p>

## Introduction

Normally, each model in Laravel is written for only one table, and it's not so easy to break this convention. This is for a good reason - it ensures a well designed and clean model. But in very specific cases, you may need to handle multiple tables via a single model. Here **Laravel Dynamic Model** comes into play! It provides you with an eloquent model which finally can handle multiple database tables!


**Warning**: this is only a good approach if you really know what you're doing and you have no other option! But in case you really dynamically need to create and handle multiple tables, this package might be a good choice for you!

## Installation

### Dependencies

This package depends on Doctrine/DBAL, so make sure you have it or install it.

``` bash
$ composer require doctrine/dbal
```

### Package

``` bash
$ composer require sairahcaz/laravel-dynamic-model
$ php artisan vendor:publish --provider="Sairahcaz\LaravelDynamicModel\DynamicModelServiceProvider" --tag="config"
```


## Usage

### Lets create some dummy tables:

``` bash
$ php artisan make:migration create_foo_table
$ php artisan make:migration create_faz_table
$ php artisan make:migration create_bar_table
$ php artisan make:migration create_baz_table
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
$ php artisan migrate
```

### Lets use our Dynamic Model:


``` php
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
^ Sairahcaz\LaravelDynamicModel\DynamicModel {#328 ▼
  #connection: "mysql"
  #table: "foo"
  #primaryKey: "id"
  #keyType: "integer"
  +incrementing: true
  #with: []
  #withCount: []
  +preventsLazyLoading: false
  #perPage: 15
  +exists: true
  +wasRecentlyCreated: false
  #escapeWhenCastingToString: false
  #attributes: array:5 [▼
    "id" => 1
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2022-09-22 15:34:22"
    "updated_at" => "2022-09-22 15:34:22"
  ]
  #original: array:5 [▼
    "id" => 1
    "col1" => "asdf"
    "col2" => 123
    "created_at" => "2022-09-22 15:34:22"
    "updated_at" => "2022-09-22 15:34:22"
  ]
  #changes: []
  #casts: []
  #classCastCache: []
  #attributeCastCache: []
  #dates: []
  #dateFormat: null
  #appends: []
  #dispatchesEvents: []
  #observables: []
  #relations: []
  #touches: []
  +timestamps: true
  #hidden: []
  #visible: []
  #fillable: []
  #guarded: []
  #routeKeyName: "id"
}
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email zacharias.creutznacher@gmail.com instead of using the issue tracker.

## Credits

- [Zacharias Creutznacher][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sairahcaz/laravel-dynamic-model.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sairahcaz/laravel-dynamic-model.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/sairahcaz/laravel-dynamic-model/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/sairahcaz/laravel-dynamic-model
[link-downloads]: https://packagist.org/packages/sairahcaz/laravel-dynamic-model
[link-travis]: https://travis-ci.org/sairahcaz/laravel-dynamic-model
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/sairahcaz
[link-contributors]: ../../contributors
