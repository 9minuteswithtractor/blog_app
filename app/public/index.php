 <?php

    loadBasicConf(); // configures server communication + starts session.

    $uri = $_SERVER['REQUEST_URI'];
    $url = parse_url($uri, PHP_URL_PATH);

    define('USERS_DB', 'users.csv');
    define('POSTS_DB', 'articles.csv');

    switch ($url) {
        case '/api/login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once '../controllers/LoginController.php';

                $loginController = new LoginController;
                $loginController->handleLogin();
            } else {
                echo json_encode(['message' => 'Login GET/PUT/DELETE req attempted']);
            }
            break;

        case '/api/logout': // logout is also handled on client side as using sessionStorage ..
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                require_once '../controllers/LogoutController.php';

                $logoutController = new LogoutController;
                $logoutController->handleLogout();
            }
            break;
        case '/api/register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once '../controllers/RegisterController.php';

                $registerController = new RegisterController;
                $registerController->handleRegistration();
            }
            break;
        case '/api/articles':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                require_once '../controllers/ShowArticlesController.php';

                $showArticlesController = new ShowArticlesController();
                $showArticlesController->handleShowArticles();
            } else {
                echo json_encode(['message' => 'Login POST/PUT/DELETE req attempted']);
            }
            break;
        default:
            echo 'Bad request...400...';
    }


    /**
     *  loadBasicConf summary
     * 
     *   Define rules for cross-origin requests, controlling which client-side *   applications ( React running on Vite) can communicate with  server and *   what type of data can be sent or received.
     *   starts session.
     */

    function loadBasicConf(): void
    {
        // CORS headers
        header('Access-Control-Allow-Origin: http://localhost:5173'); // This allows client to access server ..
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // update needed ? ( PUT )
        header('Access-Control-Allow-Headers: Content-Type'); // allows content-type incoming headers ..
        header('Content-Type: application/json'); // response back to client in json format ..

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
