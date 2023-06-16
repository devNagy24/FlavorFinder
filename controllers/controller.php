<?php
/**
 * @author Devon Nagy
 * @author SayedJ
 * @version 1.0
 *
 * This class serves as a controller for the FlavorFinder project.
 * It's responsible for handling routes and actions associated with them.
 */


class RouteController
{
    // Instance variables for F3 framework, APIController, and UserModel
    private $_f3;
    private $_apiController;
    private $_userModel;

    /**
     * Constructor function for RouteController.
     * Instantiates F3, APIController, and UserModel.
     *
     * @param object $f3 F3 instance.
     */
    public function __construct($f3)
    {
        $this->_f3 = $f3;
        $this->_apiController = new ApiController();
        $this->_userModel = new UserModel($f3->get('DB'));
    }

    /**
     * Handles the home route. Retrieves and displays recipes based on a keyword.
     */
    public function home()
    {

        $keyword = $this->_f3->get('POST.keyword') ?: $this->_f3->get('SESSION.keyword', '');
        $this->_f3->set('SESSION.keyword', $keyword);

        $allRecipes = $this->_apiController->getRecipesFromForkify($keyword);
        $totalRecipes = count($allRecipes);
        $recipesPerPage = 10;
        $totalPages = ceil($totalRecipes / $recipesPerPage);
        $page = $this->_f3->get('POST.page') ?: (isset($_GET['page']) ? (int)$_GET['page'] : 1);
        $offset = ($page - 1) * $recipesPerPage;
        $limit = min($recipesPerPage, $totalRecipes - $offset);
        $newArray = array_slice($allRecipes, $offset, $limit);

        $this->_f3->set('newArray', $newArray);
        $this->_f3->set('totalPages', $totalPages);
        $this->_f3->set('recipesPerPage', $recipesPerPage);
        $this->_f3->set('page', $page);

        $view = new Template();
        echo $view->render('views/home.html');
    }

    /**
     * Clears the user session data and resets the URL.
     */
    public function clearCookies()
    {
        session_unset();
        session_destroy();

        echo '<script>window.history.replaceState({}, "", "FlavorFinder/#");</script>';
    }

    /**
     * Handles user sign up. Registers a new user and sets a session variable if successful.
     */
    public function signUp()
    {
        // Get the raw POST data
        $rawData = file_get_contents('php://input');

        // Decode the JSON data into a PHP array
        $data = json_decode($rawData, true);

        // Access data from the associative array
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];

        // Register the new user
        $userId = $this->_userModel->register($username, $password, $email);

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
            $this->_f3->set('SESSION.username', $username);
        }

        // Respond with JSON
        header('Content-Type: application/json');
        echo json_encode($responseData);
    }


    /**
     * Handles user login. Validates the login and sets a session variable if successful.
     */
    public function login()
    {
        // Get the raw POST data
        $rawData = file_get_contents('php://input');

        // Decode the JSON data into a PHP array
        $data = json_decode($rawData, true);

        // Access data from the associative array
        $username = $data['username'];
        $password = $data['password'];

        // Login the user
        $validLogin = $this->_userModel->login($username, $password);

        // Build a response array
        $responseData = [
            "username" => $username,
            "success" => $validLogin === true,
            "error" => $validLogin === false ? 'Invalid username or password' : ''
        ];

        // If login was successful, set a session variable
        if ($responseData["success"]) {
            $this->_f3->set('SESSION.username', $username);
        }

        // Respond with JSON
        header('Content-Type: application/json');
        echo json_encode($responseData);
    }


    /**
     * Retrieves session data for the user and responds with it as JSON.
     */
    public function getSession()
    {
        // Get the session data
        $username = $this->_f3->get('SESSION.username');

        // Build a response array
        $responseData = [
            "username" => $username
        ];

        // Respond with JSON
        header('Content-Type: application/json');
        echo json_encode($responseData);
    }


    /**
     * Handles bookmarking a recipe. Adds a bookmark to the database and responds with success status as JSON.
     */
    public function postBookmarks()
    {
        // Get the raw POST data
        $rawData = file_get_contents('php://input');

        // Decode the JSON data into a PHP array
        $data = json_decode($rawData, true);

        // Access data from the associative array
        $username = $this->_f3->get('SESSION.username');
        $recipeId = $data['recipeId'];

        // Instantiate the UserModel and bookmark the recipe
        $userModel = new UserModel($this->_f3->get('DB'));
        $success = $userModel->bookmark($username, $recipeId);

        // Build a response array
        $responseData = [
            "success" => $success === true,
            "error" => $success === 'sql_error' ? 'An error occurred with the database' : ''
        ];

        // Respond with JSON
        header('Content-Type: application/json');
        echo json_encode($responseData);
    }


    /**
     * Retrieves the user's bookmarked recipes from the database and responds with them as JSON.
     */
    public function getBookmarked()
    {
        $username = $this->_f3->get('SESSION.username');

        // Instantiate the UserModel and get bookmarks
        $userModel = new UserModel($this->_f3->get('DB'));
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
    }

}

