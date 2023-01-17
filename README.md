# Laravel Share To
Generate and render social share buttons without any hassle

**Live Preview**
https://laravel-share-to.prajwalhallale.com

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

**Publish assets**
```bash
php artisan vendor:publish --provider="Prajwal89\LaravelShareTo\LaravelShareToServiceProvider"
```

**Run database migration**
```bash
php artisan migrate
```


## Usage
```php
use Prajwal89\LaravelShareTo\Share;

$options = [

    'tracking' => true, //this will keep track of share in track_shares table 

    //options for container
    'buttonGap' => 10, //in px
    'alignment' => 'center', // accepts (start|center|end) alignment of of buttons in container

    //options for button
    'borderWidth' => 2,
    'radius' => 4,
    'paddingX' => 4,
    'paddingY' => 8,
];

echo Share::Page('McqMate - MCQ Portal for Students', 'https://mcqmate.com', $options)->all()->getButtons();


```