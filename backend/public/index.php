<?php

// display errors 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS headers
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


// TODO : Q : ? Implement front controller / Router ? 
// TODO : Q : ? What is the 'good' way to implement model view controller pattern ?

/**
 * If req === post => check url (endpoint) => trigger controller
 */
