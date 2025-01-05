<?php

// display errors 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS headers
header('Access-Control-Allow-Origin: http://localhost:5173'); // allow access from client
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');




// 

/**
 * Checklist :
 * 
 *  [] - handle Login:
 *      [] - get all user from db
 *      [] - check if user exists
 * 
 *  [] - frontController / router
 *  [] - Login, Posts, User controllers 
 *  [] - session 
 */

// TODO : Q : ? Implement front controller / Router ? 

/**
 * If req === post => check url (endpoint) => trigger controller
 */

$uri = $_SERVER['REQUEST_URI'];
$url = parse_url($uri, PHP_URL_PATH);


// login -> onSuccessLogin _> session_start()

// TODO : create func save new user


/**
 * Get all users from the CSV file.
 * 
 * @return array List of users or empty arr if no users or file doesn't exist
 */

function getAllUsers(): array
{

    // first lets check the current dir
    echo getcwd(); // => backend/public
    chdir('../data');

    echo 'pwd after cd : ' . getcwd(); // => backend/data
    $file = 'users.csv';
    $filePath = getcwd() . '/' . $file;

    // TODO maybe could just write filePath = "/backend/data" . $file ?


    if (file_exists($filePath)) {
        // echo 'Yee, file exists!';

        // users arr to store local db users and to be returned for later validation 
        $usersFromDb = [];

        // open and get all user!
        $handle = fopen($filePath, 'r'); // r for reading 

        $keys = fgetcsv($handle, 0, ',', '"', '\\'); // => arr[0] for the keys ...

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            $usersFromDb[] = array_combine($keys, $row);
        }

        print_r($usersFromDb);
        return $usersFromDb;
    } else {
        echo 'File does not exist ...';
        // optionally could create the users.csv file
        // touch('users.csv');
        return [];
    };
}





function validateUser(string $user, string $password): bool
{

    $usersFromDB = getAllUsers();



    foreach ($usersFromDB as $userFromDB) {

        echo $userFromDB . '<br />';
    }




    $validUser = 'johnny';
    $validPass = '123';

    if ($user === $validUser && $password === $validPass) {

        return true;
    }
    return false;
}

function registerUser(string $user, string $password): bool
{


    $validUser = 'johnny';
    $validPass = '123';

    if ($user === $validUser && $password === $validPass) {

        return true;
    }
    return false;
}


function login()
{




    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);


    $cleanUser = htmlspecialchars($data['user']);
    $cleanPass = htmlspecialchars($data['password']);


    // validate
    $isValid = validateUser($cleanUser, $cleanPass);


    echo ($isValid) ? 'Go Go Go!' : 'Naughty naughty!';
    if ($isValid) {
        session_start();
    }
}

login();


function register()
{

    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    $cleanUser = htmlspecialchars($data['user']);
    $cleanPass = htmlspecialchars($data['password']);

    // TODO : tasks :

    // $allUsers = getAllUsers();
    // $cleanUserNameAvailable ? => register() : err
    // $newUserSuccess = registerUser($cleanUser, $cleanPass);

    // echo ($isValid) ? 'Go Go Go!' : 'Naughty naughty!';
    // if ($isValid) {
    //     session_start();
    // }
}


    // if ($url === '/api/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //     register();
    // } elseif ($url === '/api/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    //     login();
    // } else {
    //     echo 'bad request ..';
    // }
