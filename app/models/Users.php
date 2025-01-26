<?php


/**
 * session_start(): Ignoring session_start() because a session is already active ..
 */
// session_start();

// TODO write class doc

class Users
{


    /**
     * Summary of getNextUserId
     * @return int last id number in the user list 
     */
    private function getNextUserId(): int
    {
        $usersFromDB = $this->getAll(USERS_DB);

        if (empty($usersFromDB)) {
            return 1;
        }

        // Find the maximum existing user ID and increment
        $currMaxId = max(array_column($usersFromDB, 'id'));
        return $currMaxId + 1;
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

    protected static function getFilePath(string $fileName): bool|string
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
     * @var array $dataFromDb list of users got from DB_USERS
     * 
     * @return array List of users from USERS_DB or empty otherwise 
     */

    protected function getAll(string $path): array
    {
        $dbSourceFile = $this->getFilePath($path);

        if ($dbSourceFile) {
            $dataFromDb = [];
            $handle = fopen($dbSourceFile, 'r');

            if ($handle) {

                $keys = fgetcsv($handle, 0, ",", "\"", "\\");

                while (($row = fgetcsv($handle, 0, ",", "\"", "\\")) !== false) {

                    if (count($row) === count($keys)) {
                        $dataFromDb[] = array_combine($keys, $row);
                    } else {
                        error_log("Skipping row with mismatched elements: " . print_r($row, true));
                    }
                }

                fclose($handle);
                return $dataFromDb;
            }
            // cant open the file ..
            return [];
        }
        // file doesnt exist ..
        return [];
    }


    /**
     * Summary of validateUser
     * @return bool
     */
    private function validateUser(): bool
    {

        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);


        $cleanUsernameFromClient = htmlspecialchars($data['user']);
        $cleanPasswordFromClient = htmlspecialchars($data['password']);

        $patternUsername = '/^[a-zA-Z][a-zA-Z0-9]{3,}$/';
        $patternPassword = '/^[a-zA-Z0-9!#$%&()=?+*@]{6,}$/';


        $userNameIsLegit =  preg_match($patternUsername, $cleanUsernameFromClient);

        $passwordLegit = preg_match($patternPassword, $cleanPasswordFromClient);


        if ($userNameIsLegit && $passwordLegit) {
            $_SESSION['user'] = $cleanUsernameFromClient;
            $_SESSION['password'] = $cleanPasswordFromClient;

            return true;
        } else {
            return false;
        }
    }


    /**
     * Summary of writeToDb
     * @param string $regUser
     * @param string $regPassw
     * @return array{error: string, isLoggedIn: bool, message: string}
     */
    private function writeToDb(string $regUser, string $regPassw)
    {
        try {
            $usersDbFile = $this->getFilePath(USERS_DB);
            if ($usersDbFile) {

                $handleAddUser = fopen($usersDbFile, 'a');
                if ($handleAddUser) {

                    $idToAdd = $this->getNextUserId();

                    fputcsv($handleAddUser, [$idToAdd, $regUser, $regPassw], ',', '"', '');
                }

                fclose($handleAddUser);
            }
        } catch (Exception $err) {
            return [
                'message' => '',
                'isLoggedIn' => false,
                'error' => 'could not write to db ...' . $err->getMessage()
            ];
        }
    }


    /**
     * Summary of registerUser
     * @return array{error: string, isLoggedIn: bool, message: string}
     */
    public function registerUser(): array
    {

        try {
            $usersFromDB = $this->getAll(USERS_DB);
            $proceedRegistration = $this->validateUser();

            if ($proceedRegistration) {

                $registerAs =  $_SESSION['user'];
                $registerPassword = $_SESSION['password'];

                $usernameTaken = false;
                foreach ($usersFromDB as $user) {

                    if ($registerAs === $user['username']) {
                        $usernameTaken = true;
                        break;
                    }
                }

                if (!$usernameTaken) {
                    $this->writeToDb($registerAs, $registerPassword);
                    $data = [
                        'message' => 'User has been registered...',
                        'isLoggedIn' => true,
                        'error' => '',
                        'isRegistered' => true
                    ];
                } else {
                    $data = [
                        'message' => '',
                        'isLoggedIn' => false,
                        'error' => 'This username is taken ...'
                    ];
                }
                return $data;
            } else {
                $data = [
                    'message' => '',
                    'isLoggedIn' => false,
                    'error' => 'Username must start with letter, consist of at least 4 characters. Password length must be at least 6 characters long'
                ];
                return $data;
            }
        } catch (Exception $err) {
            // something went wrong ...
            return [
                'message' => '',
                'isLoggedIn' => false,
                'error' => $err->getMessage()
            ];
        }
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

        $usersFromDB = $this->getAll(USERS_DB);



        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);



        $cleanUsernameFromClient = htmlspecialchars($data['user']);
        $cleanPasswordFromClient = htmlspecialchars($data['password']);


        if ($usersFromDB) {

            foreach ($usersFromDB as $userFromDB) {

                if ($userFromDB['username'] === $cleanUsernameFromClient && $userFromDB['password'] === $cleanPasswordFromClient) {

                    $_SESSION['user'] = $cleanUsernameFromClient;


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

    public function login(): array
    {

        $validUser = $this->isUserValid();
        if ($validUser) {

            $data = [
                'message' => 'Login successful!',
                'isLoggedIn' => true,
                'error' => ''
            ];

            $_SESSION['isLoggedIn'] = true;
            // echo json_encode($data);
        } else {

            $data = [
                'message' => '',
                'isLoggedIn' => false,
                'error' => 'Incorrect username and / or password!'
            ];

            $_SESSION['isLoggedIn'] = false;
            // echo json_encode($data);
        }

        return $data;
    }
}
