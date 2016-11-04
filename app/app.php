<?php

// Load 3rd party libraries using composer.
// Notice the use of the magic constant __DIR__ which returns the directory the current file is in.
//   http://php.net/manual/en/language.constants.predefined.php
require_once __DIR__.'/../vendor/autoload.php';

// Load our custom Application class that also loads other required classes.
require_once __DIR__.'/SilexIntro/SilexIntroApplication.php';

// Instantiate the Silex service container (the application)
$app = new SilexIntro\SilexIntroApplication();

// Enable debugging so that errors are displayed via web pages.
// This would of course be false in production.
$app['debug'] = true;

// Register a twig service provider with the path to the twig templates given.
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

// A simple route that outputs static HTML that points to other routes this app implements.
// The route is '/' and the method is get.  The controller to be run is passed as a closure to get().
$app->get('/', function () use ($app) {
    return <<<'EOF'
<html><body>
<a href="/test">/test</a> - A simple route.<br />
<a href="/test_url_and_path_generator">/test_url_and_path_generator</a> - Demonstrates how the URL/path generator works using PHP.<br />
<a href="/test_url_and_path_generator_twig">/test_url_and_path_generator_twig</a> - Demonstrates how the URL/path generator works using twig.<br />
<a href="/hello/SomeGuy">/hello/SomeGuy</a> - A route that extracts the value after the last / and passes it as the $name variable.<br />
<br />
<a href="/order/Jason/Coke/Pizza">/order/Jason/Coke/Pizza</a> - a route you need to make.  See the assignment text.<br />
<a href="/order_twig/Jason/Coke/Pizza">/order_twig/Jason/Coke/Pizza</a> - a route you need to make.  See the assignment text.<br />
<a href="/add/7/3">/add/7/3</a> - a route you need to make.  See the assignment text.<br />
<a href="/multiply/7/3">/multiply/7/3</a> - a route you need to make.  See the assignment text.
</body></html>
EOF;
});

// A simple route that outputs some static text.  The route is '/test'
// Notice that the controller is a closer (sometimes referred to as an anonymous function)
$app->get('/test', function () use ($app) {
    return "Just some text!";
});

// A route with a controller that shows how to generate URLs and paths in PHP from named routes.
$app->get('/test_url_and_path_generator', function () use ($app) {
    $url = $app->url('hello_route', ['name' => 'Jason']);
    $path = $app->path('hello_route', ['name' => 'Jason']);
    $return_string = "<html><body>A generated URL for the hello_route with parameter name=Jason: <a href=\"$url\">$url</a><br />";
    $return_string .= "A generated path for the hello_route with parameter name=Jason: <a href=\"$path\">$path</a></body></html>";
    return $return_string;
});

// A route with a controller that shows how to generate URLs and paths in twig from named routes.
$app->get('/test_url_and_path_generator_twig', function () use ($app) {
    return $app->render('test_url_generator_with_twig.twig', ['name' => 'Jason']);
});

// A simple route that uses part of the route URL as a parameter to the controller closure.
// Notice the ->bind() call after the ->get() call.  That tells Silex what name we want the route to have.
// In this case, that name is hello_route.
$app->get('/hello/{name}', function ($name) use ($app) {
    // Strip out any possible HTML/etc from $name.  This is the same function as htmlspecialchars().
    return 'Hello '.$app->escape($name);
})->bind('hello_route');


// DO NOT PUT ANY OF YOUR CODE BELOW HERE!!!
// Return the service container used by web/index.php
return $app;
