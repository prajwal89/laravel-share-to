# Laravel Share To
Generate and render social share buttons without any hassle


## Installation
```bash
composer require prajwal89/laravel-share-to
```
## Publish assets
```bash
php artisan vendor:publish --provider="Prajwal89\LaravelShareTo\LaravelShareToServiceProvider"
```

## usage
```php
use Prajwal89\LaravelShareTo\Share;

echo Share::Page('McqMate - MCQ Portal for Students', 'https://mcqmate.com', $options)->all()->getButtons();

```

