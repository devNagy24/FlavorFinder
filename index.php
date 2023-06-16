<?php
/**
 * @author Devon Nagy
 * @author SayedJ
 * @version 1.0
 *
 *Index/Routing for FlavorFinder
 *
 */

// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
// Require the needed files
require_once('vendor/autoload.php');
require_once('model/API_Model.php');
require_once('model/user_model.php');
require_once('controllers/controller.php');



// Instantiate the Fat-Free Framework
$f3 = Base::instance();

// Instantiate RouteController
$controller = new RouteController($f3);

/**
 * Define the route for the homepage.
 */
$f3->route('GET|POST /', function() use ($controller) {
    $controller->home();
});

/**
 * Define the route for signing up.
 */
$f3->route('POST /sign-up', function($f3) use ($controller) {
    $controller->signUp($f3);
});

/**
 * Define the route for logging in.
 */
$f3->route('POST /login', function($f3) use ($controller) {
    $controller->login($f3);
});

/**
 * Define the route for getting session data.
 */
$f3->route('GET /get-session', function($f3) use ($controller) {
    $controller->getSession($f3);
});

/**
 * Define the route for posting bookmarks.
 */
$f3->route('POST /bookmarks', function($f3) use ($controller) {
    $controller->postBookmarks($f3);
});

/**
 * Define the route for getting bookmarked items.
 */
$f3->route('GET /bookmarks', function() use ($controller) {
    $controller->getBookmarked();
});

/**
 * Define the route for clearing cookies.
 */
$f3->route('GET /clear-cookies', function($f3) {
    // Clear session data
    session_unset();
    session_destroy();

    // Replace the URL without the page parameter
    echo '<script>window.history.replaceState({}, "", "FlavorFinder/#");</script>';
});

/**
 * Define the route for getting a specific recipe by ID.
 */
$f3->route('GET /recipe/@id', function($f3, $params) {
    $apiController = new ApiController();

    $recipe = $apiController->loadRecipe($params['id']);
//    var_dump($recipe);
    $f3->set('recipe', $recipe);
    // Display a view page
    $view = new Template();
    echo $view->render('views/recipe.html');
});

// Run the F3 instance
$f3->run();