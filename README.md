<h1 align="center">Data Migration</h1>

<p align="center">
<a href="https://travis-ci.org/fndmiranda/data-migration"><img src="https://img.shields.io/travis/fndmiranda/data-migration.svg?style=flat-square"></a>
<a href="https://packagist.org/packages/fndmiranda/data-migration"><img src="https://poser.pugx.org/fndmiranda/data-migration/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/fndmiranda/data-migration"><img src="https://poser.pugx.org/fndmiranda/data-migration/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/fndmiranda/data-migration"><img src="https://poser.pugx.org/fndmiranda/data-migration/license.svg" alt="License"></a>
</p>

# Data migrations from Laravel

This package simplifies the migration and synchronization of data between the application and the database, 
allowing you to control for example settings or permissions lists. Provides resources to view the status and 
changes not yet made, migrate and synchronize the data.

## Installation

```
composer require fndmiranda/data-migration
```

## Usage

You may generate an data migration of the `data-migration:make` Artisan command:

```terminal
php artisan data-migration:make PermissionDataMigration
```

This command will generate a data migration at `app/DataMigrations/PermissionDataMigration.php`. The data migration will contain the `model`, `data`, and `options` methods.

```php
<?php

namespace App\DataMigrations;

use Fndmiranda\DataMigration\Contracts\DataMigration;

class PermissionDataMigration implements DataMigration
{
    /**
     * Order to execute this data-migration.
     *
     * @var int
     */
    protected $order = 0;
    
    /**
     * Tag to filter on data-migrations search.
     *
     * @var string
     */
    protected $tag = 'production';

    /**
     * Get the model being used by the data migration.
     *
     * @return string
     */
    public function model()
    {
        //
    }

    /**
     * Get the data being used by the data migration.
     *
     * @return mixed
     */
    public function data()
    {
        //
    }

    /**
     * Get the data options being used by the data migration.
     *
     * @return mixed
     */
    public function options()
    {
        //
    }
}
```

#### Property order (optional)

The order property defines the order of execution of data migrations class, default value is `0`.

#### Property tag (optional)

The `tag` property is used in filter of data-migrations search, default value is `production`, if another value is 
set, it will have to pass to the tag parameter when executing the command.

#### Method model

Method to specify the model bound to the data migration class.

```php
/**
 * Get the model being used by the data migration.
 *
 * @return string
 */
public function model()
{
    return \App\Permission::class;
}
```

#### Method data

Method to specify the data to be migrated.

```php
/**
 * Get the data being used by the data migration.
 *
 * @return mixed
 */
public function data()
{
    return [
       ['name' => 'product.products.index', 'title' => 'List products', 'group' => 'Product'],
       ['name' => 'product.products.show', 'title' => 'Show product', 'group' => 'Product'],
       ['name' => 'product.products.store', 'title' => 'Create product', 'group' => 'Product'],
       ['name' => 'product.products.update', 'title' => 'Update product', 'group' => 'Product'],
       ['name' => 'product.products.destroy', 'title' => 'Delete product', 'group' => 'Product'],

       ['name' => 'product.brands.index', 'title' => 'List brands', 'group' => 'Product'],
       ['name' => 'product.brands.show', 'title' => 'Show brand', 'group' => 'Product'],
       ['name' => 'product.brands.store', 'title' => 'Create brand', 'group' => 'Product'],
       ['name' => 'product.brands.update', 'title' => 'Update brand', 'group' => 'Product'],
       ['name' => 'product.brands.destroy', 'title' => 'Delete brand', 'group' => 'Product'],
   ];
}
```

#### Method options

The options method to specify the parameters to be used in the migration.

```php
/**
 * Get the data options being used by the data migration.
 *
 * @return mixed
 */
public function options()
{
    return [
       'identifier' => 'name',
       'show' => ['name', 'title'],
   ];
}
```

The following keys are available as options:

Key | Description | Type
--- | --- | ---
identifier | Column with unique value to validate status. | string
show | Columns to show in commands output. | array
relations | Relationships options, see the usage with relationships. | array

## Run a data migration

You can run a data migration via command or facade.

Show the status of each data with the database with `data-migration:status` Artisan command:

```terminal
php artisan data-migration:status
```

Specifying the paths to search for data migrations.

```terminal
php artisan data-migration:status --path=path/with/data/migrations --path=other/path/with/data/migrations
```

