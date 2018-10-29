<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Laragle Translate
Laragle Translate is a Laravel localization management system that will update your localization files in real-time.

You can install this package via composer using this command:

```bash
composer require laragle/translate
```

The package will automatically register itself.

Next, add the following environment variables into your `.env` file.

```env
LARAGLE_TRANSLATE_API_URL=https://api.translate.laragle.com
LARAGLE_TRANSLATE_APP_ID=YOUR_PROJECT_APP_ID
LARAGLE_TRANSLATE_APP_SECRET=YOUR_PROJECT_APP_SECRET
```

You can get your PROJECT_APP_ID & PROJECT_APP_SECRET by create a project [here](https://translate.laragle.com)

## Importing Translations

You can import your existing localization file using the below artisan command.

```bash
php artisan laragle:push-translations
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
