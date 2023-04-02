<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use LaracraftTech\LaravelDynamicModel\Tests\MyExtendedDynamicModel;
use LaracraftTech\LaravelDynamicModel\Tests\MyTraitedDynamicModel;

beforeEach(function () {
    Schema::create('foo', function (Blueprint $table) {
        $table->id();
        $table->string('col1');
        $table->integer('col2');
        $table->timestamps();
    });

    Schema::create('bar', function (Blueprint $table) {
        $table->id();
        $table->string('col1');
        $table->integer('col2');
        $table->timestamps();
    });
});

it('can use different tables', function () {
    // foo
    $foo = App::make(DynamicModel::class, ['table_name' => 'foo']);

    $foo->create([
        'col1' => 'asdf',
        'col2' => 123,
    ]);

    expect($foo->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123);

    // bar
    $bar = App::make(DynamicModel::class, ['table_name' => 'bar']);

    $bar->create([
        'col1' => 'fdsa',
        'col2' => 321,
    ]);

    expect($bar->find(1))
        ->col1->toBe('fdsa')
        ->col2->toBe(321);
});

it('can use extended dynamic model', function () {
    $myDynamicModel = App::make(DynamicModelFactory::class)->create(MyExtendedDynamicModel::class, 'foo');

    $myDynamicModel->create([
        'col1' => 'asdf',
        'col2' => 123,
    ]);

    expect($myDynamicModel->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123)
        ->doSomething()->toBe('foo');
});

it('can use traited dynamic model', function () {
    $myDynamicModel = App::make(DynamicModelFactory::class)->create(MyTraitedDynamicModel::class, 'foo');

    $myDynamicModel->create([
        'col1' => 'asdf',
        'col2' => 123,
    ]);

    expect($myDynamicModel->find(1))
        ->col1->toBe('asdf')
        ->col2->toBe(123)
        ->doSomethingBase()->toBe('bar');
});
