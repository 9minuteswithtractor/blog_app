<?php

// display errors 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS headers
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


// session_start();

// 

/**
 * Checklist :
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


function validateUser(string $user, string $password): bool
{

    $validUser = 'johnny';
    $validPass = '123';

    if ($user === $validUser && $password === $validPass) {

        return true;
    }
    return false;
}

/**
 * 
 *  after class User
 * @return void
 */
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

if ($url === '/api/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {


    login();
} else {
    echo 'bad request ..';
}
