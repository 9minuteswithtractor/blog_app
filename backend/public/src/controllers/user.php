<?php

define('USERS_DB', 'users.csv');
define('POSTS_DB', 'posts.csv');



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
    $pathToDb = getcwd();
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

            $keys = fgetcsv($handle, null, ',', '"', '\\'); // => arr[0] for the keys ...
            while (($row = fgetcsv($handle, null, ',', '"', '\\')) !== false) {

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
                //echo 'User found, validated and good to proceed with login';


                // check if user already has ongoing session
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }



                $_SESSION['user'] = $cleanUsernameFromClient;
                $_SESSION['loggedIn'] = true;


                return true;
            }
        }
    }

    return false; // no users in DB, failed validation process or USERS_DB doesn't exist
}


function logout() {
    session_destroy();

    $msg = array(
        "success" => true,
        "message" => "Logout successful!",
    );

    echo json_encode($msg);
    return;
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
        
        $msg = array(
            "success" => true,
            "message" => "Login Successful!",
            "user" => $_SESSION['user']
        );

        echo json_encode($msg);
        return;

    } else {
        // echo 'Incorrect username and / or password';
        // TODO : QUESTION for K : I guess no need to unset / end session at this point as isUserValid() returned false and session_start() was never called ?

        $msg = array(
            "success" => false,
            "message" => "Incorrect username and / or password"
        );

        echo json_encode($msg);
        return;

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

        if (!isset($data['user']) || !isset($data['password'])) {
            $errorResponse = array(
                "success" => false,
                "message" => "no user or password provided"
            );

            echo json_encode($errorResponse);
            return;
        }

        $cleanUsernameFromClient = htmlspecialchars($data['user']);
        $cleanPasswordFromClient = htmlspecialchars($data['password']);
        // Checking if username contains at least 3 alphanumeric characters
        $validMatch = preg_match('/^[a-zA-Z0-9]{3,}$/', $cleanUsernameFromClient);

        $userNameIsTaken = false;
        $registerSuccess = false;
        $biggedId = 1;

        if ($validMatch) {

            // TODO : Q for Kriss : Maybe extract this foreach as function because similar logic is used also in login func ?
            foreach ($usersFromDB as $userFromDB) {
                if ($biggedId < $userFromDB['id']) {
                    $biggedId = $userFromDB['id'];
                }

                if ($userFromDB['username'] === $cleanUsernameFromClient) {
                    // echo 'Username is taken, please choose another username';
                    $userNameIsTaken = true;
                    // break;
                }
            }
            
        }
        if ($userNameIsTaken == true) {
            $errorResponse = array(
                "success" => false,
                "message" => "User name is already taken"
            );

            echo json_encode($errorResponse);
            return;
        }

        $usersDbFile = getFilePath(USERS_DB);

        $handle = fopen($usersDbFile, 'a');
        $newId = $biggedId + 1;
        $userData = array($newId, $cleanUsernameFromClient, $cleanPasswordFromClient);
        fputcsv($handle, $userData, ',', '"', '\\');
        fclose($handle);

        
        $jsonResponse = array(
            "success" => true,
            "message" => "Successfully registered new user - " . $cleanUsernameFromClient
        );

        echo json_encode($jsonResponse);
        return;
    }
}
