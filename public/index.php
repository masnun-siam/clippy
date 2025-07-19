<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package Laravel
 * @author Taylor Otwell <taylor@laravel.com>
 */

/**
 * Composer provides a convenient, automatically generated class loader
 * for our application. We just need to utilize it!
 */
require __DIR__.'/../vendor/autoload.php';

/**
 * The application is bootstrapped and ready to handle requests.
 */
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
