# Laravel Share To
Generate and render social share buttons without any hassle


## Installation
```php
composer require prajwal89/laravel-share-to
```


## usage
```php
use Prajwal89\LaravelShareTo\Share;

echo Share::Page('McqMate - MCQ Portal for Students', 'https://mcqmate.com', $options)->all()->getButtons();

```

