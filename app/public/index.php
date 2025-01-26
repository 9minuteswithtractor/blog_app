 <?php

    loadBasicConf(); // cors headers etc + starts session .

    $uri = $_SERVER['REQUEST_URI'];
    $url = parse_url($uri, PHP_URL_PATH);


    define('USERS_DB', 'users.csv');
    define('POSTS_DB', 'posts.csv');

    // Todo : change .csv to .json + file handling logic

    // api endpoints :
    /**
     *  / (home , show all articles)
     *  api/login (verify and start session)
     * 
     *  api/register (add to user 'db'if pass requirements)
     *  api/logout (track session, end session)
     * 
     */


    switch ($url) {

        case '/api/login':
            require_once '../controllers/LoginController.php';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $loginController = new LoginController;
                $loginController->handleLogin();
            } else {
                echo json_encode(['message' => 'Login GET/PUT/DELETE req attempted']);
            }
            break;

        case '/api/logout': // logout actually is handled on client side as sessionStorage.
            require_once '../controllers/LogoutController.php';

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $logoutController = new LogoutController;
                $logoutController->handleLogout();
            }
            break;
        case '/api/register':
            require_once '../controllers/RegisterController.php';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $registerController = new RegisterController;
                $registerController->handleRegistration();
            }
            break;

        case '/api/articles':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            } else {
                echo json_encode(['message' => 'Login GET/PUT/DELETE req attempted']);
            }
        default:
            echo 'Bad request ...';
    }

    /**
     *  loadBasicConf
     * 
     *   Define rules for cross-origin requests, controlling which client-side applications ( React running on Vite) can communicate with  server and what type of data can be sent or received.
     *  starts session.
     */

    function loadBasicConf(): void
    {

        // CORS headers
        header('Access-Control-Allow-Origin: http://localhost:5173'); // This allows access from client
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
