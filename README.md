# Simple Blog-App

    Things to begin with ? :
        - Backend || Frontend ?
    
    How MVC works ?
        - I kinda understand the concept but not sure about where the logic before returning the data to client is happening ( controller or model ) ?
    
    Lets start clean project 
        - Simple react with php mvc pattern
        - No design - pure functionality
  
### - Requirements -
# Implement :
    [ ] - navbar / loggedIn navbar
    [ ] - navbar Routing (ClientSide)
    [ ] - login / validation / sessions
    [ ] - registration 
    [ ] - Show all articles for everyone
    [ ] - Crate, Update, Delete for the logged in ones
    [ ] - OOP MVC pattern for Backend ( for practice )

# db :
    local .csv file


# Debbugger

First 

1. Check if php installed

`php -v`

if not then `brew install php`

Test php `php -S localhost:8000`

then install debugger:

`pecl install xdebug`

then install vs code extensions - "php debug" by xdebug and Php by DevSense



php.ini file content
```[Xdebug]
zend_extension="xdebug.so"
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_port=9003
xdebug.client_host=127.0.0.1
xdebug.idekey=VSCODE
```

then vscode

{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "${workspaceRoot}": "${workspaceRoot}"
            }
        },
        {
            "name": "Launch php server",
            "type": "php",
            "request": "launch",
            "runtimeArgs": [
                "-S",
                "localhost:8000",
                "-t",
                "backend/public",
                "-d",
                "xdebug.mode=debug",
                "-d",
                "xdebug.client_host=127.0.0.1",
                "-d",
                "xdebug.client_port=9003",
            ],
            "pathMappings": {
                "/": "${workspaceRoot}/backend/public"
            }
        },        
    ],
    "compounds": [
        {
            "name": "Launch Server & Debug",
            "configurations": ["Launch php server", "Listen for Xdebug"]
        }
    ]
}