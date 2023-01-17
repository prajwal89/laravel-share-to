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

$share =  Share::Page('McqMate - MCQ Portal for Students', 'https://mcqmate.com', $options);

echo $share->all()->getButtons();

```

**Get all available buttons**
```php
echo $share->all()->getButtons();
```

**Get single button**
```php
echo $share->facebook()->getButtons();
```

**Get multiple buttons**
```php
echo $share->whatsapp()->twitter()->getButtons();
//or
echo $share->only(['whatsapp','twitter'])->getButtons();
```

**Get raw links**
This will return array of share urls\
you can use this to render buttons according to your need
```php
echo $share->all()->getRawLinks();
//or
echo $share->only(['whatsapp','twitter'])->getRawLinks();
```

## License
laravel-share-to package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).