Specifying the tags to search for data migrations.

```terminal
php artisan data-migration:status --tag=staging --path=local
```

Run `data-migration:status` only specific a data migration.

```terminal
php artisan data-migration:status App\\DataMigrations\\PermissionDataMigration
```

Output:

```terminal
+--------------------------+------------------------+--------+
| name                     | title                  | status |
+--------------------------+------------------------+--------+
| product.products.index   | List products          | Create |
| product.products.show    | Show product           | OK     |
| product.products.store   | Create product updated | Update |
| product.products.destroy | Delete product         | OK     |
| product.brands.show      | Show brand             | Create |
| product.brands.store     | Create brand updated   | Update |
| product.brands.update    | Update brand           | OK     |
| product.brands.destroy   | Delete brand           | OK     |
| product.products.update  | Update product         | Delete |
| product.brands.index     | List brands            | Delete |
+--------------------------+------------------------+--------+
```

Or with `DataMigration` facade:

```php
$status = DataMigration::status(\App\DataMigrations\PermissionDataMigration::class);
```

Show changes between data migration and database with `data-migration:diff` Artisan command:

```terminal
php artisan data-migration:diff
```

Specifying the paths to search for data migrations.

```terminal
php artisan data-migration:diff --path=path/with/data/migrations --path=other/path/with/data/migrations
```

Specifying the tags to search for data migrations.

```terminal
php artisan data-migration:diff --tag=staging --path=local
```

Run `data-migration:diff` only specific a data migration.

```terminal
php artisan data-migration:diff App\\DataMigrations\\PermissionDataMigration
```

Output:

```terminal
+--------------------------+------------------------+--------+
| name                     | title                  | status |
+--------------------------+------------------------+--------+
| product.products.index   | List products          | Create |
| product.products.store   | Create product updated | Update |
| product.brands.show      | Show brand             | Create |
| product.brands.store     | Create brand updated   | Update |
| product.products.update  | Update product         | Delete |
| product.brands.index     | List brands            | Delete |
+--------------------------+------------------------+--------+
```

Or with `DataMigration` facade:

```php
$diff = DataMigration::diff(\App\DataMigrations\PermissionDataMigration::class);
```

Migrate data from a data migration to the database. Only necessary operations with status to create will be executed 
with `data-migration:migrate` Artisan command:

```terminal
php artisan data-migration:migrate
```

Specifying the paths to search for data migrations.

```terminal
php artisan data-migration:migrate --path=path/with/data/migrations --path=other/path/with/data/migrations
```

Specifying the tags to search for data migrations.

```terminal
php artisan data-migration:migrate --tag=staging --path=local
```

Run `data-migration:migrate` only specific a data migration.

```terminal
php artisan data-migration:migrate App\\DataMigrations\\PermissionDataMigration
```

Output:

```terminal
+--------------------------+------------------------+--------+
| name                     | title                  | status |
+--------------------------+------------------------+--------+
| product.products.index   | List products          | Create |
| product.brands.show      | Show brand             | Create |
+--------------------------+------------------------+--------+
```

Or with `DataMigration` facade:

```php
$migrated = DataMigration::migrate(\App\DataMigrations\PermissionDataMigration::class);
```

Synchronize data from a data migration with the database. All necessary `create`, `update`, and `delete` operations will be 
performed with `data-migration:sync` Artisan command:

```terminal
php artisan data-migration:sync
```

Specifying the paths to search for data migrations.

```terminal
php artisan data-migration:sync --path=path/with/data/migrations --path=other/path/with/data/migrations
```

Specifying the tags to search for data migrations.

```terminal
php artisan data-migration:sync --tag=staging --path=local
```

Run `data-migration:sync` only specific a data migration.

```terminal
php artisan data-migration:sync App\\DataMigrations\\PermissionDataMigration
```

Output:

```terminal
+--------------------------+------------------------+--------+
| name                     | title                  | status |
+--------------------------+------------------------+--------+
| product.products.index   | List products          | Create |
| product.products.store   | Create product updated | Update |
| product.brands.show      | Show brand             | Create |
| product.brands.store     | Create brand updated   | Update |
| product.products.update  | Update product         | Delete |
| product.brands.index     | List brands            | Delete |
+--------------------------+------------------------+--------+
```

Or with `DataMigration` facade:

