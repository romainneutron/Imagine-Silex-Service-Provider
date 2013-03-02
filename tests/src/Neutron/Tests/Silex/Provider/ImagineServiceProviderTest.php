<?php

namespace Neutron\Tests\Silex\Provider;

use Neutron\Silex\Provider\ImagineServiceProvider;
use Silex\Application;
use Symfony\Component\HttpKernel\Client;

class BadFaithServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $imagine = null;

        $app = new Application();
        $app->register(new ImagineServiceProvider());
        $app->get('/', function(Application $app) use (&$imagine) {
            $imagine = $app['imagine'];
        });

        $client = new Client($app, array());
        $client->request('GET', '/');

        $this->assertInstanceOf('Imagine\Image\ImagineInterface', $imagine);
    }

    /**
     * @dataProvider provideDrivers
     */
    public function testRegisterWithDriver($test, $name, $classname)
    {
        $imagine = null;

        if (!call_user_func($test)) {
            $this->markTestSkipped(sprintf('driver %s not supported', $name));
        }

        $app = new Application();
        $app->register(new ImagineServiceProvider());
        $app['imagine.driver'] = $name;
        $app->get('/', function(Application $app) use (&$imagine) {
            $imagine = $app['imagine'];
        });

        $client = new Client($app, array());
        $client->request('GET', '/');

        $this->assertInstanceOf($classname, $imagine);
    }

    public function provideDrivers()
    {
        return array(
            array(function () { return extension_loaded('gmagick'); }, 'Gmagick', 'Imagine\Gmagick\Imagine'),
            array(function () { return extension_loaded('imagick'); }, 'Imagick', 'Imagine\Imagick\Imagine'),
            array(function () { return extension_loaded('gd'); }, 'Gd', 'Imagine\Gd\Imagine'),
        );
    }
}
