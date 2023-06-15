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

session_start();
// Require the needed files
require_once('vendor/autoload.php');
require_once('model/API_Model.php');
require_once('model/user_model.php');
//require_once('controllers/HomeController.php');
//require_once('models/RecipeModel.php');


// Create an F3 (Fat-Free Framework) object
$f3 = Base::instance();

$f3->route('GET|POST /', function($f3) {
    $apiController = new ApiController();

    // Check if a keyword was posted or is available in session
    $keyword = $f3->get('POST.keyword') ?: $f3->get('SESSION.keyword', '');
    $f3->set('SESSION.keyword', $keyword);  // save the keyword to session

    // Fetch all the recipes
    $allRecipes = $apiController->getRecipesFromForkify($keyword);

    // Calculate the total number of recipes
    $totalRecipes = count($allRecipes);

    // Set the number of recipes to display per page
    $recipesPerPage = 10;

    // Calculate the total number of pages
    $totalPages = ceil($totalRecipes / $recipesPerPage);

    // Check if a page number was posted or provided in the query string
    $page = $f3->get('POST.page') ?: (isset($_GET['page']) ? (int)$_GET['page'] : 1);

    // Calculate the offset and limit for the current page
    $offset = ($page - 1) * $recipesPerPage;
    $limit = min($recipesPerPage, $totalRecipes - $offset);

    // Get the recipes for the current page
    $newArray = array_slice($allRecipes, $offset, $limit);

    // Pass the data to the view
    $f3->set('newArray', $newArray);
    $f3->set('totalPages', $totalPages);
    $f3->set('recipesPerPage', $recipesPerPage);

    // Also pass the current page number to the view
    $f3->set('page', $page);


    // Display a view page
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET /clear-cookies', function($f3) {
    // Clear session data
    session_unset();
    session_destroy();

    // Replace the URL without the page parameter
    echo '<script>window.history.replaceState({}, "", "FlavorFinder/#");</script>';
});

// Routing from preview link to recipeView
$f3->route('GET /recipe/@id', function($f3, $params) {
    $apiController = new ApiController();

    $recipe = $apiController->loadRecipe($params['id']);
//    var_dump($recipe);
    $f3->set('recipe', $recipe);
    // Display a view page
    $view = new Template();
    echo $view->render('views/recipe.html');
});

$f3->route('POST /sign-up', function($f3) {
    // Get the raw POST data
    $rawData = file_get_contents('php://input');

    // Decode the JSON data into a PHP array
    $data = json_decode($rawData, true);

    // Access data from the associative array
    $username = $data['username'];
    $password = $data['password'];
    $email = $data['email'];

    // Instantiate the UserModel and register the new user
    $userModel = new UserModel($f3->get('DB'));
    $userId = $userModel->register($username, $password, $email);

    // Build a response array
    $responseData = [
        "username" => $username,
        "email" => $email,
        "password" => $password,
        "success" => $userId !== 'username_taken' && $userId !== 'sql_error' && $userId !== 'empty_fields',
        "error" => $userId === 'username_taken' ? 'The username is already taken' :
            ($userId === 'sql_error' ? 'An error occurred with the database' :
                ($userId === 'empty_fields' ? 'Please fill in all fields' : ''))
    ];

    // If user registration was successful, set a session variable
    if ($responseData["success"]) {
        $f3->set('SESSION.username', $username);
    }

    // Respond with JSON
    header('Content-Type: application/json');
    echo json_encode($responseData);
});

$f3->route('POST /login', function($f3) {
    // Get the raw POST data
    $rawData = file_get_contents('php://input');

    // Decode the JSON data into a PHP array
    $data = json_decode($rawData, true);

    // Access data from the associative array
    $username = $data['username'];
    $password = $data['password'];

    // Instantiate the UserModel and login the user
    $userModel = new UserModel($f3->get('DB'));
    $validLogin = $userModel->login($username, $password);

    // Build a response array
    $responseData = [
        "username" => $username,
        "success" => $validLogin === true,
        "error" => $validLogin === false ? 'Invalid username or password' : ''
    ];

    // If login was successful, set a session variable
    if ($responseData["success"]) {
        $f3->set('SESSION.username', $username);
    }

    // Respond with JSON
    header('Content-Type: application/json');
    echo json_encode($responseData);
});

$f3->route('GET /get-session', function($f3) {
    // Get the session data
    $username = $f3->get('SESSION.username');

    // Build a response array
    $responseData = [
        "username" => $username
    ];

    // Respond with JSON
    header('Content-Type: application/json');
    echo json_encode($responseData);
});

$f3->route('POST /bookmarks', function($f3) {
    // Get the raw POST data
    $rawData = file_get_contents('php://input');

    // Decode the JSON data into a PHP array
    $data = json_decode($rawData, true);

    // Access data from the associative array
    $username = $f3->get('SESSION.username');
    $recipeId = $data['recipeId'];

    // Instantiate the UserModel and bookmark the recipe
    $userModel = new UserModel($f3->get('DB'));
    $success = $userModel->bookmark($username, $recipeId);

    // Build a response array
    $responseData = [
        "success" => $success === true,
        "error" => $success === 'sql_error' ? 'An error occurred with the database' : ''
    ];

    // Respond with JSON
    header('Content-Type: application/json');
    echo json_encode($responseData);
});

$f3->route('GET /bookmarks', function($f3) {
    $username = $f3->get('SESSION.username');

    // Instantiate the UserModel and get bookmarks
    $userModel = new UserModel($f3->get('DB'));
    $bookmarks = $userModel->getBookmarks($username);

    // Build a response array
    $responseData = [
        "success" => $bookmarks !== 'sql_error',
        "error" => $bookmarks === 'sql_error' ? 'An error occurred with the database' : '',
        "bookmarks" => $bookmarks !== 'sql_error' ? $bookmarks : []
    ];

    // Respond with JSON
    header('Content-Type: application/json');
    echo json_encode($responseData);
});






// Run the F3 instance
$f3->run();