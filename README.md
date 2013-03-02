# Imagine Silex ServiceProvider

[![Build Status](https://travis-ci.org/romainneutron/Imagine-Silex-Service-Provider.png)](https://travis-ci.org/romainneutron/Imagine-Silex-Service-Provider)

## Usage

```php
use Imagine\Image\Box;
use Neutron\Silex\Provider\ImagineServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();
$app->register(new ImagineServiceProvider());

$app->match('/image-resize', function(Request $request) {
    $app['imagine']
            ->open($request->files->get('image')->getPathname())
            ->resize(new Box(320, 240))
            ->save('/path/to/data/image-resized.jpg');

    return 'Image resized !';
});
$app->run();
```

##License

MIT License
