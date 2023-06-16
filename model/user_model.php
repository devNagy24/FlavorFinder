<?php
/**
 * @author Devon Nagy
 * @author SayedJ
 * @version 1.0
 *
User Model Class
 * This class handles all operations related to the User
 *registration, login, authentication, bookmarking and fetching bookmarks.
 *
 */



require_once('/home/devonnag/public_html/328/FlavorFinder/db.php');
class UserModel {
    private $db;

    /**
     * Class constructor
     *
     * @param object $db Database connection object
     */
    public function __construct($db) {
        // Storing database connection
        $this->db = $db;
    }

    /**
     * Register a new user
     *
     * @param string $username User's username
     * @param string $password User's password
     * @param string $email User's email
     * @return mixed Return string if error || last insert id in case of success
     */
    public function register($username, $password, $email) {
        // Check form data
        if (empty($username) || empty($password) || empty($email)) {
            return 'empty_fields';
        }

        // Check if the username already exists
        $sql = "SELECT username FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);

        if ($stmt->fetchColumn()) {
            return 'username_taken';
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':email'    => $email
        ]);

        // Check if the query was successful
        if (!$success) {
            echo "SQL Error: " . print_r($stmt->errorInfo(), true);
            return 'sql_error';
        }

        return $this->db->lastInsertId();
    }

    /**
     * Logging a user in
     *
     * @param string $username User's username
     * @param string $password User's password
     * @return bool Return true if login = true : false
     */

    public function login($username, $password) {
        // Check if the user exists
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        return true;
    }



    /**
     * Authenticating user
     *
     * @param string $username User's username
     * @param string $password User's password
     * @return mixed Return associative array of user data if authentication is successful, false otherwise
     */
    public function authenticate($username, $password) {
        // Fetch the user from the database
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Adding bookmark
     *
     * @param string $username User's username
     * @param string $recipeId id of the recipe to be bookmarked
     * @return mixed RReturn true if login = true : string in case of error
     */
    public function bookmark($username, $recipeId) {
        // Insert the bookmark into the database
        $sql = "INSERT INTO bookmarks (username, recipe_id) VALUES (:username, :recipe_id)";
        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            ':username' => $username,
            ':recipe_id' => $recipeId
        ]);

        // Check if the query was successful
        if (!$success) {
            echo "SQL Error: " . print_r($stmt->errorInfo(), true);
            return 'sql_error';
        }

        return true;
    }

    /**
     * Grab all bookmarks for user
     *
     * @param string $username User's username
     * @return mixed Return array of bookmarked recipe ids : string 
     */
    public function getBookmarks($username) {
        // Query to get all bookmarked recipe_ids for a user
        $sql = "SELECT recipe_id FROM bookmarks WHERE username = :username";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':username' => $username
        ]);

        $bookmarks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bookmarks[] = $row['recipe_id'];
        }

        // Check if the query was successful
        if (!$bookmarks) {
            echo "SQL Error: " . print_r($stmt->errorInfo(), true);
            return 'sql_error';
        }

        return $bookmarks;
    }
}
