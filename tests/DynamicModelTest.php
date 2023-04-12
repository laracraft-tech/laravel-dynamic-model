<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use LaracraftTech\LaravelDynamicModel\Tests\MyExtendedDynamicModel;

beforeEach(function () {
    Schema::create('foo', function (Blueprint $table) {
        $table->id();
        $table->string('col1');
        $table->integer('col2');
        $table->timestamps();
    });

    Schema::create('bar', function (Blueprint $table) {
//        $table->id();
        $table->date('period')->primary();
        $table->string('col1');
        $table->integer('col2');
        $table->timestamps();
    });
});

it('can use different tables with different primary keys', function () {
    // foo
    $foo = app(DynamicModelFactory::class)->create(DynamicModel::class, 'foo');

    $foo->create([
        'col1' => 'asdf',
        'col2' => 123,
    ]);

    expect($foo->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123);

    // bar
    $bar = app(DynamicModelFactory::class)->create(DynamicModel::class, 'bar');

    $bar->create([
        'period' => '2023-04-13',
        'col1' => 'fdsa',
        'col2' => 321,
    ]);

    expect($bar->find('2023-04-13'))
        ->period->toBe('2023-04-13')
        ->col1->toBe('fdsa')
        ->col2->toBe(321);
});

it('can reuse already bound variables', function () {
    // foo
    $foo = app(DynamicModelFactory::class)->create(DynamicModel::class, 'foo');

    $foo->create([
        'col1' => 'asdf',
        'col2' => 123,
    ]);

    expect($foo->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123);

    // bar
    $bar = app(DynamicModelFactory::class)->create(DynamicModel::class, 'bar');

    $bar->create([
        'period' => '2023-04-13',
        'col1' => 'fdsa',
        'col2' => 321,
    ]);

    expect($bar->find('2023-04-13'))
        ->period->toBe('2023-04-13')
        ->col1->toBe('fdsa')
        ->col2->toBe(321)
        ->and($foo->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123);

    //retry foo
});

it('can use extended dynamic model', function () {
    $myDynamicModel = app(DynamicModelFactory::class)->create(MyExtendedDynamicModel::class, 'foo');

    $myDynamicModel->create([
        'col1' => 'asdf',
        'col2' => 123,
    ]);

    expect($myDynamicModel->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123)
        ->doSomething()->toBe('foo');
});
