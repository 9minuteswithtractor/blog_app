<?php

loadBasicConf();


$uri = $_SERVER['REQUEST_URI'];
$url = parse_url($uri, PHP_URL_PATH);


define('USERS_DB', 'users.csv');
define('POSTS_DB', 'posts.csv');


switch ($url) {

    case '/api/login':

        require_once '../src/controllers/LoginController.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginController = new LoginController;
            $loginController->handleLogin();
        } else {
            echo json_encode(['message' => 'Login GET attempted']);
        }
        break;

    case '/api/articles':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $postController = new PostController;
            // $posController->getAllPosts();
        } else {
            echo json_encode(['message' => 'Login GET attempted']);
        }
    default:
        echo 'Bad request ...';
}




/**
 *  Finds and returns file path if it exist, false otherwise
 * 
 * @param string $fileName indicates file that will be checked
 * @var string $pathToDb dir where USERS_DB file is located
 * @var string $filePath absolute location of USERS_DB file
 * 
 *@return string absolute filePath if exist, false otherwise...
 */

function getFilePath(string $fileName): bool|string
{

    chdir('../data');
    $pathToDb =  getcwd();
    $filePath = $pathToDb . '/' . $fileName;

    return file_exists($filePath) ? $filePath : false;
}


/**
 * Get all users from the CSV file.
 * 
 * @var string||bool $userDbFile path or false if file doesn't exist
 * @var array $usersFromDb list of users got from DB_USERS
 * 
 * @return array List of users from USERS_DB or empty otherwise 
 */

function getAllUsers(): array
{

    $usersDbFile = getFilePath(USERS_DB);

    if ($usersDbFile) {

        $usersFromDb = [];

        $handle = fopen($usersDbFile, 'r'); // r for reading 

        if ($handle) {

            $keys = fgetcsv($handle); // => arr[0] for the keys ...
            while (($row = fgetcsv($handle)) !== false) {

                $usersFromDb[] = array_combine($keys, $row);
            }
        } else {
            echo 'Failed to open DB_USERS';
            // return false optionally ?
        }

        fclose($handle);

        return $usersFromDb;
    }
    return []; // if theres no USERS_DB file
    // TODO : QUESTION for K : optionally could do : touch(USERS_DB) and create this file ?
}


/**
 * Goes through users 'db' and validates username and password / optionally could also starts session (see TODO: line 118)
 * 
 * @var array $usersFromDB list of users if any from USERS_DB
 * @var string||bool $rawData raw json string from client request
 * @var array $data turns raw json string into php associative array
 * @var string $cleanUsernameFromClient sanitized string that holds user name from client request 
 * @var string $cleanPasswordFromClient sanitized string that holds user password from client request 
 * 
 * @return bool validation success or failure 
 */

function isUserValid(): bool
{

    $usersFromDB = getAllUsers();



    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    $cleanUsernameFromClient = htmlspecialchars($data['user']);
    $cleanPasswordFromClient = htmlspecialchars($data['password']);


    if ($usersFromDB) {

        foreach ($usersFromDB as $userFromDB) {

            if ($userFromDB['username'] === $cleanUsernameFromClient && $userFromDB['password'] === $cleanPasswordFromClient) {

                $_SESSION['user'] = $cleanUsernameFromClient;
                $_SESSION['password'] = $cleanPasswordFromClient;

                return true;
            }
        }
        return false;
    }
    return false;
}

/**
 *  Proceeds login 
 * 
 * @var bool $isUserValid validation success or failure
 * 
 * @return void starts session upon successful login
 */

function login()
{

    if (isUserValid()) {

        $data = [
            'message' => 'login successful',
            'loginStatus' => true
        ];

        $_SESSION['isLoggedIn'] = true;
        echo json_encode($data);
    } else {

        $data = [
            'message' => 'Incorrect username and / or password',
            'loginStatus' => false
        ];

        echo json_encode($data);
        $_SESSION['isLoggedIn'] = false;
    }
}





// TODO : Create Register new user func ( also checks USERS_DB if username is taken )

/** Register user
 * 
 */

function registerNewUser() {}


/**
 *  Loads necessary headers and starts session
 */

function loadBasicConf(): void
{
    // CORS headers
    header('Access-Control-Allow-Origin: http://localhost:5173'); // allow access from client
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
