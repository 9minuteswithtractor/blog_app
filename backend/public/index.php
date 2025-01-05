<?php
require_once 'src/controllers/user.php';
require_once 'src/controllers/articles.php';

setBasics();

$rawUri = $_SERVER['REQUEST_URI'];
$url = parse_url($rawUri, PHP_URL_PATH);



if ($url === '/api/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    registerNewUser();
} elseif ($url === '/api/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    login();
} elseif ($url === '/api/logout') {
    logout();
} elseif ($url === '/api/article' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    getArticle();
} 
 else {
    echo 'bad request ..';
}

function setBasics()
{
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);
    session_start();
    header('Access-Control-Allow-Origin: http://localhost:5173'); // allow access from client
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json; charset=utf-8');
}