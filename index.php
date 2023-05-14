<?php
/*
 * @Author: Devon Nagy
 * @Version: 1.0
 */

/*
 * Controller for FlavorFinder project
 */


// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require the needed files
require_once('vendor/autoload.php');
require('db.php');
require_once('model/API_Model.php');
//require_once('controllers/HomeController.php');
//require_once('models/RecipeModel.php');


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


// Default route
$f3->route('GET /', function($f3) {

    $apiController = new ApiController();
    // Replace 'pizza' with the user's search query, Hardcoding for testing
    $query = 'pizza';
    $id = '5ed6604591c37cdc054bcc3e';

    $recipes = $apiController->loadRecipe($id);
    $newArray = $apiController->getRecipesFromForkify();
    // Pass the data to the view
    $f3->set('newArray', $newArray);
    $f3->set('recipeBook', $recipes);

    // Display a view page
    $view = new Template();
    echo $view->render('views/home.html');
});



$f3->route('POST  /search', function($f3) {
        $query = $f3->get('POST.query');

        // Instantiate your API controller
        $apiController = new ApiController();
        $recipe = $apiController->getRecipeBySearch('pizza');

        // Save the recipe data in the hive
        $f3->set('recipe', $recipe);

        // Validate the data
    $view = new Template();
    echo $view->render('views/home.html');
});

// Routing from preview link to recipeView
$f3->route('GET /recipe/@id', function($f3, $params) {
    $apiController = new ApiController();

    $recipe = $apiController->loadRecipe($params['id']);
//    var_dump($recipe);
    $f3->set('recipe', $recipe);
//    // Get the ID from the route parameter
//    $id = $params['id'];
//    // Use the ID to load the full recipe
//    $recipe = $apiController->loadRecipe($id);

    // Display a view page
    $view = new Template();
    echo $view->render('views/recipe.html');
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