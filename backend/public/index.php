<?php

// display errors 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS headers
header('Access-Control-Allow-Origin: http://localhost:5173'); // allow access from client
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


$uri = $_SERVER['REQUEST_URI'];
$url = parse_url($uri, PHP_URL_PATH);


define('USERS_DB', 'users.csv');
define('POSTS_DB', 'posts.csv');



// front controller req api endpoint root :

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo 'post req in progress ...';
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

    // TODO : QUESTION for K : only if client post req api === '/api/login' ???

    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    $cleanUsernameFromClient = htmlspecialchars($data['user']);
    $cleanPasswordFromClient = htmlspecialchars($data['password']);

    // TODO : QUESTION for K : does cleanUser and cleanPass -> toLowerCase() ? as it is user input ...





    if ($usersFromDB) {

        // usersFromDB obj structure : [ [id, username, password] ]
        foreach ($usersFromDB as $userFromDB) {

            if ($userFromDB['username'] === $cleanUsernameFromClient && $userFromDB['password'] === $cleanPasswordFromClient) {
                echo 'User found, validated and good to proceed with login';


                // check if user already has ongoing session
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }



                $_SESSION['user'] = $cleanUsernameFromClient;


                return true;
            }
        }
    }

    return false; // no users in DB, failed validation process or USERS_DB doesn't exist
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
        echo 'Login Successful!';


        echo '<br />Welcome : ' . $_SESSION['user'] . ' !';
    } else {
        echo 'Incorrect username and / or password';
        // TODO : QUESTION for K : I guess no need to unset / end session at this point as isUserValid() returned false and session_start() was never called ?

    }
}



// TODO : Create Register new user func ( also checks USERS_DB if username is taken )

/** Register user logic :
 * 
 *  loop through the file and check if username is already registered
 *  if username is already taken proceed accordingly ...
 */


function registerNewUser()
{

    $usersFromDB = getAllUsers();

    if ($usersFromDB) {

        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);

        $cleanUsernameFromClient = htmlspecialchars($data['user']);

        // Checking if username contains at least 3 alphanumeric characters
        $validMatch = preg_match('/^[a-zA-Z0-9]{3,}$/', $cleanUsernameFromClient);


        $userNameIsTaken = false;

        if ($validMatch) {

            // TODO : Q for Kriss : Maybe extract this foreach as function because similar logic is used also in login func ?
            foreach ($usersFromDB as $userFromDB) {

                if ($userFromDB['username'] === $cleanUsernameFromClient) {
                    echo 'Username is taken, please choose another username';
                    $userNameIsTaken = true;
                    break;
                }
            }
            return $userNameIsTaken;
        }
        return $userNameIsTaken;
    }
}


echo registerNewUser();