```php
$synchronized = DataMigration::sync(\App\DataMigrations\PermissionDataMigration::class);
```

Show a list of data-migrations with `data-migration:list` Artisan command:

```terminal
php artisan data-migration:list
```

Specifying the paths to search for data migrations.

```terminal
php artisan data-migration:list --path=path/with/data/migrations --path=other/path/with/data/migrations
```

Specifying the tags to search for data migrations.

```terminal
php artisan data-migration:list --tag=staging --path=local
```

## Usage with relationships

Example of a permissions model with a relationship for dependencies of type belongsToMany with pivot_example_1 and 
pivot_example_2, and a relationship for brand of type belongsTo to exemplify a data migration.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'group', 'brand_id',
    ];

    /**
     * The dependencies that belong to the permission.
     */
    public function dependencies()
    {
        return $this->belongsToMany(Permission::class)->withPivot(['pivot_example_1', 'pivot_example_2']);
    }

    /**
     * Get the brand of the permission.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
```

#### Method data with relationships

The data method to specify the data to be migrated with relationships.

```php
/**
 * Get the data being used by the data migration.
 *
 * @return mixed
 */
public function data()
{
    return [
       ['name' => 'product.products.index', 'title' => 'List products', 'group' => 'Product', 'brand' => ['name' => 'Brand test 1']],
       ['name' => 'product.products.show', 'title' => 'Show product', 'group' => 'Product'],
       ['name' => 'product.products.store', 'title' => 'Create product', 'group' => 'Product', 'dependencies' => [
           ['name' => 'product.brands.index', 'pivot_example_1' => 'Pivot value 1'], ['name' => 'product.categories.index'],
       ], 'brand' => ['name' => 'Brand test 2']],
       ['name' => 'product.products.update', 'title' => 'Update product', 'group' => 'Product', 'dependencies' => [
           ['name' => 'product.brands.index'], ['name' => 'product.categories.index', 'pivot_example_2' => 'Pivot value 2'],
       ]],
       ['name' => 'product.products.destroy', 'title' => 'Delete product', 'group' => 'Product'],

       ['name' => 'product.brands.index', 'title' => 'List brands', 'group' => 'Product', 'brand' => ['name' => 'Brand test 1']],
       ['name' => 'product.brands.show', 'title' => 'Show brand', 'group' => 'Product'],
       ['name' => 'product.brands.store', 'title' => 'Create brand', 'group' => 'Product'],
       ['name' => 'product.brands.update', 'title' => 'Update brand', 'group' => 'Product', 'brand' => ['name' => 'Brand test 2']],
       ['name' => 'product.brands.destroy', 'title' => 'Delete brand', 'group' => 'Product'],
   ];
}
```

#### Method options with relationships

The options method with relationships to specify the parameters to be used in the data migration.

```php
/**
 * Get the data options being used by the data migration.
 *
 * @return mixed
 */
public function options()
{
    return [
       'identifier' => 'name',
       'show' => ['name', 'title'],
       'relations' => [
           [
               'type' => 'belongsToMany',
               'relation' => 'dependencies',
               'identifier' => 'name',
               'show' => ['name'],
           ],
           [
               'type' => 'belongsTo',
               'relation' => 'brand',
           ],
       ],
   ];
}
```

The following keys are available as relationships options:

Key | Description | Type
--- | --- | ---
relation | Name of the relationship of the model. | string
type | Model relationship type, `belongsToMany` or `belongsTo`. | string
identifier | Column with unique value to validate status (only with `belongsToMany`). | string
show | Columns to show in commands output (only with `belongsToMany`). | array

## Events

Events when start and finish are available for when running a `data-migration:migrate` or `data-migration:sync` Artisan command.

### onStartMigrate

Create the `onStartMigrate` method in your data migration to be called before the `data-migration:migrate` Artisan command is executed.

### onFinishMigrate

Create the `onFinishMigrate` method in your data migration to be called after the `data-migration:migrate` Artisan command is executed.

### onStartSync

Create the `onStartSync` method in your data migration to be called before the `data-migration:sync` Artisan command is executed.

### onFinishSync

Create the `onFinishSync` method in your data migration to be called after the `data-migration:sync` Artisan command is executed.

## Testing

```terminal
composer test
```

## Security

If you discover any security related issues, please email fndmiranda@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
