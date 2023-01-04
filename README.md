# Laravel Share To
Generate and render social share buttons without any hassle

## Installation
```bash
composer require prajwal89/laravel-share-to
```

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php\
This step is not required on Laravel 5.5 and above 
```php
// config/app.php
'providers' => [
    Prajwal89\LaravelShareTo\LaravelShareToServiceProvider::class,
];
```

## Publish assets
```bash
php artisan vendor:publish --provider="Prajwal89\LaravelShareTo\LaravelShareToServiceProvider"
```

Run database migration
```bash
php artisan migrate
```


## usage
```php
use Prajwal89\LaravelShareTo\Share;

$options = [
 'tracking' => true
];

echo Share::Page('McqMate - MCQ Portal for Students', 'https://mcqmate.com', $options)->all()->getButtons();

```

