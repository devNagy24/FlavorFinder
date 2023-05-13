<?php
/*
 * @Author: Devon Nagy
 * @Version: 1.0
 */

/*
 * Controller for FlavorFinder project
 */
//session_start();
// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require the needed files
require_once('vendor/autoload.php');
require('db.php');
require('apiController.php');

// Create an F3 (Fat-Free Framework) object
$f3 = Base::instance();

// Code below is what is on "db.php".. DB configs
//$f3 = \Base::instance();
//$db = new \DB\SQL(
//    "mysql:host=$hostname;port=3306;dbname=$database",
//    $username,
//    $password
//);
//$f3->set('DB', $db);

$f3->route('GET /', function($f3) {
//    echo '<h1>Welcome to Fat-Free Framework 🥸</h1>';

    // Display a view page
    $view = new Template();
    echo $view->render('views/home.html');
});


// Save recipe from JS async API calls
//$f3->route('POST /saveRecipe', function($f3) {
//    $recipe = json_decode($f3->get('BODY'), true);
//
//    $db = $f3->get('DB');
//    $recipeModel = new \DB\SQL\Mapper($db, 'recipes');
//    $recipeModel->copyfrom($recipe);
//    $recipeModel->save();
//});

// Run the F3 instance
$f3->run();